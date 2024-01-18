<?php 

/* ================== Helper Functions to check if there is Internet ================== */
function internet_connected()
{
	$ip = gethostbyname('www.google.com');
	if($ip != 'www.google.com') {
	  // echo "connected!";
	  return true;
	} else {
	  // echo "not connected";
	  return false;
	}
}


/* ================== Helper Functions for Date and Time ================== */
date_default_timezone_set('America/Los_Angeles');    //current timezone in the philippines

//function that will format the date into: "June 28,1993"
function get_format_date($date_to_format = '', $time=FALSE)
{
    $date = empty($date_to_format) ? time() : strtotime($date_to_format);
	
   if(empty($date_to_format) && $time) return date('F d, Y h:i A', $date);
    else if($time) return date('F d, Y h:i:s', $date);
    else return date('F d, Y', $date);
}


//function that will return the date or time or both based on the format params
function format_my_date($date_to_format,$date_format='M d, y h:i A')
{
    $date = date_create($date_to_format);
	return date_format($date, $date_format);
}

//function that will return the time with am/pm format
function format_my_time($time)
{
	return	format_my_date($time, "h:i a");
}

//function that will format the date for database
function format_date_for_db($date_to_format)
{
	return format_my_date($date_to_format,$date_format='Y-m-d H:i:s');
}

function get_date($date_to_format = ''){
    $date = empty($date_to_format) ? time() : $date_to_format;
    return date('Y-m-d H:i:s', $date);
}//endfct

//function that will format for all UI
function official_date_format($datetime)
{
    $stamp2 = strtotime(get_date()); //datetime now
    $stamp1 = strtotime($datetime); 
    $hours = floor(($stamp2-$stamp1) / 3600); 
    if($hours>=8766)
    {
    	return date('Y', $stamp1); // 1993
    }
    elseif($hours>=48)
    {
        return date('M j', $stamp1); // Nov 6
    }
    elseif($hours>=24)
    {
        return "Yesterday"; // Yesterday
    }
    else
    {
        return date('g:i A', $stamp1); // 9:00 am
    }
}


function get_string_day($num)
{
	$day = "";
	switch ($num) {
		
		case '1':
			 	$day = "Monday";
			break;
		case '2':
			 	$day = "Tuesday";
			break;
		case '3':
			 	$day = "Wednesday";
			break;
		case '4':
			 	$day = "Thursday";
			break;
		case '5':
			 	$day = "Friday";
			break;
		case '6':
			 	$day = "Saturday";
			break;
		case '7':
			 	$day = "Sunday";
			break;						
		default:
			# code...
			break;
	}
	return $day;
}

/* ================== End ================== */

/* ================== Helper Function for Pagination ================== */

function generate_pagination($limit_per_page, $total_rows, $url)
{
	$CI = get_instance();
	$CI->load->library('pagination');

	//pagination settings
	$config = array();
	$config['base_url'] = $url;
	$config['total_rows'] = $total_rows;
	$config['per_page'] = $limit_per_page; 
	// 
	$config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
	$config['display_pages'] = FALSE;
	
	$config['full_tag_open'] = '<ul class="pagination">';
	$config['full_tag_close'] = '</ul>';
	$config['first_link'] = false;
	$config['last_link'] = false;
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';
	// $config['prev_link'] = '<b>«</b>';
	$config['prev_link'] = '<b>Prev</b>';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
	// $config['next_link'] = '<b>»</b>';
	$config['next_link'] = '<b>Next</b>';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';
	$config['cur_tag_open'] =  '<li class="active"><a href="javascript:void(0);">';
	$config['cur_tag_close'] = '</a></li>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['anchor_class'] = 'class="" ';
	// 
	$CI->pagination->initialize($config);
	$pagination = $CI->pagination->create_links();

	return $pagination;
}

// function generate_batch_options($selected_batch)
// {
// 	$CI = & get_instance();
// 	$CI->load->model('accounts_model');
// 	$batches = $CI->accounts_model->get_all_batches();
// 	$html = "";
// 	$selected = "";

// 	foreach ($batches as $one_batch) {
// 		$selected = ($one_batch->year_graduate==$selected_batch) ? 'selected="selected"' : "";
// 		$html.='<option '.$selected.' value='.$one_batch->year_graduate.'>'.$one_batch->year_graduate.'</option>';
// 	}

// 	return $html;
// }

function generate_group_options($user_id)
{
	$CI = & get_instance();
	$CI->load->model('accounts_model');
	$groups = $CI->accounts_model->get_all_groups();

	$mygroups = $CI->accounts_model->get_user_groups($user_id);
	$selected_group_array = array();

	foreach ($mygroups as $one_selected_group) {
		array_push($selected_group_array, $one_selected_group->group_id);
	}

	$html = "";
	$selected = "";

	foreach ($groups as $one_group) {
		$selected = (in_array($one_group->group_id, $selected_group_array)) ? 'selected="selected"' : "";
		$html.='<option '.$selected.' value='.$one_group->group_id.'>'.$one_group->group_name.'</option>';
	}

	return $html;

}

function generate_skill_options()
{
	$CI = & get_instance();
	$CI->load->model('profiling_model');
	$skills = $CI->profiling_model->get_all_skills();
	$html = "";
	$selected = "";

	foreach ($skills as $one_skill) {
		// $selected = ($one_batch->year_graduate==$selected_batch) ? 'selected="selected"' : "";
		$html.='<option id='.$one_skill->id.' value='.$one_skill->id.'>'.$one_skill->name.'</option>';
	}

	return $html;
}

function generate_skill_options_with_selected($selected_skills=array())
{
	$CI = & get_instance();
	$CI->load->model('profiling_model');
	$skills = $CI->profiling_model->get_all_skills();
	$html = "";
	$selected = array();
	$selected_property = "";
	
	foreach ($selected_skills as $skill) {
		array_push($selected, $skill->skill_id);
	}

	foreach ($skills as $one_skill) {
		$selected_property = (in_array($one_skill->id, $selected)) ? 'selected disabled' : "";
		$html.='<option '.$selected_property.' id='.$one_skill->id.' value='.$one_skill->id.' data-skilltype='.$one_skill->type.'>'.$one_skill->name.'</option>';
	}

	return $html;
}

function generate_admin_options()
{
	$CI = & get_instance();
	$CI->load->model('accounts_model');
	$admins = $CI->accounts_model->get_all_users('admin');

	return $admins;
}

