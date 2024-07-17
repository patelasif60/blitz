<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('front-assets/images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ URL::asset('front-assets/images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('front-assets/images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('front-assets/images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ URL::asset('front-assets/images/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ URL::asset('front-assets/images/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ URL::asset('front-assets/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96"
        href="{{ URL::asset('front-assets/images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ URL::asset('front-assets/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('front-assets/images/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ URL::asset('front-assets/images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <title>blitznet</title>
	<style>
	body, html {
	height: 100%;
	margin: 0;
	}

	.bgimg {
	background-image: url('{{ URL::asset('front-assets/images/front/Home-banner-3.png') }}');
	height: 100%;
	background-position: center;
	background-size: cover;
	position: relative;
	color: white;
	font-family: "Poppins", sans-serif;
	font-size: 25px;
	}

	.topleft {
	position: absolute;
	top: 0;
	left: 5%;
	}

	.bottomleft {
	position: absolute;
	bottom: 0;
	left: 5%;
	}

	.middle {
	position: absolute;
	top: 50%;
	left: 5%;
	//transform: translate(-50%, -50%);
	text-align: left;
	}

	hr {
	margin: auto;
	width: 40%;
	}

	p {
		font-size: 28px;
		text-shadow: 8px 0 20px #000;
	}
	h1 {
		font-size: 55px;
		font-weight: 600;
		text-shadow: 8px 0 20px #000;
	}
	</style>
</head>
<body>

<div class="bgimg">
  <div class="topleft">
    <p><img src="{{ URL::asset('front-assets/images/front/site-logo.png') }}" class="mx-auto" alt="site logo" /></p>
  </div>
  <div class="middle">
    <p>Empowering Local Economy</p>
    <h1>Connecting Buyers &amp; Suppliers<br> To do Business Together!</h1>
  </div>

</div>

</body>
</html>
