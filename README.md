## Set up

From the root directory, run:

`cp .env.example .env`  
`composer install`
`php artisan key:generate`  
`docker-compose up -d`

Then run `docker-compose exec app bash` to get into the container and run:

`php artisan migrate`
`php artisan db:seed`
`php artisan storage:link`
`npm install`
`npm run dev`

If you would like to see emails and notifications, run the queue from inside
the container:

`php artisan queue:work`
