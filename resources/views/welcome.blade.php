<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>FATİKKK</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body style="background: black;
    color: white;">
    <h1>Welcome to My Application</h1>

    <nav>
        <ul>
            <li><a href="{{ route('cities.index') }}">Get Cities</a></li>
            <li><a href="{{ route('districts.index', ['city_id' => 1]) }}">Get Districts for City 1</a></li>
            <li><a href="{{ route('districts.index', ['city_id' => 2]) }}">Get Districts for City 2</a></li>
            <li><a href="{{ route('accounts.index') }}">View Accounts</a></li>
            <li><a href="{{ route('carts.index') }}">View Carts</a></li>
            <li><a href="{{ route('categories.index') }}">View Categories</a></li>
            <li><a href="{{ route('image-paths.index') }}">View Image Paths</a></li>
            <li><a href="{{ route('likes.index') }}">View Likes</a></li>
            <li><a href="{{ route('otp.index') }}">View OTPs</a></li>
        </ul>
    </nav>

    <!-- Add your JavaScript here -->
</body>

</html>
