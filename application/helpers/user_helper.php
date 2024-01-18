<?php 

/* ================== Helper Functions for Session and Data of the Logged in User ================== */

//function to unset user's session
function unset_user_session()
{
    $CI = & get_instance();
    $CI->session->unset_userdata('user');
}

//function to get user's session
function get_user_session()
{
    $CI = & get_instance();
    return $CI->session->userdata('user');
}

//function that get the specific data from logged in user
function get_user($data)
{
	$user = array();
    $CI = & get_instance();
    $user = $CI->session->userdata('user');
   	
    return (!empty($user) && isset($user->$data) ? $user->$data : FALSE);
}

function logged_in()
{
    return (get_user_session()) ? TRUE : FALSE;
}

function user_role($role='admin')
{
    if($role==get_user('account_type'))
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

function user_type($type='admin')
{
    if($type==get_user('account_type'))
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

// function get_account_progress($user_id=0)
// {
//     if($user_id==0) $user_id = get_user('id');
//     $CI = & get_instance();
//     $CI->load->model('accounts_model');
//     $progress = $CI->accounts_model->get_progress($user_id);
//     $progress = $progress[0];

//     $ctr = 0;
//     if($progress->password_changed=='Y') $ctr++;
//     if($progress->added_contact_number=='Y') $ctr++;
//     if($progress->added_address=='Y') $ctr++;
//     if($progress->added_introduction=='Y') $ctr++;
//     if($progress->checked_announcement=='Y') $ctr++;

//     $ctr*=20;

//     return $ctr;
// }

// function get_progress_message($user_id=0)
// {
//     if($user_id==0) $user_id = get_user('id');
//     $CI = & get_instance();
//     $CI->load->model('accounts_model');
//     $field = $CI->accounts_model->get_initial_field($user_id);
//     $field = $field[0];
//     $message = "";

//     if($field->checked_announcement=='N') $message = "Keep updated, please check the announcement of PN.";
//     if($field->added_introduction=='N') $message = "Please complete your profile. Add some introduction about yourself.";
//     if($field->added_address=='N') $message = "Please complete your profile. Add your home address";
//     if($field->added_contact_number=='N') $message = "Please complete your profile. Add your mobile number.";
//     if($field->password_changed=='N') $message = "For more security, please change your default password.";

//     return $message;
// }

// function create_profile_link($id='')
// {
//      $id = get_user('id');
//      $code = get_code($id);
//      $role = get_user('role');
//      $link = '';
//      $company_id = get_company_id($id);
//      $company_code = get_code($company_id);

//     switch ($role) {
//         case 'student':
//              $link = 'my_profile/'.$code;
//              break;
//         case 'admin':
//             $link = 'my_profile/'.$code;
//              break;  
//         case 'assessor':
//              $link = 'my_profile/'.$code;
//              break;        
//          case 'personel':
//              $link = 'view_company_details/'.$company_code;
//              break; 

//         default:
//              # code...
//              break;
//      } 

//      $link = base_url('/accounts/'.$link);
//      return $link;
// }

// function refresh_user_session_data()
// {
//     $CI = & get_instance();
//     $CI->load->model('accounts_model');
//     $select = array('id','role','first_name','middle_name','last_name','email');
//     $data = $CI->accounts_model->get($select,'is_users', '', 'id', get_user('id'),'');
//     $data[0]->type = get_user('type'); // change this, must come from DB not on Session

//     $session_data = $data[0];
//     $CI->session->set_userdata('user', $session_data);
//     // return $session_data;
// }


/* ================== End ================== */

//sample session data
/*
    [id] => 46
    [role] => admin
    [password] => 098f6bcd4621d373cade4e832627b4f6
    [gender] => 
    [first_name] => 1234
    [middle_name] => 4lk
    [last_name] => jlkj
    [contact_number] => lk
    [mobile_network] => 
    [email] => inaki@company.com
    [address] => kj
    [introduction] => lkj
    [date_created] => 2013-10-08 10:33:41
 */ 



