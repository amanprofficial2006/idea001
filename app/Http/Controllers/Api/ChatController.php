<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Create or get conversation (2-user only)
     */
    public function createOrGetConversation(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $me = Auth::id();
        $other = $request->receiver_id;

        // prevent self-chat
        if ($me == $other) {
            return response()->json(['error' => 'Invalid receiver'], 422);
        }

        $conversation = Conversation::where(function ($q) use ($me, $other) {
            $q->where('user_one_id', $me)
                ->where('user_two_id', $other);
        })->orWhere(function ($q) use ($me, $other) {
            $q->where('user_one_id', $other)
                ->where('user_two_id', $me);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $me,
                'user_two_id' => $other,
            ]);
        }

        return response()->json($conversation);
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
            ->with('sender:id,name')
            ->orderBy('created_at')
            ->get();

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

        $me = Auth::id();

        $conversation = Conversation::where('id', $request->conversation_id)
            ->where(function ($q) use ($me) {
                $q->where('user_one_id', $me)
                    ->orWhere('user_two_id', $me);
            })
            ->firstOrFail();

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $me,
            'message'         => $request->message,
        ]);

        // 🔥 REALTIME: Laravel → Socket.IO VPS
        try {
            Http::timeout(3)->post('https://socket.bitmaxgroup.com/emit-message', [
                'room'  => 'chat_' . $conversation->id,
                'event' => 'receiveMessage',
                'data'  => $message,
            ]);
        } catch (\Throwable $e) {
            // socket failure should NEVER break chat
            Log::error('Socket emit failed', [
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json($message);
    }

    /**
     * Mark message as seen
     */
    public function markSeen($messageId)
    {
        $me = Auth::id();

        $message = Message::where('id', $messageId)
            ->where('sender_id', '!=', $me)
            ->whereHas('conversation', function ($q) use ($me) {
                $q->where('user_one_id', $me)
                    ->orWhere('user_two_id', $me);
            })
            ->firstOrFail();

        $message->update(['seen' => 1]);

        return response()->json(['status' => 'seen']);
    }
}
