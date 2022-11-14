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
$route['default_controller'] = 'ignite';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['home'] = 'ignite/home';
$route['preview/(:num)'] = 'ignite/checkOutPreview';
$route['payment-refund'] = 'ignite/refundPayment';

/*
* Route for Language Switching
*/
$route['language/(:any)'] = 'ignite/switchLanguage';

/*
* Routes for Discounts
*/
$route['discounts'] = 'ignite/discounts';
$route['create-discount'] = 'ignite/newDiscount';
$route['modify-discount/(:num)'] = 'ignite/editDiscount';
$route['delete-discount/(:num)'] = 'ignite/deleteDiscount';
$route['add-discount'] = 'ignite/addDiscountInv';

/*
* Routes for Charges
*/
$route['extra-charges'] = 'ignite/extraCharges';
$route['create-charges'] = 'ignite/newCharges';
$route['modify-charge/(:num)'] = 'ignite/editCharges';
$route['delete-charge/(:num)'] = 'ignite/deleteCharges';
/*
* Routes for setting
*/
$route['warehouse'] = 'ignite/warehouse';
$route['create-warehouse'] = 'ignite/createWarehouse';
$route['edit-warehouse/(:num)'] = 'ignite/editWarehouse';

/*
* Routes for Suppliers
*/
$route['supplier'] = 'ignite/supplier';
$route['create-supplier'] = 'ignite/createSupplier';
$route['edit-supplier/(:num)'] = 'ignite/editSupplier';

/*
* Routes for Currency
*/
$route['currency'] = 'ignite/currency';

/*
* Routes for Categories
*/
$route['categories'] = 'ignite/categories';
$route['create-category'] = 'ignite/createCategory';
$route['edit-category/(:num)'] = 'ignite/editCategory';

/*
* Routes for Brands
*/
$route['brands'] = 'ignite/brands';
$route['create-brand'] = 'ignite/createBrand';
$route['edit-brand/(:num)'] = 'ignite/editBrand';

/*
* Routes for Vouchers
*/
$route['vouchers'] = 'ignite/vouchers';
$route['create-voucher'] = 'ignite/newVoucher';
$route['modify-vouchers/(:num)'] = 'ignite/editVoucher';
$route['remove-vouchers/(:num)'] = 'ignite/deleteVoucher';
$route['update-vouchers/(:num)'] = 'ignite/updateVoucher';

/*
* Routes for Items & Price
*/
$route['items-price/(:num)'] = 'ignite/itemsPrice';
$route['define-price/(:num)/(:any)'] = 'ignite/defineItemPrice';
$route['new-item/(:any)'] = 'ignite/newItem';
$route['edit-item/(:num)'] = 'ignite/editItem';
$route['get-defined-price/(:num)'] = 'ignite/getDefinedPrice';
$route['search-items'] = 'ignite/searchItemPrice';
$route['retail-price'] = 'ignite/getRetailPrice';

/*
* Routes for Purchase
*/
$route['purchase'] = 'ignite/purchase';
$route['create-purchase'] = 'ignite/newPurchase';
$route['edit-purchase/(:num)'] = 'ignite/editPurchase';
$route['detail-purchase/(:num)'] = 'ignite/purchaseDetail';
$route['set-all-purchase/:num'] = 'ignite/setAllPurchase';
$route['set-purchase/:num'] = 'ignite/setPurchase';

/*
* Routes for Sales
*/
$route['transfer'] = 'ignite/transfer';
$route['create-transfer'] = 'ignite/newTransfer';

/*
* Routes for Stocks Balance
*/
$route['stock-balance'] = 'ignite/stockBalance';
$route['export-pdf/:any'] = 'ignite/exportPdf';
$route['export-excel'] = 'ignite/exportExcel';

/*
* Routes for users
*/
$route['users'] = 'ignite/users';
$route['new-user'] = 'ignite/newUser';
$route['set-permission/:num'] = 'ignite/setPermission';
$route['modify-permission/:num'] = 'ignite/modifyPermission';
$route['reset-password/:num'] = 'ignite/resetPassword';
$route['disable-user/:num'] = 'ignite/disableUser';
$route['enable-user/:num'] = 'ignite/enableUser';

/*
* Routes for Customers
*/
$route['customers'] = 'ignite/customer';
$route['new-customer'] = 'ignite/newCustomer';
$route['edit-customer/:num'] = 'ignite/editCustomer';
$route['pay-credit'] = 'ignite/payCredit';
$route['customer-detail/:num'] = 'ignite/detailCustomer';

/*
* Routes for Invoices
*/
$route['invoices/:any'] = 'ignite/invoices';
$route['invoices/:any/:num'] = 'ignite/invoices';
$route['update-delivery/:num'] = 'ignite/updateDelivery';
$route['refer-invoice/:num'] = 'ignite/referInvoice';
$route['del-invoice/:num'] = 'ignite/delInvoice';
$route['get-invoice'] = 'ignite/getReferInvoice';
$route['update-payment/:num'] = 'ignite/updatePayment';
$route['rePrint'] = 'ignite/rePrint';
$route['search-invoices'] = 'ignite/searchInvoices';

/*
* Routes for print Receipt
*/
$route['print/:num'] = 'ignite/printReceipt';

/*
* Routes for Reports
*/
$route['reports/daily'] = 'ignite/dailyReport';
$route['reports/monthly'] = 'ignite/monthlyReport';
$route['reports/yearly'] = 'ignite/yearlyReport';

/*
* Routes for Services
*/
$route['services'] = 'ignite/services';
$route['newService'] = 'ignite/newService';

/*
* Routes for Settings
*/
$route['setting'] = 'ignite/setting';

/*
* Route for Damages
*/
$route['damages'] = 'ignite/damages';
$route['create-damage'] = 'ignite/newDamage';
$route['modify-damage/(:num)'] = 'ignite/editDamage';
$route['delete-damage/(:num)'] = 'ignite/deleteDamage';

/*
Routes for Purchase Return
*/
$route['purchase-return'] = 'ignite/purchaseReturn';
$route['create-return'] = 'ignite/newReturn';
$route['modify-return/:num'] = 'ignite/editReturn';
$route['delete-return/:num'] = 'ignite/deleteReturn';

/*
* Routes for Logout
*/
$route['logout'] = 'ignite/signOut';