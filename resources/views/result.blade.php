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
        <x-navbar />

        {{-- @dd($proxy) --}}

        <h1>Результаты проверки прокси<b></h1>

        <div class="alert alert-primary" role="alert">
            <p>Проверено <b>{{ $log->complete }}</b> из <b>{{ $log->total }}</b> проксей.</p>
            <p>Проверено <b>{{ $log->alive }}</b> из них рабочие.</p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    {{-- <th scope="col">№</th> --}}
                    <th scope="col">Адрес прокси</th>
                    <th scope="col">Тип</th>
                    <th scope="col">Местоположение</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Скорость скачивания</th>
                    <th scope="col">Реальный IP</th>
                    <th scope="col">Время проверки</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proxies as $proxy)
                    <tr>
                        {{-- <th scope="row">{{ $proxy['id'] }}</th> --}}
                        <td>{{ $proxy['ip'] }}</td>
                        <td>{{ $proxy['type'] }}</td>
                        <td>{{ $proxy['country_city'] }}</td>
                        <td>{{ $proxy['status'] ? 'Работает' : 'Не работает' }}</td>
                        <td>{{ $proxy['speed'] }}</td>
                        <td>{{ $proxy->real_ip }}</td>
                        <td>{{ $proxy->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    {{-- <script src="{{ asset('js/echo.js') }}" defer></script> --}}

</body>


</html>
