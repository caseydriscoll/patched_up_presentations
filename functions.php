<?php
// Start the engine
require_once( get_template_directory() . '/lib/init.php' );

// Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Patched Up Presentations' );


add_theme_support( 'html5' );
add_theme_support( 'genesis-responsive-viewport' );
add_theme_support( 'genesis-footer-widgets', 3 );

add_action('genesis_setup','genesischild_theme_setup'); 
function genesischild_theme_setup() { 
}

function shortcode_slide( $atts ){
  return "</section><section class='slide'>";
}
add_shortcode( 'slide', 'shortcode_slide' );


function shortcode_note( $atts, $content ){
  return "<note>" . $content . "</note>";
}
add_shortcode( 'note', 'shortcode_note' );


function shortcode_play( $atts ){
  wp_enqueue_script( 'presentation_script', get_stylesheet_directory_uri() . '/script.js', array( 'jquery' ) );
  return "<a id='play'>Toggle Notes</a>";
}
add_shortcode( 'play', 'shortcode_play' );


function create_presentation_post_type() {
  register_post_type( 'presentation',
    array(
      'labels' => array(
        'name' => __( 'Presentations' ),
        'singular_name' => __( 'Presentation' )
      ),
      'menu_icon' => '',
      'public' => true,
      'has_archive' => true,
    )
  );
}
add_action( 'init', 'create_presentation_post_type' );


function add_menu_icons_styles(){
?>
 
<style>
#adminmenu .menu-icon-presentation div.wp-menu-image:before {
  content: '\f183';
}
</style>
 
<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );




// Thanks to http://colorlabsproject.com/tutorials/remove-slugs-custom-post-type-url/
/**
 * Remove the slug from published post permalinks.
 */
function custom_remove_cpt_slug( $post_link, $post, $leavename ) {
 
    if ( 'presentation' != $post->post_type || 'publish' != $post->post_status ) {
        return $post_link;
    }
 
    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
 
    return $post_link;
}
add_filter( 'post_type_link', 'custom_remove_cpt_slug', 10, 3 );


/**
 * Some hackery to have WordPress match postname to any of our public post types
 * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
 * Typically core only accounts for posts and pages where the slug is /post-name/
 */
function custom_parse_request_tricksy( $query ) {
 
    // Only noop the main query
    if ( ! $query->is_main_query() )
        return;
 
    // Only noop our very specific rewrite rule match
    if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }
 
    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if ( ! empty( $query->query['name'] ) ) {
        $query->set( 'post_type', array( 'post', 'presentation', 'page' ) );
    }
}
add_action( 'pre_get_posts', 'custom_parse_request_tricksy' );
