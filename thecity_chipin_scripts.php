<?php

function thecity_chipin_wordpress_scripts() {     
  wp_register_script('thecity_chipin_js', plugins_url('/the-city-chipin/scripts/thecity_chipin.js'));   
  wp_register_script('thecity_chipin_load_js', plugins_url('/the-city-chipin/scripts/thecity_chipin_load.js')); 
  wp_register_script('formatter_city_style_js', plugins_url('/the-city-chipin/scripts/formatter_city_style.js')); 

  wp_enqueue_script( array('jquery') );  
  wp_enqueue_script('thecity_chipin_js');    
  wp_enqueue_script('thecity_chipin_load_js');  
  wp_enqueue_script('formatter_city_style_js');  
}

add_action('wp_enqueue_scripts', 'thecity_chipin_wordpress_scripts');  



function thecity_chipin_wordpress_styles() {  
  wp_register_style('thecity_chipin_wordpress_style', plugins_url( '/the-city-chipin/scripts/thecity_chipin.css'));   
  wp_register_style('thecity_appkit', plugins_url( '/the-city-chipin/scripts/thecity_appkit.css'));     
  wp_register_style('formatter_city_style_css', plugins_url( '/the-city-chipin/scripts/formatter_city_style.css'));   

  wp_enqueue_style('thecity_chipin_wordpress_style'); 
  wp_enqueue_style('thecity_appkit');  
  wp_enqueue_style('formatter_city_style_css');  
}

add_action('wp_enqueue_scripts', 'thecity_chipin_wordpress_styles');  

?>