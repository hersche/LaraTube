/**
** First file for initialise everything
**/
window.$ = window.jQuery = require('jquery');

require('bootstrap');
var $ = require('jquery');

$( document ).ready(function() {
  require('./siteManager').init(process.env);
});
