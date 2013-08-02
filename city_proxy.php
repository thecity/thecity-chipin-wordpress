<?php

  #require_once '../../../wp-blog-header.php';
  require_once 'lib/chipin/lib/the_city_chipin.php';

  $api_key = isset($_POST['api_key']) ? $_POST['api_key'] : '';
  $user_token = isset($_POST['user_token']) ? $_POST['user_token'] : '';
  $load = isset($_POST['load']) ? $_POST['load'] : '';
  $campus_id = isset($_POST['campus_id']) ? $_POST['campus_id'] : '';

  $html = array();
  switch($load) {
    case 'campuses':
      $html = array('campuses' => array());
      $campuses = TheCityChipin::campus_options($api_key, $user_token);
      foreach ($campuses as $id => $name) { $html['campuses'][] = array('id' => $id, 'name' => $name); }
      break;
    case 'funds':
      $campus_id = isset($campus_id) ? $campus_id : 0;
      $html = array('campus_id' => $campus_id, 'funds' => array());
      $funds = TheCityChipin::fund_options($api_key, $user_token, $campus_id);
      foreach ($funds as $id => $name) { $html['funds'][] = array('id' => $id, 'name' => $name); }
      break;
  }

  echo json_encode($html);

?>
