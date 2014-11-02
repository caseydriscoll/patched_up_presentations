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
