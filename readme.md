## PandaLove Website (Destiny/H5/Xbox/ APIs)

[![build status](https://gitlab.connortumbleson.com/ci/projects/1/status.png?ref=master)](https://gitlab.connortumbleson.com/ci/projects/1?ref=master)

PandaLove is a website devoted to tracking our weekly raid Tuesdays, PVP destruction and more. It helps us to allow trash talking among Panda Love.


# How to install
1. Get [Composer](https://getcomposer.org/)
2. Get [NodeJs](http://nodejs.org/)
3. `git clone git@github.com:iBotPeaches/PandaLove.git`
4. `cd PandaLove`
5. `composer install`
6. `npm install`
7. `npm install -g gulp`
8. `cp .env.example .env`
9. Create a local database. Write down the database name, along with database username & password.
10. Change values in `.env` to match your settings (the 3 database fields)
11. `php artisan migrate`
12. `php artisan db:seed`
13. `gulp`
14. `php artisan serve`
15. done
