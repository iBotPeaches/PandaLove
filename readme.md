## PandaLove Raid Website

[![build status](http://warlock.connortumbleson.com/projects/5/status.png?ref=master)](http://warlock.connortumbleson.com/projects/5?ref=master)

PandaLove is a website devoted to tracking our weekly raid Tuesdays, PVP destruction and more. It helps us to allow trash talking among Panda Love.


# How to install
1. Get [Composer](ssh://git@gitlab.techcomworldwide.com:22774/iBotPeaches/PandaLove.git)
2. `git clone ssh://git@gitlab.techcomworldwide.com:22774/iBotPeaches/PandaLove.git`
3. `cd PandaLove`
4. `composer install`
5. `cp .env.example .env`
6. Change values in `.env` to match your settings. Obviously create DB
7. `php artisan db:migrate`
8. `php artisan db:seed`
9. `php artisan serve`
10. done