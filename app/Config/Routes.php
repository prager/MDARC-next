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
$routes->post('master/add-fam/(:num)', 'Master::add_fam_mem/$1');
$routes->post('master/add-fam-existing/(:num)', 'Master::add_fam_existing/$1');
$routes->post('edit-mem/(:num)', 'Master::edit_mem/$1');
$routes->post('edit-mem', 'Master::edit_mem');
$routes->post('load-silent/(:num)', 'Master::load_silent/$1');
$routes->post('man-payment/(:num)', 'Master::man_payment/$1');

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
$routes->get('delete-mem/(:num)', 'Master::delete_mem/$1');
$routes->get('purge-mem/(:num)', 'Master::purge_mem/$1');
$routes->get('un-delete-mem/(:num)', 'Master::un_delete_mem/$1');
$routes->get('set-silent-key/(:num)', 'Master::set_silent/$1');
$routes->get('unset-silent-key/(:num)', 'Master::unset_silent/$1');
$routes->get('master/rem-fam/(:num)', 'Master::remove_fam_mem/$1');

$routes->get('members', 'Master::show_members');
$routes->get('all-members', 'Master::show_all_members');
$routes->get('terms', 'Home::terms');
$routes->get('export-all-mems', 'Master::export_all_mems');
$routes->get('export-cur-emails', 'Master::export_cur_emails');
$routes->get('export-due-emails', 'Master::export_due_emails');

$routes->get('members/parent/(:num)',   'Master::parent/$1');
$routes->get('members/children/(:num)', 'Master::children/$1');
// (optional) also keep query-string versions:
$routes->get('members/children',        'Master::children');



