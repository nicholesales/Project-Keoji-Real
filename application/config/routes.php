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
|	https://codeigniter.com/userguide3/general/routing.html
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

// Auth routes
$route['auth/login'] = 'AuthController/login';
$route['auth/register'] = 'AuthController/register';
$route['auth/process-registration'] = 'AuthController/processRegistration';
$route['auth/process-login'] = 'AuthController/processLogin';
$route['auth/forgot-password'] = 'AuthController/forgotPassword';  // Changed from forgot_password
$route['auth/process-forgot-password'] = 'AuthController/processForgotPassword';
$route['auth/security-question'] = 'AuthController/securityQuestion';
$route['auth/process-security-question'] = 'AuthController/processSecurityQuestion';
$route['auth/reset-password'] = 'AuthController/resetPassword';
$route['auth/process-reset-password'] = 'AuthController/processResetPassword';
$route['auth/logout'] = 'AuthController/logout';

// Posts routes
$route['posts'] = 'PostsController/index';
$route['posts/create'] = 'PostsController/create';
$route['posts/edit/(:num)'] = 'PostsController/edit/$1';
$route['posts/update/(:num)'] = 'PostsController/update/$1';
$route['posts/delete/(:num)'] = 'PostsController/delete/$1';
$route['posts/view/(:num)'] = 'PostsController/view/$1';
$route['posts/get_drafts'] = 'PostsController/get_drafts';
$route['posts/get_published'] = 'PostsController/get_published';

// Feed routes
$route['feed'] = 'PostsController/feed';
$route['posts/feed'] = 'PostsController/feed';
$route['posts/toggle_like'] = 'PostsController/toggle_like';
$route['posts/add_comment'] = 'PostsController/add_comment';
$route['posts/load_comments'] = 'PostsController/load_comments';

// User routes
$route['profile'] = 'UserController/profile';
$route['user/profile'] = 'UserController/profile';
$route['user/update-profile'] = 'UserController/updateProfile';

// Default routes
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Comments routes
$route['comments/my'] = 'CommentController/my_comments';
$route['comments/received'] = 'CommentController/received_comments';
$route['comments/delete/(:num)'] = 'CommentController/delete/$1';
$route['comments/edit/(:num)'] = 'CommentController/edit/$1';
$route['comments/update'] = 'CommentController/update';
