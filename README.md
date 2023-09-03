# Laravel API with JWT
This project is for starter pack for JWT API applications with Laravel  
## How To install

```bash
git clone 
cd
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```
### Setting Database configuration
`DB_DATABSE` is name of your database
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api-app
DB_USERNAME=root
DB_PASSWORD=
```

### Final
```bash
php artisan migrate
php artisan serve
```