$(function() {
  var toc = $("#toc").tocify({ 
               selectors: "h2,h3,h4,h5", 
               theme: "bootstrap3", 
               context: '.col-md-9' 
             }).data("toc-tocify"); 
});