<?php
header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
 ?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui, minimum-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @guest
      <meta id="loggedUserId" content="0">
    @else
      <meta id="loggedUserId" content="{{ Auth::id() }}">
    @endguest
    <title>{{ config('app.name', 'LaraTube') }}</title>
    <script>var baseUrl = "/";</script>
    <!-- Scripts -->
    @yield('header-before-js')
    <script src="js/app.js" defer></script>

    @yield('header')

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">                                           
  </head>
  <body class="">
    <div id="app" v-cloak >
      <thesidebar v-bind:alertshown="alertshown" v-bind:alerttext="alerttext" v-bind:alertcolor="alertcolor"></thesidebar>
      <main class="py-4 mt-5 col-12 mb-3">
        <div class="">
          <div class="d-flex justify-content-center">
            <v-flex xs12 sm12 md10 lg9 >
            <div class="" id="outerContainer">
              @yield('content')
            </div>
          </v-flex>
          </div>
        </div>
      </main>
    </div>
  </body>
  <footer>

  </footer>
</html>
