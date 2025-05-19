Task from Webby

1. Clone this project
2. Setup .env
3. Run **composer install** if needed
4. Run the following commands
    1. **php artisan migrate**
    2. **php artisan db:seed**
5. Admin credential is seeded with the below
    1. email: [admin@gmail.com](mailto:admin@gmail.com)
    2. password: publish
6. Then run locally by using the command below
    1. **php -S localhost:8000 -t public/**
    2. **npm run dev**
7. Visit http://localhost:8000/ for the system
8. Visit http://localhost:8000/graphiql for GrapgQL interface

---

Completed task

1. Login / Logout
2. Registration for user
3. Admin
    1. To create a area with parking rate
    2. To delete exisitng area
    3. To create a new parking rate
    4. To delete existing parking rate
    5. To check all the users
4. User
    1. To check available spaces from an area
