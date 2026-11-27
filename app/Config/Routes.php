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
$routes->get('frontpage', 'Home::index');
$routes->get('/search', 'SearchController::query');
$routes->get('page/(:any)', 'Page::view/$1');

// News & PR Module Routes
$routes->get('news', 'News::index');
$routes->get('news/datatables', 'News::datatables');
$routes->get('news/detail/(:any)', 'News::detail/$1');
$routes->get('news/get-json/(:any)', 'News::getJson/$1');
$routes->post('news/save', 'News::save');
$routes->post('news/delete', 'News::delete');
$routes->post('news/delete/(:any)', 'News::delete/$1');
$routes->post('news/upload-image', 'News::uploadImage');
$routes->post('news/save-category', 'News::saveCategory');
$routes->post('news/resize-cover', 'News::resizeCover');
$routes->post('news/toggle-featured', 'News::toggleFeatured');
$routes->post('news/toggle-featured/(:any)', 'News::toggleFeatured/$1');
$routes->match(['get', 'post'], 'news/test-db', 'News::testDb');
$routes->match(['get', 'post'], 'news/testDb', 'News::testDb');
$routes->match(['get', 'post'], 'news/sync-db', 'News::syncDb');
$routes->match(['get', 'post'], 'news/syncDb', 'News::syncDb');

/*
 * --------------------------------------------------------------------
 * Authentication & Login Routes
 * --------------------------------------------------------------------
 */
$routes->get('login', 'Auth\Login::index');
$routes->post('login/attempt', 'Auth\Login::attempt');
$routes->get('register', 'Auth\Register::index');
$routes->post('register/attempt', 'Auth\Register::attempt');
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

    // User & Permission Management Routes
    $routes->get('users', 'UserManager::index');
    $routes->get('users/get-json/(:num)', 'UserManager::getJson/$1');
    $routes->post('users/save', 'UserManager::save');
    $routes->post('users/approve/(:num)', 'UserManager::approve/$1');
    $routes->post('users/reject/(:num)', 'UserManager::reject/$1');
    $routes->post('users/delete/(:num)', 'UserManager::delete/$1');
    $routes->post('users/reset-password', 'UserManager::resetPassword');

    // Navigation & Submenu Management Routes
    $routes->get('menu', 'MenuManager::index');
    $routes->post('menu/save', 'MenuManager::save');
    $routes->post('menu/reset', 'MenuManager::reset');

    // Universal Site Text & Content Management Routes
    $routes->get('site-texts', 'SiteTextManager::index');
    $routes->post('site-texts/save', 'SiteTextManager::save');
    $routes->post('site-texts/reset', 'SiteTextManager::reset');

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

    // Digital Services & Information Systems Management Routes
    $routes->get('digital-services', 'DigitalServiceManager::index');
    $routes->post('digital-services/save', 'DigitalServiceManager::save');
    $routes->post('digital-services/save-inline', 'DigitalServiceManager::saveInline');
    $routes->post('digital-services/delete/(:any)', 'DigitalServiceManager::delete/$1');
    $routes->post('digital-services/toggle-status/(:any)', 'DigitalServiceManager::toggleStatus/$1');
    $routes->post('digital-services/reset', 'DigitalServiceManager::reset');

    // Government Procurement & e-GP Management Routes
    $routes->get('procurement', 'ProcurementManager::index');
    $routes->get('procurement/get-inline/(:any)', 'ProcurementManager::getInline/$1');
    $routes->post('procurement/save-inline', 'ProcurementManager::saveInline');
    $routes->post('procurement/delete-inline/(:any)', 'ProcurementManager::deleteInline/$1');

    // Database & Hosting Table Manager Routes
    $routes->get('database-manager', 'DatabaseManager::index');
    $routes->post('database-manager/migrate', 'DatabaseManager::runMigrations');
    $routes->post('database-manager/repair-columns', 'DatabaseManager::repairColumns');
    $routes->post('database-manager/sync-data', 'DatabaseManager::syncData');
    $routes->get('database-manager/inspect-table/(:any)', 'DatabaseManager::inspectTable/$1');
    $routes->post('database-manager/reset-token', 'DatabaseManager::resetToken');
    $routes->post('database-manager/execute-sql', 'DatabaseManager::executeSql');
    $routes->post('database-manager/import-sql', 'DatabaseManager::importSqlFile');
    $routes->get('database-manager/export-sql', 'DatabaseManager::exportSqlFile');
    // Directory & Contacts Management Routes
    $routes->get('directory', 'DirectoryManager::index');
    $routes->post('directory/save', 'DirectoryManager::save');
    $routes->post('directory/delete', 'DirectoryManager::delete');
    $routes->post('directory/save-ministry', 'DirectoryManager::saveMinistry');
    $routes->post('directory/delete-ministry', 'DirectoryManager::deleteMinistry');
    $routes->post('directory/save-department', 'DirectoryManager::saveDepartment');
    $routes->post('directory/delete-department', 'DirectoryManager::deleteDepartment');
    $routes->post('directory/sync', 'DirectoryManager::syncFromRemote');


    // Damrongtham Statistics Manager Routes
    $routes->get('damrongtham/stats', 'DamrongthamManager::stats');
    $routes->post('damrongtham/stats/save', 'DamrongthamManager::saveStats');
    $routes->post('damrongtham/stats/reset', 'DamrongthamManager::resetStats');
    $routes->match(['get', 'post'], 'damrongtham/stats/reset-zero', 'DamrongthamManager::resetToZero');
    $routes->post('damrongtham/daily/save', 'DamrongthamManager::saveDaily');
    $routes->match(['get', 'post'], 'damrongtham/daily/delete-record', 'DamrongthamManager::deleteDaily');
    $routes->match(['get', 'post'], 'damrongtham/daily-delete', 'DamrongthamManager::deleteDaily');
    $routes->match(['get', 'post'], 'damrongtham/daily-delete/(:num)', 'DamrongthamManager::deleteDaily/$1');
    $routes->match(['get', 'post'], 'damrongtham/daily/delete/(:num)', 'DamrongthamManager::deleteDaily/$1');
    $routes->post('damrongtham/baseline/save', 'DamrongthamManager::saveBaseline');
});

