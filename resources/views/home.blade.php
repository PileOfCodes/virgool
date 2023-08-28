<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @vite('resources/css/app.css')
    <script>
        window.csrf_token = '{{ csrf_token() }}'
    </script>
    @auth
    <script> 
    window.user = {
        id: "{{ auth('sanctum')->user()->id }}",
        name: "{{ auth('sanctum')->user()->name }}",
        profile_src: "{{ auth('sanctum')->user()->profile_src }}",
        isVerified: "{{ auth('sanctum')->user()->email_verified_at == null ? 1: 2 }}",
        roles: @json(auth('sanctum')->user()->roles->pluck('name'))
    }
    </script>
    @endauth
    <title>laravel vue</title>
</head>
<body>
    <div id="app">
        <router-view :key="$route.fullPath"></router-view>
    </div>

    @vite('resources/js/app.js')
</body>
</html>
