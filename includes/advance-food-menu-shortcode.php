<?php

//MY Function 

function afm_value($var,$def){
	$myvar = get_option($var);
	if($myvar){
		return $myvar;
	}
	else{
		return $def;
	}
}

//Shortcode Function Here
	
function advance_food_menu_shortcodes($atts,$content){
	
	$afm_shortcode_attr = shortcode_atts(
		array(
			'currency_symbol'	=>afm_value('afm_currency_symbol','$'),
			'afm_disable_thum'	=>afm_value('afm_disable_thum','on'),
			
		),$atts
	);
	$id=rand();
	extract($afm_shortcode_attr);
	ob_start();
	?>
	<!-- Menu Area Start -->
	<div class="menu-area">
			
		<div class="row">
			<div class="col-sm-12 col-lg-12 col-md-12">
				<div class="menu-list">
					<button class="active" data-filter="*">ALL</button>
					<?php 
						$all_terms = get_terms('menu-item-type', array(
							'hide_empty' => false 
						));
						
						foreach($all_terms as $single_term){
							echo '<button data-filter=".'. $single_term->slug .'">'. $single_term->name .'</button>';
						}
					?>
				</div>	
			</div>
		</div>
		
		<div class="row">	
			<div class="custom-row">
				<div class="grid">
					<?php 
						$advance_food_menus = null;
						$advance_food_menus = new WP_Query(array(
							'post_type' => 'menu-item',
							'posts_per_page' => -1
						));
						
					?>
					<?php 
						if($advance_food_menus->have_posts()){
							while($advance_food_menus->have_posts()){
								$advance_food_menus->the_post(); $afm_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false ); 
								$filter_terms = get_the_terms(get_the_ID(), 'menu-item-type'); ?>
								<?php $afm_price = get_post_meta(get_the_ID(), 'afm-price', true); ?>
								<div class="col-md-6 grid-item <?php foreach($filter_terms as $filter_term){ echo $filter_term->slug . ' '; } ?>">
									<div class="menu-item">
										<?php if($afm_disable_thum == 'on'): ?>
										<div class="menu-image">
											<a data-lightbox="image-1" title="<?php the_title(); ?>" href="<?php echo $afm_src[0];?>"><img src="<?php echo $afm_src[0];?>" alt="<?php the_title_attribute(); ?>"></a>
										</div>
										<?php else: ?>
										<div class='thumbnail-11'></div>
										<?php endif; ?>
										<div class="menu-text">
											<h4><?php the_title(); ?><span><?php echo esc_html($currency_symbol); ?><?php echo esc_html($afm_price); ?></span></h4>
											<span><?php echo wp_trim_words(get_the_content(), 8, false); ?></span>
										</div>
									</div>
								</div>
							<?php }
						}else{
							echo 'No Post Found';
						}
						wp_reset_postdata();
						$advance_food_menus = null;
					?>
					
				</div>
			</div>
		</div>
	</div>
	<!-- Menu Area End -->
	<style>
	.menu-image img{
		height: 75px;
	}
	</style>
	<?php 
	wp_reset_query();
	return ob_get_clean();
	
	
}
add_shortcode('afm_shortcode','advance_food_menu_shortcodes');

