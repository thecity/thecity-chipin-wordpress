<?php

  #require_once '../../../wp-blog-header.php';
  require_once 'lib/chipin/lib/the_city_chipin.php';


  if(isset($argv[1])) { $_GET['load'] = $argv[1]; }
  if(isset($argv[2])) { $_GET['campus_id'] = $argv[2]; }    

  $html = array();
  switch($_GET['load']) {
    case 'campuses':
      $html = array('campuses' => array());
      $campuses = TheCityChipin::campus_options();
      foreach ($campuses as $id => $name) { $html['campuses'][] = array('id' => $id, 'name' => $name); }
      break;
    case 'funds':
      $campus_id = isset($_GET['campus_id']) ? $_GET['campus_id'] : 0;
      $html = array('campus_id' => $campus_id, 'funds' => array());
      $funds = TheCityChipin::fund_options($campus_id);
      foreach ($funds as $id => $name) { $html['funds'][] = array('id' => $id, 'name' => $name); }
      break;
  }

  echo json_encode($html);

?>
