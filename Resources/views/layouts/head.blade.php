<!DOCTYPE html>
<html >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>{{ config('app.name', 'FactuDav') }}</title>

    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://api.jquery.com/resources/events.js"></script>
    <script type='text/javascript' src='http://code.jquery.com/jquery-1.7.2.min.js'></script>
    @yield('styles')

    <!-- Custom Theme Style -->
</head>

@yield('app')
