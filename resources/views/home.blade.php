<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>{{ config('app.name', 'Licencia SIPROSA') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="//dyb3lop9kdviu.cloudfront.net/assets/favicon/favicon-96x96-478c05a4e78c23f90988716c7478c6aa9e904d20904cd8ebe8a204fa666bbe62.png" rel="icon" sizes="96x96" type="image/png">
    <script src="https://kit.fontawesome.com/8df74ae2d0.js"></script>
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<main>
    <div id="app">

    </div>
</main>
</body>
</html>
