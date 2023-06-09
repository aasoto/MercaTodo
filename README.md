[![merca-todo-logo-mini.png](https://i.postimg.cc/2SgCTSLx/merca-todo-logo-mini.png)](https://postimg.cc/VJByk1RS)
 
# MercaTodo
## Description

>MercaTodo is a system that allows the sale of products from different categories online.
>
>The system has two types of users, administrator and client. The administrator role allows you to manage user accounts, in addition to managing the store's products. On the other hand, the role of the client can see a showcase of products, place orders and check the status of the orders.
>
>To register on the platform, the interested party must click on the register button located at the top of the navigation bar and from there they can use the store and start purchasing products.

## Mainly developed with the following tecnologies
| [![logo-laravel.png](https://i.postimg.cc/sg3ch6qs/logo-laravel.png)](https://postimg.cc/sv07Fc3N) | [![logo-inertia.png](https://i.postimg.cc/1zdRFdWd/logo-inertia.png)](https://postimg.cc/cKQNyFG7) | [![logo-vue.png](https://i.postimg.cc/90nh7wFj/logo-vue.png)](https://postimg.cc/D4LHVZXC) | [![logo-tailwindcss.png](https://i.postimg.cc/0jQC4bGj/logo-tailwindcss.png)](https://postimg.cc/Q9259NyZ) |
| :-------------: |:-------------:|:-------------:|:-------------:|
| **10.0** | **1.0.0** | **3.2.41** | **3.2.1** |

### Read more about the tecnologies

- **[Laravel](https://laravel.com/)**
- **[Inertia.js](https://inertiajs.com)**
- **[Vue.js](https://vuejs.org)**
- **[Pinia](https://pinia.vuejs.org)**
- **[Vite](https://vitejs.dev)**
- **[Tailwindcss](https://tailwindcss.com)**
- **[CKEditor](https://ckeditor.com)**
- **[PHP](https://www.php.net/manual/es/intro-whatis.php)**
- **[PHPUnit](https://phpunit.de)**
- **[FakerPHP](https://fakerphp.github.io)**
- **[PHPStan](https://phpstan.org)**
- **[LaraStan](https://github.com/nunomaduro/larastan)**
- **[Spatie - Laravel Permission](https://spatie.be/docs/laravel-permission/v5/introduction)**

## Installation

1. Clone the repo locally:

```sh
git clone https://github.com/aasoto/MercaTodo.git merca-todo
cd merca-todo
```

2. Install PHP dependencies:

```sh
composer install
```

3. Install NPM dependencies:

```sh
npm install
```

4. Chose the mode of work with vite.

> - You can run the development mode:

```sh
npm run dev
```
>- Or build the assets
```sh
npm run build
```

5. Setup configuration:

```sh
cp .env.example .env
```

6. Generate application key:

```sh
php artisan key:generate
```

7. You need to create a new database with the name of the field DB_DATABASE in the file .env located on the root directory, the name of the database by default is merca-todo.

8. Run database migrations:

```sh
php artisan migrate
```

9. Run database seeder:

```sh
php artisan db:seed
```

10. Run the dev server (the output will give the address):

```sh
php artisan serve
```

## Sign Up
> You are ready to user the application, if you want to sign up with administrator mode use the following credentials
>
>>- **Username:** admin@example.com
>>- **Password:** 12345678
>
> Or if you want to sign up as a client use:
>>- **Username:** client@example.com
>>- **Password:** 12345678

## Running tests

To run the MercaTodo tests, run:

```
php artisan test
```
