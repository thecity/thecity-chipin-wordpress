<?php
  require_once 'lib/chipin/lib/the_city_chipin.php';
  require_once 'lib/chipin_wordpress_cache.php';  

  add_action('admin_footer', 'thecity_process_request_javascript' );
  add_action('wp_ajax_thecity_process_request', 'thecity_process_request_callback');
?>


<?php function thecity_process_request_javascript() { ?>
  <script type="text/javascript" >
    jQuery(document).ready(function($) {
       jQuery.each(jQuery(".city_chipin_widget_admin"), function(index, elem) { 
        var chipin_widget_id = jQuery(elem).find(".chipin_widget_id").val();
        if(chipin_widget_id != "") {  
          var api_key = jQuery(elem).find(".chipin_city_secret_key").val();
          var user_token = jQuery(elem).find(".chipin_city_user_token").val();
          var selected_campus_id = jQuery(elem).find(".chipin_campus_id_current").val();
          var selected_fund_id = jQuery(elem).find(".chipin_fund_id_current").val();
          //load_city_campus_options(chipin_widget_id, campus_id, fund_id);

          var data = {
            action: "thecity_process_request",
            load: "campuses",
            api_key: api_key,
            user_token: user_token,
            chipin_widget_id: chipin_widget_id,
            campus_id: selected_campus_id, 
            fund_id: selected_fund_id
          };

          jQuery.post(ajaxurl, data, function(response) {       
            var json_data = jQuery.parseJSON( jQuery.trim(response) );

console.log(json_data);

            var campus_select_field = jQuery(elem).find(".chipin_campus_id option").remove();
            var list = jQuery(elem).find(".chipin_campus_id");
            list.append(new Option('Select Church/Campus...', '0'));

            jQuery.each(json_data["campuses"], function(index, campus) {
              list.append(new Option(campus["name"], campus["id"]));
            });
            list.find("[value='"+selected_campus_id+"']").attr('selected', 'selected');

            // if(parseInt(selected_campus_id) > 0) {
            //   load_city_fund_options(chipin_widget_id, selected_campus_id, selected_fund_id);
            // }            
          });      
        } 
      });
    });
  </script>
<?php } ?>



<?php
  function thecity_process_request_callback() {
    $api_key = isset($_POST['api_key']) ? $_POST['api_key'] : '';
    $user_token = isset($_POST['user_token']) ? $_POST['user_token'] : '';
    $load = isset($_POST['load']) ? $_POST['load'] : '';

    $html = array();
    switch($load) {
      case 'campuses':
        $html = array('campuses' => array());
        $campuses = TheCityChipin::campus_options($api_key, $user_token);
        foreach ($campuses as $id => $name) { $html['campuses'][] = array('id' => $id, 'name' => $name); }
        break;
      
      case 'funds':
        $campus_id = isset($_POST['campus_id']) ? $_POST['campus_id'] : 0;
        $html = array('campus_id' => $campus_id, 'funds' => array());
        $funds = TheCityChipin::fund_options($api_key, $user_token, $campus_id);
        foreach ($funds as $id => $name) { $html['funds'][] = array('id' => $id, 'name' => $name); }
        break;

      case 'info':
        $chipin_widget_id = isset($_POST['chipin_widget_id']) ? $_POST['chipin_widget_id'] : ''; 
        global $wpdb;
        $cacher = new ChipinWordPressCache($wpdb);
        $data = $cacher->get_data($chipin_widget_id);
        if(!is_null($data)) {
          $white_list = empty($data['designation']) ? array() : array($data['designation']);
          $chipin = new TheCityChipin($data['secret_key'], $data['user_token'], $data['campus_id'], $data['fund_id'], $data['start_date'], $data['end_date']);
          $chipin->donations($white_list);
          $html['totals'] = $chipin->designation_totals();
          $html['widget_info'] = array(
            'subdomain_key'    => $data['subdomain_key'],
            'campus_name'      => $data['campus_name'],
            'campus_id'        => $data['campus_id'],
            'fund_id'          => $data['fund_id'],
            'designation'      => $data['designation'],
            'suggested_amount' => $data['suggested_amount'],
            'display_choice'   => $data['display_choice'],
            'start_date'       => $data['start_date'],
            'end_date'         => $data['end_date'],
            'goal_amount'      => $data['goal_amount']
          );      
        }
        break;
    }

    echo json_encode($html);

    die(); // this is required to return a proper result
  }
?>
