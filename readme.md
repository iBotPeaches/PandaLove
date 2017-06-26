## PandaLove Website (Destiny/H5/Xbox APIs)

[![build status](https://gitlab.connortumbleson.com/iBotPeaches/PandaLove/badges/master/build.svg)](https://gitlab.connortumbleson.com/iBotPeaches/PandaLove/commits/master) [![license](https://img.shields.io/badge/license-dbad-green.svg)](http://www.dbad-license.org/)

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
* Comments
* Xbox "Who is Online" checks
* Halo 5 Stats
    * Post Game Carnage Reports (Arena/Warzone/FFA)
    * Historic Playlist history
    * Medal/Weapon counts
    * CSR Percentile
* Halo 5 Profile pages
* Calendar w/ RSVP support to attend events
* Validation of Ownership of Destiny account
* Admin Support via commands to API via Google Hangouts bot

## APIs
- Get ready to go online and register for 3 API keys. Some sites don't have instant activation.
- Go here https://xboxapi.com/ - Get an XboxAPI API Key and put it in `XBOXAPI_KEY` in `.env` file.
- Go here https://www.bungie.net/en/User/API - Get a Destiny API Key and put it in `BUNGIE_KEY` in `.env` file.
- Go here https://developer.haloapi.com/ - Get a Halo API Key and put it in `HALO5_KEY` in `.env` file.
- Now we need to utilize those APIs to get some data. If you don't have API keys for the above 3 systems. This will fail.
    - `php artisan halo5:batch-metadata`
- Go here https://console.developers.google.com - "Create a Project"
    - After creating project on Google Developer go to "Enable APIs and get credentials like keys"
    - Find "Google+ API" and enable it. Then click on it. Click "Credentials" on sidebar.
    - Create a "Web Application" application to get "ClientID", "Client Secret" and redirect URL.
    - Redirect URL will be `$URL/auth/callback`. In the case of `php serve` - `http://localhost:8000/auth/callback`.
    - Once created replace `GOOGLE_REDIRECT` with the RedirectURL in `.env` file.
    - Replace `GOOGLE_ID` with the ClientID in `.env` file.
    - Replace `GOOGLE_SECRET` with the Client Secret in `.env` file.

## How to install (PHP 7.0)
- `sudo apt-get install apache2 mysql-server php7.0 libapache2-mod-php7.0 php7.0-mbstring php7.0-xml php7.0-mysql`
- Get [Composer](https://getcomposer.org/)
- Get [NodeJs](http://nodejs.org/)
- `curl -o- -L https://yarnpkg.com/install.sh | bash`
- `cp .env.example .env`
- `php artisan key:generate`
- `php artisan clear-compiled`
- `npm install`
- `composer install && yarn install`
- `sudo npm install -g gulp`
- `cd node_modules/semantic-ui; gulp build; cd ../..;`
- Create DB and user and add to .env
- Go do API stuff above
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan halo5:batch-metadata`
- `php artisan serve`

## Thanks
* 343 - Halo 5 API - [https://developer.haloapi.com](https://developer.haloapi.com)
* Bungie - Destiny API - [https://bungie.net](https://bungie.net)
* XboxAPI.com - Xbox API - [https://xboxapi.com/](https://xboxapi.com/)
* Hangups Bot - Google Hangouts Bot - [https://github.com/hangoutsbot/hangoutsbot](https://github.com/hangoutsbot/hangoutsbot)
* Laravel 5.1 - PHP Framework - [http://laravel.com](http://laravel.com/)
* Semantic UI - CSS Framework [http://semantic-ui.com](http://semantic-ui.com/)
* HTML5UP - Initial Template [http://html5up.net/](http://html5up.net/)