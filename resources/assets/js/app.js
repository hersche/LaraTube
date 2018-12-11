
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
//var siteManager = require('./siteManager').siteManager();
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

$( document ).ready(function() {
console.log("why the hell??");
// sm = new siteManager();
var sm1 = require('./siteManager').init();
console.log(sm1);
});
