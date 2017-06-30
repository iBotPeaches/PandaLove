## PandaLove Website (Destiny/H5/Xbox APIs)

[![build status](https://gitlab.connortumbleson.com/iBotPeaches/PandaLove/badges/master/build.svg)](https://gitlab.connortumbleson.com/iBotPeaches/PandaLove/commits/master)  [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

PandaLove was a website devoted to tracking our weekly raid Tuesdays, PVP destruction and more in Destiny. It quickly grew to be more than that
supporting now 3 full games (Destiny, Halo 5 & Overwatch) with countless plugins from Xbox API, Google Hangouts, Calendar and more.

# Features
* Destiny
    * Profile Pages
        * Character gear
        * Light, playtime
        * Unbroken Games list
    * Post Game Carnage Reports
        * Score, Kills, Deaths, Assists, KD, KDR
        * Comment System
        * "Drunk Mode" - games added but not shown to visitors
    * Trials of Orisis complete card tracking/analytics
        * Random Map support
        * Determines boone usage based on win count
        * Support for games that enemy team quits out
        * Combined analytics of all trial matches
    * Xur Module
        * Determines Xur loadout, API endpoint for Google Hangouts
    * Grimoire Module
        * Determines grimoire over API for any user
* Halo 5
    * Profile Pages
        * Warzone (Overview, Medals, Weapons)
        * Arena (Overview, Playlists, Seasons, Medals, Weapons)
        * CSR Percentile
    * Post Game Carnage Reports
        * Quick Game Facts
        * Per Team Analytics
        * Game MVP
        * Medal Overview
        * Per Spartan Stats (Medals, Weapons, Killed By, Killed)
    * Enhanced Post Game Carnage Reports
        * Chart of kills over time, points labeled with kill/death
        * Timeline of every weapon drop/pickup/kill/death
        * Top down maps (PRIMA licensed maps, restricted to admin panel)
        * Round module for round based games like Elimination
    * KD Over time charts
        * KD/KAD for Arena/Warzone of just clan members.
        * Updated daily before clan members time on Halo 5 reached none.
    * API Module
        * Feeds data for [leafapp.co](https://leafapp.co/)
* Overwatch
    * Profile Pages
        * Current Season analytics
        * Historic seasons as they end (nothing retroactive)
        * Per Character stats for seasons
* Xbox
    * Validation System
        * Prove ownership of gamertag by using "motto" field for unique code
    * Online System
        * Checks active PandaLove members for online status via bot
    * GUID recording
        * Allows easier gamertag changes
* Calendar
    * Event module
        * Create games for Destiny, Halo5 & Overwatch
        * RSVP via API in Google Hangouts
        * Make event via API in Google Hangouts
        * Specify character being used for Destiny
    * Calendar module
        * UI on website for clan members to view calendar
* Google Hangouts
    * Alert module
        * Can send private messages at 15m and 5m till event to remind attendees
    * Association module
        * Google id allows look up of validated gamertags for easy API updates
* API
    * Hangout module
        * Communicates with plugins to a Google hangout bot
        * Bot sits in chat awaiting `/bot` commands
    * Update module
        * Update Destiny stats via `/bot destiny`
        * Update Overwatch stats via `/bot ow`
        * Update Halo5 stats via `/bot h5`
        
## Contributors
 * [Contributors](https://github.com/iBotPeaches/PandaLove/graphs/contributors)

## APIs
- Get ready to go online and register for 3 API keys. Some sites don't have instant activation.
- Go here https://xboxapi.com/ - Get an XboxAPI API Key and put it in `XBOXAPI_KEY` in `.env` file.
- Go here https://www.bungie.net/en/User/API - Get a Destiny API Key and put it in `BUNGIE_KEY` in `.env` file.
- Go here https://developer.haloapi.com/ - Get a Halo API Key and put it in `HALO5_KEY` in `.env` file.
- Go here https://github.com/SunDwarf/OWAPI - You don't need an API key, but be reasonable. Toss the dev some money.
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
* owapi.net - Overwatch API (Scraper) -[https://github.com/SunDwarf/OWAPI](https://github.com/SunDwarf/OWAPI)
* Hangups Bot - Google Hangouts Bot - [https://github.com/hangoutsbot/hangoutsbot](https://github.com/hangoutsbot/hangoutsbot)
* Laravel 5.1 - PHP Framework - [http://laravel.com](http://laravel.com/)
* Semantic UI - CSS Framework [http://semantic-ui.com](http://semantic-ui.com/)
* HTML5UP - Initial Template [http://html5up.net/](http://html5up.net/)