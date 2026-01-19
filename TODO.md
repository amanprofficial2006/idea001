# TODO: Create Accepted Tasks GET API

- [x] Add `acceptedTasks()` method to `app/Http/Controllers/Api/TaskController.php` to fetch accepted tasks for the authenticated helper user, including relations like images, skills, and user details.
- [x] Add new route `Route::get('/tasks/accepted', [TaskController::class, 'acceptedTasks']);` to `routes/api.php` under the auth:sanctum middleware group.
- [ ] Test the new endpoint to ensure it returns the correct data.
