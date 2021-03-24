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

        <h1>Архив проверок прокси<b></h1>

        <table class="table">
            <thead>
                <tr>
                    {{-- <th scope="col">№</th> --}}
                    <th scope="col">Дата и время проверки</th>
                    <th scope="col">Сколько заняла проверка</th>
                    <th scope="col">Количество проксей</th>
                    <th scope="col">Количество живых проксей</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        {{-- <th scope="row">{{ $proxy['id'] }}</th> --}}
                        <td>{{ $log->created_at }}</td>
                        <td></td>
                        <td>{{ $log->total }}</td>
                        <td></td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('result', $log->id) }}" role="button">Подробнее</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>


</html>
