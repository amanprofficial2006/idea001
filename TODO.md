# Task Completion TODO

## Completed Tasks
- [x] Fix Task model's assignedHelper relationship to use 'helper_id' foreign key
- [x] Update show function in TaskController to use 'helper' relationship and include 'user_uid' in select fields

## Summary
The issue was that the assignedHelper relationship in the Task model was using the wrong foreign key ('assigned_to' instead of 'helper_id'), causing the helper to not load properly. Additionally, the show function was using 'assignedHelper' instead of 'helper' for consistency, and 'user_uid' was not included in the select fields. These have been fixed, so now when helper_id is null, the helper object will be null, but the fields (id, name, email, phone, user_uid) will be included as null in the response.
