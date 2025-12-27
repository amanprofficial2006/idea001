# TODO: Add Serial No. and Image Column to Users Index Page

-   [ ] Add "Serial No." and "Image" headers to the table in resources/views/admin/users/index.blade.php
-   [ ] Add Serial No. and Image cells to each user row in the table body
-   [ ] Update the colspan in the empty state message from 6 to 8

# TODO for Organisation Info GET API

-   [x] Create OrganisationController with index method
-   [x] Add import for OrganisationController in routes/api.php
-   [x] Add GET /organisation route in routes/api.php
-   [x] Add full path to logo in API response
-   [ ] Test the API endpoint

# TODO: Implement Login API

-   [x] Add login method to UserController that validates phone/password and returns token
-   [x] Add POST /login route to routes/api.php
-   [x] Update last_login_at when user logs in

# TODO: Implement Profile APIs

-   [x] Add getProfile method to UserController for authenticated user profile
-   [x] Add updateProfile method to UserController for updating profile
-   [x] Add GET /profile and PUT /profile routes with auth:sanctum middleware

# TODO: Implement Logout API

-   [x] Add logout method to UserController that deletes current access token and marks user offline
-   [x] Add POST /logout route with auth:sanctum middleware