// Direct Web-based Hosting Table Updater (Accessible with ?token=...)
$routes->match(['get', 'post'], 'admin/database-manager/direct-update', 'Admin\DatabaseManager::directUpdate');
$routes->match(['get', 'post'], 'update-db', 'Admin\DatabaseManager::directUpdate');

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
$routes->get('news/datatables', 'News::datatables');
$routes->get('news/detail/(:any)', 'News::detail/$1');
$routes->get('news/get-json/(:any)', 'News::getJson/$1');
$routes->post('news/save', 'News::save');
$routes->post('news/delete/(:any)', 'News::delete/$1');
$routes->post('news/delete', 'News::delete');
$routes->post('news/upload-image', 'News::uploadImage');
$routes->post('news/upload-doc', 'News::uploadDoc');
$routes->post('news/save-category', 'News::saveCategory');
$routes->post('news/resize-cover', 'News::resizeCover');
$routes->post('news/toggle-featured', 'News::toggleFeatured');
$routes->post('news/toggle-featured/(:any)', 'News::toggleFeatured/$1');

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
 * Financial Reports & Trial Balance (รายงานทางการเงินและงบทดลอง)
 * --------------------------------------------------------------------
 */
$routes->get('financial-reports', 'FinancialReports::index');
$routes->get('financial-reports/(:any)', 'FinancialReports::index/$1');
$routes->get('news-money', 'FinancialReports::index');
$routes->get('news_money', 'FinancialReports::index');

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

// Legacy 2022 compatibility route for news_form (e.g. news_form/showList?cid=7)
$routes->get('news_form/showList', 'Document::legacyNewsForm');
$routes->get('news_form', 'Document::legacyNewsForm');
$routes->get('news-form', 'Document::legacyNewsForm');

$routes->get('admin/documents/get-item/(:any)', 'Admin\DocumentManager::getItem/$1');
$routes->post('admin/documents/save-item', 'Admin\DocumentManager::saveItem');
$routes->post('admin/documents/delete-item/(:any)', 'Admin\DocumentManager::deleteItem/$1');

/*
 * --------------------------------------------------------------------
 * Executive Leadership Directory & Vision Center Routes
 * --------------------------------------------------------------------
 */
