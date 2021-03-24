<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Proxy checker</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">

        <x-navbar/>

        <h1>Welcome to the Proxy Checker!</h1>

        <form action={{ route('check') }} method="POST">
            @csrf
            <div class="form-group">
                <label for="proxyFormControlTextarea">Enter proxy list into text area below:</label>
                <textarea class="form-control" name="proxy_list" id="proxyFormControlTextarea" rows="5"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Check</button>
        </form>

    </div>
</body>

</html>
