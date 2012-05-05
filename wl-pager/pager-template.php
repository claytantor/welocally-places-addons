<div id="wl-pager-content-<?php echo $t->uid; ?>" class="wl-pager-content">
	<div class="template-wrapper">
		<div>
			<script type="text/javascript" charset="utf-8">
			var cfg = { 
				id:  '<?php echo $t->uid; ?>', 
				ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
				table:  '<?php echo $t->table; ?>',
				pagesize:  '<?php echo $t->pagesize; ?>', 
				fields:  '<?php echo $t->fields; ?>', 
				filter:  '<?php echo $t->filter; ?>', 
				orderBy:  '<?php echo $t->orderBy; ?>', 
				odd:  '<?php echo $t->odd; ?>',
				even:  '<?php echo $t->even; ?>',				 			
				content:  '<?php echo base64_encode($t->content); ?>' 
			};			    		
			var pager<?php echo $t->uid; ?> = 
				  new WELOCALLY_WpPager(cfg)
			  		.init();			 
	 		 </script>
		</div>
	</div>
</div>