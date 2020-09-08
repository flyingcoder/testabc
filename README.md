to install

composer install

php artisan migrate

copy .env example

asside from changing db

change or add this variable also

TEMP_PASSWORD=tmpPassword
QUEUE_DRIVER=database
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@testabc.com

you can register or login to mailtrap to get the username and password
just click the dome inbox and copy the username and password

lastly edit your cron job
for mac user just edit crontab by running crontab -e
for windows follow this link https://quantizd.com/how-to-use-laravel-task-scheduler-on-windows-10/

* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

https://laravel.com/docs/7.x/scheduling#introduction