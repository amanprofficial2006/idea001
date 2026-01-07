# TODO: Create API for Posting Tasks

-   [x] Create TaskController in app/Http/Controllers/Api/TaskController.php with store method for task creation
-   [x] Add POST /tasks route in routes/api.php under auth:sanctum middleware
-   [x] Create StoreTaskRequest for validation
-   [x] Update TaskController to use StoreTaskRequest instead of inline validation
-   [ ] Test the API endpoint (run Laravel server and test with Postman or similar)
