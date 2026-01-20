<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function createOrGetConversation(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $me = Auth::id();
        $other = $request->receiver_id;

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

    public function getMyConversations()
    {
        $me = Auth::id();

        $conversations = Conversation::where('user_one_id', $me)
            ->orWhere('user_two_id', $me)
            ->with(['messages' => function ($q) {
                $q->orderBy('created_at', 'desc')->limit(1);
            }, 'userOne', 'userTwo'])
            ->get()
            ->map(function ($conversation) use ($me) {
                $otherUser = $conversation->getOtherUser($me);
                $lastMessage = $conversation->messages->first();
                $unreadCount = $conversation->messages()->where('sender_id', '!=', $me)->where('seen', 0)->count();

                return [
                    'conversation_id' => $conversation->id,
                    'other_user' => $otherUser,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                ];
            });

        return response()->json($conversations);
    }

    public function getMessages($conversationId)
    {
        $me = Auth::id();

        $conversation = Conversation::where('id', $conversationId)
            ->where(function ($q) use ($me) {
                $q->where('user_one_id', $me)->orWhere('user_two_id', $me);
            })
            ->firstOrFail();

        $messages = $conversation->messages()->with('sender')->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);

        $me = Auth::id();

        $conversation = Conversation::where('id', $request->conversation_id)
            ->where(function ($q) use ($me) {
                $q->where('user_one_id', $me)->orWhere('user_two_id', $me);
            })
            ->firstOrFail();

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $me,
            'message' => $request->message,
        ]);

        // Emit socket event
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function markSeen($messageId)
    {
        $me = Auth::id();

        $message = Message::where('id', $messageId)
            ->whereHas('conversation', function ($q) use ($me) {
                $q->where('user_one_id', $me)->orWhere('user_two_id', $me);
            })
            ->where('sender_id', '!=', $me)
            ->firstOrFail();

        $message->update(['seen' => 1]);

        return response()->json(['status' => 'seen']);
    }
}
