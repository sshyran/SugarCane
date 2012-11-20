<?php
/**
 * SugarCane Functions.php
 *
 * Adding new functionality and overriding Thematic's functionality.
 *
 */


/**
 * sugar_cane_theme_link function.
 * 
 * @param mixed $themelink
 */
function sugar_cane_theme_link($themelink) {
    return $themelink . ' &amp; <a class="Sugar Cane" href="http://theme-url.somewhere" title="A sweet Wordpress Theme">Sugar Cane</a> by <a href="http://ableparris.com">Able Parris</a>';
}
add_filter('thematic_theme_link', 'sugar_cane_theme_link');


/**
 * sugar_cane_content_length function.
 * 
 */
function sugar_cane_content_length($thematic_content_length) {
		
	return 'full';
}		
add_filter('thematic_content', 'sugar_cane_content_length');

		
/**
 * Filtering Thematic's Featured Image size
 * 
 * All post thunbnails are set to full sized images.
 *
 */
function sugar_cane_featured_image_size() {
	return 'full';
}
add_filter( 'thematic_post_thumb_size','sugar_cane_featured_image_size');


/**
 * Filtering Thematic's post/page output to modify the featured image handling.
 * 
 * @param mixed $post
 */
function sugar_cane_featured_image($post) {
	global $thematic_content_length;
	if ( has_post_thumbnail() ) {
		$size = 'full';
		if( is_single() ) {
			$attr =  array('class'=>('featured-image'));
			$post = get_the_post_thumbnail (get_the_ID(), $size, $attr) . $post;
		} else {
			$attr = apply_filters( 'thematic_post_thumb_attr', array('title'	=> sprintf( esc_attr__('Permalink to %s', 'thematic'), the_title_attribute( 'echo=0' ) ), 'class'=>('featured-image') ) );
			$post = sprintf('<a class="entry-thumb" href="%s" title="%s">%s</a>',
									get_permalink() ,
									sprintf( esc_attr__('Permalink to %s', 'thematic'), the_title_attribute( 'echo=0' ) ),
									get_the_post_thumbnail(get_the_ID(), $size, $attr)) . $post;
		}
	}					
	return $post;
}
add_filter( 'thematic_post', 'sugar_cane_featured_image');


/**
 * Overriding Thematic's #access
 * 
 */
function childtheme_override_access() { 
	// silence
}


/**
 * Overriding Thematic's navmenu location
 * 
 */
function childtheme_override_init_navmenu() { 
	// silence
}  


/**
 * Limiting SugarCane's theme_support
 *
 * Removing javascripts associated with thematics access nav
 * 
 */
function sugar_cane_theme_support(){
	remove_theme_support('thematic_superfish');
	
}	
add_action ('thematic_child_init', 'sugar_cane_theme_support');


/**
 * Limiting SugarCane's theme_support
 *
 * Adjusting search form length
 * 
 */
function sugar_cane_search_form_length() {
	return '28';
}
add_filter('thematic_search_form_length', 'sugar_cane_search_form_length');


/**
 * Overriding Thematic's nav above removing it completely
 * 
 * @access public
 * @return void
 */
function childtheme_override_nav_above() {
	//silence
}

/**
 * Overriding Thematic's nav below removing it if the query is not paged
 *  
 */
function childtheme_override_nav_below() {
	global $wp_query;
	
	if ( $wp_query->max_num_pages > 1 ) {
	
		if (is_single()) { ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php thematic_previous_post_link() ?></div>
				<div class="nav-next"><?php thematic_next_post_link() ?></div>
			</div>

<?php
		} else { ?>

			<div id="nav-below" class="navigation">
                <?php if(function_exists('wp_pagenavi')) { ?>
                <?php wp_pagenavi(); ?>
                <?php } else { ?>  
				
				<div class="nav-previous"><?php next_posts_link(sprintf('<span class="meta-nav">&laquo;</span> %s', __('Older posts', 'thematic') ) ) ?></div>
					
				<div class="nav-next"><?php previous_posts_link(sprintf('%s <span class="meta-nav">&raquo;</span>',__( 'Newer posts', 'thematic') ) ) ?></div>

				<?php } ?>
			</div>	
	
<?php
		}
	}
}

/**
 * Filtering Thematic's widgetized areas 
 *
 * Alter the Single Insert Aside args hook and callback 
 *
 * @todo evaluate localization options for child theme 
 * @param mixed $widgetized_areas
 */
function sugar_cane_widgetized_areas_filter($widgetized_areas) {
	
	$widgetized_areas['Single Insert']['args']['name'] = 'Singlular Insert Above Comments';
	$widgetized_areas['Single Insert']['args']['description'] = 'The widget area inserted above comments on a single post and page views.';
	$widgetized_areas['Single Insert']['function'] = 'sugar_cane_single_insert';
	$widgetized_areas['Single Insert']['action_hook'] = 'thematic_abovecomments';
	
	return $widgetized_areas;
}

add_filter('thematic_widgetized_areas', 'sugar_cane_widgetized_areas_filter');


/**
 * Callback for the Singular Insert Aside
 *
 * Conditionally displayed when coments are active or closed but comments are present.
 *
 */
 function sugar_cane_single_insert() {

	if ( is_active_sidebar( 'single-insert' ) && (  comments_open() || (get_comments_number() > 0) ) ) {
		echo thematic_before_widget_area( 'single-insert' );
		dynamic_sidebar( 'single-insert' );
		echo thematic_after_widget_area( 'single-insert' );
	}
}
