## PandaLove Website (Destiny/H5/Xbox APIs)

[![build status](https://gitlab.connortumbleson.com/ci/projects/1/status.png?ref=master)](https://gitlab.connortumbleson.com/ci/projects/1?ref=master)[![license](https://img.shields.io/badge/license-dbad-green.svg)](http://www.dbad-license.org/)

PandaLove was a website devoted to tracking our weekly raid Tuesdays, PVP destruction and more. It quickly grew to be more than that.

# Features
* Destiny Stats
    * Trials of Orisis
    * Prison of Elders
    * Raids
    * PVP
* Destiny Profile pages
* Destiny Post Game Reports
* Destiny XUR checks
* Roster page for "PandaLove" clan
* Enhanced Destiny Trials reports
* Comments
* Xbox "Who is Online" checks
* Halo 5 Active Playlists
* Halo 5 Profile pages
* Calendar w/ RSVP support to attend events
* Validation of Ownership of Destiny account
* Admin Support via commands to API via Google Hangouts bot

## How to install
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

## Thanks
* 343 - Halo 5 API - [https://developer.haloapi.com](https://developer.haloapi.com)
* Bungie - Destiny API - [https://bungie.net](https://bungie.net)
* XboxAPI.com - Xbox API - [https://xboxapi.com/](https://xboxapi.com/)
* Hangups Bot - Google Hangouts Bot - [https://github.com/hangoutsbot/hangoutsbot](https://github.com/hangoutsbot/hangoutsbot)
* Laravel 5.1 - PHP Framework - [http://laravel.com](http://laravel.com/)
* Semantic UI - CSS Framework [http://semantic-ui.com](http://semantic-ui.com/)
* HTML5UP - Initial Template [http://html5up.net/](http://html5up.net/)