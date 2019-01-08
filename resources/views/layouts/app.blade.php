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
    <div id="app" v-cloak >
      <thesidebar v-bind:notifications="notifications" v-bind:csrf="csrf" v-bind:currentuser="currentuser" v-bind:medias="medias" v-bind:users="users" v-bind:tags="tags"></thesidebar>
      <main class="py-4 mt-5 col-12 mb-3">
        <div class="">
          <div class="d-flex justify-content-center">
            <div class="col-sm-12 col-12 col-lg-10" id="outerContainer">
              @yield('content')
            </div>
          </div>
        </div>
      </main>
    </div>
  </body>
  <footer>

  </footer>
</html>
