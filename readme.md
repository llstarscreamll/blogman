# Simple Blogging System

Blog creation and management application with users administration and roles.

## Features

-   [ ] Users authentication
-   [ ] Users registration
-   [ ] Users management, CRUD stuff plus:
    -   [ ] filter users by type
    -   [ ] batch assign "Blogger"s to "Supervisor"s
-   [ ] Supervisors page to list out all the "Supervisor" users and the "Blogger" users that are under them
-   [ ] Dashboard page with count of posts, users count by type, authenticated user details, last login date and update details
-   [ ] Posts management
-   [ ] Users ACL:
    -   [ ] Blogger (default):
        -   [ ] dashboard access with their own posts count, authenticated user details, last login date and update their details
        -   [ ] post page for view, search, add, edit, delete their own blogs
    -   [ ] Supervisor:
        -   [ ] dashboard access with underneath posts users count, underneath users count by type, authenticated user details, last login date and update their details
        -   [ ] view, search, add, edit, delete their own posts
        -   [ ] view, search, add, edit, delete users posts assigned under the supervisor user
        -   [ ] view and search their underneath users
    -   [ ] Admin:
        -   [ ] full access
        -   [ ] dashboard access with all posts count, users count by type, authenticated user details, last login date and update their details
        -   [ ] view, search, add, edit, delete users
        -   [ ] supervisors page access
        -   [ ] post page to manage all posts form all users
-   [ ] Default admin account
