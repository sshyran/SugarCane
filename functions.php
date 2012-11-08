<?php
// Add a link back to your Child Theme release page in the [theme-link] shortcode
function sugar_cane_theme_link($themelink) {
    return $themelink . ' &amp; <a class="Sugar Cane" href="http://theme-url.somewhere" title="A sweet Wordpress Theme">Sugar Cane</a> by <a href="http://ableparris.com">Able Parris</a>';
}
add_filter('thematic_theme_link', 'sugar_cane_theme_link');


//uncomment below to make the "blog" index show excerpts instead of full posts

//function sugar_cane_content_length($length) {
//	if (is_home() )
//		$c = 'excerpt';
//		return $c;
//}
//add_filter('thematic_content','sugar_cane_content_length');	

function sugar_cane_featured_image_size() {
	return 'full';
}
add_filter( 'thematic_post_thumb_size','sugar_cane_featured_image_size');

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
		
		
		
?>