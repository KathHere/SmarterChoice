<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "main";
$route['404_override'] = '';

/* Custom Routes */
//$route["order/create-order"] = "order/create_order";
$route['order/order-list'] = "order/order_list";
$route['order/order-grid']	    = "order/order_list";
$route['order/patient-vault'] = "order/patient_vault_records";
// $route['hospice/hospice-list']	 = "hospice/hospice_list";
$route['hospice/company-list']	 = "hospice/company_list";
$route['hospice/create-hospice']	 = "hospice/create";
$route['subscribe']	 				= "main/subscribe_notification";
$route['getnotifications']	 		= "main/getnotifications";
$route['login']				= "main/index";
$route['equipment/submit-equipment-rates'] = "equipment/submit_equipment_rates";


/* End */

/* End of file routes.php */
/* Location: ./application/config/routes.php */