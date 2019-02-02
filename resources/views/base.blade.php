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
  <router-view v-bind:treecatptions="treecatptions" v-bind:catlevel="Number(0)" v-bind:fullmedias="fullmedias" :key="$route.fullPath" v-bind:notifications="notifications" v-bind:nextvideos="nextvideos" v-bind:csrf="csrf" v-bind:categories="categories" v-bind:currentuser="currentuser" v-bind:tagenabled="true" v-bind:search="search" v-bind:users="users" v-bind:loggeduserid="loggeduserid" v-bind:canloadmore="canloadmore" v-bind:tags="tags" v-bind:user="user"></router-view>

</div>
@endsection
