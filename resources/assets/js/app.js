
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
//import treeNav from 'vue-tree-nav'

var $ = require('jquery');

//require("jquery-mousewheel")($);
//require('malihu-custom-scrollbar-plugin')($);
var WebTorrent = require('webtorrent')
//var siteManager = require('./siteManager').siteManager();
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

$( document ).ready(function() {

/*  $("#sidebar").mCustomScrollbar({
     theme: "minimal"
});
*/
$('#sidebarCollapse').on('click', function () {
  console.log("toggle clicked")
    // open or close navbar

});
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }});
  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
  require('./siteManager').init(process.env.MIX_APP_URL);
  //require('./siteManager').init("http://127.0.0.1:8000");
});
