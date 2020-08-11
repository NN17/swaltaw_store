<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ignite extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

    function __construct(){
        parent::__construct();
        $this->load->model('migrate');
        $this->migrate->table_migrate();

        date_default_timezone_set('Asia/Rangoon');
    }

	public function index()
	{
		$this->load->view('layouts/auth');
    }

    public function login(){
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('psw', TRUE);

        $login = $this->ignite_model->loginState($username, $password);
        if($login['status']){
            redirect('home');
            // echo 'true';
        }else{
            $this->load->view('layouts/auth');
            // echo 'false';
        }
    }
    
    public function home(){
        $this->breadcrumb->add('Home');

        $data['content'] = 'pages/home';
        $this->load->view('layouts/template', $data);
    }

    public function saleItemSearch(){
        $key = $this->input->get('keyword');
        $items = [];
        if(!empty($key)){
            $items = $this->ignite_model->get_saleItemSearch($key);
        }

        header('Content-Type: application/json');
        echo json_encode($items);
    }

    public function switchLanguage(){
        $key = $this->uri->segment(2);

        $language = ($key != "") ? $key : "english";
        $this->session->set_userdata('site_lang', $language);
        redirect($_SERVER['HTTP_REFERER']);
    }

    /*
    * Setting Section
    */

    // Warehouse
    public function warehouse(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Warehouse');

        $data['warehouses'] = $this->ignite_model->get_data('warehouse_tbl')->result_array();
        $data['content'] = 'pages/warehouse';
        $this->load->view('layouts/template', $data);
    }

    public function createWarehouse(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Warehouse', 'warehouse');
        $this->breadcrumb->add('Create');

        $data['content'] = 'pages/createWarehouse';
        $this->load->view('layouts/template', $data);
    }

    public function addWarehouse(){
        $referer = $this->input->post('referer');

        $name = $this->input->post('name');
        $serial = $this->input->post('serial');
        $remark = $this->input->post('remark');
        if($this->input->post('active')){
            $active = true;
        }
        else{
            $active = false;
        }

        if($this->input->post('shop')){
            $shop = true;
        }
        else{
            $shop = false;
        }

        $arr = array(
            'warehouseName' => $name,
            'serial' => $serial,
            'remark' => $remark,
            'activeState' => $active,
            'shop' => $shop
        );

        $this->db->insert('warehouse_tbl', $arr);
        $this->session->set_flashdata('success', 'Warehouse Successfully Created.');
        redirect($referer);
    }

    public function editWarehouse(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Warehouse', 'warehouse');
        $this->breadcrumb->add('Modify');

        $warehouseId = $this->uri->segment(2);

        $data['warehouse'] = $this->ignite_model->get_limit_data('warehouse_tbl', 'warehouseId', $warehouseId)->row_array();
        $data['content'] = 'pages/editWarehouse';
        $this->load->view('layouts/template', $data);
    }

    public function updateWarehouse(){
        $warehouseId = $this->uri->segment(3);

        $name = $this->input->post('name');
        $serial = $this->input->post('serial');
        $remark = $this->input->post('remark');
        if($this->input->post('active')){
            $active = true;
        }
        else{
            $active = false;
        }

        if($this->input->post('shop')){
            $shop = true;
        }
        else{
            $shop = false;
        }

        $arr = array(
            'warehouseName' => $name,
            'serial' => $serial,
            'remark' => $remark,
            'activeState' => $active,
            'shop' => $shop
        );

        $this->db->where('warehouseId', $warehouseId);
        $this->db->update('warehouse_tbl', $arr);
        $this->session->set_flashdata('success', 'Warehouse Successfully Updated.');
        redirect('warehouse');
    }

    public function deleteWarehouse(){
        $warehouseId = $this->uri->segment(3);

        $this->db->where('warehouseId', $warehouseId);
        $this->db->delete('warehouse_tbl');
        $this->session->set_flashdata('success', 'Warehouse Successfully Deleted.');
        redirect('warehouse');
    }

    
    // Supplier
    public function supplier(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Supplier');

        $data['suppliers'] = $this->ignite_model->get_suppliers();
        $data['content'] = 'pages/supplier';
        $this->load->view('layouts/template', $data);
    }

    public function createSupplier(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Supplier', 'supplier');
        $this->breadcrumb->add('Create');

        $data['content'] = 'pages/createSupplier';
        $this->load->view('layouts/template', $data);
    }

    public function addSupplier(){
        $referer = $this->uri->segment(3);
        $seg4 = $this->uri->segment(4);

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $cPerson = $this->input->post('contactPerson');
        $cPhone1 = $this->input->post('phone1');
        $cPhone2 = $this->input->post('phone2');
        $cAddr1 = $this->input->post('address1');
        $cAddr2 = $this->input->post('address2');
        $remark = $this->input->post('remark');

        $arr = array(
            'supplierName' => $name,
            'emailAddress' => $email,
            'contactPerson' => $cPerson,
            'contactPhone1' => $cPhone1,
            'contactPhone2' => $cPhone2,
            'contactAddress1' => $cAddr1,
            'contactAddress2' => $cAddr2,
            'remark' => $remark
        );

        $this->db->insert('supplier_tbl', $arr);
        $this->session->set_flashdata('success', 'Supplier Successfully Created.');
        if(!empty($referer)){
            redirect($referer.'/'.$seg4);
        }
        else{
            redirect('supplier');
        }
    }

    public function editSupplier(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Supplier', 'supplier');
        $this->breadcrumb->add('Modify');

        $supplierId = $this->uri->segment(2);

        $data['supplier'] = $this->ignite_model->get_limit_data('supplier_tbl', 'supplierId', $supplierId)->row_array();

        $data['content'] = 'pages/editSupplier';
        $this->load->view('layouts/template', $data);
    }

    public function updateSupplier(){
        $supplierId = $this->uri->segment(3);

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $cPerson = $this->input->post('contactPerson');
        $cPhone1 = $this->input->post('phone1');
        $cPhone2 = $this->input->post('phone2');
        $cAddr1 = $this->input->post('address1');
        $cAddr2 = $this->input->post('address2');
        $remark = $this->input->post('remark');

        $arr = array(
            'supplierName' => $name,
            'emailAddress' => $email,
            'contactPerson' => $cPerson,
            'contactPhone1' => $cPhone1,
            'contactPhone2' => $cPhone2,
            'contactAddress1' => $cAddr1,
            'contactAddress2' => $cAddr2,
            'remark' => $remark
        );

        $this->db->where('supplierId', $supplierId);
        $this->db->update('supplier_tbl', $arr);
        $this->session->set_flashdata('success', 'Supplier Successfully Updated.');
        redirect('supplier');
    }

    public function deleteSupplier(){
        $supplierId = $this->uri->segment(3);

        $this->db->where('supplierId', $supplierId);
        $this->db->delete('supplier_tbl');
        $this->session->set_flashdata('success', 'Supplier Successfully Deleted.');
        redirect('supplier');
    }

    // Currency
    public function currency(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Currency');

        $data['currencies'] = $this->ignite_model->get_data('currency_tbl')->result_array();
        $data['content'] = 'pages/currency';
        $this->load->view('layouts/template', $data);
    }

    public function changeCurrency(){
        $currencyId = $this->uri->segment(3);
        $this->db->update('currency_tbl', ['default' => true], ['currencyId' => $currencyId]);
        $this->db->update('currency_tbl', ['default' => false], ['currencyId !=' => $currencyId]);
    }

    /*
    * Items & Price Section
    */
    public function itemsPrice(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Items & Price');

        $this->load->library('pagination');

        $config['base_url'] = base_url().'items-price/';
        $config['total_rows'] = $this->ignite_model->get_items_rows();
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        $config['first_url'] = 'items-price/0';
        // $config['num_tag_open'] = '<div class="ui button tiny circular olive">';
        // $config['num_tag_close'] = '</div>';
        $config['attributes'] = array('class' => 'ui button tiny circular olive');
        $config['cur_tag_open'] = '<div class="ui button tiny circular">';
        $config['cur_tag_close'] = '</div>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        $data['items'] = $this->ignite_model->get_itemsPrice($page, $config['per_page']);
        
        $data['content'] = 'pages/itemPrice';
        $this->load->view('layouts/template', $data);
    }

    public function newItem(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Items & price', 'items-price/0');
        $this->breadcrumb->add('Create');

        $data['referer'] = $this->uri->segment(2);
        $data['code'] = $this->ignite_model->max('items_price_tbl','itemId');
        $data['categories'] = $this->ignite_model->get_data_order('categories_tbl', 'categoryName', 'asc')->result_array();
        $data['brands'] = $this->ignite_model->get_data_order('brands_tbl', 'brandName', 'asc')->result_array();
        $data['suppliers'] = $this->ignite_model->get_data_order('supplier_tbl', 'supplierName', 'asc')->result_array();
        $data['currencies'] = $this->ignite_model->get_data('currency_tbl')->result_array();
        $data['content'] = 'pages/newItem';
        $this->load->view('layouts/template', $data);
    }

    public function getLetterCode(){
        $catId = $this->input->post('catId');

        $cat = $this->ignite_model->get_limit_data('categories_tbl', 'categoryId', $catId)->row();
        echo json_encode(array('status' => true, 'code' => $cat->letterCode));
    }

    public function addItem(){
        $referer = $this->input->post('referer');

        $category = $this->input->post('category');
        $brand = $this->input->post('brand');
        $supplier = $this->input->post('supplier');
        $name = $this->input->post('name');
        $model = $this->input->post('model');
        $code = $this->input->post('code');
        $currency = $this->input->post('currency');
        $p_price = $this->input->post('p_price');
        $r_price = $this->input->post('r_price');
        $w_price = $this->input->post('w_price');
        $remark = $this->input->post('remark');

        $arr = array(
            'itemName' => $name,
            'itemModel' => $model,
            'categoryId' => $category,
            'codeNumber' => $code,
            'brandId' => $brand,
            'currency' => $currency,
            'purchasePrice' => $p_price,
            'retailPrice' => $r_price,
            'wholesalePrice' => $w_price,
            'supplierId' => $supplier,
            'remark' => $remark,
            'referId' => 0,
            'active' => TRUE
        );

        $this->db->insert('items_price_tbl', $arr);
        $this->session->set_flashdata('success', 'New Item Successfully Created.');
        if($referer === '~'){
            redirect('items-price/0');
        }else{
            redirect($referer);
        }
        
    }

    public function editItem(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Items & price', 'items-price/0');
        $this->breadcrumb->add('Modify');

        $itemId = $this->uri->segment(2);

        $data['item'] = $this->ignite_model->get_limit_data('items_price_tbl', 'itemId', $itemId)->row_array();

        $data['code'] = $this->ignite_model->max('items_price_tbl','itemId');
        $data['categories'] = $this->ignite_model->get_data('categories_tbl')->result_array();
        $data['brands'] = $this->ignite_model->get_data('brands_tbl')->result_array();
        $data['suppliers'] = $this->ignite_model->get_data('supplier_tbl')->result_array();
        $data['currencies'] = $this->ignite_model->get_data('currency_tbl')->result_array();

        $data['content'] = 'pages/editItem';
        $this->load->view('layouts/template', $data);
    }

    public function updateItem(){
        $itemId = $this->uri->segment(3);

        $category = $this->input->post('category');
        $brand = $this->input->post('brand');
        $supplier = $this->input->post('supplier');
        $name = $this->input->post('name');
        $model = $this->input->post('model');
        $code = $this->input->post('code');
        $currency = $this->input->post('currency');
        $remark = $this->input->post('remark');

        $arr = array(
            'itemName' => $name,
            'itemModel' => $model,
            'categoryId' => $category,
            'codeNumber' => $code,
            'brandId' => $brand,
            'currency' => $currency,
            'supplierId' => $supplier,
            'remark' => $remark,
            'referId' => 0,
            'active' => TRUE
        );

        $this->db->where('itemId', $itemId);
        $this->db->update('items_price_tbl', $arr);
        redirect('items-price/0');
    }

    public function deleteItem(){
        $itemId = $this->uri->segment(3);

        $this->db->where('itemId', $itemId);
        $this->db->delete('items_price_tbl');

        redirect('items-price/0');
    }

    public function getPrice(){
        $itemId = $this->uri->segment(3);

        $price = $this->ignite_model->get_limit_data('items_price_tbl','itemId',$itemId)->row_array();
        echo json_encode($price);
    }

    public function changePrice(){
        $itemId = $this->uri->segment(3);
        $item = $this->ignite_model->get_limit_data('items_price_tbl', 'itemId', $itemId)->row_array();

        $p_price = $this->input->post('p_price');
        $r_price = $this->input->post('r_price');
        $w_price = $this->input->post('w_price');

        $arr = array(
            'itemName' => $item['itemName'],
            'categoryId' => $item['categoryId'],
            'codeNumber' => $item['codeNumber'],
            'brandId' => $item['brandId'],
            'currency' => $item['currency'],
            'purchasePrice' => $p_price,
            'retailPrice' => $r_price,
            'wholesalePrice' => $w_price,
            'supplierId' => $item['supplierId'],
            'remark' => $item['remark'],
            'referId' => $itemId,
            'active' => TRUE
        );

        $this->db->insert('items_price_tbl', $arr);

        $this->db->update('items_price_tbl', ['active' => false], ['itemId' => $itemId]);
        redirect('items-price/0');
    }

    /*
    * Categories Section
    */
    public function categories(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Categories');

        $data['content'] = 'pages/categories';
        $data['categories'] = $this->ignite_model->get_categories();
        $this->load->view('layouts/template', $data);
    }

    public function createCategory(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Categories', 'categories');
        $this->breadcrumb->add('Create');

        $data['content'] = 'pages/newCategory';
        $this->load->view('layouts/template', $data);
    }

    public function addCategory(){
        $referer = $this->uri->segment(3);
        $seg4 = $this->uri->segment(4);

        $name = $this->input->post('name');
        $l_code = $this->input->post('l_code');
        $remark = $this->input->post('remark');

        $arr = array(
            'categoryName' => $name,
            'letterCode' => $l_code,
            'remark' => $remark
        );

        $this->db->insert('categories_tbl', $arr);
        if(!empty($referer)){
            redirect($referer.'/'.$seg4);
        }
        else{
            redirect('categories');
        }
    }

    public function checkLetterCode(){
        $letter = $this->input->post('character');
        $checkLC = $this->ignite_model->get_limit_data('categories_tbl', 'letterCode', $letter)->row();
        if(isset($checkLC->categoryId)){
            echo json_encode(array('status' => false));
        }else{
            echo json_encode(array('status' => true, 'letter' => $letter));
        }
    }

    public function editCategory(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Categories', 'categories');
        $this->breadcrumb->add('Modify');

        $catId = $this->uri->segment(2);

        $data['catDetail'] = $this->ignite_model->get_limit_data('categories_tbl', 'categoryId', $catId)->row_array();

        $data['content'] = 'pages/editCategory';
        $this->load->view('layouts/template', $data);
    }

    public function updateCategory(){
        $catId = $this->uri->segment(3);

        $name = $this->input->post('name');
        $l_code = $this->input->post('l_code');
        $remark = $this->input->post('remark');

        $arr = array(
            'categoryName' => $name,
            'letterCode' => $l_code,
            'remark' => $remark
        );

        $this->db->where('categoryId', $catId);
        $this->db->update('categories_tbl', $arr);

        redirect('categories');
    }

    public function deleteCategory(){
        $catId = $this->uri->segment(3);

        $this->db->where('categoryId', $catId);
        $this->db->delete('categories_tbl');

        redirect('categories');
    }

    /*
    * Brands Section
    */
    public function brands(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Brands');

        $data['brands'] = $this->ignite_model->get_brands();
        $data['content'] = 'pages/brands';
        $this->load->view('layouts/template', $data);
    }

    public function createBrand(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Brands', 'brands');
        $this->breadcrumb->add('Create');

        $data['content'] = 'pages/newBrand';
        $this->load->view('layouts/template', $data);
    }

    public function addBrand(){
        $referer = $this->uri->segment(3);
        $seg4 = $this->uri->segment(4);

        $name = $this->input->post('name');
        $remark = $this->input->post('remark');

        $arr = array(
            'brandName' => $name,
            'remark' => $remark
        );

        $this->db->insert('brands_tbl', $arr);

        if(!empty($referer)){
            redirect($referer.'/'.$seg4);
        }
        else{            
            redirect('brands');
        }
    }

    public function editBrand(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Brands', 'brands');
        $this->breadcrumb->add('Modify');

        $brandId = $this->uri->segment(2);
        $data['brand'] = $this->ignite_model->get_limit_data('brands_tbl', 'brandId', $brandId)->row_array();

        $data['content'] = 'pages/editBrand';
        $this->load->view('layouts/template', $data);
    }

    public function updateBrand(){
        $brandId = $this->uri->segment(3);

        $name = $this->input->post('name');
        $remark = $this->input->post('remark');

        $arr = array(
            'brandName' => $name,
            'remark' => $remark
        );

        $this->db->where('brandId', $brandId);
        $this->db->update('brands_tbl', $arr);

        redirect('brands');
    }

    public function deleteBrand(){
        $brandId = $this->uri->segment(3);

        $this->db->where('brandId', $brandId);
        $this->db->delete('brands_tbl');
        redirect('brands');
    }

    //Purchase Section
    public function purchase(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Stocks In');

        $data['purchaseItem'] = $this->ignite_model->get_purchaseItem();
        $data['content'] = 'pages/purchase';
        $this->load->view('layouts/template', $data);
    }

    public function newPurchase(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Stocks In', 'purchase/0');
        $this->breadcrumb->add('Create');

        $data['items'] = $this->ignite_model->get_allItems();
        $data['warehouses'] = $this->ignite_model->get_data('warehouse_tbl')->result_array();

        $data['content'] = 'pages/newPurchase';
        $this->load->view('layouts/template', $data);
    }

    public function addPurchase(){
        $itemId = $this->input->post('item');
        $warehouse = $this->input->post('warehouse');
        $date = $this->input->post('pDate');
        $qty = $this->input->post('qty');
        $remark = $this->input->post('remark');

        $arr = array(
            'itemId' => $itemId,
            'warehouseId' => $warehouse,
            'purchaseDate' => $date,
            'quantity' => $qty,
            'remark' => $remark
        );

        $this->db->insert('purchase_tbl', $arr);

        // Check balance exist
        $balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $itemId, 'warehouseId' => $warehouse])->row_array();
        if(count($balance) > 0){
            $arr = array(
                'qty' => $qty + $balance['qty'],
            );

            $this->db->where('itemId', $itemId);
            $this->db->where('warehouseId', $warehouse);
            $this->db->update('stocks_balance_tbl', $arr);
        }
        else{
            $arr = array(
                'itemId' => $itemId,
                'qty' => $qty,
                'warehouseId' => $warehouse,
            );

            $this->db->insert('stocks_balance_tbl', $arr);
        }
        redirect('purchase/0');
    }

    public function editPurchase(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Stocks In', 'purchase/0');
        $this->breadcrumb->add('Modify');

        $purchaseId = $this->uri->segment(2);

        $data['purchase'] = $this->ignite_model->get_limit_data('purchase_tbl', 'purchaseId', $purchaseId)->row_array();

        $data['items'] = $this->ignite_model->get_allItems();
        $data['warehouses'] = $this->ignite_model->get_data('warehouse_tbl')->result_array();

        $data['content'] = 'pages/editPurchase';
        $this->load->view('layouts/template', $data);
    }

    public function updatePurchase(){
        $purchaseId = $this->uri->segment(3);

        $itemId = $this->input->post('item');
        $warehouse = $this->input->post('warehouse');
        $date = $this->input->post('pDate');
        $qty = $this->input->post('qty');
        $remark = $this->input->post('remark');

        $arr = array(
            'itemId' => $itemId,
            'warehouseId' => $warehouse,
            'purchaseDate' => $date,
            'quantity' => $qty,
            'remark' => $remark
        );

        $this->db->where('purchaseId', $purchaseId);
        $this->db->update('purchase_tbl', $arr);

        redirect('purchase/0');
    }

    public function delPurchase(){
        $purchaseId = $this->uri->segment(3);

        $this->db->where('purchaseId', $purchaseId);
        $this->db->delete('purchase_tbl');
        redirect('purchase/0');
    }

    /*
    * Sales Section Start
    */
    public function transfer(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Stocks Out');

        $data['issues'] = $this->ignite_model->get_issuedItems();

        $data['content'] = 'pages/transfer';
        $this->load->view('layouts/template', $data);
    }

    public function newTransfer(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Stocks Out', 'transfer');
        $this->breadcrumb->add('Create');

        $data['items'] = $this->ignite_model->get_issueItems();
        $data['warehouses'] = $this->ignite_model->get_data('warehouse_tbl')->result_array();
        
        $data['content'] = 'pages/newTransfer';
        $this->load->view('layouts/template', $data);
    }

    public function getItemsByWarehouse(){
        $warehouseId = $this->uri->segment(3);

        $items = $this->ignite_model->get_issueItemsByWarehouse($warehouseId);

        if(count($items) > 0){            
            foreach($items as $item){
                echo '<option value="">Select</option>';
                echo '<option value="'.$item['codeNumber'].'">'.$item['itemName'].' ( '.$item['categoryName'].' / '.$item['brandName'].' ) ~ '.number_format($item['purchasePrice']).' '.$item['currency'].'</option>';
            }
        }
        else{
            echo '<option value="">No Result Found ..</option>';
        }
    }

    public function checkQty(){
        $qty = $this->uri->segment(3);
        $warehouse = $this->uri->segment(4);
        $itemCode = $this->uri->segment(5);

        $item = $this->ignite_model->get_limit_data('items_price_tbl', 'codeNumber', $itemCode)->row();

        $status = $this->ignite_model->checkQty($qty, $warehouse, $item->itemId);
        if($status['qty'] >= $qty){
            echo json_encode(array('status' => true, 'quantity' => $status['qty']));
        }
        else{
            echo json_encode(array('status' => false, 'quantity' => $status['qty']));
        }
    }

    public function doTransfer(){
        $source = $this->input->post('warehouseFrom');
        $destination = $this->input->post('warehouseTo');
        $itemCode = $this->input->post('item');
        $issueDate = $this->input->post('iDate');
        $qty = $this->input->post('qty');
        $remark = $this->input->post('remark');

        $item = $this->ignite_model->get_limit_data('items_price_tbl', 'codeNumber', $itemCode)->row();

        $arr = array(
            'itemId' => $item->itemId,
            'qty' => $qty,
            'transferIn' => $destination,
            'transferOut' => $source,
            'tranType' => 'T',
            'issueDate' => $issueDate,
            'remark' => $remark
        );

        $this->db->insert('transfer_tbl', $arr);

        $balIn = $this->ignite_model->get_limit_datas('stocks_balance_tbl',['itemId' => $item->itemId, 'warehouseId' => $destination])->row();

        if(isset($balIn->qty)){
            $updBalIn = array(
                'qty' => ($balIn->qty + $qty)
            );

            $this->db->where('itemId', $item->itemId);
            $this->db->where('warehouseId', $destination);
            $this->db->update('stocks_balance_tbl', $updBalIn);
        }else{
            $updBalIn = array(
                'itemId' => $item->itemId,
                'qty' => $qty,
                'warehouseId' => $destination
            );

            $this->db->insert('stocks_balance_tbl', $updBalIn);
        }

        $balOut = $this->ignite_model->get_limit_datas('stocks_balance_tbl',['itemId' => $item->itemId, 'warehouseId' => $source])->row();

        $updBalOut = array(
            'qty' => ($balOut->qty - $qty)
        );

        $this->db->where('itemId', $item->itemId);
        $this->db->where('warehouseId', $source);
        $this->db->update('stocks_balance_tbl', $updBalOut);

        redirect('transfer');
    }
    /*
    * Sales Section End
    */

    /*
    * Balance Section Start
    */
    public function stockBalance(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Stock Balance');

        $data['items'] = $this->ignite_model->get_stock_items()->result();
        $data['warehouse'] = $this->ignite_model->get_limit_data('warehouse_tbl', 'activeState', true)->result();
        $data['content'] = 'pages/balance';
        $this->load->view('layouts/template', $data);
    }
    /*
    * Balance Section End
    */

    /*
    * User Section Start
    */

    public function users(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Users');

        $data['users'] = $this->ignite_model->get_user_data();
        $data['content'] = 'pages/users';
        $this->load->view('layouts/template', $data);
    }

    public function newUser(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Users', 'users');
        $this->breadcrumb->add('Create');

        $data['content'] = 'pages/newUser';
        $this->load->view('layouts/template', $data);
    }

    public function addUser(){
        $arr = array(
            'username' => $this->input->post('name'),
            'secret' =>  $this->auth->hash_password($this->input->post('psw')),
            'role' => 1,
            'accountState' => true
        );

        $this->db->insert('accounts_tbl', $arr);
        $max = $this->ignite_model->max('accounts_tbl','accId');
        redirect('set-permission/'.$max['accId']);
    }

    public function setPermission(){
        $accId = $this->uri->segment(2);
        $data['user'] = $this->ignite_model->get_limit_data('accounts_tbl', 'accId', $accId)->row();
        $data['links'] = $this->ignite_model->get_data('link_structure_tbl')->result();

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Users', 'users');
        $this->breadcrumb->add('Permission');

        $data['content'] = 'pages/permission';
        $this->load->view('layouts/template', $data);
    }

    public function addPermission(){
        $accId = $this->uri->segment(3);

        $links = $this->ignite_model->get_data('link_structure_tbl')->result();
        $count = count($this->input->post());
        $accept = '';
        $i = 1;
        foreach($links as $link){
            if($this->input->post('permission'.$link->linkId)){
                $accept .= $link->linkId;
                if($i < ($count-1)){
                    $accept .= ',';
                    $i++;
                }
            }
        }
        
        $arr = array(
            'accId' => $accId,
            'link_accept' => $accept,
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s')
        );

        $this->db->insert('permission_tbl', $arr);
        redirect('users');
    }

    public function modifyPermission(){
        $permissionId = $this->uri->segment(2);

        $data['user'] = $this->ignite_model->get_limit_data('permission_tbl', 'permissionId', $permissionId)->row();
        $data['links'] = $this->ignite_model->get_data('link_structure_tbl')->result();

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Users', 'users');
        $this->breadcrumb->add('Modify Permission');

        $data['content'] = 'pages/modifyPermission';
        $this->load->view('layouts/template', $data);
    }

    public function updatePermission(){
        $accId = $this->uri->segment(3);

        $links = $this->ignite_model->get_data('link_structure_tbl')->result();
        $count = count($this->input->post());
        $accept = '';
        $i = 1;
        foreach($links as $link){
            if($this->input->post('permission'.$link->linkId)){
                $accept .= $link->linkId;
                if($i < ($count-1)){
                    $accept .= ',';
                    $i++;
                }
            }
        }
        
        $arr = array(
            'link_accept' => $accept,
            'updated_at' => date('Y-m-d h:i:s')
        );

        $this->db->where('accId', $accId);
        $this->db->update('permission_tbl', $arr);
        redirect('users');
    }

    public function resetPassword(){
        $accId = $this->uri->segment(2);
        $data['acc'] = $this->ignite_model->get_limit_data('accounts_tbl', 'accId', $accId)->row();

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Users', 'users');
        $this->breadcrumb->add('Reset Password');

        $data['content'] = 'pages/resetPassword';
        $this->load->view('layouts/template', $data);
    }

    public function updatePassword(){
        $accId = $this->uri->segment(3);
        $psw = $this->auth->hash_password($this->input->post('psw'));

        $this->db->update('accounts_tbl', ['secret' => $psw], ['accId' => $accId]);
        redirect('users');
    }

    public function disableUser(){
        $accId = $this->uri->segment(2);

        $this->db->update('accounts_tbl', ['accountState' => false], ['accId' => $accId]);
        redirect('users');
    }

    public function enableUser(){
        $accId = $this->uri->segment(2);

        $this->db->update('accounts_tbl', ['accountState' => true], ['accId' => $accId]);
        redirect('users');
    }
}

