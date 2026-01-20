# Chat System Implementation TODO

## Database

- [x] Create conversations table migration
- [x] Create messages table migration
- [x] Run migrations

## Models

- [x] Create Conversation model with relationships
- [x] Create Message model with relationships

## Controllers

- [x] Create ChatController
- [x] Implement createOrGetConversation method
- [x] Implement getMyConversations method
- [x] Implement getMessages method
- [x] Implement sendMessage method
- [x] Implement markSeen method

## Routes

- [x] Add chat routes to api.php

## Broadcasting

- [x] Create MessageSent event
- [x] Configure broadcasting for socket.io
- [x] Update MessageSent event broadcast channel to 'chat.{conversation_id}'
- [x] Fire MessageSent event in sendMessage method

## Testing

- [ ] Test all APIs
- [ ] Test socket integration

## Notes

- Socket room logic: room = "chat\_" + conversation_id
- Only 2 users join the same room
- Use the provided socket.io configuration
