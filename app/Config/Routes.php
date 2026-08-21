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
$routes->get('/search', 'SearchController::query');

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

    // Banner & Widescreen Layout Management Routes
    $routes->get('banners', 'BannerManager::index');
    $routes->post('banners/save', 'BannerManager::save');
    $routes->post('banners/reset', 'BannerManager::reset');
    $routes->post('banners/upload', 'BannerManager::upload');

    // e-Services Banner & Custom Link Management Routes
    $routes->get('service-banners', 'ServiceBannerManager::index');
    $routes->post('service-banners/save', 'ServiceBannerManager::save');
    $routes->post('service-banners/reset', 'ServiceBannerManager::reset');
    $routes->post('service-banners/upload', 'ServiceBannerManager::upload');

    // Government Procurement & e-GP Management Routes
    $routes->get('procurement', 'ProcurementManager::index');
    $routes->get('procurement/get-inline/(:any)', 'ProcurementManager::getInline/$1');
    $routes->post('procurement/save-inline', 'ProcurementManager::saveInline');
    $routes->post('procurement/delete-inline/(:any)', 'ProcurementManager::deleteInline/$1');
});

/*
 * --------------------------------------------------------------------
 * Public Interactive API Routes (Phase 3 No-Reload SPA)
 * --------------------------------------------------------------------
 */
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function($routes) {
    $routes->get('news', 'PublicData::getNews');
    $routes->post('submit-request', 'PublicData::submitRequest');
    
    // Universal Omni-Search Engine Routes
    $routes->get('search', 'Search::query');
    $routes->get('search/trending', 'Search::trending');
});

/*
 * --------------------------------------------------------------------
 * News & PR On-Page Management & Reading Rooms (Frontend CMS)
 * --------------------------------------------------------------------
 */
$routes->get('news', 'News::index');
$routes->get('news/detail/(:any)', 'News::detail/$1');
$routes->get('news/get-json/(:any)', 'News::getJson/$1');
$routes->post('news/save', 'News::save');
$routes->post('news/delete/(:any)', 'News::delete/$1');
$routes->post('news/delete', 'News::delete');
$routes->post('news/upload-image', 'News::uploadImage');
$routes->post('news/upload-doc', 'News::uploadDoc');
$routes->post('news/save-category', 'News::saveCategory');
$routes->post('news/resize-cover', 'News::resizeCover');

/*
 * --------------------------------------------------------------------
 * e-Service Banner & Link On-Page Studio Routes
 * --------------------------------------------------------------------
 */
$routes->get('service-banners/get-all-json', 'Admin\ServiceBannerManager::getAllJson');
$routes->post('service-banners/save-inline', 'Admin\ServiceBannerManager::saveInline');
$routes->post('service-banners/delete/(:any)', 'Admin\ServiceBannerManager::deleteById/$1');
$routes->post('service-banners/upload-image', 'Admin\ServiceBannerManager::upload');

/*
 * --------------------------------------------------------------------
 * Government Procurement & e-GP Public & Inline Studio Routes
 * --------------------------------------------------------------------
 */
$routes->get('procurement', 'Procurement::index');
$routes->get('procurement/category/(:any)', 'Procurement::index/$1');
$routes->get('admin/procurement/get-inline/(:any)', 'Admin\ProcurementManager::getInline/$1');
$routes->post('admin/procurement/save-inline', 'Admin\ProcurementManager::saveInline');
$routes->post('admin/procurement/delete-inline/(:any)', 'Admin\ProcurementManager::deleteInline/$1');

/*
 * --------------------------------------------------------------------
 * Phatthalung Interactive GIS & Geographic Portal Routes
 * --------------------------------------------------------------------
 */
$routes->get('gis', 'Gis::index');
$routes->get('gis/data', 'Gis::getData');

/*
 * --------------------------------------------------------------------
 * Provincial Activity Photo Gallery & Studio Routes
 * --------------------------------------------------------------------
 */
$routes->get('gallery', 'Gallery::index');
$routes->get('gallery/category/(:any)', 'Gallery::index/$1');
$routes->get('gallery/album/(:any)', 'Gallery::viewAlbum/$1');

$routes->get('admin/gallery/get-item/(:any)', 'Admin\GalleryManager::getItem/$1');
$routes->post('admin/gallery/save-item', 'Admin\GalleryManager::saveItem');
$routes->post('admin/gallery/delete-item/(:any)', 'Admin\GalleryManager::deleteItem/$1');
$routes->post('admin/gallery/delete-photo', 'Admin\GalleryManager::deletePhoto');

/*
 * --------------------------------------------------------------------
 * Provincial Event Calendar Routes (Unified News Integration)
 * --------------------------------------------------------------------
 */
$routes->get('calendar', 'EventCalendar::index');
$routes->get('calendar/get-json', 'EventCalendar::getJson');

/*
 * --------------------------------------------------------------------
 * Phatthalung Web TV & YouTube Video Showcase Routes
 * --------------------------------------------------------------------
 */
$routes->get('videos', 'Video::index');
$routes->get('videos/category/(:any)', 'Video::index/$1');
$routes->post('videos/count-view/(:any)', 'Video::countView/$1');

