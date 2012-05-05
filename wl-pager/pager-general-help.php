<div class="wrap wl_pager_admin">
	<div class="section">
		<div class="wl_options_heading">Quick Start Guide</div>
		<p><em>This quick start guide is intended to be a brief overview of the capabilities of the paginator short code 
		plugin. For a more detailed example with downloadable data and broader use cases goto our our <a href="http://welocally.com/?page_id=1199" target="new">online guide</a></em></p>
		<p>
			The paginator short code is intended to be used with any table in your wordpress database, but in 
			the interest of getting up and running we have created a shortcode example that should work with most
			wordpress databases. Just copy and paste the example below into a page or post and publish it. 
			<strong>For this example to work you must add the wp_posts table to the permitted table options and
			the save options.</strong> 
		</p>
		<script src="https://gist.github.com/2596391.js?file=shortcode_posts.html"></script>		
		<div class="wl_options_heading">Shortcode Options</div>
		<ul>
			<li><strong style="font-family: monospace;">pagesize</strong> - 
				Size of the page that will be shown, if 100 records were returned 
				and the page size was 20 then 5 pages would be shown.</li>
   			<li><strong style="font-family: monospace;">table</strong> - 
   				Table that will be queried, and whose fields will be returned. Any 
   				table you would like to paginate must be included in the permitted 
   				tables option.</li>
   			<li><strong style="font-family: monospace;">fields</strong> - 
   				Comma deliminated list of fields that will be queried. Only fields 
   				in this list can be shown in the page.</li>
   			<li><strong style="font-family: monospace;">filter</strong> - 
   				Ampersand (&amp;) delimited list of conjuctive filters 
   				(all conditions must be met) with operators. Supported operators are 
   				= (equals), &gt; (greater than), and &lt; (less than). 
   				You can have has many filter conditions as you wish, and they do 
   				not need to be the same fields as the query. They must support 
   				the type annotations (see filters section below).</li>
   			<li><strong style="font-family: monospace;">odd</strong> - 
   				Text that will be substituted in an odd number row. Use this 
   				to toggle styles or classes per row.</li>
   			<li><strong style="font-family: monospace;">even</strong> - 
   				Text that will be substituted in an even number row. Use 
   				this to toggle styles or classes per row.</li>
   			<li><strong style="font-family: monospace;">order_by</strong> - 
   				The sort field(s) and order of the results. Fields should be 
   				comma delimited and the order is ASC (ascending) or DESC (descending). 
   				If neither is specified, the default is ASC.</li>  		
		</ul>
	</div>
	<div class="section">
		<div class="wl_options_heading">Filtering Results</div>
		<p>
			Filtering provides fine grain control of what results will be returned and paged. 
			We have created a way to simply specify all the filter criteria you would like to make with one statement in the filter field
			of the tag. All criteria must be met for a result to be returned. Sorry, OR operators are not supported yet. Each criteria is separated by 
			an &amp; (Ampersand) and has the following structure:
		</p>
		<table class="filter_example">
			<tr><th>Field Name</th><th>Operator</th><th>Type Value</th></tr>
			<tr><td><span style="font-family: monospace; font-size: 1.4em;">post_date</span></td><td align="center"><span style="font-family: monospace; font-size: 1.4em;">&gt;</span></td><td><span style="font-family: monospace; font-size: 1.4em;">@2011-12-03</span></td></tr>
		</table> 
		<p>
			<strong>Operators</strong><br />
			Operators determine how the filter will select rows, will it need to be equal, less than or greater than the value specified for a result to be displayed. Supported operator types are:
			<ul>
				<li><strong style="font-family: monospace;">= (equals)</strong> - 
					Return a result if the value for the specified field equals the value provided</li>
	   			<li><strong style="font-family: monospace;">&gt; (greater than)</strong> - 
					Return a result if the value for the specified field is greater than the value provided</li>
	   			<li><strong style="font-family: monospace;">&lt; (less than)</strong> - 
					Return a result if the value for the specified field is less than the value provided</li>  					
			</ul>	
		</p>
		<p>
		<strong>Type Value Types</strong><br />
		To know how to filter the results the plugin needs to know what type of field is being used to make the comparison, we provide three supported field types for filtering:
			<ul>
				<li><strong><em>empty</em> (text type)</strong> - 
					Text types dont require any prefix, that's because thats the most common filter. This would be used to filter on a text status for example <strong style="font-family: monospace;">post_status=published</strong></li>
	   			<li><strong style="font-family: monospace;"># (number)</strong> - 
					If your filter is testing on a number value just prefix the number with the # sign for example <strong style="font-family: monospace;">ID>#10</strong></li>
	   			<li><strong style="font-family: monospace;">@ (date)</strong> - 
					When testing against TIMESTAMP or DATETIME types use the @ prefix. This is helpful for only showing time relevant results such as <strong style="font-family: monospace;">past_date>@2012-02-13</strong></li>  					
			</ul>		
		</p>	
	
	</div>
	<div class="section">	
		<div class="wl_options_heading">Row Template HTML</div>
		<p>
			The area inside of the two shortcode tags is used as a template for substituting values per row result. Any HTML inside will replace field names annotated with <strong style="font-family: monospace;">%field_name%</strong> 
			and replace it with the value for that field for each row. There is also the concept of Row Toggling, wherever in the template that the %ROW_TOGGLE% annotation is made, the odd and even field values will get supstituted. 
			With both field substitution and field toggle the the template:
		</p>
		<script src="https://gist.github.com/2596391.js?file=template_before.html"></script>
		Would get translated to for a specific result record:
		<script src="https://gist.github.com/2596391.js?file=template_after.html"></script>
		
	</div>
	<div class="section">
		<div class="wl_options_heading">Themes</div>
		<p>
			Five themes are provided in the package for the pagination controls. There is currently no way to create custom themes unless 
			you go into one of the existing themes and change it. This requires write access to the plugin directory.
		</p>
		<table class="filter_example">
			<tr><td><span style="font-family: monospace; font-size: 1.4em;">default</span></td><td><img src="<?php echo $imagePrefix ?>/theme-basic.png"/> </td></tr>
			<tr><td><span style="font-family: monospace; font-size: 1.4em;">sky</span></td><td><img src="<?php echo $imagePrefix ?>/theme-sky.png"/></td></tr>
			<tr><td><span style="font-family: monospace; font-size: 1.4em;">froggy</span></td><td><img src="<?php echo $imagePrefix ?>/theme-froggy.png"/></td></tr>
			<tr><td><span style="font-family: monospace; font-size: 1.4em;">santa fe</span></td><td><img src="<?php echo $imagePrefix ?>/theme-santafe.png"/></td></tr>
			<tr><td><span style="font-family: monospace; font-size: 1.4em;">cherry</span></td><td><img src="<?php echo $imagePrefix ?>/theme-cherry.png"/></td></tr>
		</table> 				
	</div>
	<div class="section">
		<div class="wl_options_heading">Permit Tables</div>
		<p>
			As a protection, only the tables that are explicitly permitted to be paginated can be used in pagination shortcodes. Make sure that
			you have entered all the tables you would like to be allowed to paginate with the exact table name and comma delimited 
			for example <strong style="font-family: monospace;">place, wp_posts</strong>
			
		</p>
	</div>

</div>