$routes->get('executives', 'Executive::index');
$routes->get('executives/detail/(:any)', 'Executive::detail/$1');
$routes->get('executives/category/(:any)', 'Executive::index/$1');
$routes->get('admin/governor-policy', 'Admin\GovernorPolicyManager::index', ['filter' => 'auth']);
$routes->post('admin/governor-policy/save', 'Admin\GovernorPolicyManager::save', ['filter' => 'auth']);

$routes->get('admin/executives', 'Admin\ExecutiveManager::index', ['filter' => 'auth']);
$routes->get('admin/executives/get-item/(:any)', 'Admin\ExecutiveManager::getItem/$1');
$routes->post('admin/executives/save-item', 'Admin\ExecutiveManager::saveItem');
$routes->post('admin/executives/toggle-active/(:any)', 'Admin\ExecutiveManager::toggleActive/$1');
$routes->post('admin/executives/delete-item/(:any)', 'Admin\ExecutiveManager::deleteItem/$1');

/*
 * --------------------------------------------------------------------
 * Automated News & Social Media Aggregator (ระบบรวบรวมข่าวสารอัตโนมัติ)
 * --------------------------------------------------------------------
 */
$routes->get('admin/news-aggregator', 'Admin\NewsAggregator::index');
$routes->get('admin/news-aggregator/sync', 'Admin\NewsAggregator::sync');
$routes->post('admin/news-aggregator/import', 'Admin\NewsAggregator::import');

/*
 * --------------------------------------------------------------------
 * Hall of Governors (ทำเนียบเจ้าเมืองและผู้ว่าราชการจังหวัดพัทลุง)
 * --------------------------------------------------------------------
 */
$routes->get('governors', 'Governor::index');
$routes->get('governor-hall', 'Governor::index');
$routes->get('governor_hall', 'Governor::index');
$routes->get('hall-of-governors', 'Governor::index');
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

$routes->get('admin/nora-ai', 'Admin\NoraAiManager::index');
$routes->get('admin/nora-ai/list', 'Admin\NoraAiManager::getKnowledgeList');
$routes->post('admin/nora-ai/save-qa', 'Admin\NoraAiManager::saveQaItem');
$routes->post('admin/nora-ai/delete-qa/(:any)', 'Admin\NoraAiManager::deleteQaItem/$1');
$routes->post('admin/nora-ai/save-settings', 'Admin\NoraAiManager::saveSettings');
$routes->post('admin/nora-ai/sync-knowledge', 'Admin\NoraAiManager::syncKnowledge');
$routes->post('admin/nora-ai/gemini-extract', 'Admin\NoraAiManager::geminiExtract');
$routes->post('admin/nora-ai/save-multiple-qa', 'Admin\NoraAiManager::saveMultipleQa');
$routes->post('admin/nora-ai/test-gemini', 'Admin\NoraAiManager::testGeminiConnection');
$routes->get('admin/nora-ai/logs', 'Admin\NoraAiManager::getQuestionLogs');
$routes->post('admin/nora-ai/delete-log/(:any)', 'Admin\NoraAiManager::deleteQuestionLog/$1');
$routes->post('admin/nora-ai/clear-logs', 'Admin\NoraAiManager::clearQuestionLogs');

/*
 * --------------------------------------------------------------------
 * Emergency & Disaster Early Warning System Routes
 * --------------------------------------------------------------------
 */
$routes->get('admin/emergency', 'Admin\EmergencyManager::index');
$routes->get('admin/emergency/get-alert', 'Admin\EmergencyManager::getAlert');
$routes->post('admin/emergency/save-alert', 'Admin\EmergencyManager::saveAlert');
$routes->post('admin/emergency/toggle', 'Admin\EmergencyManager::toggle');
$routes->get('admin/emergency/sync-weather', 'Admin\EmergencyManager::syncWeather');
$routes->post('admin/emergency/auto-apply-weather', 'Admin\EmergencyManager::autoApplyWeather');
$routes->get('weather-radar', 'WeatherRadar::index');
$routes->get('disaster-radar', 'WeatherRadar::index');
$routes->get('cctv-flood', 'WeatherRadar::index');
$routes->get('ddpm-radar', 'WeatherRadar::index');

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

