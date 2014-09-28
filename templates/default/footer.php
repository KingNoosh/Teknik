    </div>
    <footer id="footer" class="footer navbar navbar-default">
      <div class="container">
          <p class="text-muted">&copy; Teknik 2013-2014 | <a href="<?php echo get_page_url("privacy", $CONF); ?>">Privacy</a> | <a href="<?php echo get_page_url("transparency", $CONF); ?>">Transparency</a> | <a href="<?php echo get_page_url("server", $CONF); ?>">Server</a></p>
      </div>
    </footer>
    
    <!-- Piwik -->
    <script type="text/javascript">
      var _paq = _paq || [];
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="//stats.teknik.io/";
        _paq.push(['setTrackerUrl', u+'piwik.php']);
        _paq.push(['setSiteId', 1]);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
    <noscript><p><img src="//stats.teknik.io/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
    <!-- End Piwik Code -->
  </body>
</html>