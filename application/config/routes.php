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
|	http://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin/menu/add'] = "admin/menu_add";
$route['admin/menu/add/verify'] = "admin/verify_menu_add";
$route['admin/menu/modify/(:num)'] = "admin/menu_modify/$1";
$route['admin/menu/delete/(:num)'] = "admin/menu_remove/$1";
$route['admin/menu/modify/verify/(:num)'] = "admin/verify_menu_modify/$1";

$route['admin/ingredients/add'] = "admin/ingredients_add";
$route['admin/ingredients/add/verify'] = "admin/verify_ingredients_add";
$route['admin/ingredients/modify/(:num)'] = "admin/ingredients_modify/$1";
$route['admin/ingredients/delete/(:num)'] = "admin/ingredients_remove/$1";
$route['admin/ingredients/modify/verify/(:num)'] = "admin/verify_ingredients_modify/$1";

$route['manage/add'] = "manage/add";
$route['manage/add/verify'] = "manage/verify_add";
$route['manage/modify/(:num)'] = "manage/modify/$1";
$route['manage/delete/(:num)'] = "manage/remove/$1";
$route['manage/modify/verify/(:num)'] = "manage/verify_modify/$1";