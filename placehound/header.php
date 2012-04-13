<?php 
	include (dirname(__FILE__) . "/PlacehoundNetwork.class.php");	
	include (dirname(__FILE__) . "/PlaceHound.class.php");
	global $placehoundNetwork, $placeHound;
	$t =$placeHound->getConfigs(); 
?> 
      <div style="display:inline">
		  <div style="display: inline-block;"><a href="<?php echo($t->baseUrl); ?>"><img src="images/phound02.png" class="logo" /></a></div>
		  <div class="placehound_title_area">
				<div class="placehound_title">Place Hound</div>
				<div class="welocally_link"><a href="http://welocally.com" target="_new"><img src="images/powered-welocally.png"/></a></div>
		  </div>
      </div>  
			     