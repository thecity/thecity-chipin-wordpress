function load_city_chipin_widget_admin(elem) {
  load_city_campus_options(elem)
}

function load_city_campus_options(elem) {
  var chipin_widget_id = jQuery(elem).find(".chipin_widget_id").val();
  if(chipin_widget_id != "") {  
    var api_key = jQuery(elem).find(".chipin_city_secret_key").val();
    var user_token = jQuery(elem).find(".chipin_city_user_token").val();
    var selected_campus_id = jQuery(elem).find(".chipin_campus_id_current").val();
    selected_campus_id = parseInt(selected_campus_id);

    var data = {
      action: "thecity_process_request",
      load: "campuses",
      api_key: api_key,
      user_token: user_token
    };


    jQuery.post(ajaxurl, data, function(response) {       
      var json_data = jQuery.parseJSON( jQuery.trim(response) );

      jQuery(elem).find(".chipin_campus_id option").remove();
      
      var list = jQuery(elem).find(".chipin_campus_id");
      list.append(new Option('Select Church/Campus...', '0'));

      jQuery.each(json_data["campuses"], function(index2, campus) {
        list.append(new Option(campus["name"], campus["id"]));
      });

      if(selected_campus_id > 0) {
        list.find("[value='"+selected_campus_id+"']").attr('selected', 'selected');        
        load_city_fund_options(elem, selected_campus_id);
      }            
    });      
  } 
}



function load_city_fund_options(elem, selected_campus_id) {
  var chipin_widget_id = jQuery(elem).find(".chipin_widget_id").val();
  if(chipin_widget_id != "") {  
    var api_key = jQuery(elem).find(".chipin_city_secret_key").val();
    var user_token = jQuery(elem).find(".chipin_city_user_token").val();
    var selected_fund_id = jQuery(elem).find(".chipin_fund_id_current").val();
    selected_fund_id = parseInt(selected_fund_id);

    var data = {
      action: "thecity_process_request",
      load: "funds",
      api_key: api_key,
      user_token: user_token,
      campus_id: selected_campus_id,
    };  

    jQuery(elem).find(".chipin_fund_id option").remove();
    var list = jQuery(elem).find(".chipin_fund_id");
    list.append(new Option('Loading ...', '0'));

    jQuery.post(ajaxurl, data, function(response) {    
      var json_data = jQuery.parseJSON( jQuery.trim(response) );
      jQuery(elem).find(".chipin_fund_id option").remove();

      jQuery.each(json_data["funds"], function(index, fund) {
        list.append(new Option(fund["name"], fund["id"]));
      });    
      list.find("[value='"+selected_fund_id+"']").attr('selected', 'selected');

      if(list.find('option').size() == 0) {
        list.append(new Option('NO FUNDS FOUND!!', '0'));
      }
    });  
  }
}



function load_city_fund_options_for_campus_change(elem) {
  var chipin_widget_id = jQuery(elem).parents('.city_chipin_widget_admin').find('.chipin_widget_id').val();
  var campus_name_field = jQuery(elem).parents('.city_chipin_widget_admin').find('.campus_name');
  var selected_campus_id = jQuery(elem).parents('.city_chipin_widget_admin').find(".chipin_campus_id").val();
  if(parseInt(selected_campus_id) > 0) {
    campus_name_field.val( jQuery(elem).find("option:selected").text() );
    var admin_elem = jQuery(jQuery(elem).parents('.city_chipin_widget_admin'));
    load_city_fund_options(admin_elem, selected_campus_id);
  } else {
    reset_city_funds_list(chipin_widget_id);
  }
}

function reset_city_funds_list(elem) {
  jQuery(elem).find(".chipin_fund_id option").remove();
  var list = jQuery(elem).parents('.widget-content').find(".chipin_fund_id")
  list.append(new Option('Enter Key/Token above and save to load', '0'));  
}


