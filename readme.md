# Simple Blogging System

Blog creation and management application with users administration and roles.

## Features

-   [x] Users authentication
-   [x] Users registration
-   [ ] Default admin account
-   [x] Users management, CRUD actions and:
    -   [ ] filter users by type
    -   [ ] batch assign "Blogger"s to "Supervisor"s
-   [x] Supervisors page to list out all the "Supervisor" users and the "Blogger" users that are under them
-   [x] Dashboard page with:
    -   [x] count of posts
    -   [x] users count by type
    -   [x] authenticated user details
    -   [x] last login date
    -   [ ] update details
-   [x] Posts management, CRUD actions
-   [ ] Users ACL:
    -   [ ] Blogger (default):
        -   [x] dashboard access with their own posts count, authenticated user details, last login date and update their details
        -   [ ] post page for view, search, add, edit, delete their own blogs
    -   [ ] Supervisor:
        -   [x] dashboard access to all posts count, assigned blogger users count, authenticated user details, last login date and update their details
        -   [ ] view, search, add, edit, delete their own posts
        -   [ ] view, search, add, edit, delete users posts assigned under the supervisor user
        -   [ ] view and search their underneath users
    -   [ ] Admin:
        -   [ ] full access
        -   [ ] dashboard access with all posts count, users count by type, authenticated user details, last login date and update their details
        -   [ ] view, search, add, edit, delete users
        -   [ ] supervisors page access
        -   [ ] post page to manage all posts form all users
