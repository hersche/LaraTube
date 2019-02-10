@extends('layouts.vueApp')

@section('header')


@endsection

@section('content')
<div style="">
  <!-- Prepared for the right place <h1>404 - this page does not exist!</h1>
  <p>There is no such site - go <router-link to="/">home</router-link>!</p>
  <p>
    <router-link to="/" class="btn btn-primary">Home</router-link>
  </p>
-->
  <router-view v-bind:treecatptions="treecatptions" v-bind:medias="medias" v-bind:catlevel="Number(0)" v-bind:search="search" :key="$route.fullPath" v-bind:tagenabled="true" v-bind:canloadmore="canloadmore"></router-view>

</div>
@endsection
