<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @guest
      <meta id="loggedUserId" content="0">
    @else
      <meta id="loggedUserId" content="{{ Auth::id() }}">
    @endguest
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script>var baseUrl = "{{ url("/") }}/";</script>
    <!-- Scripts -->
    @yield('header-before-js')
    <script src="{{ asset('js/app.js') }}" defer></script>

    @yield('header')
    <!-- <script src="https://unpkg.com/ionicons@4.3.0/dist/ionicons.js"></script> -->


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <!--  <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet"> -->
</head>
<body class="container-fluid">

  <!-- Sidebar -->
<!--  <div id="wrapper">
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand"><a href="#">Start Bootstrap</a></li>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Shortcuts</a></li>
            <li><a href="#">Overview</a></li>
            <li><a href="#">Events</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </div>
  </div> -->
<!-- /#sidebar-wrapper -->
    <div id="app2">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
              <router-link class="navbar-brand" to="/">{{ config('app.name', 'LaraTube') }}</router-link>
              <!--  <li class="nav-item">
                  <a href="#menu-toggle" class="nav-link" id="menu-toggle">Toggle Menu</a>
                </li> -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item"><router-link class="dropdown-item" to="/tags">{{ __('Tags') }}</a></li>
                        @guest
                            <li class="nav-item">
                                <router-link class="nav-link" to="/login">{{ __('Login') }}</router-link>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item"><router-link class="dropdown-item" to="/upload">{{ __('Upload') }}</router-link></li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img class="mr-1" style="max-height: 45px;" src="{{ url(Auth::user()->avatar()) }}" />{{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                  <a class="dropdown-item" href="{{ route('users.selfedit') }}">{{ __('Settings') }}</a>
                                  <a class="dropdown-item" href="{{ route('friends') }}">{{ __('Manage friends') }}</a>
                                  @can('role-list')
                                    <a class="dropdown-item" href="{{ route('roles.index') }}">{{ __('Manage Role') }}</a>
                                  @endcan
                                  @can('user-admin')
                                    <a class="dropdown-item" href="{{ route('users.index') }}">{{ __('Manage Users') }}</a>
                                  @endcan
                                  @can('admin')
                                    <a class="dropdown-item" href="{{ route('users.index') }}">{{ __('Site-configuration') }}</a>
                                  @endcan
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
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
          <div class="container">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
              <p>{{ $message }}</p>
            </div>
            @endif
              <div class="justify-content-center">
            @yield('content')
          </div></div>
        </main>
    </div>
  </body>
  <footer>

  </footer>
</html>
