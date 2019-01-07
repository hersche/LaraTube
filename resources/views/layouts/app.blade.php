<?php
header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
 ?>

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
    <title>{{ config('app.name', 'LaraTube') }}</title>
    <script>var baseUrl = "{{ url("/") }}/";</script>
    <!-- Scripts -->
    @yield('header-before-js')
    <script src="{{ asset('js/app.js') }}" defer></script>

    @yield('header')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <!--  <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet"> -->
</head>
<body class="">

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
    <div id="app" v-cloak >


  <thesidebar v-bind:notifications="notifications" v-bind:csrf="csrf" v-bind:currentuser="currentuser" v-bind:medias="medias" v-bind:users="users" v-bind:tags="tags"></thesidebar>

    <!--  <nav id="sidebar" class="d-none bg-light">
          <ul class="list-unstyled components">
            @guest
                <li class="nav-item">
                    <router-link class="nav-link" to="/login">{{ __('Login') }}</router-link>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
            @else

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle btn btn-danger btn-block mb-1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <img class="mr-1" style="max-height: 25px;" src="{{ url(Auth::user()->avatar()) }}" />{{ Auth::user()->name }} <span class="caret"></span>
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
                <li ><router-link class="btn btn-primary btn-block mb-1 " to="/upload">{{ __('Upload') }}</router-link></li>
            @endguest
                      <li>
                          <router-link class="btn btn-primary btn-block mb-1 " to="/">{{ __('Medias') }}</router-link>
                      </li>
                      <li>
                          <router-link class="btn btn-primary btn-block mb-1" to="/charts">{{ __('Charts') }}</router-link>
                      </li>
                      <li>
                          <router-link class="btn btn-primary btn-block mb-1" to="/tags">{{ __('Tags') }}</router-link>
                      </li>

              <li>
                  <router-link class="btn btn-primary btn-block mb-1" to="/about">{{ __('About') }}</router-link>
              </li>
              <li class=""><button @click="emitGetNewMedias()" class="btn btn-block btn-success mb-1">Check 4 new</button></li>
              <li class=""><b-btn v-b-modal.moremodal class=" btn-block" >More</b-btn></li>
          </ul>

      </nav>

-->

<!--

        <nav class="navbar navbar-expand-md fixed-top navbar-light navbar-laravel">
          <button class="btn" type="button" @click="toggleSidebar()" aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="{{ __('Toggle navigation') }}">
              <span class="navbar-toggler-icon"></span>
          </button>

              <router-link class="navbar-brand" to="/">{{ config('app.name', 'LaraTube') }}</router-link>
-->              <!--  <li class="nav-item">
                  <a href="#menu-toggle" class="nav-link" id="menu-toggle">Toggle Menu</a>
                </li> -->


<!--
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
<!--
                    <li class="nav-item"><input type="text" placeholder="Search" id="theLiveSearch" class="btn btn-outline-info" @keyup="searching()" @focus="searching()"></li>

                </ul>
        </nav> -->

        <main class="py-4 mt-5 col-12 mb-3">
          <div class="">
            <div class="d-flex justify-content-center">
              <div class="col-sm-12 col-12 col-lg-10" id="outerContainer">
            @yield('content')
          </div></div></div>

        </main>

    </div>

  </body>
  <footer>

  </footer>
</html>
