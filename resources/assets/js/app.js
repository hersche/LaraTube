/**
** First file for initialise everything
**/
window.$ = window.jQuery = require('jquery');
//require('bootstrap');
var $ = require('jquery');
var WebTorrent = require('webtorrent')

$( document ).ready(function() {
  require('./siteManager').init(process.env.MIX_APP_URL);
});
