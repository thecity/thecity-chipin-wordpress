<?php
/*
Plugin Name: The City Chipin Widget
Plugin URI: http://developer.onthecity.org/thecity-plugins/wordpress-chipin/
Description: A WordPress plugin that uses The City Admin API (api.onthecity.org) so that donations can be segmented for a campaign registry.
Author: Wes Hays
Version: 0.1
Author URI: http://www.OnTheCity.org
*/

include_once 'thecity_chipin_scripts.php';


class The_City_Chipin_Widget extends WP_Widget {
  
  function __construct() {
    $widget_ops = array('classname' => 'the_city_chipin_widget', 
                        'description' => 'A WordPress plugin that allows users to chipin directly to The City from your WordPress website.' );
    $this->WP_Widget('the-city-chipin-widget', 'The City Chipin', $widget_ops);
  }
  

  function form($instance) {
    /* Set up some default widget settings. */
		$defaults = array( 'chipin_display_choice' => '');

		$instance = wp_parse_args( (array) $instance, $defaults );    

    $secret_key = strip_tags($instance['secret_key']);
    $user_token = strip_tags($instance['user_token']);
    $chipin_display_choice = strip_tags($instance['chipin_display_choice']);

    $campus_id = strip_tags($instance['campus_id']);
    $fund_id = strip_tags($instance['fund_id']);
    $suggested_amount = strip_tags($instance['suggested_amount']);
    $designation = strip_tags($instance['designation']);


    $load_campus_data = (!empty($secret_key) && !empty($user_token));

    $chipin_widget_id = strip_tags($instance['chipin_widget_id']);
    if(empty($chipin_widget_id) && !empty($user_token)) { $chipin_widget_id = intval(microtime(true)); }    

    ?>

    <p>
      <label for="<?php echo $this->get_field_id('chipin_widget_id'); ?>">
        Chipin ID: <?php echo $chipin_widget_id; ?>
        <input type="hidden" 
               id="<?php echo $this->get_field_id('chipin_widget_id'); ?>"  
               name="<?php echo $this->get_field_name('chipin_widget_id'); ?>" 
               value="<?php echo $chipin_widget_id; ?>">        
      </label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('secret_key'); ?>">
        City Secret Key: 
        <input class="widefat" 
              id="<?php echo $this->get_field_id('secret_key'); ?>" 
              name="<?php echo $this->get_field_name('secret_key'); ?>" 
              type="text" 
              value="<?php echo attribute_escape($secret_key); ?>" />
      </label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('user_token'); ?>">
        City User Token: 
        <input class="widefat" 
              id="<?php echo $this->get_field_id('user_token'); ?>" 
              name="<?php echo $this->get_field_name('user_token'); ?>" 
              type="text" 
              value="<?php echo attribute_escape($user_token); ?>" />
      </label>
    </p>


    <?php 
      $plain_s = $inline_s = '';
      switch($instance['chipin_display_choice']) {
        case 'plain':
          $plain_s = 'selected="selected"'; 
          break;
        case 'inline':
          $inline_s = 'selected="selected"'; 
          break;
        case 'city_style_normal':
          $city_style_normal = 'selected="selected"'; 
          break;
        case 'city_style_inline':
          $city_style_inline = 'selected="selected"'; 
          break;
      }
    ?> 

    <p>    
      <label for="<?php echo $this->get_field_id('chipin_display_choice'); ?>">
        Display:        			
        <select class="widefat" 
                id="<?php echo $this->get_field_id('chipin_display_choice'); ?>" 
                name="<?php echo $this->get_field_name('chipin_display_choice'); ?>">
            <option value="plain" <?php echo $plain_s; ?> >Plain</option>
        		<option value="inline" <?php echo $inline_s; ?> >Inline</option>
            <option value="city_style_normal" <?php echo $city_style_normal; ?> >City Style Normal</option>
            <option value="city_style_inline" <?php echo $city_style_inline; ?> >City Style Inline</option>
        </select>
      </label>    
    </p>    


    <p>    
      <label for="<?php echo $this->get_field_id('campus_id'); ?>">
        Church/Campus:              
        <select class="widefat" 
                id="<?php echo $this->get_field_id('campus_id'); ?>" 
                name="<?php echo $this->get_field_name('campus_id'); ?>"
                data="city_campuses-<?php echo $chipin_widget_id; ?>">
          <option value="0">Enter Key/Token above and save to load</option>
        </select>
      </label>    
      <?php if($load_campus_data) { ?>
        <script type="text/javascript">
           load_city_campus_options(<?php echo $chipin_widget_id; ?>, <?php echo $campus_id ?>);
        </script>
      <?php } ?>
    </p>    


    <p>    
      <label for="<?php echo $this->get_field_id('fund_id'); ?>">
        Fund:              
        <select class="widefat" 
                id="<?php echo $this->get_field_id('fund_id'); ?>" 
                name="<?php echo $this->get_field_name('fund_id'); ?>"
                data="city_funds-<?php echo $chipin_widget_id; ?>">
            <option value="0">Select Church/Campus above</option>
        </select>
      </label>    
    </p>        


    <p>
      <label for="<?php echo $this->get_field_id('suggested_amount'); ?>">
        Suggested Amount (optional): 
        <input class="widefat" 
              id="<?php echo $this->get_field_id('suggested_amount'); ?>" 
              name="<?php echo $this->get_field_name('suggested_amount'); ?>" 
              type="text" 
              value="<?php echo attribute_escape($suggested_amount); ?>" />
      </label>
      <i>Ex: "25" for "$25"</i>
    </p>


    <p>
      <label for="<?php echo $this->get_field_id('designation'); ?>">
        Designation: 
        <input class="widefat" 
              id="<?php echo $this->get_field_id('designation'); ?>" 
              name="<?php echo $this->get_field_name('designation'); ?>" 
              type="text" 
              value="<?php echo attribute_escape($designation); ?>" />
      </label>
      <i>Ex: "Chairs", Classrooms", etc.</i>
    </p>



    <?php
  }


  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['chipin_widget_id'] = strip_tags($new_instance['chipin_widget_id']);

