<?php

namespace App\Broadcasting;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Support\Facades\Http;

class SocketIoBroadcaster extends Broadcaster
{
    public function auth($request)
    {
        //
    }

    public function validAuthenticationResponse($request, $result)
    {
        //
    }

    public function broadcast(array $channels, $event, array $payload = [])
    {
        $socketUrl = config('broadcasting.connections.socketio.host') . '/broadcast';

        foreach ($channels as $channel) {
            $channelName = $channel->name;

            // Remove 'private-' prefix if present
            if (str_starts_with($channelName, 'private-')) {
                $channelName = substr($channelName, 8);
            }

            Http::post($socketUrl, [
                'channel' => $channelName,
                'event' => $event,
                'data' => $payload,
            ]);
        }
    }
}
