# Employee CRUD

A Mix of Laravel+Vue+Vueitfy 

## Backend

+ Laravel Framework v7
+ Eloquent ORM 
+ Passport for Authentication

#### Run Steps 

- composer install 
- link to database by .env 
- php artisan passport:install
- php artisan migrate --seed to insert prepared data
- php artisan serve (or) use virtualhost 

## Frontend

+ Vue,
+ Vuetify for UI 
+ Vuex for state management 
+ Vue-Router for frontend routing
#### Run Steps

- npm install 
- npm run dev (or) npm run watch 

## Usage 

+ all employees occupy staffid(4 digits format) to and password (initially 'password') 
+ admin login credentials (staffId and password) are followings:
 - 0001
 - password
+ all employees can update their password in home screen
