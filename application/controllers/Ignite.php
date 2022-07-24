<?php
use Dompdf\Dompdf;
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
        $this->load->library('libigniter');

        date_default_timezone_set('Asia/Rangoon');
    }

	public function index()
	{
		$this->load->view('layouts/auth');
    }

    public function login(){
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('psw', TRUE);
        $remember = $this->input->post('remember');

        $login = $this->ignite_model->loginState($username, $password);
        if($login['status']){
            if(!empty($remember)){
                set_cookie ("loginId", $username, time()+ (10 * 365 * 24 * 60 * 60));  
                set_cookie ("loginPass", $password,  time()+ (10 * 365 * 24 * 60 * 60));
            }
                else{
                    set_cookie ("loginId",""); 
                    set_cookie ("loginPass","");
                }
            redirect('home');
            // echo 'true';
        }else{
            $this->load->view('layouts/auth');
            // echo 'false';
        }
    }
    
    public function home(){
        $this->breadcrumb->add('Home');

        $data['customers'] = $this->ignite_model->get_data('customers_tbl')->result();
        $data['allLink'] = $this->ignite_model->get_data('link_structure_tbl')->result();
        $data['content'] = 'pages/home';
        $this->load->view('layouts/template', $data);
    }

    // Checkout
    public function checkOut(){
        $sType = $this->input->post('saleType');
        $byCustomer = $this->input->post('customer');
        $customerId = $this->input->post('customerId');
        $payment = $this->input->post('paymentType');
        $depositAmt = $this->input->post('depositAmt');
        $referId = $this->input->post('referId');

        $deliveryState = 0;

        $arr = json_decode($this->input->post('order'));

        $max = $this->ignite_model->max_value('invoices_tbl', 'invoiceId');
        $serial = 'INV-'.$sType.'-'.date('myd').sprintf('%05d', $max+1);

        if($referId > 0){
            $invDetail = $this->ignite_model->get_limit_data('invoice_detail_tbl', 'invoiceId', $referId)->result();

            $this->db->where('invoiceId', $referId);
            $this->db->update('invoices_tbl', array('active' => false));

            foreach($invDetail as $detail){
                $item = $this->ignite_model->get_limit_data('items_price_tbl', 'codeNumber', $detail->itemCode)->row();
                $balance = $this->ignite_model->get_limit_data('stocks_balance_tbl', 'itemId', $item->itemId)->row();

                $bal = array(
                    'qty' => $balance->qty + $detail->itemQty
                );

                $this->db->where('itemId', $item->itemId);
                $this->db->update('stocks_balance_tbl', $bal);
            }
        }

        $inv = array(
            'invoiceSerial' => $serial,
            'saleType' => $sType,
            'paymentType' => $payment,
            'byCustomer' => $byCustomer,
            'customerId' => $customerId,
            'depositAmt' => $depositAmt,
            'discountAmt' => 0,
            'payment' => 0,
            'delivered' => $deliveryState,
            'referId' => $referId,
            'created_date' => date('Y-m-d H:i:s A'),
            'created_time' => date('H:i:s A'),
            'created_by' => $this->session->userdata('Id'),
            'active' => true
        );

        $this->db->insert('invoices_tbl', $inv);

        $totalAmt = 0;
        foreach($arr as $row){
            $totalAmt += $row->price * $row->qty;
            $detail = array(
                'invoiceId' => $max+1,
                'itemCode' =>$row->code,
                'itemName' => $row->name,
                'itemPrice' => $row->price,
                'itemQty' => $row->qty
            );

            $this->db->insert('invoice_detail_tbl', $detail);

            $item = $this->ignite_model->get_limit_data('items_price_tbl', 'codeNumber', $row->code)->row();
            $balance = $this->ignite_model->get_limit_data('stocks_balance_tbl', 'itemId', $item->itemId)->row();

            $bal = array(
                'qty' => $balance->qty - $row->qty
            );

            $this->db->where('itemId', $item->itemId);
            $this->db->update('stocks_balance_tbl', $bal);
        }

        if($payment == 'CRD'){
            $crd = array(
                'customerId' => $customerId,
                'invoiceId' => $max+1,
                'Amount' => $totalAmt,
                'depositAmt' => $depositAmt,
                'balance' => $totalAmt - $depositAmt,
                'created_date' => date('Y-m-d H:i:s A')
            );

            $this->db->insert('credits_tbl', $crd);
        }

        echo $max+1;
    }

    public function addDiscountInv(){
        $invId = $this->input->post('invId');
        $discountAmt = $this->input->post('discount');

        $arr = array('discountAmt' => $discountAmt);
        $this->db->where('invoiceId', $invId);
        $this->db->update('invoices_tbl', $arr);

        echo 'success';
    }

    public function checkOutPreview(){
        $invId = $this->uri->segment(2);

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Preview Invoice');

        $data['discounts'] = $this->ignite_model->get_limit_data('discounts_tbl', 'active', true)->result();
        $data['invoice'] = $this->ignite_model->get_limit_data('invoices_tbl','invoiceId', $invId)->row();
        $data['items'] = $this->ignite_model->get_limit_data('invoice_detail_tbl', 'invoiceId',$invId)->result();
        
        $data['content'] = 'pages/invoicePreview';
        $this->load->view('layouts/template', $data);
    }

    public function refundPayment() {
        $payment = $this->input->post('payment');
        $invId = $this->input->post('invId');

        $this->db->where('invoiceId', $invId);
        $this->db->update('invoices_tbl', ['payment' => $payment]);
        echo 'Success';
    }

    public function getCreditByCustomer(){
        $customerId = $this->input->get('customerId');

        $credit = $this->ignite_model->getCreditAmount($customerId);
        echo $credit;
    }

    public function saleItemSearch(){
        $key = $this->input->get('keyword');
        $type = $this->input->get('saleType');
        $items = [];
        if(!empty($key)){
            $items = $this->ignite_model->get_saleItemSearch($key, $type);
        }

        header('Content-Type: application/json');
        echo json_encode($items);
    }

    public function getItem(){
        $itemId = $this->input->post('itemId');

        $itemDetail = $this->ignite_model->itemDetail($itemId);

        // header('Content-Type: application/json');
        echo json_encode($itemDetail, JSON_UNESCAPED_UNICODE);
    }

    public function getItemByCode(){
        $code = $this->input->get('code');
        $item = [];
        $item = $this->ignite_model->get_itemByCode($code);

        header('Content-Type: application/json');
        echo json_encode($item);
    }

    public function switchLanguage(){
        $key = $this->uri->segment(2);

        $language = ($key != "") ? $key : "english";
        $this->session->set_userdata('site_lang', $language);
        redirect($_SERVER['HTTP_REFERER']);
    }

    /*
    * Credits
    */

    public function invoices(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Credits');
        $data['pType'] = $this->uri->segment(2);
        if($data['pType'] == '~') {
            $data['invoices'] = $this->ignite_model->getAllInvoices()->result();
        }
            elseif($data['pType'] == 'COD') {
                $data['invoices'] = $this->ignite_model->getCODinvoices()->result();
            }
                elseif($data['pType'] == 'MBK') {
                    $data['invoices'] = $this->ignite_model->getMBKinvoices()->result();
                }
                    else{
                        $data['invoices'] = $this->ignite_model->getInvoicesByType($data['pType'])->result();
                    }
        $data['content'] = 'pages/invoices';
        $this->load->view('layouts/template', $data);
    }

    public function updateDelivery(){
        $id = $this->uri->segment(2);

        $this->db->where('invoiceId', $id);
        $this->db->update('invoices_tbl',array('delivered' => true));
        redirect('invoices/~');
    }

    public function referInvoice() {
        $refId = $this->uri->segment(2);

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Invoices', 'invoices/~');
        $this->breadcrumb->add('Refer Invoice');

        $data['customers'] = $this->ignite_model->get_data('customers_tbl')->result();
        $data['invoice'] = $this->ignite_model->get_limit_data('invoices_tbl', 'invoiceId', $refId)->row();
        $data['content'] = 'pages/referInvoice';
        $this->load->view('layouts/template', $data);
    }

    public function getReferInvoice() {
        $invId = $this->input->get('invId');

        $data['invoice'] = $this->ignite_model->get_limit_data('invoices_tbl', 'invoiceId', $invId)->row();
        $data['detail'] = $this->ignite_model->get_referInvDetail($invId)->result();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function delInvoice() {
        $invId = $this->uri->segment(2);

        $invs = $this->ignite_model->get_invoice_items($invId)->result();
        // Restore balance
        foreach($invs as $inv){
            $bal = $this->ignite_model->get_limit_data('stocks_balance_tbl', 'itemId', $inv->itemId)->row();
            $upd = array(
                'qty' => $bal->qty + $inv->itemQty
            );

            $this->db->where('itemId', $inv->itemId);
            $this->db->update('stocks_balance_tbl', $upd);
        }

        $this->db->where('invoiceId', $invId);
        $this->db->update('invoices_tbl', array('active' => false));

        redirect('invoices/~');
    }

    public function updatePayment() {
        $invId = $this->uri->segment(2);

        $this->db->where('invoiceId', $invId);
        $this->db->update('invoices_tbl',array('pReceived' => true));
        redirect('invoices/~');
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
        $this->session->set_tempdata('success', 'Warehouse Successfully Created.', 5);
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
        $this->session->set_tempdata('success', 'Warehouse Successfully Updated.', 5);
        redirect('warehouse');
    }

    public function deleteWarehouse(){
        $warehouseId = $this->uri->segment(3);

        $this->db->where('warehouseId', $warehouseId);
        $this->db->delete('warehouse_tbl');
        $this->session->set_tempdata('success', 'Warehouse Successfully Deleted.', 5);
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
        $this->session->set_tempdata('success', 'Supplier Successfully Created.', 5);
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
        $this->session->set_tempdata('success', 'Supplier Successfully Updated.', 5);
        redirect('supplier');
    }

    public function deleteSupplier(){
        $supplierId = $this->uri->segment(3);

        $this->db->where('supplierId', $supplierId);
        $this->db->delete('supplier_tbl');
        $this->session->set_tempdata('success', 'Supplier Successfully Deleted.', 5);
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

    public function uploadImage(){
        $data = $_POST['image'];
     
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
     
        $data = base64_decode($data);
        $imageName = time().'.png';
        file_put_contents('assets/uploads/'.$imageName, $data);
     
        echo 'assets/uploads/'.$imageName;
    }

    public function getLetterCode(){
        $catId = $this->input->post('catId');

        $cat = $this->ignite_model->get_limit_data('categories_tbl', 'categoryId', $catId)->row();
        echo json_encode(array('status' => true, 'code' => $cat->letterCode));
    }

    public function addItem(){
        $referer = $this->input->post('referer');

        $category = $this->input->post('category');
        $name = $this->input->post('name');
        $code = $this->input->post('code');
        $currency = $this->input->post('currency');
        $remark = $this->input->post('remark');

        $upload = $this->ignite_model->upload_img('itemImage', 'assets/uploads', $code);
        if($upload['status']){
            $path = $upload['path'];
        }
            else{
                $path = '';
            }

        $arr = array(
            'itemName' => $name,
            'categoryId' => $category,
            'codeNumber' => $code,
            'currency' => $currency,
            'imgPath' => $path,
            'remark' => $remark,
            'referId' => 0,
            'active' => TRUE
        );

        $this->db->insert('items_price_tbl', $arr);
        $max = $this->ignite_model->max('items_price_tbl', 'itemId');
        $this->session->set_tempdata('success', 'New Item Successfully Created.', 5);
        redirect('define-price/'. $max['itemId'] .'/'. $referer);
        // if($referer === '~'){
        //     redirect('items-price/0');
        // }else{
        //     redirect($referer);
        // }
        
    }

    public function defineItemPrice(){
        $itemId = $this->uri->segment(2);
        $referer = $this->uri->segment(3);

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Items & price', 'items-price/0');
        $this->breadcrumb->add('Define Price');

        $data['item'] = $this->ignite_model->get_limit_data('items_price_tbl', 'itemId', $itemId)->row();
        $data['content'] = 'pages/definePrice';
        $this->load->view('layouts/template', $data);
    }

    public function addPrice(){
        $itemId = $this->uri->segment(3);
        $arr = json_decode($this->input->raw_input_stream, true);

        // Check If price has define
        $checkPrice = $this->ignite_model->get_limit_data('count_type_tbl', 'related_item_id', $itemId)->result();

        // Delete the prev prices
        if(count($checkPrice) > 0){
            $this->db->where('related_item_id', $itemId);
            $this->db->delete('count_type_tbl');
        }

        // Add new price
        foreach($arr as $row){
            $price_arr = array(
                'related_item_id' => $itemId,
                'type' => $row['type'],
                'count_type' => 'Pcs',
                'qty' => 1,
                'price' => $row['price'],
                'remark' => $row['remark'],
                'created_at' => date('Y-m-d H:i:s A')
            );

            $this->db->insert('count_type_tbl', $price_arr);
        }

        $result = $this->ignite_model->get_data('count_type_tbl')->result();
        echo count($result);
    }

    public function getDefinedPrice(){
        $itemId = $this->uri->segment(2);

        $result = $this->ignite_model->get_limit_data('count_type_tbl', 'related_item_id', $itemId)->result();
        echo json_encode($result);
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
        $name = $this->input->post('name');
        $code = $this->input->post('code');
        $currency = $this->input->post('currency');
        $remark = $this->input->post('remark');

        if ($_FILES['itemImage']['size'] > 0) {
            $upload = $this->ignite_model->upload_img('itemImage', 'assets/uploads', $code);
            if($upload['status']){
                $path = $upload['path'];
            }
        }else{
            $item = $this->ignite_model->get_limit_data('items_price_tbl', 'itemId', $itemId)->row();
            $path = $item->imgPath;
        }

        echo $path;

        $arr = array(
            'itemName' => $name,
            'categoryId' => $category,
            'codeNumber' => $code,
            'currency' => $currency,
            'imgPath' => $path,
            'remark' => $remark,
            'referId' => 0,
            'active' => TRUE
        );

        $this->db->where('itemId', $itemId);
        $this->db->update('items_price_tbl', $arr);
        $this->session->set_tempdata('success', 'Item Successfully Updated.', 3);
        redirect('items-price/0');
    }

    public function deleteItem(){
        $itemId = $this->uri->segment(3);

        $this->db->where('itemId', $itemId);
        $this->db->delete('items_price_tbl');
        $this->session->set_tempdata('success', 'Item Successfully Deleted.', 3);

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

        $this->session->set_tempdata('success', 'Item Price Successfully Updated.', 5);
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
        $this->session->set_tempdata('success', 'Category Successfully Created.', 5);
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

        $this->session->set_tempdata('success', 'Category Successfully Updated.', 5);

        redirect('categories');
    }

    public function deleteCategory(){
        $catId = $this->uri->segment(3);

        $this->db->where('categoryId', $catId);
        $this->db->delete('categories_tbl');

        $this->session->set_tempdata('success', 'Category Successfully Deleted.', 5);

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

        $this->session->set_tempdata('success', 'New Brand Successfully Created.', 5);

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

        $this->session->set_tempdata('success', 'Brand Successfully Updated.', 5);

        redirect('brands');
    }

    public function deleteBrand(){
        $brandId = $this->uri->segment(3);

        $this->db->where('brandId', $brandId);
        $this->db->delete('brands_tbl');

        $this->session->set_tempdata('success', 'Brand Successfully Deleted.', 5);
        redirect('brands');
    }

    //Purchase Section
    public function purchase(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Stocks In');

        $data['vouchers'] = $this->ignite_model->get_data_order('vouchers_tbl', 'vDate', 'DESC')->result();
        $data['content'] = 'pages/purchase';
        $this->load->view('layouts/template', $data);
    }

    public function purchaseDetail(){
        $vocId = $this->uri->segment(2);

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Stocks In', 'purchase');
        $this->breadcrumb->add('Detail');

        $data['purchaseItem'] = $this->ignite_model->get_purchaseItem($vocId);
        $data['content'] = 'pages/purchaseDetail';
        $this->load->view('layouts/template', $data);
    }

    public function newPurchase(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Stocks In', 'purchase/0');
        $this->breadcrumb->add('Create');

        $data['items'] = $this->ignite_model->get_allItems();
        $data['warehouses'] = $this->ignite_model->get_data('warehouse_tbl')->result_array();
        $data['vouchers'] = $this->ignite_model->get_data_order('vouchers_tbl','created_at', 'DESC')->result();
        $data['extCharges'] = $this->ignite_model->get_data_order('extra_charges_tbl', 'created_at', 'DESC')->result();
        $data['suppliers'] = $this->ignite_model->get_data('supplier_tbl')->result();

        $data['content'] = 'pages/newPurchase';
        $this->load->view('layouts/template', $data);
    }

    public function getCountType(){
        $itemId = $this->input->get('itemId');

        $countTypes = $this->ignite_model->getCountType($itemId)->result();

        echo '<option value="">Select CountType</option>';
        foreach($countTypes as $countType){
            echo '<option value="'.$countType->count_type_id.'"> ( '.$countType->qty.' Piece / s ) '.$countType->count_type.'</option>';
        }
    }

    public function addPurchase(){
        $itemId = $this->input->post('item');
        $warehouse = $this->input->post('warehouse');
        $voucher = $this->input->post('voucher');
        $date = $this->input->post('pDate');
        $qty = $this->input->post('qty');
        $remark = $this->input->post('remark');

        $arr = array(
            'itemId' => $itemId,
            'count_type_id' => 0,
            'warehouseId' => $warehouse,
            'voucherId' => $voucher,
            'purchaseDate' => $date,
            'quantity' => $qty,
            'remark' => $remark,
            'active' => false
        );

        $this->db->insert('purchase_tbl', $arr);

        $this->session->set_tempdata('success', 'New Purchase Successfully Created.', 3);

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function setAllPurchase() {
        $vrId = $this->uri->segment(2);

        $purchaseItems = $this->ignite_model->get_limit_datas('purchase_tbl', ['voucherId' => $vrId, 'active' => false])->result();

        foreach($purchaseItems as $item) {

            // Check balance exist
            $balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $item->itemId, 'warehouseId' => $item->warehouseId])->row_array();
            
            if(!empty($balance)){
                $arr = array(
                    'qty' => $item->quantity + $balance['qty'],
                );

                $this->db->where('itemId', $item->itemId);
                $this->db->where('warehouseId', $item->warehouseId);
                $this->db->update('stocks_balance_tbl', $arr);
            }
            else{
                $arr = array(
                    'itemId' => $item->itemId,
                    'qty' => $item->quantity,
                    'warehouseId' => $item->warehouseId,
                );

                $this->db->insert('stocks_balance_tbl', $arr);
            }

        }

        $this->db->where('voucherId', $vrId);
        $this->db->update('purchase_tbl', ['active' => true]);

        $this->session->set_tempdata('success', 'Purchase Successfully Arrived and set balance data.', 3);
        redirect('purchase');
    }

    public function setPurchase() {
        $pId = $this->uri->segment(2);

        $purchaseItem = $this->ignite_model->get_limit_data('purchase_tbl', 'purchaseId', $pId)->row();

        // Check balance exist
        $balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $purchaseItem->itemId, 'warehouseId' => $purchaseItem->warehouseId])->row_array();
        
        if(!empty($balance)){
            $arr = array(
                'qty' => $purchaseItem->quantity + $balance['qty'],
            );

            $this->db->where('itemId', $purchaseItem->itemId);
            $this->db->where('warehouseId', $purchaseItem->warehouseId);
            $this->db->update('stocks_balance_tbl', $arr);
        }
        else{
            $arr = array(
                'itemId' => $purchaseItem->itemId,
                'qty' => $purchaseItem->quantity,
                'warehouseId' => $purchaseItem->warehouseId,
            );

            $this->db->insert('stocks_balance_tbl', $arr);
        }

        $this->db->where('purchaseId', $pId);
        $this->db->update('purchase_tbl', ['active' => true]);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function editPurchase(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Stocks In', 'purchase/0');
        $this->breadcrumb->add('Modify');

        $purchaseId = $this->uri->segment(2);

        $data['purchase'] = $this->ignite_model->get_limit_data('purchase_tbl', 'purchaseId', $purchaseId)->row_array();

        $data['items'] = $this->ignite_model->get_allItems();
        $data['warehouses'] = $this->ignite_model->get_data('warehouse_tbl')->result_array();
        $data['vouchers'] = $this->ignite_model->get_data_order('vouchers_tbl','created_at', 'DESC')->result();
        $data['vouchers'] = $this->ignite_model->get_data_order('vouchers_tbl','created_at', 'DESC')->result();
        $data['suppliers'] = $this->ignite_model->get_data('supplier_tbl')->result();

        $data['content'] = 'pages/editPurchase';
        $this->load->view('layouts/template', $data);
    }

    public function updatePurchase(){
        $purchaseId = $this->uri->segment(3);
        $lastPurchase = $this->ignite_model->get_limit_data('purchase_tbl', 'purchaseId', $purchaseId)->row();

        // Restore Balance
        $balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $lastPurchase->itemId, 'warehouseId' => $lastPurchase->warehouseId])->row();

        $balArr = array(
            'qty' => ($balance->qty - $lastPurchase->quantity)
        );

        $this->db->where('balanceId', $balance->balanceId);
        $this->db->update('stocks_balance_tbl', $balArr);
        // End of Restore Balance

        $itemId = $this->input->post('item');
        $warehouse = $this->input->post('warehouse');
        $voucher = $this->input->post('voucher');
        $date = $this->input->post('pDate');
        $qty = $this->input->post('qty');
        $remark = $this->input->post('remark');

        $arr = array(
            'itemId' => $itemId,
            'warehouseId' => $warehouse,
            'voucherId' => $voucher,
            'purchaseDate' => $date,
            'quantity' => $qty,
            'remark' => $remark
        );

        $this->db->where('purchaseId', $purchaseId);
        $this->db->update('purchase_tbl', $arr);

        $this->session->set_tempdata('success', 'Purchase Successfully Updated.', 3);

        redirect('purchase');
    }

    public function delPurchase(){
        $purchaseId = $this->uri->segment(3);

        $this->db->where('purchaseId', $purchaseId);
        $this->db->delete('purchase_tbl');
        redirect('purchase');
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

        $this->session->set_tempdata('success', 'Successfully Transferred.', 5);

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

        $this->session->set_tempdata('success', 'New User Successfully Created.', 5);

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
        $count = count($links);

        $accept = array();
        $modify = array();

        foreach($links as $link){
            if($this->input->post('permission'.$link->linkId)){                
               $accept[$link->linkId] = true;
            }
                else{
                    $accept[$link->linkId] = false;
                }

            if($this->input->post('modify'.$link->linkId)){
                $modify[$link->linkId] = true;
            }
                else{
                    $modify[$link->linkId] = false;
                }
        }

        
        $arr = array(
            'accId' => $accId,
            'link' => json_encode($accept),
            'modify' => json_encode($modify),
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s')
        );

        $this->db->insert('permission_tbl', $arr);

        $this->session->set_tempdata('success', 'Permission Successfully Defined.', 5);
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

        foreach($links as $link){
            if($this->input->post('permission'.$link->linkId)){                
               $accept[$link->linkId] = true;
            }
                else{
                    $accept[$link->linkId] = false;
                }

            if($this->input->post('modify'.$link->linkId)){
                $modify[$link->linkId] = true;
            }
                else{
                    $modify[$link->linkId] = false;
                }
        }
        
        $arr = array(
            'link' => json_encode($accept),
            'modify' => json_encode($modify),
            'updated_at' => date('Y-m-d h:i:s')
        );

        $this->db->where('accId', $accId);
        $this->db->update('permission_tbl', $arr);

        $this->session->set_tempdata('success', 'Permission Successfully Updated.', 3);

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

        $this->session->set_tempdata('success', 'Password Successfully Updated.', 3);
        redirect('users');
    }

    public function disableUser(){
        $accId = $this->uri->segment(2);

        $this->db->update('accounts_tbl', ['accountState' => false], ['accId' => $accId]);

        $this->session->set_tempdata('success', 'User has been disabled.', 3);
        redirect('users');
    }

    public function enableUser(){
        $accId = $this->uri->segment(2);

        $this->db->update('accounts_tbl', ['accountState' => true], ['accId' => $accId]);

        $this->session->set_tempdata('success', 'User has been enabled.', 3);
        redirect('users');
    }

    /*
    * Customers
    */
    public function customer(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Customers');

        $data['customers'] = $this->ignite_model->get_data('customers_tbl')->result();

        $data['content'] = 'pages/customers';
        $this->load->view('layouts/template', $data);
    }

    public function newCustomer(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Customers', 'customers');
        $this->breadcrumb->add('New Customer');

        $data['content'] = 'pages/newCustomer';
        $this->load->view('layouts/template', $data);
    }

    public function addCustomer(){
        $input_arr = $this->input->post();

        $arr = array(
            'customerName' => $input_arr['name'],
            'email' => $input_arr['email'],
            'phone1' => $input_arr['phone1'],
            'phone2' => $input_arr['phone2'],
            'address1' => $input_arr['address1'],
            'address2' => $input_arr['address2'],
            'remark' => $input_arr['remark']
        );

        $this->db->insert('customers_tbl', $arr);

        $this->session->set_tempdata('success', 'Customer successfully created.', 3);
        redirect('customers');
    }

    public function editCustomer(){
        $customerId = $this->uri->segment(2);

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Edit Customer');

        $data['customer'] = $this->ignite_model->get_limit_data('customers_tbl', 'customerId', $customerId)->row();
        $data['content'] = 'pages/editCustomer';
        $this->load->view('layouts/template', $data);
    }

    public function updateCustomer(){
        $customerId = $this->uri->segment(3);

        $input_arr = $this->input->post();

        $arr = array(
            'customerName' => $input_arr['name'],
            'email' => $input_arr['email'],
            'phone1' => $input_arr['phone1'],
            'phone2' => $input_arr['phone2'],
            'address1' => $input_arr['address1'],
            'address2' => $input_arr['address2'],
            'remark' => $input_arr['remark']
        );

        $this->db->where('customerId', $customerId);
        $this->db->update('customers_tbl', $arr);

        $this->session->set_tempdata('success', 'Customer successfully updated.', 3);
        redirect('customers');
    }

    public function deleteCustomer(){
        $customerId = $this->uri->segment(3);

        $this->db->where('customerId', $customerId);
        $this->db->delete('customers_tbl');

        redirect('customers');
    }

    public function payCredit() {
        $payAmt = $this->input->post('payAmt');
        $remark = $this->input->post('remark');
        $customerId = $this->input->post('customerId');

        $arr = array(
            'customerId' => $customerId,
            'payAmt' => $payAmt,
            'remark' => $remark,
            'created_at' => date('Y-m-d h:i:s A')
        );

        $this->db->insert('payments_tbl', $arr);
        echo 'Success';
    }

    public function detailCustomer() {
        $customerId = $this->uri->segment(2);

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Customer', 'customers');
        $this->breadcrumb->add('Detail');

        $data['customer'] = $this->ignite_model->get_limit_data('customers_tbl', 'customerId', $customerId)->row();
        $data['invoices'] = $this->ignite_model->get_invoicesByCustomer($customerId);

        $data['content'] = 'pages/detailCustomer';
        $this->load->view('layouts/template', $data);
    }

    /*
    * Reports
    */

    public function reports(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Reports');

        $data['tab'] = $this->uri->segment(2);
        if(empty($data['tab'])){
            $data['tab'] = 'daily';
        }

        $data['dDate'] = $this->input->post('dailyDate');
        if(empty($data['dDate'])){
            $data['dDate'] = date('Y-m-d');
        }

        $data['mMonth'] = $this->input->post('mMonth');
        if(empty($data['mMonth'])){
            $data['mMonth'] = date('m');
        }

        $data['mYear'] = $this->input->post('mYear');
        if(empty($data['mYear'])){
            $data['mYear'] = date('Y');
        }

        $data['yYear'] = $this->input->post('yYear');
        if(empty($data['yYear'])){
            $data['yYear'] = date('Y');
        }

        $data['dailyData'] = $this->ignite_model->get_dailyReportsData($data['dDate'])->result();

        $data['content'] = 'pages/reports';
        $this->load->view('layouts/template', $data);
    }

    public function getInvoice(){
        $invId = $this->input->get('invId');

        $invData = $this->ignite_model->get_invoiceDetail($invId)->result();
        echo json_encode($invData);
    }

    public function getDailyChart(){
        $month = $this->input->post('month');
        $year = date('Y');
        $days = cal_days_in_month(CAL_GREGORIAN,$month, $year);

        $chartData['days'] = [];
        $chartData['datas'] = [];
        $chartData['gross'] = [];
        $chartData['net'] = [];

        for($i = 1; $i <= $days; $i++){
            $amtTotal = $this->ignite_model->get_M_Total($i, $month, $year);
            $invoices = $this->ignite_model->get_daily_invoices($i, $month, $year);

            $net = 0;
            foreach($invoices as $inv) {
                $dNetProfit = $this->ignite_model->get_dNetProfit($inv->invoiceId, $inv->saleType);
                $net += $dNetProfit - $inv->discountAmt;
            }
            
            array_push($chartData['days'], date('M', strtotime($year.'-'.$month.'-1')).' '.$i);
            array_push($chartData['datas'], $amtTotal['total']);
            array_push($chartData['gross'], $amtTotal['profit']);
            array_push($chartData['net'], $net);
        }

        echo json_encode($chartData);
    }

    public function getMonthlyChart(){
        $year = date('Y');

        $chartData['months'] = [];
        $chartData['datas'] = [];
        $chartData['gross'] = [];
        $chartData['net'] = [];

        for($i = 1; $i <= 12; $i++){

            $y_data = $this->ignite_model->get_Y_Total($i, $year);
            $invoices = $this->ignite_model->get_monthly_invoices($i, $year);

            $net = 0;
            foreach($invoices as $inv) {
                $dNetProfit = $this->ignite_model->get_dNetProfit($inv->invoiceId, $inv->saleType);
                $net += $dNetProfit - $inv->discountAmt;
            }

            array_push($chartData['months'], $this->ignite_model->getShortMonth($i));
            array_push($chartData['datas'], $y_data['total']);
            array_push($chartData['gross'], ($y_data['total'] - $y_data['gpTotal']));
            array_push($chartData['net'], $net);
        }

        echo json_encode($chartData);
    }

    public function getYearlyChart(){
        $year = date('Y');

        $chartData['years'] = [];
        $chartData['data'] = [];
        $chartData['gross'] = [];
        $chartData['net'] = [];

        for($i=($year-10); $i<= $year; $i++){
            $y_data = $this->ignite_model->get_yChart($i);
            $invoices = $this->ignite_model->get_yearly_invoices($i);
            $net = 0;
            foreach($invoices as $inv) {
                $dNetProfit = $this->ignite_model->get_dNetProfit($inv->invoiceId, $inv->saleType);
                $net += $dNetProfit - $inv->discountAmt;
            }

            array_push($chartData['years'], $i);
            array_push($chartData['data'], $y_data['total']);
            array_push($chartData['gross'], ($y_data['total'] - $y_data['gpTotal']));
            array_push($chartData['net'], $net);
        }

        echo json_encode($chartData);
    }

    public function printReceipt(){
        $invId = $this->uri->segment(2);
        $invoice = $this->ignite_model->get_limit_data('invoices_tbl', 'invoiceId', $invId)->row();
        $items = $this->ignite_model->get_limit_data('invoice_detail_tbl', 'invoiceId', $invId)->result();
        $this->load->library('escpos');

        $this->escpos->print_receipt($invoice, $items);

        // $this->session->set_tempdata('success', 'Printer is Printing', 5);
        redirect('home');
    }

    /*
    * Settings Section
    */
    public function setting(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Settings');

        $data['content'] = 'pages/settings';
        $this->load->view('layouts/template', $data);
    }

    public function discounts() {
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Setting', 'setting');
        $this->breadcrumb->add('Discounts');

        $data['content'] = 'pages/discounts';
        $data['discounts'] = $this->ignite_model->get_data('discounts_tbl')->result();
        $this->load->view('layouts/template', $data);
    }

    public function newDiscount() {
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Discounts', 'discounts');
        $this->breadcrumb->add('Create');

        $data['content'] = 'pages/newDiscount';
        $this->load->view('layouts/template', $data);
    }

    public function addDiscount(){
        $title = $this->input->post('title');
        $type = $this->input->post('discountType');
        if($type == 'DF'){
            $rate = 0;
        }else{
            $rate = $this->input->post('discountRate');
        }
        $remark = $this->input->post('discountRemark');
        if($this->input->post('active')){
            $active = true;
        }else{
            $active = false;
        }

        $insert = array(
            'discountTitle' => $title,
            'discountType' => $type,
            'discountRate' => $rate,
            'remark' => $remark,
            'active' => $active,
            'created_at' => date('Y-m-d H:i:s A')
        );

        $this->db->insert('discounts_tbl', $insert);
        $this->session->set_tempdata('success', 'Discount successfully Created.', 5);
        redirect('discounts');
    }

    public function editDiscount(){
        $discountId = $this->uri->segment(2);

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Discounts', 'discounts');
        $this->breadcrumb->add('Modify');

        $data['discount'] = $this->ignite_model->get_limit_data('discounts_tbl', 'discountId', $discountId)->row();
        $data['content'] = 'pages/editDiscount';
        $this->load->view('layouts/template', $data);

    }

    public function updateDiscount() {
        $discountId = $this->uri->segment(3);

        $title = $this->input->post('title');
        $type = $this->input->post('discountType');
        if($type == 'DF'){
            $rate = 0;
        }else{
            $rate = $this->input->post('discountRate');
        }
        $remark = $this->input->post('discountRemark');
        if($this->input->post('active')){
            $active = true;
        }else{
            $active = false;
        }

        $update = array(
            'discountTitle' => $title,
            'discountType' => $type,
            'discountRate' => $rate,
            'remark' => $remark,
            'active' => $active,
            'created_at' => date('Y-m-d H:i:s A')
        );

        $this->db->where('discountId', $discountId);
        $this->db->update('discounts_tbl', $update);
        $this->session->set_tempdata('success', 'Discount successfully Upadated.', 5);
        redirect('discounts');
    }

    public function deleteDiscount(){
        $discountId = $this->uri->segment(2);

        $this->db->where('discountId', $discountId);
        $this->db->delete('discounts_tbl');

        $this->session->set_tempdata('success', 'Discount successfully Deleted.');
        redirect('discounts','refresh');
    }

    public function getDiscount(){
        $id = $this->input->post('disId');

        $discount = $this->ignite_model->get_limit_data('discounts_tbl', 'discountId', $id)->row();
        echo json_encode($discount);
    }

    public function extraCharges() {
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Setting', 'setting');
        $this->breadcrumb->add('Extra Charges');

        $data['charges'] = $this->ignite_model->get_data_order('extra_charges_tbl', 'created_at', 'DESC')->result();
        $data['content'] = 'pages/extraCharges';
        $this->load->view('layouts/template', $data);
    }

    public function newCharges() {
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Setting', 'setting');
        $this->breadcrumb->add('Extra Charges', 'extra-charges');
        $this->breadcrumb->add('Create');

        $data['content'] = 'pages/newCharges';
        $this->load->view('layouts/template', $data);
    }

    public function addCharges() {
        $title = $this->input->post('title');
        $amount = $this->input->post('chargeAmt');
        $remark = $this->input->post('chrgeRemark');

        if($this->input->post('active')){
            $active = true;
        }
            else{
                $active = false;
            }

        $arr = array(
            'chargeTitle' => $title,
            'chargeAmount' => $amount,
            'remark' => $remark,
            'active' => $active,
            'created_at' => date('Y-m-d H:i:s A')
        );

        $this->db->insert('extra_charges_tbl', $arr);
        $this->session->set_tempdata('success', 'Extra Charges Successfully Created !', 3); 
        redirect('extra-charges');
    }

    public function editCharges() {
        $id = $this->uri->segment(2);

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Setting', 'setting');
        $this->breadcrumb->add('Extra Charges', 'extra-charges');
        $this->breadcrumb->add('Modify');

        $data['chargeData'] = $this->ignite_model->get_limit_data('extra_charges_tbl', 'chargeId', $id)->row();
        $data['content'] = 'pages/editCharges';
        $this->load->view('layouts/template', $data);
    }

    public function updateCharges() {
        $id = $this->uri->segment(3);

        $title = $this->input->post('title');
        $amount = $this->input->post('chargeAmt');
        $remark = $this->input->post('chrgeRemark');

        if($this->input->post('active')){
            $active = true;
        }
            else{
                $active = false;
            }

        $arr = array(
            'chargeTitle' => $title,
            'chargeAmount' => $amount,
            'remark' => $remark,
            'active' => $active
        );

        $this->db->where('chargeId', $id);
        $this->db->update('extra_charges_tbl', $arr);
        $this->session->set_tempdata('success', 'Extra Charges Successfully Updated !', 3);
        redirect('extra-charges');
    }

    public function deleteCharges() {
        $id = $this->uri->segment(2);

        $this->db->where('chargeId', $id);
        $this->db->delete('extra_charges_tbl');
        $this->session->set_tempdata('success', 'Extra Charges Successfully Deleted !', 3);
        redirect('extra-charges');
    }

    /*
    * Services Section
    */
    public function services(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Services');

        $data['content'] = 'pages/services';
        $this->load->view('layouts/template', $data);
    }

    public function newService(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Services', 'services');
        $this->breadcrumb->add('Create');

        $data['categories'] = $this->ignite_model->get_data_order('categories_tbl', 'categoryName', 'asc')->result_array();
        $data['content'] = 'pages/newService';
        $this->load->view('layouts/template', $data);
    }

    /*
    * Vouchers Section
    */
    public function vouchers(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Vouchers');

        $data['vouchers'] = $this->ignite_model->get_data_order('vouchers_tbl', 'vDate', 'DESC')->result();
        $data['content'] = 'pages/vouchers';
        $this->load->view('layouts/template', $data);
    }

    public function newVoucher(){
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Vouchers', 'vouchers');
        $this->breadcrumb->add('Create');

        $data['extCharges'] = $this->ignite_model->get_data_order('extra_charges_tbl', 'created_at', 'DESC')->result();
        $data['suppliers'] = $this->ignite_model->get_data('supplier_tbl')->result();
        $data['content'] = 'pages/newVoucher';
        $this->load->view('layouts/template', $data);
    }

    public function addVoucher(){
        $referer = $this->input->post('referer');
        $vDate = $this->input->post('vDate');
        $vSerial = $this->input->post('vSerial');
        $chargeId = $this->input->post('extCharge');
        $supplier = $this->input->post('supplier');
        $remark = $this->input->post('remark');

        if(!empty($chargeId)){
            $extCharge = $this->ignite_model->get_limit_data('extra_charges_tbl','chargeId', $chargeId)->row();
            $amount = $extCharge->chargeAmount;
        }
            else{
                $chargeId = 0;
                $amount = 0;
            }

        $insert = array(
            'vDate' => $vDate,
            'vSerial' => $vSerial,
            'supplier' => $supplier,
            'extCharge' => $chargeId,
            'chargeAmt' => $amount,
            'remark' => $remark,
            'created_at' => date('Y-m-d H:i:s A')
        );

        $this->db->insert('vouchers_tbl', $insert);
        $this->session->set_tempdata('success', 'New Vouchers created Successfully !', 3);
        redirect($referer);
    }

    public function editVoucher(){
        $vId = $this->uri->segment(2);

        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Vouchers', 'vouchers');
        $this->breadcrumb->add('Modify');

        $data['extCharges'] = $this->ignite_model->get_data_order('extra_charges_tbl', 'created_at', 'DESC')->result();
        $data['suppliers'] = $this->ignite_model->get_data('supplier_tbl')->result();
        $data['voucher'] = $this->ignite_model->get_limit_data('vouchers_tbl', 'voucherId', $vId)->row();
        $data['content'] = 'pages/editVoucher';
        $this->load->view('layouts/template', $data);
    }

    public function updateVoucher(){
        $vId = $this->uri->segment(2);

        $vDate = $this->input->post('vDate');
        $vSerial = $this->input->post('vSerial');
        $supplier = $this->input->post('supplier');
        $chargeId = $this->input->post('extCharge');
        $remark = $this->input->post('remark');

        $extCharge = $this->ignite_model->get_limit_data('extra_charges_tbl','chargeId', $chargeId)->row();

        $update = array(
            'vDate' => $vDate,
            'vSerial' => $vSerial,
            'supplier' => $supplier,
            'extCharge' => $extCharge->chargeId,
            'chargeAmt' => $extCharge->chargeAmount,
            'remark' => $remark,
        );

        $this->db->where('voucherId', $vId);
        $this->db->update('vouchers_tbl', $update);
        $this->session->set_tempdata('success', 'Vouchers updated Successfully !', 3);
        redirect('vouchers');
    }

    public function deleteVoucher(){
        $vId = $this->uri->segment(2);

        $this->db->where('voucherId', $vId);
        $this->db->delete('vouchers_tbl');
        $this->session->set_tempdata('success', 'Voucher Deleted Successfully', 3);

        redirect('vouchers');
    }

    /*
    * Damage Section
    */
    public function damages() {
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Damages');

        $data['damages'] = $this->ignite_model->get_data_order('damages_tbl', 'created_at', 'DESC')->result();
        $data['content'] = 'pages/damages';
        $this->load->view('layouts/template', $data);
    }

    public function newDamage() {
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Damages', 'damages');
        $this->breadcrumb->add('Create');

        $data['items'] = $this->ignite_model->get_itemsForDamage();
        $data['content'] = 'pages/newDamage';
        $this->load->view('layouts/template', $data);
    }

    public function addDamages(){
        $itemId = $this->input->post('item');
        $qty = $this->input->post('qty');
        $remark = $this->input->post('remark');

        $arr = array(
            'related_item_id' => $itemId,
            'qty' => $this->input->post('qty'),
            'remark' => $this->input->post('remark'),
            'created_at' => date('Y-m-d H:i:s A')
        );

        $this->db->insert('damages_tbl', $arr);

        // extract for stock_balance_tbl
        $balance = $this->ignite_model->get_limit_data('stocks_balance_tbl', 'itemId', $itemId)->row();
        $upd = array(
            'qty' => ($balance->qty - $qty)
        );
        $this->db->where('itemId', $itemId);
        $this->db->update('stocks_balance_tbl', $upd);

        $this->session->set_tempdata('success', 'Damage Items Created Successfully !', 3);
        redirect('damages');
    }

    public function editDamage() {
        $this->breadcrumb->add('Home', 'home');
        $this->breadcrumb->add('Damages', 'damages');
        $this->breadcrumb->add('Create');
        $id = $this->uri->segment(2);

        $data['damage'] = $this->ignite_model->get_limit_data('damages_tbl', 'damageId', $id)->row();
        $data['items'] = $this->ignite_model->get_itemsForDamage();
        $data['content'] = 'pages/editDamage';
        $this->load->view('layouts/template', $data);
    }

    public function updateDamage() {
        $id = $this->uri->segment(3);

        $damage = $this->ignite_model->get_limit_data('damages_tbl', 'damageId', $id)->row();

        $itemId = $this->input->post('item');
        $qty = $this->input->post('qty');
        $remark = $this->input->post('remark');

        if($itemId == $damage->related_item_id){

            $arr = array(
                'qty' => $qty,
                'remark' => $this->input->post('remark')
            );

            $balance = $this->ignite_model->get_limit_data('stocks_balance_tbl', 'itemId', $itemId)->row();
            $bal = array(
                'qty' => ($balance->qty + $damage->qty) - $qty
            );

            $this->db->where('itemId', $itemId);
            $this->db->update('stocks_balance_tbl', $bal);

            $this->db->where('damageId', $id);
            $this->db->update('damages_tbl', $arr);
        }
            else{
                $arr = array(
                    'related_item_id' => $itemId,
                    'qty' => $qty,
                    'remark' => $remark
                );

                $this->db->where('damageId', $id);
                $this->db->update('damages_tbl', $arr);

                // Restore Balance
                $balance = $this->ignite_model->get_limit_data('stocks_balance_tbl', 'itemId', $damage->related_item_id)->row();
                $restoreBal = array(
                    'qty' => $balance->qty + $damage->qty
                );

                $this->db->where('itemId', $damage->related_item_id);
                $this->db->update('stocks_balance_tbl', $restoreBal);
                // Update New Balance
                $newBalance = $this->ignite_model->get_limit_data('stocks_balance_tbl', 'itemId', $itemId)->row();
                $newBal = array(
                    'qty' => $newBalance->qty - $qty
                );

                $this->db->where('itemId', $itemId);
                $this->db->update('stocks_balance_tbl', $newBal);
            }

        $this->session->set_tempdata('success', 'Damage Item Updated Successfully !', 3);
        redirect('damages');
    }

    /*
    * Logout Section
    */
    public function signOut(){
        session_destroy();

        redirect(base_url());
    }

    public function exportPdf(){
        $data['name'] = $this->uri->segment(2);

        $data['content'] = 'htmlPdfs/'.str_replace('-','_',$data['name']);
        $data['items'] = $this->ignite_model->get_stock_items()->result();
        $data['warehouse'] = $this->ignite_model->get_limit_data('warehouse_tbl', 'activeState', true)->result();

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
        $html = $this->load->view('layouts/pdf_template',$data,true);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function exportExcel() {
       $this->load->library('excel');
       $balance = $this->ignite_model->get_stock_items()->result();
       $warehouse = $this->ignite_model->get_limit_data('warehouse_tbl', 'activeState', true)->result();
       $this->excel->create($balance, $warehouse);
    }

    /*
    * Testing and Inserting Data
    */

    public function test_print(){
        $this->load->library('escpos');
        $this->escpos->test_print();
    }


    public function insertBalance(){
        $purchase = $this->ignite_model->get_data('purchase_tbl')->result();
        foreach($purchase as $ps){

            $balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $ps->itemId, 'warehouseId' => $ps->warehouseId])->row_array();
            
            if(!empty($balance)){
                $arr = array(
                    'qty' => $ps->quantity + $balance['qty'],
                );

                $this->db->where('itemId', $ps->itemId);
                $this->db->where('warehouseId', $ps->warehouseId);
                $this->db->update('stocks_balance_tbl', $arr);
            }
            else{
                $arr = array(
                    'itemId' => $ps->itemId,
                    'qty' => $ps->quantity,
                    'warehouseId' => $ps->warehouseId,
                );

                $this->db->insert('stocks_balance_tbl', $arr);
            }
        }
    }
}

