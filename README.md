# Svyazkom_testovoe

Для корректной работы необходимо следующее:

node js;  
PHP_8 >;  
PostgreSQL;  
composer;  
7zip/unzip;  

Запускать проект через:
php artisan serve  
npm run dev  

Перед запуском обязательно нужно прописать следующие команды:
composer install  
npm install  
php artisan migrate  

Также необходимо раскомментировать(убрать ; в начале строки) следующие строки в файле (папкпа установки php)/php.ini:
extension=fileinfo  
extension=pgsql  

Также нужно добавить в файле конфигурации .env следующий ключ приложения -> base64:u4HAYJ9vjrsXLsiEjXU3vQQRYSDXcKnvPz7RDA8/fLU=


Чтобы БД конкретно отрабатывала, необходимо изменить конфигурацию в .env:  
Пример  
DB_CONNECTION=pgsql  
DB_HOST=127.0.0.1  
DB_PORT=5432  
DB_DATABASE=waterPump  
DB_USERNAME=postgres  
DB_PASSWORD=root  
