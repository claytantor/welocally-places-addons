<?php 
	include (dirname(__FILE__) . "/PlacehoundNetwork.class.php");	
	include (dirname(__FILE__) . "/PlaceHound.class.php");
	global $placehoundNetwork, $placeHound;
	$t =$placeHound->getConfigs(); 
?>    
<div id="stickyfooter">
<div style="width:100%;  margin-left: auto; margin-right: auto;">
		<div style="margin-right: auto; margin-left: auto; width:600px;">
		<div class="span-11" style="text-align:left; display:inline;">
			<div class="footer-link-title">Links</div>
			<div class="footer-link" ><a href="http://placehound.com">Home</a></div>
			<div class="footer-link"><a href="http://welocally.com/?page_id=139">Contact</a></div>
		</div>
		<div class="span-12 last" style="text-align:right;"><div class="footer-logo"><a href="<?php echo($t->baseUrl); ?>"><img src="images/welocally-footer.png" /></a></div></div>		
		</div>
		
<div>

<div style="width:100%;  margin-left: auto; margin-right: auto;">
		<div style="margin-right: auto; margin-left: auto; width:600px;">
		<div class="span-24 last" style="text-align:center; margin-top: 30px;">
		copyright 2012 - welocally. all facts found on this site are creative commons share alike.
		</div>
		</div>
</div>
		
		

</div>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29987811-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>  