$routes->get('admin/videos', 'Admin\VideoManager::index');
$routes->get('admin/videos/get-item/(:any)', 'Admin\VideoManager::getItem/$1');
$routes->post('admin/videos/save-item', 'Admin\VideoManager::saveItem');
$routes->post('admin/videos/delete-item/(:any)', 'Admin\VideoManager::deleteItem/$1');

/*
 * --------------------------------------------------------------------
 * Smart Digital Document Archive & Download Hub Routes
 * --------------------------------------------------------------------
 */
$routes->get('documents', 'Document::index');
$routes->get('documents/category/(:any)', 'Document::index/$1');
$routes->post('documents/count-download/(:any)', 'Document::countDownload/$1');

$routes->get('admin/documents/get-item/(:any)', 'Admin\DocumentManager::getItem/$1');
$routes->post('admin/documents/save-item', 'Admin\DocumentManager::saveItem');
$routes->post('admin/documents/delete-item/(:any)', 'Admin\DocumentManager::deleteItem/$1');

/*
 * --------------------------------------------------------------------
 * Executive Leadership Directory & Vision Center Routes
 * --------------------------------------------------------------------
 */
$routes->get('executives', 'Executive::index');
$routes->get('executives/category/(:any)', 'Executive::index/$1');
$routes->get('admin/executives/get-item/(:any)', 'Admin\ExecutiveManager::getItem/$1');
$routes->post('admin/executives/save-item', 'Admin\ExecutiveManager::saveItem');
$routes->post('admin/executives/delete-item/(:any)', 'Admin\ExecutiveManager::deleteItem/$1');

/*
 * --------------------------------------------------------------------
 * Hall of Governors (ทำเนียบเจ้าเมืองและผู้ว่าราชการจังหวัดพัทลุง)
 * --------------------------------------------------------------------
 */
$routes->get('governors', 'Governor::index');
$routes->get('admin/governors', 'Admin\GovernorManager::index');
$routes->get('admin/governors/get-item/(:any)', 'Admin\GovernorManager::getItem/$1');
$routes->post('admin/governors/save-item', 'Admin\GovernorManager::saveItem');
$routes->post('admin/governors/delete-item/(:any)', 'Admin\GovernorManager::deleteItem/$1');

/*
 * --------------------------------------------------------------------
 * ITA / OIT Transparency Assessment & Open Data Hub Routes
 * --------------------------------------------------------------------
 */
$routes->get('ita', 'Ita::index');
$routes->get('ita/category/(:any)', 'Ita::index/$1');
$routes->post('ita/count-download/(:any)', 'Ita::countDownload/$1');

$routes->get('admin/ita/get-item/(:any)', 'Admin\ItaManager::getItem/$1');
$routes->post('admin/ita/save-item', 'Admin\ItaManager::saveItem');
$routes->post('admin/ita/delete-item/(:any)', 'Admin\ItaManager::deleteItem/$1');
$routes->post('admin/ita/save-scorecard', 'Admin\ItaManager::saveScorecard');

/*
 * --------------------------------------------------------------------
 * "น้องโนรา AI Assistant" 24/7 Citizen Service Chatbot Routes
 * --------------------------------------------------------------------
 */
$routes->post('api/nora-ai/chat', 'Api\NoraAi::chat');
$routes->get('api/nora-ai/settings', 'Api\NoraAi::getSettings');

$routes->get('admin/nora-ai/list', 'Admin\NoraAiManager::getKnowledgeList');
$routes->post('admin/nora-ai/save-qa', 'Admin\NoraAiManager::saveQaItem');
$routes->post('admin/nora-ai/delete-qa/(:any)', 'Admin\NoraAiManager::deleteQaItem/$1');
$routes->post('admin/nora-ai/save-settings', 'Admin\NoraAiManager::saveSettings');

/*
 * --------------------------------------------------------------------
 * Emergency & Disaster Early Warning System Routes
 * --------------------------------------------------------------------
 */
$routes->get('admin/emergency/get-alert', 'Admin\EmergencyManager::getAlert');
$routes->post('admin/emergency/save-alert', 'Admin\EmergencyManager::saveAlert');

/*
 * --------------------------------------------------------------------
 * Static Page Management & Routing
 * --------------------------------------------------------------------
 */
$routes->get('page/(:any)', 'Page::view/$1');

$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], static function($routes) {
    // Other Admin routes remain defined above, but we append Pages here.
    // However, it's better to add the specific page routes into the existing group, or just define them here as long as we use the auth filter.
});

// Since we already have an 'admin' group, we can just define the routes directly with the filter and namespace:
$routes->get('admin/pages', 'Admin\PageManager::index', ['filter' => 'auth']);
$routes->get('admin/pages/get-item/(:any)', 'Admin\PageManager::getItem/$1', ['filter' => 'auth']);
$routes->post('admin/pages/save-item', 'Admin\PageManager::saveItem', ['filter' => 'auth']);
$routes->post('admin/pages/delete-item/(:any)', 'Admin\PageManager::deleteItem/$1', ['filter' => 'auth']);
$routes->post('admin/pages/upload-image', 'Admin\PageManager::uploadImage', ['filter' => 'auth']);

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
