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
-   [x] User roles security roles:
    -   [x] Blogger (default):
        -   [x] dashboard access with their own posts count, authenticated user details, last login date and update their details
        -   [x] post page for view, search, add, edit, delete their own blogs
    -   [x] Supervisor:
        -   [x] dashboard access to assigned blogger posts count, assigned blogger users count, authenticated user details, last login date and update their details
        -   [x] view, search, add, edit, delete their own posts
        -   [x] view, search, edit, delete users posts assigned under the supervisor
        -   [x] view and search their underneath users
    -   [x] Admin:
        -   [x] full access
        -   [x] dashboard access with all posts count, users count by type, authenticated user details, last login date and update their details
        -   [x] view, search, add, edit, delete users
        -   [x] supervisors page access
        -   [x] post page to manage all posts form all users

## Running tests

You need a mysql database to run the test suite with the credentials specified in `.env.testing`, then:

```
git clone https://github.com/llstarscreamll/blogman.git
cd blogman
composer install
vendor/bin/phpunit
```
