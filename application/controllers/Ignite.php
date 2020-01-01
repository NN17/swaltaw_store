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
        $data['content'] = 'pages/home';
        $this->load->view('layouts/template', $data);
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
        $data['warehouses'] = $this->ignite_model->get_data('warehouse_tbl')->result_array();
        $data['content'] = 'pages/warehouse';
        $this->load->view('layouts/template', $data);
    }

    public function createWarehouse(){
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

        $arr = array(
            'warehouseName' => $name,
            'serial' => $serial,
            'remark' => $remark,
            'activeState' => $active
        );

        $this->db->insert('warehouse_tbl', $arr);
        redirect($referer);
    }

    public function editWarehouse(){
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

        $arr = array(
            'warehouseName' => $name,
            'serial' => $serial,
            'remark' => $remark,
            'activeState' => $active
        );

        $this->db->where('warehouseId', $warehouseId);
        $this->db->update('warehouse_tbl', $arr);
        redirect('warehouse');
    }

    public function deleteWarehouse(){
        $warehouseId = $this->uri->segment(3);

        $this->db->where('warehouseId', $warehouseId);
        $this->db->delete('warehouse_tbl');
        redirect('warehouse');
    }

    
    // Supplier
    public function supplier(){
        $data['suppliers'] = $this->ignite_model->get_suppliers();
        $data['content'] = 'pages/supplier';
        $this->load->view('layouts/template', $data);
    }

    public function createSupplier(){
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
        if(!empty($referer)){
            redirect($referer.'/'.$seg4);
        }
        else{
            redirect('supplier');
        }
    }

    public function editSupplier(){
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
        redirect('supplier');
    }

    public function deleteSupplier(){
        $supplierId = $this->uri->segment(3);

        $this->db->where('supplierId', $supplierId);
        $this->db->delete('supplier_tbl');
        redirect('supplier');
    }

    // Currency
    public function currency(){
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
        $data['code'] = $this->ignite_model->max('items_price_tbl','itemId');
        $data['categories'] = $this->ignite_model->get_data_order('categories_tbl', 'categoryName', 'asc')->result_array();
        $data['brands'] = $this->ignite_model->get_data_order('brands_tbl', 'brandName', 'asc')->result_array();
        $data['suppliers'] = $this->ignite_model->get_data_order('supplier_tbl', 'supplierName', 'asc')->result_array();
        $data['currencies'] = $this->ignite_model->get_data('currency_tbl')->result_array();
        $data['content'] = 'pages/newItem';
        $this->load->view('layouts/template', $data);
    }

    public function addItem(){
        $referer = $this->input->post('referer');

        $category = $this->input->post('category');
        $brand = $this->input->post('brand');
        $supplier = $this->input->post('supplier');
        $name = $this->input->post('name');
        $code = $this->input->post('code');
        $currency = $this->input->post('currency');
        $p_price = $this->input->post('p_price');
        $r_price = $this->input->post('r_price');
        $w_price = $this->input->post('w_price');
        $remark = $this->input->post('remark');

        $arr = array(
            'itemName' => $name,
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
        redirect($referer);
        
    }

    public function editItem(){
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
        $code = $this->input->post('code');
        $currency = $this->input->post('currency');
        $remark = $this->input->post('remark');

        $arr = array(
            'itemName' => $name,
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
        $data['content'] = 'pages/categories';
        $data['categories'] = $this->ignite_model->get_categories();
        $this->load->view('layouts/template', $data);
    }

    public function createCategory(){
        $data['content'] = 'pages/newCategory';
        $this->load->view('layouts/template', $data);
    }

    public function addCategory(){
        $referer = $this->uri->segment(3);
        $seg4 = $this->uri->segment(4);

        $name = $this->input->post('name');
        $remark = $this->input->post('remark');

        $arr = array(
            'categoryName' => $name,
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

    public function editCategory(){
        $catId = $this->uri->segment(2);

        $data['catDetail'] = $this->ignite_model->get_limit_data('categories_tbl', 'categoryId', $catId)->row_array();

        $data['content'] = 'pages/editCategory';
        $this->load->view('layouts/template', $data);
    }

    public function updateCategory(){
        $catId = $this->uri->segment(3);

        $name = $this->input->post('name');
        $remark = $this->input->post('remark');

        $arr = array(
            'categoryName' => $name,
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
        $data['brands'] = $this->ignite_model->get_brands();
        $data['content'] = 'pages/brands';
        $this->load->view('layouts/template', $data);
    }

    public function createBrand(){
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
        $data['purchaseItem'] = $this->ignite_model->get_purchaseItem();
        $data['content'] = 'pages/purchase';
        $this->load->view('layouts/template', $data);
    }

    public function newPurchase(){
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
        redirect('purchase/0');
    }

    public function editPurchase(){
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
    public function sales(){
        $data['content'] = 'pages/sales';
        $this->load->view('layouts/template', $data);
    }
    /*
    * Sales Section End
    */
}

