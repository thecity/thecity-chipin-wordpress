// jQuery(function() {  

// });

function load_city_campus_options(chipin_widget_id, selected_campus_id, selected_fund_id) {
  var params = {
    "api_key" : jQuery("[data='secret_key-"+chipin_widget_id+"']").val(),
    "user_token" : jQuery("[data='user_token-"+chipin_widget_id+"']").val(),
    "load" : "campuses",
    "campus_id" : selected_campus_id
  };  

  var list = jQuery("[data='city_campuses-"+chipin_widget_id+"']");
  jQuery("[data='city_campuses-"+chipin_widget_id+"'] option").remove();
  list.append(new Option('Loading ...', '0'));  

  jQuery.post('/wp-content/plugins/the-city-chipin/city_proxy.php', params, function(data) {
    var json_data = jQuery.parseJSON(data);
    jQuery("[data='city_campuses-"+chipin_widget_id+"'] option").remove();
    list.append(new Option('Select Church/Campus...', '0'));

    jQuery.each(json_data["campuses"], function(index, campus) {
      list.append(new Option(campus["name"], campus["id"]));
    });
    list.find("[value='"+selected_campus_id+"']").attr('selected', 'selected');

    if(parseInt(selected_campus_id) > 0) {
      load_city_fund_options(chipin_widget_id, selected_campus_id, selected_fund_id);
    }
  });  
}


function load_city_fund_options(chipin_widget_id, selected_campus_id, selected_fund_id) {
  var params = {
    "api_key" : jQuery("[data='secret_key-"+chipin_widget_id+"']").val(),
    "user_token" : jQuery("[data='user_token-"+chipin_widget_id+"']").val(),
    "load" : "funds",
    "campus_id" : selected_campus_id
  };

  var list = jQuery("[data='city_funds-"+chipin_widget_id+"']");
  jQuery("[data='city_funds-"+chipin_widget_id+"'] option").remove();
  list.append(new Option('Loading ...', '0'));

  jQuery.post('/wp-content/plugins/the-city-chipin/city_proxy.php', params, function(data) {
    var json_data = jQuery.parseJSON(data);
    jQuery("[data='city_funds-"+chipin_widget_id+"'] option").remove();

    jQuery.each(json_data["funds"], function(index, fund) {
      list.append(new Option(fund["name"], fund["id"]));
    });    
    list.find("[value='"+selected_fund_id+"']").attr('selected', 'selected');

    if(list.find('option').size() == 0) {
      list.append(new Option('NO FUNDS FOUND!!', '0'));
    }
  });  
}

function load_city_fund_options_for_campus_change(elem) {
  var chipin_widget_id = jQuery(elem).parents('.widget-content').find('.chipin_widget_id').val();
  var selected_campus_id = jQuery(elem).val();
  if(parseInt(selected_campus_id) > 0) {
    load_city_fund_options(chipin_widget_id, selected_campus_id, 0);
  } else {
    reset_city_funds_list(chipin_widget_id);
  }
}

function reset_city_funds_list(chipin_widget_id) {
  var list = jQuery("[data='city_funds-"+chipin_widget_id+"']");
  jQuery("[data='city_funds-"+chipin_widget_id+"'] option").remove();
  list.append(new Option('Enter Key/Token above and save to load', '0'));  
}