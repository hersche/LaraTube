
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 function sendFriendRequest(id,type){
   console.log("i should send a request to userid "+id);
   $.ajax({
     url: '{{ url("/friends") }}',
     type: 'PUT',
     data: "users_id="+id+"&type="+type,
     success: function(data) {
     //  $("#cid"+id).html("");
       console.log("friend-request done: "+type);
     }
   });
 }

$( document ).ready(function() {

});
