<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

#DASHBOARD
$route['Registration'] = 'login/Registration';
$route['dashboard'] = 'user/users/index';
$route['admin/dashboard'] = 'user/users/index';
$route['dashboard1'] = 'admin/Dashboard/DashboardAmc';
#ADMIN
$route['admin/create'] = 'admin/admin/CreateAdmins';
$route['admin/view'] = 'admin/admin/viewAdmin';
$route['user_profile'] = 'admin/profile/userProfile';

$route['user/create'] = 'user/users/index';

$route['user_login'] = 'login/UserLogin';
$route['user_dashboard'] = 'clients/clients/index';




$route['authorization'] = 'settings/Authorization/authorization';
#privileges
$route['privilegesAccess'] = 'settings/userPrivilegesAccess';



#MASTERS
#COUNTRY
$route['country'] = 'masters/country/country';
$route['country_datatable'] = 'masters/country/ajax_list';
$route['edit_country'] = 'masters/country/editCountry';

#STATE
$route['state'] = 'masters/state/state';
$route['state_datatable'] = 'masters/state/ajax_list';
$route['edit_state'] = 'masters/state/editState';

#DISTRICT
$route['district'] = 'masters/district/district';
$route['district_datatable'] = 'masters/district/ajax_list';
$route['edit_district'] = 'masters/district/editDistrict';

#CITY
$route['city'] = 'masters/city/city';
$route['city_datatable'] = 'masters/city/ajax_list';
$route['edit_city'] = 'masters/city/editCity';

$route['mandal'] = 'masters/mandal/mandal';
$route['mandal_datatable'] = 'masters/mandal/ajax_list';
$route['edit_mandal'] = 'masters/mandal/editMandal';



#Currency
$route['currency'] = 'masters/currency/currency';
$route['currency_datatable'] = 'masters/currency/ajax_list';
$route['edit_currency'] = 'masters/currency/editCurrency';


#client
$route['client_profile'] = 'clients/profile/userProfile';












