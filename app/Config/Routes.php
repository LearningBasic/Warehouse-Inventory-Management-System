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
$routes->post('/auth','Auth::check');
$routes->get('/logout','Auth::logout');
//dashboard
$routes->get('out-of-stock','Dashboard::outofStock');
$routes->get('list-supplier','Dashboard::listSupplier');
$routes->get('pending-damage-report','Dashboard::damageItem');
$routes->get('pending-repair-report','Dashboard::overhaulItem');
$routes->get('pending-transfer-report','Dashboard::transferItem');
$routes->get('return-order-report','Dashboard::returnOrder');
$routes->get('notification-bar/(:any)','Dashboard::Notification/$1');
//auto login
$routes->get('auto-login/(:any)','Dashboard::autoLogin/$1');
//saving the data
$routes->post('save-industry','Home::saveIndustry');
$routes->get('list-industry','Home::listIndustry');
$routes->get('fetch-industry','Home::fetchIndustry');
$routes->post('save-category','Home::saveCategory');
$routes->get('list-category','Home::listCategory');
$routes->post('save-warehouse','Home::saveWarehouse');
$routes->get('list-warehouse','Home::listWarehouse');
$routes->post('save-supplier','Home::saveSupplier');
$routes->post('update-supplier','Home::updateSupplier');
$routes->post('save-account','Home::saveAccount');
$routes->post('update-account','Home::updateAccount');
$routes->post('add-product','Home::addProduct');
$routes->post('save-product','Home::saveProduct');
$routes->post('update','Home::update');
$routes->get('assignment','Home::assignment');
$routes->post('change-password','Home::changePassword');
$routes->post('save-inventory','ProductController::saveInventory');
$routes->post('save-stocks','Home::saveStocks');
$routes->post('add-stock','Home::addStock');
$routes->post('upload-image','Home::uploadImage');
$routes->post('remove-category','Home::removeCategory');
$routes->post('remove-location','Home::removeLocation');
$routes->post('remove-industry','Home::removeIndustry');
$routes->post('reset-account','Home::resetAccount');
$routes->post('save-entry','Purchase::saveEntry');
$routes->post('add-comment','Purchase::addComment');
$routes->post('update-orders','Purchase::updateOrder');
$routes->post('delete-item','Purchase::deleteItem');
$routes->post('send-item','Purchase::sendItem');
$routes->post('accept-item','Purchase::acceptItem');
$routes->post('cancel-item','Purchase::cancelItem');
$routes->get('count-item','Purchase::countItem');
$routes->post('save-changes','Purchase::saveChanges');
$routes->post('change-assignment','Home::changeAssignment');
$routes->post('save-group','Home::saveGroup');
$routes->post('forward','Purchase::forwardPRF');
//fetch the product details
$routes->get('product-information','ProductController::productInfo');
$routes->post('save-report','ProductController::saveReport');
$routes->post('submit-report','ProductController::submitReport');
$routes->post('send-report','ProductController::sendReport');
$routes->post('send-accomplishment','ProductController::sendAccomplishment');
$routes->get('view-report','ProductController::viewReport');
$routes->get('view-accomplishment','ProductController::viewAccomplishmentReport');
$routes->post('transfer-item','ProductController::transferItem');
$routes->post('receive-report','ProductController::receiveReport');
$routes->post('save-data','ProductController::scanning');
$routes->get('view-items','ProductController::viewItems');
$routes->get('view-vendors','ProductController::viewVendor');
$routes->get('list-editor','Purchase::listEditor');
//standard user reports
$routes->post('send-damage-report','ProductController::damageReport');
$routes->post('accept-damage-report','ProductController::acceptDamageReport');
$routes->post('send-repair-report','ProductController::repairReport');
$routes->post('accept-repair-report','ProductController::acceptRepairReport');
$routes->post('save-request','ProductController::saveRequest');
$routes->post('accept-transfer-request','ProductController::acceptRequest');
$routes->post('submit-return-order','ProductController::submitReturnOrder');
$routes->post('accept-return-order','ProductController::acceptReturnOrder');
//report
$routes->get('search-stocks','Report::searchStockReport');
$routes->get('search-inventory','Report::searchInventory');
$routes->post('save-order','Purchase::saveOrder');
$routes->post('re-submit','Purchase::reSubmit');
$routes->get('get-editor','Purchase::getEditor');
$routes->post('cancel-order','Purchase::cancelOrder');
$routes->get('view-order','Purchase::viewOrder');
$routes->get('notification-purchase','Purchase::purchaseNotification');
$routes->get('notification','Purchase::notification');
$routes->get('notification-purchase-order','Purchase::PONotification');
$routes->get('canvas-notification','Purchase::canvasNotification');
$routes->get('total-notification','Purchase::totalNotification');
$routes->get('view-purchase','Purchase::viewPurchase');
$routes->get('view-quotation','Purchase::viewQuotation');
$routes->post('accept','Purchase::Accept');
$routes->post('cancel','Purchase::Cancel');
$routes->post('cancel-purchase','Purchase::CancelPurchase');
$routes->post('cancel-transfer','Purchase::cancelTransfer');
$routes->post('close-purchase','Purchase::archivePurchase');
$routes->get('fetch-added-supplier','Purchase::fetchSupplier');
$routes->post('add-entry','Purchase::addEntry');
$routes->post('remove-item','Purchase::removeItem');
$routes->post('save-form','Purchase::saveForm');
$routes->get('auto-reset','Purchase::autoReset');
$routes->get('auto-email','Purchase::autoEmail');
$routes->get('auto-detect','Report::autoDetect');
$routes->get('view-images','Purchase::viewImage');
$routes->post('add-assignment','Home::addAssignment');
$routes->post('accept-assignment','Home::acceptAssignment');
$routes->post('accept-request','Home::acceptRequest');
$routes->post('cancel-request','Home::cancelRequest');
$routes->post('proceed','Home::proceedRequest');
$routes->post('create-purchase-order','Home::createPO');
$routes->post('save-settings','Home::saveSettings');
$routes->post('approve','Home::approve');
$routes->post('decline','Home::decline');
$routes->get('search-vendor','Home::searchVendor');
$routes->get('vendor-information','Home::vendorInformation');
$routes->get('download/(:any)','Report::Download/$1');
$routes->get('download-file/(:any)','Report::DownloadFile/$1');
$routes->get('file-download/(:any)','Report::fileDownload/$1');
$routes->get('open-file/(:any)','Report::openFile/$1');
$routes->get('vendor-ledger','Report::vendorLedger');
$routes->get('fetch-purchase_number','Report::fetchPO');
$routes->get('generate-return-summary','Report::generateReturnSummary');
$routes->get('load-entries','Purchase::loadEntries');
$routes->post('save-entries','Purchase::saveEntries');
$routes->get('issued-items','Report::issuedItems');
$routes->get('library','Report::Library');
$routes->post('save-task','Report::saveTask');
$routes->post('remove-task','Report::removeTask');
$routes->get('fetch-items','Purchase::fetchItems');
$routes->get('search-request','Purchase::searchRequest');
$routes->get('search-order','Purchase::searchOrder');
$routes->get('search-purchase','Purchase::searchPurchase');

