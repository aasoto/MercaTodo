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
|:-------------:|:-------------:|:-------------:|:-------------:|
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


## Prepare your development environment
The MercaTodo web application was developed using the following tools, so it is recommended to install it using this configuration:

* ### [XAMPP](https://www.apachefriends.org/)
This is the most popular PHP development environment. XAMPP is a completely free and easy to install Apache distribution that contains [MariaDB](https://mariadb.org/), [PHP](https://www.php.net/manual/en/intro-whatis.php) and [Perl](https://www.perl.org). The XAMPP installation package has been designed to be incredibly easy to install and use.
>[Download version of development of this application.](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.1.6/)
>>If you have an older or newer version of XAMPP installed, keep in mind that this application was developed with [PHP 8.1.6](https://www.php.net/downloads.php) and that it also has [Xdebug version 3.2.1](https://xdebug.org/docs/install) configured.
>
>> 1. [What Xdebug is?](https://xdebug.org/) 
>> 1. [How to configure Xdebug with XAMPP?](https://www.youtube.com/watch?v=TexkCrk6njc)

* ### [Composer](https://getcomposer.org/)
Composer is a tool for dependency management in PHP. It allows you to declare the libraries your project depends on and it will manage (install/update) them for you.
> Remember that [Laravel 10.0](https://laravel.com/docs/10.x/releases) our development framework requires Composer 2.2.0 or greater. [Download the latest version of composer here.](https://getcomposer.org/download/)

* ### [Node.js](https://nodejs.org/en)
Node.js is a cross-platform, open-source server environment that can run on Windows, Linux, Unix, macOS, and more. Node.js is a back-end JavaScript runtime environment, runs on the V8 JavaScript Engine, and executes JavaScript code outside a web browser.
> [Download the latest version of Node.js](https://nodejs.org/en/download)
>> We are installing this development environment since we are going to use its package manager which is **npm**
>>
>> **npm**
>> is a package manager for the JavaScript programming language maintained by npm, Inc. npm is the default package manager for the JavaScript runtime environment Node.js. [Read more.](https://docs.npmjs.com/about-npm)

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

7. You need to create a database with the name of the DB_DATABASE field located in the .env file, the default database name is merca_todo. You can choose your preferred database management application. It can be [MySQL Workbench](https://www.mysql.com/products/workbench/), [DBeaver](https://dbeaver.io/), [phpMyAdmin](https://www.phpmyadmin.net/), etc.

> **Example**
>
> If you have XAMPP installed as a PHP development environment do the following steps for create the MySQL database by command console.
> * Open the command console prefered it can be CMD, PowerShell y Git Bash.
> * Locate yourself in the root of your system and run the following command:
> ```sh
cd C:\xampp\mysql\bin
>```
> * Access the MySQL command line
>```sh
mysql -u root -p
>```
> * If your databases has password, type it or, if it hasn't push enter.
> * Create database
>```sh
create database merca_todo
>```
> The Other option of create database if you are using XAMPP as PHP development environment is using phpMyAdmin, who comes by default with the installation of XAMPP. 
> * To access phpMyAdmin you need to open the XAMPP control panel, then activate the MySQL service by clicking the start button, then when the background of the letters turns green and the PID and port have been assigned, you must click on the button Admin in the same row of MySQL. [How to create a database with phpMyAdmin?](https://www.youtube.com/watch?v=k9yJR_ZJbvI)
>
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
* For run the tests make you sure that you have created a database called *test*, if you have not please create it, you can follow the example given at the step **7** when we created the database.

To run the MercaTodo tests you can use one of these commands:

> ```
php artisan test
> ```
> ```
vendor/bin/phpunit
> ```
> ```
vendor/bin/phpunit --testdox
> ```

Current coverage: **98.1%**
- **[See code current coverage report](https://soft-stardust-f90554.netlify.app/)**

To generate the coverage views in HTML, use the following command, remember that you must have Xdebug configured with the version of PHP you use.
```sh
vendor/bin/phpunit --coverage-html tests/coverage
```

## Additional settings

* ### [Mailtrap](https://mailtrap.io/)
Mailtrap is an Email Delivery Platform for businesses and individuals to test, send, and control email infrastructure in one place.
We use this tools in the verification of user's email, it's necessary that you make this configuration if you want to create new users appart of those who has been created by default.
#### To configure Mailtrap:
1. [Go to this page.](https://mailtrap.io/)
1. You need to create an account.
1. Go to the Email testing tab.
1. Create a new inbox or use the one that is created.
1. Rename the inbox.
1. Open the inbox.
1. On the right side, in the integration section, you must select the development framework, in this case select Laravel 7+.
1. Copy the given variables and paste them over the same environment variables in the .env file.
1. Finally change the name of the environment variable called MAIL_FROM_ADDRESS and place the email of the Mailtrap account that is being used.

* ## [Placetopay by Evertec](https://sites.placetopay.com/)
You need to configure the Webcheckout API payment gateway with the access credentials, exactly are the _login_, _tranKey_ and _url_; those credentials are provided by Placetopay. When you had the creditials what you need to do is go to the .env file and put the _login_ in the variable __PLACETOPAY_LOGIN__, the _tranKey_ in __PLACETOPAY_TRANKEY__ and the _url_ in __PLACETOPAY_URL__.