function load_info_for_widget(chipin_widget_id) {
  var params = {
    "load" : "info",
    "chipin_widget_id" : chipin_widget_id
  };

  jQuery.post('/wp-content/plugins/the-city-chipin/city_proxy.php', params, function(data) {    
    var json_data = jQuery.parseJSON(data);
    var designation = json_data["widget_info"]["designation"].toLowerCase();
    var total_amount_cents = json_data["totals"].hasOwnProperty(designation) ? json_data["totals"][designation] : 0;
    render_city_chipin_widget(chipin_widget_id, total_amount_cents, json_data["widget_info"]); 
    calculate_percentage_raised();  
  });   
}

function calculate_percentage_raised() {
  goal = parseInt(jQuery("#campaign-goal").text(), 10);
  current_amt = parseInt(jQuery("#campaign-dollar-amt-raised").text(), 10);
  percent_raised = Math.round(current_amt * 100.0 / goal)
  jQuery("#campaign-pct-raised").text(percent_raised + "%"); 
  jQuery("#campaign-progress-bar").css("width", percent_raised + "%")
}

function convert_date_format(old_date) {
  var d = old_date.split('-');
  if(d.length != 3) return '';
  return [d[1],'/',d[2],'/',d[0]].join('');
}



function render_city_chipin_widget(chipin_widget_id, total_amount_cents, widget_info) {
  var raised_amount = parseInt(total_amount_cents, 10);

  var subdomain_key = widget_info["subdomain_key"];
  var campus_name = widget_info["campus_name"];
  var campus_id = widget_info["campus_id"];
  var fund_id = widget_info["fund_id"];
  var designation = widget_info["designation"];
  var suggested_amount = widget_info["suggested_amount"];
  var start_date = widget_info["start_date"]; // not currently used
  var end_date = convert_date_format(widget_info["end_date"]);
  var goal_amount = parseInt(widget_info["goal_amount"], 10);
  var display_choice = parseInt(widget_info["display_choice"], 10);

  var give_link = [
    'http://', subdomain_key, '.onthecity.org/give?',
    'campus_id=', campus_id,
    '&fund_id=', fund_id,
    '&memo=', designation,
    '&suggested_amount=', suggested_amount
  ].join('');

  var css_classes = "";
  switch (display_choice) {
    case 1:
      css_classes = "pledge-box pledge-box-large pledge-box-light";
      break;
    case 2:
      css_classes = "pledge-box pledge-box-large pledge-box-dark";
      break;
    case 3:
      css_classes = "pledge-box pledge-box-small pledge-box-light";
      break;
    case 4:
      css_classes = "pledge-box pledge-box-small pledge-box-dark";
      break;
    default:
      css_classes = "pledge-box pledge-box-small pledge-box-dark";
      break;
  } 

  jQuery("#city_chipin_widget-"+chipin_widget_id).html(
    '<div class="'+css_classes+'"> '+
      '<h1 id="campaign-title" class="pledge-title">'+designation+'</h1> '+
      '<p id="campaign-org-name" class="org-name">'+campus_name+'</p> '+

      '<div class="pledge-progress"> '+
        '<div class="progress-bar-wrapper"> '+
          '<div class="progress-bar-bg"> '+
            '<div id="campaign-progress-bar" class="progress-bar"></div> '+
          '</div>'+
        '</div>'+

        '<div class="progress-desc"> '+
          '<span class="pledge-zero hide-on-small">$0</span> '+
          '<span id="campaign-pct-raised" class="pledge-pct-raised"></span> '+
          '<span class="pledge-dollar-raised"><span class="hide-on-small">or</span> $<span id="campaign-dollar-amt-raised">'+raised_amount+'</span></span> '+
          '<span class="hide-on-large">of</span> '+
          '<span class="pledge-goal">$<span id="campaign-goal">'+goal_amount+'</span></span> '+
          '<span class="hide-on-large">raised.</span> '+
          '<span class="pledge-ending-date hide-on-small">ends <span id="campaign-end-date">'+end_date+'</span></span> '+
        '</div> '+
      '</div> '+

      '<div class="clearfix"> '+
        '<div class="pledge-action"> '+
          '<a href="'+give_link+'" class="pledge-button">Give now</a> '+
        '</div> '+
        '<div class="credits"> '+
          '<a href="http://www.onthecity.org" target="_blank" class="city-powered">powered by The City</a> '+
        '</div> '+
      '</div> '+
    '</div>'
  );
}

