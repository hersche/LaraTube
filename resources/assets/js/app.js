
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

   Array.prototype.remove = function() {
       var what, a = arguments, L = a.length, ax;
       while (L && this.length) {
           what = a[--L];
           while ((ax = this.indexOf(what)) !== -1) {
               this.splice(ax, 1);
           }
       }
       return this;
   };

$( document ).ready(function() {

});
