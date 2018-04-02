<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Installation

To run application you need to have installed: PHP 7.2, MySQL 5.7, Nginx/Apache, Redis, npm, node and composer.
I recommend you to use laravel homestead for this purpose.

Download source code from (https://github.com/zezeljquantox/chord)

Run composer install.

Edit .env to match your configuration

Import database from database/dump

Download source code of node server from: (https://github.com/zezeljquantox/chord_node)

Run npm install

Set mysql database credentials inside mysql_connection.js

Start server.js : node server.js or use some task runner for node (forever, nodemon)

Add cron entry to your server:
```
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```
Enjoy, sorry if i missed something.

## About application

The application consists of two parts. The first part is related to showing information about postcodes (tasks 1 and 2).
This is home page and unauthenticated users can access to it.

The second part of application is reserved for authenticated users. After the user logs in,
  the list of houses will be shown to him. Users can react on houses (like/dislike).
  When two users like each other's house, a match is created, allowing them to start 
  messaging each other. Also, they can swap house with other user.
  If you want to send a message to another user, you first need to select user from the list of available users.
   This page relies on web sockets, because of that you need to run node server in the background.
   There is a missing functionality for notifications of newly received messages as well as feature 'seen' for already viewed messages.
   
## Application structure

Backend application have three main parts: App, Domain and Http.

In App directory you will find core classes, providers, traits and console commands.

In Domain directory are located all modules of application. Each module consists of several parts:

- Models
- Repositories
- Services
- Events

Http directory is responsable for handling requests. it is divided into modules, similar to Domain,
but with different structure:

- Controllers
- Requests
- ViewComposers
- Exeptions

Communication between laravel and node server is achieved through Redis pub/sub functionality.
 Beside that Redis is used as cache driver.
 
 There are two commands which should be run every minute. One of them is command for generating csv, and another one is for clean up directory.
 If you don't run cronjob, csv will be generated on every user request.
 
 You need to have symlink on storage/app/public, so files from that directory can be accessed by clients.
 
 ```
 php artisan storage:link
 ```
  or you can manually create soft link.
