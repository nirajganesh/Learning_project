<?php

namespace Config;

use App\Controllers\AdminController;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('user-create', 'Home::insert');
$routes->get('user-update', 'Home::updateUser');
$routes->get('user-list','Home::selectData');
$routes->get('users-list','AdminController::insertData');

// $routes->get('api/users-create','AdminController::create_data');
// $routes->get('api/users-read','AdminController::read_data');
// $routes->get('api/users-update','AdminController::update_data');
// $routes->get('api/users-insert','AdminController::insert_data');

$routes->group("admin",function($routes){
    $routes->get('users-create','AdminController::create_data');
    $routes->get('users-read','AdminController::read_data');
    $routes->get('users-update','AdminController::update_data');
    $routes->get('users-insert','AdminController::insert_data');
});

$routes->group("home",function($routes){
    $routes->get('users-create','Home::create_data');
    $routes->get('users-read','Home::read_data');
    $routes->get('users-update','Home::update_data');
    $routes->get('users-insert','Home::insert_data');
});


$routes->group("api",function($routes){
    $routes->post('create-employee','EmployeeController::createEmployee');
    $routes->get('list-employee','EmployeeController::listEmployee');
    $routes->get('single-employee/(:num)','EmployeeController::singleEmployeeDetails/$1');
    $routes->put('update-employee/(:num)','EmployeeController::updateEmployee/$1');
    $routes->delete('delete-employee/(:num)','EmployeeController::deleteEmployee/$1');
});

$routes->group("mobileapi",["namespace" => "App\Controllers\Api"],function($routes){

    // Category
    $routes->post('create-category','ApiController::createCategory');
    $routes->get('list-category','ApiController::listCategory');

    // Blog
    $routes->post('create-blog','ApiController::createBlog');
    $routes->get('list-blog','ApiController::listBlogs');
    $routes->get('single-blog/(:num)','ApiController::singleBlogDetails/$1');
    $routes->put('update-blog/(:num)','ApiController::updateBlog/$1');
    $routes->delete('delete-blog/(:num)','ApiController::deleteBlog/$1');
});

$routes->group("apimobile",["namespace" =>"App\Controllers\MobileApi"],function($routes){

    $routes->post('register','MobileApiController::userRegister');
    $routes->post('login','MobileApiController::userLogin');
    $routes->get('profile','MobileApiController::userProfile');
  
    $routes->post('create-book','MobileApiController::createBook');
    $routes->get('list-books','MobileApiController::listBooks');
    $routes->put('update-book/(:num)','MobileApiController::updateBook/$1');
    $routes->delete('delete-book/(:num)','MobileApiController::deleteBook/$1');
  
});





/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
