<?php

  require_once '../../../wp-blog-header.php';
  require_once 'lib/chipin/lib/the_city_chipin.php';
  require_once 'lib/chipin_wordpress_cache.php';  

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
        $white_list = empty($dataq['designation']) ? array() : array($dataq['designation']);
        $chipin = new TheCityChipin($data['secret_key'], $data['user_token'], $data['campus_id'], $data['fund_id'], $data['start_date'], $data['end_date']);
        $html['donations'] = $chipin->donations($white_list);
        $html['totals'] = $chipin->designation_totals();
        $html['widget_info'] = $data;
      }
      break;
  }

  echo json_encode($html);

?>
