<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('public/js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
</head>
@yield('css')
<body class="bg-dark">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <img src="{{ asset('public/logo.png') }}" alt="" style="height:50px">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent" class="bg-">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('index') }}">{{ __('首頁') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('index') }}">{{ __('車次查詢') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('booking') }}">{{ __('預訂車票') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('book_select') }}">{{ __('訂票查詢') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('train_select') }}">{{ __('列車資訊') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('登入後台') }}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login_type') }}">{{ __('車種管理') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login_station') }}">{{ __('車站管理') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login_train') }}">{{ __('列車管理') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login_book') }}">{{ __('訂票紀錄查詢') }}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('登出') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(session('message'))
            <div class="alert alert-{{ session('error')?'danger':'success' }}">
                {{ session('message') }}
            </div>
            @endif

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                        @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @yield('js')
</body>
</html>
