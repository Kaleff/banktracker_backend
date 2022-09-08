Project installation
1) Project basic setup
Using either <kbd>php artisan serve<kbd> or Apache/Nginx
2) Rename .env.example to .env and setup the database accordingly
3) When the database is ready to use, use the <kbd>php artisan migrate</kbd> to run migrations
4) To start the cronjob<kbd>php artisan schedule:work</kbd>, this will start a cron job process. <a href='https://laravel.com/docs/9.x/scheduling'>More information about the laravel cronjobs</a> 
5) If you want to scrape the needed data manually, use the <kbd>php artisan updaterates</kbd> command