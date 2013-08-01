jQuery(function() {  

});

function load_city_campus_options(selected_id) {
  var url = window.location.protocol + '//' + window.location.host + '/wp-content/plugins/the-city-chipin/city_proxy.php?load=campuses';
  console.log("URL: " + url);
  console.log("Selected: " + selected_id);

  jQuery.get('/wp-content/plugins/the-city-chipin/city_proxy.php?load=campuses', function(data) {
    var json_data = jQuery.parseJSON(data);
    jQuery.each(json_data['campuses'], function(index, campus) {
      console.log(campus['name']);
    });
  });  
}


function load_city_fund_options(campus_id, selected_id) {
  var url = window.location.protocol + '//' + window.location.host + '/wp-content/plugins/the-city-chipin/city_proxy.php?load=funds&campus_id=' + campus_id;
  console.log("URL: " + url);
  console.log("Selected: " + selected_id);

  jQuery.get('/wp-content/plugins/the-city-chipin/city_proxy.php?load=campuses', function(data) {
    var json_data = jQuery.parseJSON(data);
    jQuery.each(json_data['campuses'], function(index, campus) {
      console.log(campus['name']);
    });
  });  
}