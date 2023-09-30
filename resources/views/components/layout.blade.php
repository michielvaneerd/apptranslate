<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? '' }} :: {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/adminlte.min.css') }}">

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>

<body class="sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('logout') }}" class="nav-link">{{ __('app.logout') }}</a>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <div>
                <a href="/" class="brand-link">{{ config('app.name') }}</a>
            </div>
            <div class="sidebar">
                <nav class="mt-2">
                    <?php
                    $menuItems = \App\Helpers\Menu::getMenu(auth()->user());
                    $currentUrl = url()->current();
                    ?>
                    <x-menu :items="$menuItems" :currentUrl="$currentUrl" root="true"></x-menu>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <h1>
                                {{ $title ?? '' }}
                                @if(!empty($titleButton))
                                <a class="btn btn-sm btn-outline-primary ml-1" href="{{ $titleButton['route'] }}">{{ $titleButton['title'] }}</a>
                                @endif
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>

</body>

</html>