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

> *Installing the following configuration is not mandatory, but you should make sure that the development tools you are using locally can support this project. In case of an error during installation you should seek help based on your environment. __This application was developed using Windows as operating system__.*

* ### [XAMPP](https://www.apachefriends.org/)
This is the most popular PHP development environment. XAMPP is a completely free and easy to install Apache distribution that contains [MariaDB](https://mariadb.org/), [PHP](https://www.php.net/manual/en/intro-whatis.php) and [Perl](https://www.perl.org). The XAMPP installation package has been designed to be incredibly easy to install and use.
>[Download version of development of this application.](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.1.6/)
>>If you have an older or newer version of XAMPP installed, keep in mind that this application was developed with [PHP 8.1.6](https://www.php.net/downloads.php) and that it also has [Xdebug version 3.2.1](https://xdebug.org/docs/install) configured.
>
>> 1. [What Xdebug is?](https://xdebug.org/) 
>> 1. [How to configure Xdebug with XAMPP?](https://www.youtube.com/watch?v=TexkCrk6njc) additionally add the line *xdebug.mode=coverage* to the file **php.ini**.

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

## How can I read this code properly?
You can use one of these free code editors like [Visual Studio Code](https://code.visualstudio.com/), [Sublime Text](https://www.sublimetext.com/), [etc](https://www.hostinger.com/tutorials/best-code-editors). or a full IDE like [PHPStorm](https://www.jetbrains.com/phpstorm/).

## Installation

1. [Clone the GitHub repository](https://docs.github.com/en/repositories/creating-and-managing-repositories/cloning-a-repository) locally:

```sh
git clone https://github.com/aasoto/MercaTodo.git merca-todo
cd merca-todo
```

2. [Install PHP dependencies](https://getcomposer.org/doc/01-basic-usage.md):

```sh
composer install
```

3. [Install npm dependencies](https://docs.npmjs.com/cli/v8/commands/npm-install):

```sh
npm install
```

4. Chose the mode of work with [vite](https://vitejs.dev/).

> - You can run the [development mode](https://laravel.com/docs/10.x/vite#running-vite)
>```sh
>npm run dev
>```
>- Or build the [assets](https://laravel.com/docs/10.x/vite#running-vite)
>```sh
>npm run build
>```

5. [Setup configuration](https://laravel.com/docs/10.x/configuration#environment-configuration):

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
> * Open the command console CMD.
> * Locate yourself in the root of your system and run the following command:
> ```sh
> cd C:\xampp\mysql\bin
> ```
> * Access the MySQL command line
>```sh
> mysql -u root -p
>```
> * If your databases has password, type it or, if it hasn't push enter.
> * Create database
>```sh
> create database merca_todo;
>```
> The Other option of create database if you are using XAMPP as PHP development environment is using phpMyAdmin, who comes by default with the installation of XAMPP. 
> * To access phpMyAdmin you need to open the XAMPP control panel, then activate the MySQL service by clicking the start button, then when the background of the letters turns green and the PID and port have been assigned, you must click on the button Admin in the same row of MySQL. [How to create a database with phpMyAdmin?](https://www.youtube.com/watch?v=k9yJR_ZJbvI)
>
8. Run database [migrations](https://laravel.com/docs/10.x/migrations#running-migrations) and [seeders](https://laravel.com/docs/10.x/seeding#running-seeders):

```sh
php artisan migrate --seed
```

9. Public the symbolic links, the folders need to be created in _storage/app_:

> For create the remaining required folders please introduce the following commands (you need to be located in the project root):
>```sh
> mkdir storage\app\exports storage\app\reports
>```

Finally public the created folders.
```sh
php artisan storage:link
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

## Task Scheduling
The software has been programmed for a periodic review of the order status, to enable this option use one of the following commands. [How this works?](https://laravel.com/docs/10.x/scheduling)

> For run the scheduled tasks or commands (for one time)
> ```sh
> php artisan schedule:run
> ```
> Start the schedule worker (execute periodly the scheduled tasks or commands)
> ```sh
> php artisan schedule:work
> ```

## Queues
This application works with queues for the email verfication, export and import of products and for the generation of reports, please run this command for dispatch the jobs:

>The download of the generated files from the email only works if you have specified the environment variable called *APP_URL* in **.env** file with your domain URL. Example: *[https://www.mydomain.com]()*
```sh
php artisan queue:work
```
* If you want to know more about queues and how they work internally, [please click here.](https://laravel.com/docs/10.x/queues)

## Running tests
These tests has been made with [PHPUnit](https://phpunit.de/); you can learn more about the assertions reading [How can I test a Laravel application?](https://laravel.com/docs/10.x/testing) or [How to tests Endpoints with Inertia.js?](https://inertiajs.com/testing)

* For run the tests make you sure that you have created a database called *test*, if you have not please create it, you can follow the example given at the step **7** when we created the database.

To run the MercaTodo tests you can use one of these commands:

> ```
> php artisan test
> ```
> ```
> vendor/bin/phpunit
> ```
> ```
> vendor/bin/phpunit --testdox
> ```

Current coverage: **98.27%**
- **[See code current coverage report](https://mercatodo-coverage.netlify.app/)**

To generate the coverage views in HTML, use the following command, remember that you must have [Xdebug configured](https://www.youtube.com/watch?v=TexkCrk6njc) with the version of PHP you use. Additionally add the line *xdebug.mode=coverage* to the file **php.ini**.

```sh
vendor/bin/phpunit --coverage-html tests/coverage
```

## Search bugs with [PHPStan](https://phpstan.org/)
Find bugs before they reach production. PHPStan scans your whole codebase and looks for both obvious & tricky bugs. Even in those rarely executed if statements that certainly aren't covered by tests. You can run it on your machine and in CI to prevent those bugs ever reaching your customers in production. [Learn more about it.](https://phpstan.org/user-guide/getting-started)

If you want to measure the quality of the code run this command:
```sh
vendor/bin/phpstan analyse -level [choose level from 0 to 9]
```
[Learn more about PHPStan levels](https://phpstan.org/user-guide/rule-levels)

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
1. You can opcionally change the name of the environment variables called MAIL_FROM_ADDRESS and MAIL_FROM_NAME, who comes configured from the file .env.example.

* ## [Placetopay by Evertec](https://sites.placetopay.com/)
You need to configure the Webcheckout API payment gateway with the access credentials, exactly are the _login_, _tranKey_ and _url_; those credentials are provided by Placetopay. When you had the creditials what you need to do is go to the .env file and put the _login_ in the variable __PLACETOPAY_LOGIN__, the _tranKey_ in __PLACETOPAY_TRANKEY__ and the _url_ in __PLACETOPAY_URL__. Also you will need to specify the __endpoint__ of the API, the __endpoint__ has to be assigned in the constant called __API_SESSION__ located on _App/Domain/Order/Services/Contracts/Placetopay/Endpoints.php_

## Product API features
This web application has a REST API, which allows product management, including dependent tables (categories and units) and complementary functionalities (export, import and image management). In turn, as an additional complement, it also has a small authentication module.
You can read all the __documentation [here](https://documenter.getpostman.com/view/23091806/2s946fdCN6)__ and also download the __postman collection [here](https://drive.google.com/drive/folders/1i1C2Pq01xI0r2aV-0hR9MYVnWfPfJuF_?usp=sharing)__ to be able to test the endpoints.
