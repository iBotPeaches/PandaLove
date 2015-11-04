<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => '',
		'secret' => '',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'User',
		'secret' => '',
	],

	'google' => [
		'client_id' => '91509261390-e0t993ktrhgql2kbp1sndfrfrso76pvc.apps.googleusercontent.com',
		'client_secret' => 'ZPOfNqyTJUTmXy1tJmg6G27q',
		'redirect' => env('GOOGLE_REDIRECT', 'http://localhost:8000/auth/callback'),
	],

	'panda' => [
		'group_id' => 'UgxUq_ISrKy6NCSM-RV4AaABAQ'
	],

];
