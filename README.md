# Dlabs - DataTech Documentation

## Docs

API Docs : <https://documenter.getpostman.com/view/19154922/2sAY4ygN3L>

## Notes

- all Datas and Schemas for DB are provided on seeder


## Developer's Corner

### Environtment Setup

Requirement

- PHP 8.1
- Composer 2.2.23
- MySQL

#### Initializing Project

```
composer install
cp .env.example .env
npm install && npm run build
composer run dev
php artisan key:generate
php artisan migrate
php artisan db:seed
```

Modify .env according to local config and add JWT_SECRET key inside it.

#### Setting PHP Unit Code Coverage

- install xdebug for php
- Set xdebug.mode=coverage (preferably in php.ini)

After running the `php artisan test`, you can find the coverage report in storage/reports/coverage

#### Testing

whole testing :
```
php artisan test
```

postive testing :
```
php artisan test tests/Feature/UserApiPostiveTest.php
```

negative testing :
```
php artisan test tests/Feature/UserApiNegativeTest.php
```

## Directory Tree

```.
./
├── App/
│   ├── Exception/
│   │   └── Handler.php
│   ├── Http/
│   │   ├── Controller/
│   │   │   ├── AuthController.php
│   │   │   └── UserController.php
│   ├── Kernel.php
│   │   
│   ├── Models/
│   │   ├── Role.php ...
│   │   └── User.php
|   ├──────
├─── Database/
│   ├── factories/
|   |   ├── RoleFactory.php
|   |   └── UserFactory.php
│   ├── migrations/
|   |   └── migration... .php
├─── tests/
│   ├── Feature/
|   |   ├── UserApiNegativeTest.php
|   |   └── UserApiPositiveTest.php
└────────
```