$routes->group('',['filter'=>'AuthCheck'],function($routes)
{
    $routes->get('/scan','Home::Scanner');
    $routes->get('/dashboard','Home::dashboard');
    $routes->get('/stocks','Home::stocks');
    $routes->get('/generate-qrcode/(:any)','Home::generateQR/$1');
    $routes->get('/add','Home::addItem');
    $routes->get('/edit/(:any)','Home::edit/$1');
    $routes->get('/transfer/(:any)','Home::transfer/$1');
    $routes->get('/manage','Home::manageStocks');
    $routes->get('/create-report/(:any)','Home::createReport/$1');
    $routes->get('receiving-item','Home::receiveItem');
    $routes->get('receive-order','Home::receiveOrder');
    $routes->get('/reserved','Home::storage');
    $routes->get('/new-product/(:any)','Home::newProduct/$1');
    $routes->get('/suppliers','Home::suppliers');
    $routes->get('/add-supplier','Home::addSupplier');
    $routes->get('/edit-supplier/(:any)','Home::editSupplier/$1');
    $routes->get('/configuration','Home::systemConfiguration');
    $routes->get('/edit-account/(:any)','Home::editAccount/$1');
    $routes->get('/add-report','Home::addReport');
    $routes->get('/damage-report','Home::damageReport');
    $routes->get('/repair-report','Home::repairReport');
    $routes->get('/transfer-item','Home::transferItem');
    $routes->get('/return-order','Home::returnOrder');
    $routes->get('/request','Home::userRequest');
    $routes->get('/orders','Home::purchaseRequest');
    $routes->get('/edit-purchase/(:any)','Home::editPurchase/$1');
    $routes->get('/list-orders','Home::listOrders');
    $routes->get('/export/(:any)','Home::export/$1');
    $routes->get('/create/(:any)','Home::createCanvas/$1');
    $routes->get('/approve-orders','Home::approver');
    $routes->get('/canvass-sheet-request','Home::canvassRequest');
    $routes->get('/profile','Home::profile');
    //reports
    $routes->get('/report-stocks','Home::stocksReport');
    $routes->get('/assign','Home::Assign');
    $routes->get('/edit-order/(:any)','Home::editOrder/$1');
    $routes->get('/local-purchase','Home::localPurchase');
    $routes->get('/view/(:any)','Home::viewVendor/$1');
    $routes->get('/purchase-order','Home::purchaseOrder');
    $routes->get('/modify/(:any)','Home::Modify/$1');
    $routes->get('/overall-report','Home::overAllReport');
    $routes->get('/ledger','Home::ledger');
    $routes->get('/return-order-summary','Home::returnOrderReport');
    $routes->get('/generate/(:any)','Home::generatePRF/$1');
    $routes->get('/issuance','Home::Issuance');
});
$routes->group('',['filter'=>'AlreadyLoggedIn'],function($routes)
{
    $routes->get('/auth','Auth::index');
    $routes->get('/Auth','Auth::index');
    $routes->get('/','Home::index');
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
