<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');
$routes->get('/', 'Home::default');
$routes->get('public', 'Home::mainpg');

$routes->post('login', 'Login::index');
$routes->post('master-edit-faq/(:num)', 'Master::edit_faq/$1');
$routes->post('master-edit-faq', 'Master::edit_faq');
$routes->post('load-admin/(:num)', 'Master::load_admin/$1');
$routes->post('reset-user/(:num)', 'Master::reset_user/$1');
$routes->post('master-search', 'Master::search');

$routes->get('delete-faq/(:num)', 'Master::delete_faq/$1');
$routes->get('edit-users', 'Master::edit_users');
$routes->get('ci-ver', 'Home::index');
$routes->get('logout', 'Login::logout');
$routes->get('master', 'Master::index');
$routes->get('faqs', 'Home::faqs');
$routes->get('master-faqs', 'Master::master_faqs');
$routes->get('deactivate/(:num)', 'Master::activate/$1');
$routes->get('activate/(:num)', 'Master::activate/$1');
$routes->get('unauthorize/(:num)', 'Master::authorize/$1');
$routes->get('authorize/(:num)', 'Master::authorize/$1');
$routes->get('add-mem', 'Master::add_mem');
$routes->get('members', 'Master::show_members');

$routes->get('members/parent/(:num)', 'Master::show_parent/$1'); // JSON endpoint