    $instance['secret_key'] = strip_tags($new_instance['secret_key']);
    $instance['user_token'] = strip_tags($new_instance['user_token']);
    $instance['chipin_display_choice'] = strip_tags($new_instance['chipin_display_choice']);

    $instance['campus_id'] = strip_tags($new_instance['campus_id']);
    $instance['fund_id'] = strip_tags($new_instance['fund_id']);
    $instance['suggested_amount'] = strip_tags($new_instance['suggested_amount']);
    $instance['designation'] = strip_tags($new_instance['designation']);

    return $instance;
  }
  


  function widget($args, $instance) {
    extract($args);
    $chipin_widget_id = empty($instance['chipin_widget_id']) ? '' : $instance['chipin_widget_id'];

    $secret_key = empty($instance['secret_key']) ? ' ' : $instance['secret_key'];
    $user_token = empty($instance['user_token']) ? ' ' : $instance['user_token'];
    $chipin_display_choice = empty($instance['chipin_display_choice']) ? 'plain' : $instance['chipin_display_choice'];

    $campus_id = empty($instance['campus_id']) ? ' ' : $instance['campus_id'];
    $fund_id = empty($instance['fund_id']) ? ' ' : $instance['fund_id'];
    $suggested_amount = empty($instance['suggested_amount']) ? ' ' : $instance['suggested_amount'];
    $designation = empty($instance['designation']) ? ' ' : $instance['designation'];

    echo $before_widget;
    if (!empty( $title )) {
        echo $before_title . $title . $after_title;
    };

    include dirname(__FILE__).'/widget_info.php';
    echo $after_widget;
  }
  
}

add_action('widgets_init', create_function('', 'return register_widget("The_City_Chipin_Widget");'));

?>