**To setup the project**
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
8. Visit http://localhost:8000/graphiql for GraphQL interface

---
**Completed task**
1. Login
2. Logout
3. User Registration
4. Admin
    1. To create an area with a parking rate
    2. To delete the existing area
<<<<<<< HEAD
    3. Ability to track the number of users parked in a specific area, categorized by parking type (only work for 1 user now)
    4. Ability to view the current number of available parking spaces after users have parked (only work for 1 user now)
=======
    3. Ability to track the number of users parked in a specific area, categorized by parking type (only 1 user for now)
    4. Ability to view the current number of available parking spaces after users have parked (only 1 user for now)
>>>>>>> 7901644166c116a66a6b4fec1c0934d3adc9ba27
    5. To create a new parking rate
    6. To delete the existing parking rate
    7. Able to check on a list of users
5. User
    1. To choose a parking space based on different categories
<<<<<<< HEAD
    2. To check available spaces in an area based on parking categories
    3. To make a payment (simple click)
=======
    2. To check available spaces in an area
    3. To make a payment 
>>>>>>> 7901644166c116a66a6b4fec1c0934d3adc9ba27
  
---
**Incomplete task**
1. Admin registration / user creation under Admin
2. Admin
   1. To edit the existing area
   2. To edit the existing parking rate
<<<<<<< HEAD
   3. Unable to create special rate for special date / day / parking categories / other settings
   4. To edit the users' profiles
3. User
   1. Unable to search number plate
   2. Users' parking records are not displayed separately for each user
=======
   3. Unable to create special rate for special date / day / other settings
   4. To edit the users' profiles
3. User
   1. Unable to search number plate
   2. Users' parking records are not displayed separately for each user
>>>>>>> 7901644166c116a66a6b4fec1c0934d3adc9ba27
