# BulletinBoard

This is a training project - a bulletin board where you can sell goods and services.

<p align="center">
  <img src="https://sun9-23.userapi.com/impg/KBFdt_R-IuTzcTwkf4NaIqo5Iuxy26NEtwHYvw/VVZK1Z0nPZ4.jpg?size=1858x1474&quality=96&proxy=1&sign=1d2745c4c3a0ad5ff0207a67d43ae652&type=album" width="500" title="hover text">
</p>

##### Table of Contents

-   [Requirements](#Requirements)
-   [Installation](#Installation)
-   [DataBase structure](#database)
-   [Main page](#main)
-   [Login page ](#login)
-   [Register page ](#register)
-   [Bulletin list ](#list)
-   [Bulletin create and edit ](#edit)
-   [Bulletin show page ](#show)

## Requirements

    Docker version 19.03.12
    Composer version 2.0.8
    NPM version  6.14.5

## Installation

    $ git clone https://github.com/viktor0018/bulletin-board.test
    $ cd bulletin-board.test
    $ composer install

Run the sail:

    $ sail up

Run migrations:

    $ sail artisan migrate:fresh

Seeding the database :

    $ sail artisan db:seed

Run elastic search reindex:

    $ sail artisan search:reindex

Run rabbitmq queue

    $ sail artisan queue:work

Frontend:

    $ git clone https://github.com/viktor0018/bulletin-board.frontend
    $ cd bulletin-board.frontend
    $ npm install
    $ npm run serve

Now you can access the application via http://0.0.0.0:80

Frontend via http://localhost:8083/

And application database via adminer - http://0.0.0.0:8081/?pgsql=postgres&username=root&db=bulletin_board.test&ns=public&table=users

RabbitMQ

http://localhost:15672/

Usefull commands:

    alias sail='bash vendor/bin/sail'
    sail artisan optimize:clear

<a name="database"/>

## DataBase structure

    https://app.dbdesigner.id/?action=open&uuid=064808d3-c135-4ee3-9957-acfa12900353

<p align="center">
  <img src="https://sun9-6.userapi.com/impg/rz2ITBp09RkKVVxoYziqTBMGA6zYGUKUQizMcg/PfBMeFa552M.jpg?size=2560x1499&quality=96&proxy=1&sign=9fbc7deb9e83d24a31a2c427a8301d70&type=album" width="500" title="hover text">
</p>

<a name="main"/>

## Main page

<p align="center">
  <img src="https://sun9-23.userapi.com/impg/KBFdt_R-IuTzcTwkf4NaIqo5Iuxy26NEtwHYvw/VVZK1Z0nPZ4.jpg?size=1858x1474&quality=96&proxy=1&sign=1d2745c4c3a0ad5ff0207a67d43ae652&type=album" width="500" title="hover text">
</p>

On the main page you can search bulletins by region, city, category, by price and by text. Text search is implemented with elasticsearch.

Also you can log in or register.

<a name="login"/>

## Login page

<p align="center">
  <img src="https://sun9-71.userapi.com/impg/FTb98D2ZoLQoSm0u-FQkBAb6wJyLi9Z225HfJg/JLUE1ZiZDOU.jpg?size=2070x1512&quality=96&proxy=1&sign=1e589cbd5f7498644de775d8970511b4&type=album" width="500" title="hover text">
</p>

All attempts of authorization are checked with Google ReCaptcha

If you have forgotten your password, you can request a password reset.
To do this, go to [password forgot page ](http://localhost:8083/?#/password_forgot "title ") and enter your email.
If there is exists such email in the database, you will receive a letter with a token for resetting your password (implemented with the native laravel functionality)

<a name="register"/>

## Register page

<p align="center">
  <img src="https://sun1-96.userapi.com/impg/A-6ENuVCcnmUvAC8ByI22jnGP1-SspL3qD0arQ/aOsX4WNIWWE.jpg?size=2352x1520&quality=96&proxy=1&sign=4114edfd8c6f9105936b12575845ee63&type=album" width="500" title="hover text">
</p>

All attempts of registration are checked with Google ReCaptcha

Email and phone fields are unique for all users. After registration you will recieve email with verification link. (Implemented with the native laravel functionality)

<a name="list"/>

## Bulletin list

<p align="center">
  <img src="https://sun9-29.userapi.com/impg/HjbQLz6YjKeK47AR_RnsscVgU7VPWJ6697-hXw/CBp-u7PoPDs.jpg?size=2010x1382&quality=96&proxy=1&sign=c80dedf76cc38f89dcf7da41502860e6&type=album" width="500" title="hover text">
</p>

After login you get access fou your bulletin list.
Here you can add new one, see, send to moderate, edit and delete it.

<a name="edti"/>

## Bulletin create and edit

<p align="center">
  <img src="https://sun9-38.userapi.com/impg/4JpGTEfD0j2yrVMD5v1qvLnR8N1Zwob9xcmwOQ/7XvAcKjozHg.jpg?size=2016x1482&quality=96&proxy=1&sign=1d02be750a07a02bf240bef7396bfb86&type=album" width="500" title="hover text">
</p>

Here you can edit your bulletin. You can uplod up to 10 photos. Also you can select which of them will be main photo. (Will be demonstrate on main page).

<a name="show"/>

# Bulletin show page

<p align="center">
  <img src="https://sun9-28.userapi.com/impg/gdA9iUj5SRLsTJ2RSVcIJhYM6-fqo6AmdE5M9g/byNOQ5xhkIA.jpg?size=2006x1186&quality=96&proxy=1&sign=057d0b73966076c61dbd63f468a7443e&type=album" width="500" title="hover text">
</p>

<p align="center">
  <img src="https://sun9-41.userapi.com/impg/srIrlc-NDFDI8-SG0O-a5NLrKfz9sGXUmKaF-A/fIBFde9XZHs.jpg?size=1982x1520&quality=96&proxy=1&sign=ca997e7ba92fc10a10bcd7b5409aea00&type=album" width="500" title="hover text">
</p>

Here you can see your bulletin. All photos are accesible via carousel plugin.
