<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ $title or 'Panda Love' }}</title>
	<!--[if lte IE 8]><script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script><![endif]-->
	<script src="{{ elixir("js/jquery.min.js") }}"></script>
	<script src="{{ elixir("js/jquery.scrolly.min.js") }}"></script>
	<script src="{{ elixir("js/skel.min.js") }}"></script>
	<script src="{{ elixir("js/init.js") }}"></script>
	<script src="{{ elixir("js/app.js") }}"></script>
	<noscript>
		<link rel="stylesheet" href="{{ elixir('css/skel.css') }}" />
		<link rel="stylesheet" href="{{ elixir('css/style.css') }}" />
		<link rel="stylesheet" href="{{ elixir('css/style-desktop.css') }}" />
	</noscript>
	<!--[if lte IE 8]><link rel="stylesheet" href="{{ asset('css/ie/v8.css') }}" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="{{ asset('css/ie/v9.css') }}" /><![endif]-->
	<link rel="stylesheet" href="{{ elixir('css/app.css') }}" />
	@yield('inline-css')
</head>
<body>
	@include('includes.navigation')
	@include('includes.message')
	@yield('content')
	@include('includes.footer')
	@yield('inline-js')
</body>
</html>