$routes->get('admin/pages', 'Admin\PageManager::index', ['filter' => 'auth']);
$routes->get('admin/pages/get-item/(:any)', 'Admin\PageManager::getItem/$1', ['filter' => 'auth']);
$routes->post('admin/pages/save-item', 'Admin\PageManager::saveItem', ['filter' => 'auth']);
$routes->post('admin/pages/delete-item/(:any)', 'Admin\PageManager::deleteItem/$1', ['filter' => 'auth']);
$routes->post('admin/pages/upload-image', 'Admin\PageManager::uploadImage', ['filter' => 'auth']);

/*
 * --------------------------------------------------------------------
 * Provincial Strategy & Development Hub Routes
 * --------------------------------------------------------------------
 */
$routes->get('strategy', 'Strategy::index');
$routes->get('strategy/download/(:any)', 'Strategy::download/$1');
$routes->get('strategy/categories', 'Strategy::getCategories');
$routes->post('strategy/categories/save', 'Strategy::saveCategory');
$routes->post('strategy/categories/delete/(:any)', 'Strategy::deleteCategory/$1');
$routes->post('strategy/document/save-inline', 'Strategy::saveDocumentInline');
$routes->post('strategy/document/delete-inline/(:any)', 'Strategy::deleteDocumentInline/$1');

// Interactive 3D E-Book Flipbook Viewer & Bookshelf
$routes->get('ebooks', 'Ebook::index');
$routes->get('ebook', 'Ebook::index');
$routes->post('ebooks/save', 'Ebook::save');
$routes->post('ebooks/delete/(:any)', 'Ebook::delete/$1');
$routes->get('ebook/view', 'Ebook::view');
$routes->get('ebook/stream', 'Ebook::stream');

// Provincial Budget Summary Portal (Bureau of the Budget FY 2568-2570)
$routes->get('budget', 'Budget::index');

$routes->get('admin/strategy', 'Admin\StrategyManager::index', ['filter' => 'auth']);
$routes->post('admin/strategy/save-vision', 'Admin\StrategyManager::saveVision', ['filter' => 'auth']);
$routes->post('admin/strategy/save-kpis', 'Admin\StrategyManager::saveKpis', ['filter' => 'auth']);
$routes->post('admin/strategy/save-kpi', 'Admin\StrategyManager::saveKpi', ['filter' => 'auth']);
$routes->post('admin/strategy/delete-kpi', 'Admin\StrategyManager::deleteKpi', ['filter' => 'auth']);
$routes->post('admin/strategy/save-pillar', 'Admin\StrategyManager::savePillar', ['filter' => 'auth']);
$routes->post('admin/strategy/delete-pillar', 'Admin\StrategyManager::deletePillar', ['filter' => 'auth']);
$routes->post('admin/strategy/save-document', 'Admin\StrategyManager::saveDocument', ['filter' => 'auth']);
$routes->post('admin/strategy/delete-document', 'Admin\StrategyManager::deleteDocument', ['filter' => 'auth']);
$routes->post('admin/strategy/save-plan-books', 'Admin\StrategyManager::savePlanBooks', ['filter' => 'auth']);

// Provincial Projects & GIS Map & eMENSCR Dashboard
$routes->get('projects', 'Projects::index');
$routes->get('projects/gis', 'Projects::gis');
$routes->get('projects/dashboard', 'Projects::dashboard');
$routes->get('projects/detail/(:num)', 'Projects::getDetail/$1');
$routes->get('projects/geojson', 'Projects::apiGeojson');

// Admin Project Manager
$routes->get('admin/projects', 'Admin\ProjectManager::index', ['filter' => 'auth']);
$routes->post('admin/projects/save', 'Admin\ProjectManager::save', ['filter' => 'auth']);
$routes->post('admin/projects/delete/(:num)', 'Admin\ProjectManager::delete/$1', ['filter' => 'auth']);
$routes->post('admin/projects/sync-emenscr', 'Admin\ProjectManager::syncEmenscr', ['filter' => 'auth']);
$routes->post('admin/projects/save-settings', 'Admin\ProjectManager::saveSettings', ['filter' => 'auth']);
$routes->post('admin/projects/upload-photo', 'Admin\ProjectManager::uploadPhoto', ['filter' => 'auth']);
$routes->post('admin/projects/upload-doc', 'Admin\ProjectManager::uploadDoc', ['filter' => 'auth']);

