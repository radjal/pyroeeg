{{ if user:profile group="user" }}
        <?php if (empty($this->current_user->group) or $this->current_user->group=='User' ) : ?>
	<?php if ($this->settings->ga_tracking): ?>
    <script type="text/javascript">
     // THIS DOES NOT WORK !
     var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '<?php echo $this->settings->ga_tracking;?>'], ['_trackPageview']);
    
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
      })();
    </script>
    
    <?php endif; ?>
<?php endif; ?>
{{ endif }}