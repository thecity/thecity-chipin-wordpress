jQuery(function() {  

});

function load_city_campus_options(chipin_widget_id, selected_id) {
  var params = {
    "api_key" : jQuery("[data='secret_key-"+chipin_widget_id+"']").val(),
    "user_token" : jQuery("[data='user_token-"+chipin_widget_id+"']").val(),
    "load" : "campuses",
    "selected_id" : selected_id
  };  

  jQuery.post('/wp-content/plugins/the-city-chipin/city_proxy.php', params, function(data) {
    var json_data = jQuery.parseJSON(data);
    console.log(json_data);
    jQuery.each(json_data['campuses'], function(index, campus) {
      console.log(campus['name']);
    });
  });  
}


function load_city_fund_options(chipin_widget_id, selected_id) {
  // var params = {
  //   "api_key" : jQuery("[data='api_key-'"+chipin_widget_id+"']").val(),
  //   "user_token" : jQuery("[data='user_token-'"+chipin_widget_id+"']").val(),
  //   "load" : "funds",
  //   "campus_id" : campus_id,
  //   "selected_id" : selected_id
  // };

  // jQuery.post('/wp-content/plugins/the-city-chipin/city_proxy.php', params, function(data) {
  //   var json_data = jQuery.parseJSON(data);
  //   jQuery.each(json_data['campuses'], function(index, campus) {
  //     console.log(campus['name']);
  //   });
  // });  
}