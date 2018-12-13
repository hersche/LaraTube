@extends('layouts.app')

@section('header')


@endsection

@section('content')

            <div id="app2">
  <!-- Prepared for the right place <h1>404 - this page does not exist!</h1>
  <p>There is no such site - go <router-link to="/">home</router-link>!</p>
  <p>
    <router-link to="/" class="btn btn-primary">Home</router-link>
  </p>
-->
  <router-view v-bind:medias="medias" v-bind:currentTitle="currentTitle"></router-view>
</div>
@endsection
