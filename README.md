## Set up

You will need PHP 7.4 or above, MySQL 5.7 or above, and Composer installed to run this project.

### Laravel Valet

To run the project with Laravel Valet, you will need to install Laravel Valet via the instructions here: https://laravel.com/docs/8.x/valet
Once it is installed, you can run `valet link stories-app` from inside the root directory.

### Database

You will also need a local database. You can use the credentials for any MySQL database, but the commands run for this project were:

`brew install mysql@5.7`
`brew link --force mysql@5.7`
`brew services start mysql@5.7`
`mysql_secure_installation` to set your password
`mysql -u root -p`

Enter your password, then run `create database storieapp`. You should now have a fresh database that can be used with this project.

### Running the Project

Set the database credentials in `.env`.

From the root directory, run:

`cp .env.example .env`  
`composer install`
`npm install`
`php artisan migrate`
`php artisan db:seed`
`php artisan storage:link`
`npm run dev`

If you would like to see emails and notifications, run the queue from inside the container:

`php artisan queue:work`
