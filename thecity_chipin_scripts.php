<?php

function thecity_chipin_wordpress_scripts() {    
  wp_enqueue_script( array('jquery') );  
  wp_enqueue_script('thecity_chipin_load_js', plugins_url('/scripts/thecity_chipin_load.js', __FILE__));  
}
add_action('wp_enqueue_scripts', 'thecity_chipin_wordpress_scripts');  



function thecity_chipin_wordpress_styles() {  
  wp_enqueue_style('thecity_chipin_wordpress_style', plugins_url( '/scripts/thecity_chipin.css', __FILE__)); 
  wp_enqueue_style('thecity_appkit', plugins_url( '/scripts/thecity_appkit.css', __FILE__));  
  wp_enqueue_style('thecity_widget_styles', plugins_url( '/scripts/thecity_widget_styles.css', __FILE__));  
}
add_action('wp_enqueue_scripts', 'thecity_chipin_wordpress_styles');  



function thecity_chipin_wordpress_admin_scripts($hook) {
  wp_enqueue_script('thecity_chipin_load_js', plugins_url('/scripts/thecity_chipin_load.js', __FILE__) );
}
add_action('admin_enqueue_scripts', 'thecity_chipin_wordpress_admin_scripts');

?>