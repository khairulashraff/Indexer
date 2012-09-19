/*
 * Author: Paul
 * http://paulgueller.com/2011/04/26/parse-the-querystring-with-jquery/
 */
jQuery.extend({
  querystring: function(){
    var nvpair = {};
    var qs = window.location.search.replace('?', '');
    var pairs = qs.split('&');
    $.each(pairs, function(i, v){
      var pair = v.split('=');
      nvpair[pair[0]] = pair[1];
    });
    return nvpair;
  }
});