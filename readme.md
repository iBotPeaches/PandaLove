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
9. Create a local database, `MySQL` preferred.
10. Edit `.env` using those database credentials.
    1. Edit `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
11. Get ready to go online and register for 3 API keys. Some sites don't have instant activation.
12. Go here https://xboxapi.com/ - Get an XboxAPI API Key and put it in `XBOXAPI_KEY` in `.env` file.
13. Go here https://www.bungie.net/en/User/API - Get a Destiny API Key and put it in `BUNGIE_KEY` in `.env` file.
14. Go here https://developer.haloapi.com/ - Get a Halo API Key and put it in `HALO5_KEY` in `.env` file.
15. `php artisan migrate`
16. `php artisan db:seed`
17. Now we need to utilize those APIs to get some data. If you don't have API keys for the above 3 systems. This will fail.
    18. `php artisan halo5:batch-metadata`
23. Go here https://console.developers.google.com - "Create a Project"
    24. After creating project on Google Developer go to "Enable APIs and get credentials like keys"
    25. Find "Google+ API" and enable it. Then click on it. Click "Credentials" on sidebar.
    26. Create a "Web Application" application to get "ClientID", "Client Secret" and redirect URL.
    27. Redirect URL will be `$URL/auth/callback`. In the case of `php serve` - `http://localhost:8000/auth/callback`.
    28. Once created replace `GOOGLE_REDIRECT` with the RedirectURL in `.env` file.
    29. Replace `GOOGLE_ID` with the ClientID in `.env` file.
    30. Replace `GOOGLE_SECRET` with the Client Secret in `.env` file.
31. `gulp`
32. `php artisan serve`
33. Click the GooglePlus sign in button on top navigation.
34. (The first user to sign in automatically becomes admin)
35. Sign in with GoogleAccount, this should prompt the sign in you made on previous steps.
36. Now go to `/usercp` via gear icon in navigation.
37. Add gamertag (assuming you play Xbox One - Destiny) to Destiny section.
38. Add gamertag (assuming you play Xbox One - Halo5) to Halo 5 section.
39. Celebrate.

## Thanks
* 343 - Halo 5 API - [https://developer.haloapi.com](https://developer.haloapi.com)
* Bungie - Destiny API - [https://bungie.net](https://bungie.net)
* XboxAPI.com - Xbox API - [https://xboxapi.com/](https://xboxapi.com/)
* Hangups Bot - Google Hangouts Bot - [https://github.com/hangoutsbot/hangoutsbot](https://github.com/hangoutsbot/hangoutsbot)
* Laravel 5.1 - PHP Framework - [http://laravel.com](http://laravel.com/)
* Semantic UI - CSS Framework [http://semantic-ui.com](http://semantic-ui.com/)
* HTML5UP - Initial Template [http://html5up.net/](http://html5up.net/)