/*
 * --------------------------------------------------------------------
 * e-GP Procurement Routes
 * --------------------------------------------------------------------
 */
$routes->get('procurement', 'Procurement::index');
$routes->get('procurement/detail/(:segment)', 'Procurement::detail/$1');
$routes->match(['get', 'post'], 'api/egp/datatable', 'Procurement::ajaxDatatable');


/*
 * --------------------------------------------------------------------
 * Citizen Contact & Public Service Hub (ศูนย์บริการประชาชนและรับเรื่องร้องเรียน)
 * --------------------------------------------------------------------
 */
$routes->get('contact', 'Contact::index');
$routes->post('api/contact/submit', 'Contact::submit');
$routes->get('api/contact/track', 'Contact::track');
$routes->get('api/contact/track/(:any)', 'Contact::track/$1');

/*
 * --------------------------------------------------------------------
 * Damrongtham Center Portal (ศูนย์ดำรงธรรมจังหวัดพัทลุง 1567)
 * --------------------------------------------------------------------
 */
$routes->get('damrongtham', 'Damrongtham::index');
$routes->get('damrongtham/presentation', 'Damrongtham::presentation');
$routes->get('damrong', 'Damrongtham::index');
$routes->get('damrongtham/stats-api', 'Damrongtham::statsApi');
$routes->post('api/damrongtham/submit', 'Damrongtham::submit');
$routes->get('api/damrongtham/track', 'Damrongtham::track');
$routes->get('api/damrongtham/track/(:any)', 'Damrongtham::track/$1');

/*
 * --------------------------------------------------------------------
 * Royal Volunteer 904 Portal (ศูนย์อำนวยการจิตอาสาพระราชทาน จังหวัดพัทลุง)
 * --------------------------------------------------------------------
 */
$routes->get('volunteer', 'Volunteer::index');
$routes->get('royal-volunteer', 'Volunteer::index');
$routes->get('jitasaphra-ratchathan', 'Volunteer::index');

// Admin Contact & Complaint Manager
$routes->get('admin/contacts', 'Admin\ContactManager::index', ['filter' => 'auth']);
$routes->get('admin/contacts/detail/(:num)', 'Admin\ContactManager::detail/$1', ['filter' => 'auth']);
$routes->post('admin/contacts/update-status/(:num)', 'Admin\ContactManager::updateStatus/$1', ['filter' => 'auth']);
$routes->post('admin/contacts/delete/(:num)', 'Admin\ContactManager::delete/$1', ['filter' => 'auth']);

// Admin Official Mailbox Hub (phatthalung@moi.go.th)
$routes->get('admin/mailbox', 'Admin\MailboxManager::index', ['filter' => 'auth']);
$routes->get('admin/mailbox/detail/(:num)', 'Admin\MailboxManager::detail/$1', ['filter' => 'auth']);
$routes->post('admin/mailbox/sync', 'Admin\MailboxManager::sync', ['filter' => 'auth']);
$routes->post('admin/mailbox/toggle-star/(:num)', 'Admin\MailboxManager::toggleStar/$1', ['filter' => 'auth']);
$routes->post('admin/mailbox/delete/(:num)', 'Admin\MailboxManager::deleteEmail/$1', ['filter' => 'auth']);
$routes->post('admin/mailbox/save-settings', 'Admin\MailboxManager::saveSettings', ['filter' => 'auth']);
$routes->post('admin/mailbox/convert/(:num)', 'Admin\MailboxManager::convertToRequest/$1', ['filter' => 'auth']);

/*
 * --------------------------------------------------------------------
 * Digital Directory & Phone Contacts Routes (ทำเนียบส่วนราชการและสมุดโทรศัพท์)
 * --------------------------------------------------------------------
 */
$routes->get('directory', 'Directory::index');
$routes->get('directory/export-vcf', 'Directory::exportVcf');
$routes->get('directory/export-excel', 'Directory::exportExcel');
$routes->get('directory/detail/(:num)', 'Directory::detail/$1');
$routes->get('phone', 'Directory::index'); // Short alias for /phone

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

