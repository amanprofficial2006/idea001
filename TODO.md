# TODO: Implement Task Edit and Delete APIs

-   [x] Add 'update' method to app/Http/Controllers/Api/TaskController.php: Validate request, check ownership and status=='pending', update task fields, recalculate deadline if needed, replace skills and images.
-   [x] Add PUT route for /tasks/{id} in routes/api.php under auth:sanctum middleware.
-   [x] Add 'destroy' method to app/Http/Controllers/Api/TaskController.php: Check ownership and status=='pending', delete task with related skills and images.
-   [x] Add DELETE route for /tasks/{id} in routes/api.php under auth:sanctum middleware.
