<?php get_header(); ?>

		<section id="primary">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php if ( is_day() ) : ?>
							<?php printf( __( 'Daily Archives: %s', 'welocally' ), '<span>' . get_the_date() . '</span>' ); ?>
						<?php elseif ( is_month() ) : ?>
							<?php printf( __( 'Monthly Archives: %s', 'welocally' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
						<?php elseif ( is_year() ) : ?>
							<?php printf( __( 'Yearly Archives: %s', 'welocally' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
						<?php else : ?>
							<?php _e( 'Blog Archives', 'welocally' ); ?>
						<?php endif; ?>
					</h1>
				</header>

				
				<?php /* ==== START category map, place this where you want the map to show ===== */ ?>
				<div class="wl_category_container">  
				    <div id="wl_category_map">
				      <script type="text/javascript">
				//<![CDATA[
						var catId = <?php echo($cat); ?>;
						
				      	var placeSelected = new WELOCALLY_PlaceWidget({}).init();
					    var cfg = { 
								id:'multi_1',
								showLetters: true,
								imagePath:'<?php echo(WP_PLUGIN_URL.'/welocally-places/resources'); ?>/images',
						    	endpoint:'http://stage.welocally.com',
						    	showSelection: true,
						    	observers:[placeSelected],
								places: <?php echo(get_places_for_category($cat));  ?>
					    };
					    var placesMulti = 
							  new WELOCALLY_PlacesMultiWidget(cfg)
						  		.init();
				  		
				  		//now register the display for the place
					    placeSelected.setWrapper(cfg, jQuery(placesMulti._selectedSection));	          
					 
				      //]]>
				      </script>
				    </div>
				  </div>
				<?php /* ==== END category map, place this where you want the map to show ===== */ ?>
				
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
					?>

				<?php endwhile; ?>


			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'welocally' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'welocally' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>