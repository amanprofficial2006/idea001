<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;


class ChatController extends Controller
{
    /**
     * Create or get conversation (2-user only)
     */


    public function createOrGetConversation(Request $request)
    {
        $request->validate([
            'receiver_uid' => 'required|exists:users,user_uid',
            'task_id' => 'required|exists:tasks,id',
        ]);

        $me = Auth::user();
        if (!$me) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $receiver = User::where('user_uid', $request->receiver_uid)->firstOrFail();

        if ($me->id === $receiver->id) {
            return response()->json(['error' => 'Cannot chat with self'], 422);
        }

        $conversation = Conversation::where(function ($q) use ($me, $receiver, $request) {
            $q->where('user_one_id', $me->id)
                ->where('user_two_id', $receiver->id)
                ->where('task_id', $request->task_id);
        })->orWhere(function ($q) use ($me, $receiver, $request) {
            $q->where('user_one_id', $receiver->id)
                ->where('user_two_id', $me->id)
                ->where('task_id', $request->task_id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $me->id,
                'user_two_id' => $receiver->id,
                'task_id' => $request->task_id,
            ]);
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'me_uid'          => $me->user_uid,
            'receiver_uid'    => $receiver->user_uid,
            'task_id'         => $conversation->task_id,
        ]);
    }

    /**
     * Get my conversations list
     */
    public function getMyConversations()
    {
        $me = Auth::id();

        $conversations = Conversation::where('user_one_id', $me)
            ->orWhere('user_two_id', $me)
            ->with([
                'messages' => function ($q) {
                    $q->latest()->limit(1);
                },
                'userOne:id,name,user_uid',
                'userTwo:id,name,user_uid',
            ])
            ->get()
            ->map(function ($conversation) use ($me) {
                $otherUser = $conversation->user_one_id == $me
                    ? $conversation->userTwo
                    : $conversation->userOne;

                $lastMessage = $conversation->messages->first();

                $unreadCount = $conversation->messages()
                    ->where('sender_id', '!=', $me)
                    ->where('seen', 0)
                    ->count();

                return [
                    'conversation_id' => $conversation->id,
                    'other_user'      => $otherUser,
                    'last_message'    => $lastMessage,
                    'unread_count'    => $unreadCount,
                ];
            });

        return response()->json($conversations);
    }

    /**
     * Get messages of a conversation
     */
    public function getMessages($conversationId)
    {
        $me = Auth::id();

        $conversation = Conversation::where('id', $conversationId)
            ->where(function ($q) use ($me) {
                $q->where('user_one_id', $me)
                    ->orWhere('user_two_id', $me);
            })
            ->firstOrFail();

        $messages = $conversation->messages()
            ->with('sender:id,name,user_uid')
            ->orderBy('created_at')
            ->get()
            ->map(function ($m) {
                return [
                    'id'              => $m->id,
                    'conversation_id' => $m->conversation_id,
                    'message'         => $m->message,
                    'sender_id'       => $m->sender_id,
                    'sender_uid'      => $m->sender->user_uid, // 🔥 REQUIRED
                    'seen'            => $m->seen,
                    'created_at'      => $m->created_at,
                ];
            });

        return response()->json($messages);
    }

    /**
     * Send message + REALTIME SOCKET EMIT
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message'         => 'required|string',
        ]);

        $me = Auth::user();

        $conversation = Conversation::where('id', $request->conversation_id)
            ->where(function ($q) use ($me) {
                $q->where('user_one_id', $me->id)
                    ->orWhere('user_two_id', $me->id);
            })
            ->firstOrFail();

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $me->id,
            'message'         => $request->message,
        ]);

        // 🔥 VERY IMPORTANT PAYLOAD
        $payload = [
            'id'              => $message->id,
            'conversation_id' => $conversation->id,
            'message'         => $message->message,
            'sender_id'       => $me->id,
            'sender_uid'      => $me->user_uid,   // ✅ THIS WAS MISSING
            'created_at'      => $message->created_at,
        ];

        try {
            Http::timeout(3)->post('https://socket.bitmaxgroup.com/emit-message', [
                'room'    => 'chat_' . $conversation->id,
                'message' => $payload,
            ]);
        } catch (\Throwable $e) {
            Log::error('Socket emit failed', ['error' => $e->getMessage()]);
        }

        return response()->json($payload);
    }


    /**
     * Mark message as seen
     */
    public function markSeen($id)
    {
        $message = Message::findOrFail($id);
        $message->seen = true;
        $message->save();

        // 🔥 Emit to socket server
        Http::post('https://socket.bitmaxgroup.com/emit-message-seen', [
            'conversation_id' => $message->conversation_id,
            'message_id' => $message->id,
            'receiver_uid' => $message->sender_uid,
        ]);

        return response()->json(['success' => true]);
    }
}
