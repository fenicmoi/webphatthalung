<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

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
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

/*
 * --------------------------------------------------------------------
 * Authentication & Login Routes
 * --------------------------------------------------------------------
 */
$routes->get('login', 'Auth\Login::index');
$routes->post('login/attempt', 'Auth\Login::attempt');
$routes->get('logout', 'Auth\Login::logout');

/*
 * --------------------------------------------------------------------
 * Admin Backend Routes (Phase 2 Portal)
 * --------------------------------------------------------------------
 */
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], static function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('settings', 'Settings::index');
    $routes->post('settings/save', 'Settings::save');

    // Navigation & Submenu Management Routes
    $routes->get('menu', 'MenuManager::index');
    $routes->post('menu/save', 'MenuManager::save');
    $routes->post('menu/reset', 'MenuManager::reset');
});

/*
 * --------------------------------------------------------------------
 * Public Interactive API Routes (Phase 3 No-Reload SPA)
 * --------------------------------------------------------------------
 */
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function($routes) {
    $routes->get('news', 'PublicData::getNews');
    $routes->post('submit-request', 'PublicData::submitRequest');
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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
