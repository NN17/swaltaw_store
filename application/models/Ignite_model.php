<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ignite_model extends CI_Model {
    // **************** Login State Function ****************

	function loginState($username,$password)
	{
		$row = $this->db->query("SELECT * FROM accounts_tbl WHERE username = '$username'")->row_array();
		
        // Check Username or Email
        if (!empty($row['username'])){
            $hash = $row['secret'];
        //     // Check Password
            if ($this->auth->verify_password($password, $hash) == true){
        //         // Check Account State
                if($row['accountState'] == true){
                    $this->session->set_userdata('loginState',true);
                    $this->session->set_userdata('username',$username);
                    $this->session->set_userdata('Id',$row['accId']);
                    $this->session->set_userdata('roleId', $row['role']);
                    $this->session->set_userdata('site_lang', 'english');

                    $loginState = array(
                        'status' => true,
                        'msg' => $row['accId']
                        );
        //             break;
                }
        //             // Account State false
                    else{
                        $loginState = array(
                            'status' => false,
                            'errCode' => 1003
                        );
                    }
            }
        //         // If Password False
                else {
                    $loginState = array(
                        'status' => false,
                        'errCode' => 1002
                        );
                }
        }
        //     // If Username False
            else {
                $loginState = array(
                        'status' => false,
                        'errCode' => 1001
                        );
            }
        return $loginState;

    }
    
    function get_data($table){
        $query = $this->db->get($table);
        return $query;
    }

    function get_data_order($table, $field, $order){
        $this->db->order_by($field, $order);
        $data = $this->db->get($table);
        return $data;
    }

    function get_limit_data($table, $field, $value){
        $this->db->where($field, $value);
        $query = $this->db->get($table);
        return $query;
    }

    function get_limit_data_order($table, $field, $value, $order, $method){
        $this->db->where($field, $value);
        $this->db->order_by($order, $method);
        $query = $this->db->get($table);
        return $query;
    }

    function get_limit_datas($table, $parms){
        foreach($parms as $key => $value){
            $this->db->where($key, $value);
        }
        $query = $this->db->get($table);
        return $query;
    }

    function get_link_name($id){
        $this->db->where('linkId', $id);
        $query = $this->db->get('link_structure_tbl')->row_array();
        return $query['name'];
    }

    function get_username($id) {
        $this->db->where('accId', $id);
        $data = $this->db->get('accounts_tbl')->row();
        return $data->username;
    }

    /* 
    * Calculate Remaining Day 
    * Date format must be (Y-m-d)
    //////////////////////////////////////////////////////////////////////
    //PARA: Date Should In YYYY-MM-DD Format
    //RESULT FORMAT:
    // '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
    // '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
    // '%m Month %d Day'                                            =>  3 Month 14 Day
    // '%d Day %h Hours'                                            =>  14 Day 11 Hours
    // '%d Day'                                                        =>  14 Days
    // '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
    // '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
    // '%h Hours                                                    =>  11 Hours
    // '%a Days                                                        =>  468 Days
    //////////////////////////////////////////////////////////////////////
    */

    function remainingDay($startDate, $endDate){
        $startDate = date_create($startDate);
        $endDate = date_create($endDate);

        $diff = date_diff($startDate, $endDate, TRUE);
        return $diff->format('%a');
    }

    /* 
    * Select Max Value From Database
    */
    function max($table, $field){
        $this->db->select_max($field);
        $query = $this->db->get($table)->row_array();
        return $query;
    }

    function max_value($table, $field){
        $this->db->select_max($field);
        $query = $this->db->get($table)->row();
        return $query->$field;
    }

    function emailCheck($email){
        $this->db->where('email', $email);
        $query = $this->db->get('users_tbl')->row_array();
        if(empty($query['email'])){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                return array('status' => true, 'msg' => 'success');
            }
                else{
                    return array('status' => false, 'msg' => 'Invalid Email Address');
                }
        }
            else{
                return array('status' => false, 'msg' => 'Email Already taken');
            }
    }

    // Check Password Strength method
    function valid_password($password)
	{
		$password = trim($password);
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
		if (empty($password))
		{
            $data = array('status' => false, 'msg' => 'Password must not be empty.');
			return $data;
		}
		if (preg_match_all($regex_uppercase, $password) < 1)
		{
            $data = array('status' => false, 'msg' => 'Password must include at least one Uppercase letter.');
			return $data;
		}
		if (preg_match_all($regex_number, $password) < 1)
		{
            $data = array('status' => false, 'msg' => 'Password must include at least one number.');
			return $data;
		}
		
		if (strlen($password) < 5)
		{
            $data = array('status' => false, 'msg' => 'Password must be at least 5 character in length.');
			return $data;
		}
		if (strlen($password) > 32)
		{
			$data = array('status' => false, 'msg' => 'Password must be exceed 32 character in length.');
			return $data;
		}
		return $data = array('status' => true);
	}

    // Error Return Function

    // 1001 : return Invalid username or Email Address
    // 1002 : return Invalid Password
    // 1003 : return Account is not Activated

    function error($errorNum){
        switch($errorNum){
            case 1001:
                return 'Invalid Username or Email Address';
                break;
            case 1002:
                return 'Invalid Password !';
                break;
            case 1003:
                return 'Your Account is not Activate !';
                break;
        }
    }

    /* 
    * Image Upload Function
    */
    function upload_img($file,$path, $name)
    {
        $this->load->library('upload');
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size']     = '1024';
        $config['file_name'] = $name;
        $this->upload->initialize($config);
        
        if ($this->upload->do_upload($file))
        {
            $file_name = $this->upload->data('file_name');
            return array('status' => true, 'path' => $path.'/'.$file_name);

        }
            else
            {
                return array('status' => false, 'err' => $this->upload->display_errors());
            }

    }

    /* 
    * Image Resize Function
    */
    function resize_img($path){
        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        // $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 1080;
        $config['height'] = 480;

        $this->load->library('image_lib', $config);

        if($this->image_lib->resize()){
            return true;
        }
            else{
                return $this->image_lib->display_errors();
            }
    }

    function getMonth($month){
        switch($month){
            case 1:
                return 'January';
                break;
            case 2:
                return 'February';
                break;
            case 3:
                return 'March';
                break;
            case 4:
                return 'April';
                break;
            case 5:
                return 'May';
                break;
            case 6:
                return 'June';
                break;
            case 7:
                return 'July';
                break;
            case 8:
                return 'August';
                break;
            case 9:
                return 'September';
                break;
            case 10:
                return 'October';
                break;
            case 11:
                return 'November';
                break;
            case 12:
                return 'December';
                break;
        }
    }

    // Get Short Month Names

    function getShortMonth($month){
        switch($month){
            case 1:
                return 'JAN';
                break;
            case 2:
                return 'FEB';
                break;
            case 3:
                return 'MAR';
                break;
            case 4:
                return 'APR';
                break;
            case 5:
                return 'MAY';
                break;
            case 6:
                return 'JUN';
                break;
            case 7:
                return 'JUL';
                break;
            case 8:
                return 'AUG';
                break;
            case 9:
                return 'SEP';
                break;
            case 10:
                return 'OCT';
                break;
            case 11:
                return 'NOV';
                break;
            case 12:
                return 'DEC';
                break;
        }
    }

    // Custom Functions ..

    function supplier($id){
        $this->db->where('supplierId', $id);
        $query = $this->db->get('supplier_tbl')->row();
        return $query->supplierName;
    }

    function get_suppliers(){
        $this->db->order_by('supplierName', 'ASC');
        $data = $this->db->get('supplier_tbl');
        return $data->result_array();
    }

    function get_categories(){
        $this->db->order_by('categoryName', 'ASC');
        $data = $this->db->get('categories_tbl');
        return $data->result_array();
    }

    function get_brands(){
        $this->db->order_by('brandName', 'ASC');
        $data = $this->db->get('brands_tbl');
        return $data->result_array();
    }

    function get_itemsPrice($start, $limit){
        $data = $this->db->query("SELECT * FROM items_price_tbl AS ip
                LEFT JOIN categories_tbl AS cat
                ON cat.categoryId = ip.categoryId
                LEFT JOIN brands_tbl AS brand
                ON brand.brandId = ip.brandId
                LEFT JOIN currency_tbl AS currency
                ON currency.currencyId = ip.currency
                LEFT JOIN supplier_tbl AS supplier
                ON supplier.supplierId = ip.supplierId
                WHERE ip.active = TRUE
                ORDER BY ip.itemId DESC, cat.categoryName ASC, brand.brandName ASC
                LIMIT $start, $limit
        ");
        return $data->result_array();
    }

    function get_items_rows(){
        $rows = $this->db->query("SELECT COUNT(ip.itemId) AS itemId FROM items_price_tbl AS ip
            LEFT JOIN categories_tbl AS cat
            ON cat.categoryId = ip.categoryId
            LEFT JOIN brands_tbl AS brand
            ON brand.brandId = ip.brandId
            LEFT JOIN currency_tbl AS currency
            ON currency.currencyId = ip.currency
            LEFT JOIN supplier_tbl AS supplier
            ON supplier.supplierId = ip.supplierId
            WHERE ip.active = TRUE
            ORDER BY cat.categoryName ASC
            ")->row_array();
        return $rows['itemId'];
    }

    function get_allItems(){
        $data = $this->db->query("SELECT * FROM items_price_tbl AS ip
                LEFT JOIN categories_tbl AS cat
                ON cat.categoryId = ip.categoryId
                LEFT JOIN brands_tbl AS brand
                ON brand.brandId = ip.brandId
                LEFT JOIN currency_tbl AS currency
                ON currency.currencyId = ip.currency
                LEFT JOIN supplier_tbl AS supplier
                ON supplier.supplierId = ip.supplierId
                LEFT JOIN count_type_tbl AS ct
                ON ct.related_item_id = ip.itemId
                WHERE ip.active = TRUE
                AND ct.type = 'P'
                ORDER BY ip.itemName ASC
        ");
        return $data->result_array();
    }

    function get_total_pItem($vocId) {
        $data = $this->db->query("SELECT COUNT(voucherId) AS totalItem FROM purchase_tbl
                    WHERE voucherId = $vocId
                    ")->row();
        return $data->totalItem;
    }

    function get_total_pAmt($vocId) {
        $data = $this->db->query("SELECT ct_tbl.price AS price, p_tbl.quantity AS qty FROM purchase_tbl AS p_tbl
                LEFT JOIN items_price_tbl AS ip_tbl
                ON ip_tbl.itemId = p_tbl.itemId
                LEFT JOIN count_type_tbl AS ct_tbl
                ON ct_tbl.related_item_id = ip_tbl.itemId
                WHERE ct_tbl.type = 'P'
                AND p_tbl.voucherId = $vocId
                ")->result();

        $total = 0;
        foreach($data as $row){
            $total += $row->price * $row->qty;
        }

        return $total;
    }

    function get_purchaseItem($vocId){
        $data = $this->db->query("SELECT * FROM purchase_tbl AS purchase
            LEFT JOIN items_price_tbl AS item
            ON item.itemId = purchase.itemId
            LEFT JOIN count_type_tbl AS ctype
            ON item.itemId = ctype.related_item_id
            LEFT JOIN warehouse_tbl AS warehouse
            ON warehouse.warehouseId = purchase.warehouseId
            LEFT JOIN brands_tbl AS brand
            ON brand.brandId = item.brandId
            WHERE ctype.type = 'P'
            AND purchase.voucherId = $vocId
            ORDER BY purchase.purchaseDate DESC, warehouse.serial ASC
            ");
        return $data->result_array();
    }

    function get_issueItems(){
        $data = $this->db->query("SELECT * FROM stocks_balance_tbl AS sb
                LEFT JOIN items_price_tbl AS ip
                ON ip.itemId = sb.itemId 
                LEFT JOIN categories_tbl AS cat
                ON cat.categoryId = ip.categoryId
                LEFT JOIN brands_tbl AS brand
                ON brand.brandId = ip.brandId
                LEFT JOIN currency_tbl AS currency
                ON currency.currencyId = ip.currency
                LEFT JOIN supplier_tbl AS supplier
                ON supplier.supplierId = ip.supplierId
                WHERE sb.qty > 0
                AND ip.active = TRUE
                ORDER BY ip.itemName ASC
                ");
        return $data->result_array();
    }

    function get_issueItemsByWarehouse($warehouse){
        $data = $this->db->query("SELECT * FROM stocks_balance_tbl AS sb
                LEFT JOIN items_price_tbl AS ip
                ON ip.itemId = sb.itemId 
                LEFT JOIN categories_tbl AS cat
                ON cat.categoryId = ip.categoryId
                LEFT JOIN brands_tbl AS brand
                ON brand.brandId = ip.brandId
                LEFT JOIN currency_tbl AS currency
                ON currency.currencyId = ip.currency
                LEFT JOIN supplier_tbl AS supplier
                ON supplier.supplierId = ip.supplierId
                WHERE sb.qty > 0
                AND ip.active = TRUE
                AND sb.warehouseId = $warehouse
                ORDER BY ip.itemName ASC
                ");
        return $data->result_array();
    }

    function checkQty($qty, $warehouse, $item){
        $data = $this->db->query("SELECT * FROM stocks_balance_tbl
                    WHERE itemId = $item
                    AND warehouseId = $warehouse
                    ")->row_array();
        
        return $data;
    }

    function get_issuedItems(){
        $data = $this->db->query("SELECT * FROM transfer_tbl AS tr
            LEFT JOIN items_price_tbl AS ip
            ON ip.itemId = tr.itemId
            LEFT JOIN brands_tbl AS bd
            ON bd.brandId = ip.brandId
            LEFT JOIN warehouse_tbl AS wh
            ON wh.warehouseId = tr.transferIn
            ORDER BY tr.issueDate DESC
            ");
        return $data->result_array();
    }

    function get_user_data(){
        $query = $this->db->query("SELECT * FROM accounts_tbl
            LEFT JOIN permission_tbl
            ON permission_tbl.accId = accounts_tbl.accId
            WHERE accounts_tbl.role != 0
            ");
        return $query->result();
    }

    function get_stock_items(){
        $query = $this->db->query("SELECT * FROM items_price_tbl AS ip
            LEFT JOIN supplier_tbl AS sp
            ON sp.supplierId = ip.supplierId
            LEFT JOIN count_type_tbl AS ctype
            ON ip.itemId = ctype.related_item_id
            WHERE ip.active = true
            AND ctype.type = 'P'
            ORDER BY ip.codeNumber
            ");

        return $query;
    }

    function get_purchase_items_by_warehouse($warehouseId, $itemId){
        $query = $this->db->query("SELECT SUM(quantity) AS qty
            FROM purchase_tbl
            WHERE warehouseId = $warehouseId
            AND itemId = $itemId
            ");
        return $query;
    }

    function get_transfer_items_by_warehouse($warehouseId, $itemId){
        $query = $this->db->query("SELECT SUM(qty) AS qty
            FROM stocks_out_tbl
            WHERE warehouseFrom = $warehouseId
            AND itemId = $itemId
            ");
        return $query;
    }

    function get_saleItemSearch($key, $type){
        $query = $this->db->query("SELECT sb.qty AS qty, ct.price AS price, ip.itemName AS itemName, ip.itemModel AS itemModel, ip.codeNumber AS codeNumber  FROM stocks_balance_tbl AS sb
            LEFT JOIN warehouse_tbl AS wh
            ON wh.warehouseId = sb.warehouseId
            LEFT JOIN items_price_tbl AS ip
            ON ip.itemId = sb.itemId
            LEFT JOIN count_type_tbl AS ct
            ON ip.itemId = ct.related_item_id
            LEFT JOIN brands_tbl AS bd
            ON bd.brandId = ip.brandId
            LEFT JOIN purchase_tbl AS ps
            ON ps.itemId = sb.itemId
            WHERE (ip.itemName LIKE '$key%'
            OR ip.codeNumber LIKE '$key%'
            OR bd.brandName LIKE '$key%')
            AND sb.qty > 0
            AND wh.shop = true
            AND ct.type = '$type'
            AND ps.active = true
            ");
        return $query->result_array();
    }

    function get_itemByCode($code){
        $query = $this->db->query("SELECT * FROM stocks_balance_tbl AS sb
            LEFT JOIN warehouse_tbl AS wh
            ON wh.warehouseId = sb.warehouseId
            LEFT JOIN items_price_tbl AS ip
            ON ip.itemId = sb.itemId
            LEFT JOIN brands_tbl AS bd
            ON bd.brandId = ip.brandId
            WHERE ip.codeNumber = '$code'
            AND sb.qty > 0
            AND wh.shop = true
            ");
        return $query->row_array();
    }

    function get_invoiceItems($invId){
        $query = $this->db->query("SELECT * FROM invoice_detail_tbl
            
            ");
    }

    function getCreditAmount($customerID){
        $query = $this->db->query("SELECT balance FROM credits_tbl
            WHERE customerId = $customerID
            AND created_date = (SELECT MAX(created_date) FROM credits_tbl WHERE customerId = $customerID)
            ")->row();
        if(!empty($query->balance)){
            return $query->balance;
        }else{
            return 0;
        }

    }

    function get_balOutbySale($code){
        $query = $this->db->query("SELECT sb.qty, wh.warehouseId, ip.itemId FROM stocks_balance_tbl AS sb
            LEFT JOIN items_price_tbl AS ip
            ON ip.itemId = sb.itemId
            LEFT JOIN warehouse_tbl AS wh
            ON wh.warehouseId = sb.warehouseId
            WHERE ip.codeNumber = '$code'
            AND wh.shop = true
            ");

        return $query;
    }

    function get_itemsForDamage(){
        $data = $this->db->query("SELECT * FROM items_price_tbl AS ip_tbl
                LEFT JOIN purchase_tbl AS p_tbl
                ON p_tbl.itemId = ip_tbl.itemId
                LEFT JOIN count_type_tbl AS ct_tbl
                ON ct_tbl.related_item_id = ip_tbl.itemId
                LEFT JOIN stocks_balance_tbl AS sb_tbl
                ON sb_tbl.itemId = ip_tbl.itemId
                WHERE ct_tbl.type = 'P'
                AND sb_tbl.qty > 0
                ORDER BY ip_tbl.itemName ASC
                ")->result();

        return $data;
    }

    function itemDetail($id) {
        $dataP = $this->db->query("SELECT ip_tbl.itemName, ct_tbl.price, ip_tbl.codeNumber, ip_tbl.imgPath, ip_tbl.itemModel, sb_tbl.qty FROM items_price_tbl AS ip_tbl
                LEFT JOIN count_type_tbl AS ct_tbl
                ON ct_tbl.related_item_id = ip_tbl.itemId
                LEFT JOIN stocks_balance_tbl AS sb_tbl
                ON sb_tbl.itemId = ip_tbl.itemId
                WHERE ip_tbl.itemId = $id
                AND ct_tbl.type = 'P'
                ")->row();
        $dataS = $this->db->query("SELECT ip_tbl.itemName, ct_tbl.price, ip_tbl.codeNumber, ip_tbl.imgPath, ip_tbl.itemModel, sb_tbl.qty FROM items_price_tbl AS ip_tbl
                LEFT JOIN count_type_tbl AS ct_tbl
                ON ct_tbl.related_item_id = ip_tbl.itemId
                LEFT JOIN stocks_balance_tbl AS sb_tbl
                ON sb_tbl.itemId = ip_tbl.itemId
                WHERE ip_tbl.itemId = $id
                AND ct_tbl.type = 'R'
                ")->row();

        $array = array(
            'itemName' => $dataP->itemName,
            'sellPrice' => $dataS->price,
            'purchasePrice' => $dataP->price,
            'itemCode' => $dataP->codeNumber,
            'model' => $dataP->itemModel,
            'imgPath' => $dataP->imgPath,
            'balance' => $dataP->qty,
        );

        return $array;
    }

    function get_credit($customerID){
        $query = $this->db->query("SELECT * FROM credits_tbl AS crd
            LEFT JOIN invoices_tbl AS inv
            ON inv.invoiceId = crd.invoiceId
            WHERE crd.created_date = (SELECT MAX(created_date) FROM credits_tbl WHERE customerId = $customerID)
            ");

        return $query;
    }

    function get_invTotal($customerID){
        $query = $this->db->query("SELECT COUNT(inv.invoiceId) AS inv FROM credits_tbl AS crd
            LEFT JOIN invoices_tbl AS inv
            ON inv.invoiceId = crd.invoiceId
            WHERE crd.customerId = $customerID
            ")->row();

        return $query->inv;
    }

    function get_dTotalItems($invID){
        $query = $this->db->query("SELECT COUNT(invoiceId) AS total FROM invoice_detail_tbl
            WHERE invoiceId = $invID
            ")->row();
        return $query->total;
    }

    function get_dTotalAmount($invID){
        $inv = $this->get_limit_data('invoices_tbl', 'invoiceId', $invID)->row();
        $query = $this->db->query("SELECT * FROM invoice_detail_tbl
            WHERE invoiceId = $invID
            ")->result();
        $total = 0;
        foreach($query as $row){
            $total += $row->itemQty * $row->itemPrice;
        }
        return ($total-$inv->discountAmt);
    }

    function get_dGrossProfit($invID){
        $query = $this->db->query("SELECT * FROM invoice_detail_tbl AS idetail
            LEFT JOIN items_price_tbl AS ip
            ON ip.codeNumber = idetail.itemCode
            LEFT JOIN count_type_tbl AS ct
            ON ct.related_item_id = ip.itemId
            WHERE idetail.invoiceId = $invID
            AND ct.type = 'p'
            ")->result();
        $sTotal = 0;
        $pTotal = 0;
        foreach($query as $row) {
            $sTotal += $row->itemPrice * $row->itemQty;
            $pTotal += $row->price * $row->itemQty;
        }
        return ['sTotal' => $sTotal, 'pTotal' => $pTotal];
    }

    function get_dNetProfit($invID, $sType) {
        $items = $this->db->query("SELECT * FROM invoice_detail_tbl AS invDetail
            LEFT JOIN items_price_tbl AS ip_tbl
            ON ip_tbl.codeNumber = invDetail.itemCode
            LEFT JOIN count_type_tbl AS ct_tbl
            ON ct_tbl.related_item_id = ip_tbl.itemId
            WHERE invDetail.invoiceId = $invID
            AND ct_tbl.type = 'P'
            ")->result();

        $ProfitTotal = 0;
        $totalCharge = 0;
        foreach($items as $item) {
            $charge = $this->db->query("SELECT * FROM purchase_tbl AS ps
                LEFT JOIN vouchers_tbl AS vr
                ON vr.voucherId = ps.voucherId
                WHERE ps.itemId = $item->itemId
                ")->row();

            $chargeAmt = $charge->chargeAmt;

            $totalItem = $this->db->query("SELECT SUM(quantity) AS pQty FROM purchase_tbl
                WHERE voucherId = $charge->voucherId
                ")->row();
            $avgCharge = round($chargeAmt / $totalItem->pQty);
            $totalCharge += $avgCharge;

            $dmg = $this->db->query("SELECT SUM(qty) AS dQty FROM damages_tbl
                WHERE related_item_id = $item->itemId
                ")->row();

            $pTotalPrice = $charge->quantity * $item->price;
            $pActualPrice = round($pTotalPrice / ($charge->quantity - $dmg->dQty));

            $sPrice = $this->db->query("SELECT * FROM count_type_tbl
                WHERE related_item_id = $item->itemId
                AND type = '$sType'
                ")->row();

            $totalSale = $sPrice->price * $item->itemQty;
            $actualSale = ($pActualPrice + $avgCharge) * $item->itemQty;

            $netProfit = $totalSale - $actualSale;
            $ProfitTotal += $netProfit;
        }

        return $ProfitTotal;

    }

    function get_daily_invoices($day, $month, $year) {
        $dStart = $year.'-'.$month.'-'.sprintf('%02d', $day).' 00:00:00';
        $dEnd = $year.'-'.$month.'-'.sprintf('%02d', $day).' 23:59:59';

        $data = $this->db->query("SELECT invoiceId, saleType, discountAmt FROM invoices_tbl
            WHERE created_date
            BETWEEN '$dStart'
            AND '$dEnd'
            AND active = true
            ")->result();

        return $data;
    }

    function get_monthly_invoices($month, $year) {
        $dStart = $year.'-'.$month.'-01 00:00:00';
        $dEnd = $year.'-'.$month.'-31 23:59:59';

        $data = $this->db->query("SELECT invoiceId, saleType, discountAmt FROM invoices_tbl
            WHERE created_date
            BETWEEN '$dStart'
            AND '$dEnd'
            AND active = true
            ")->result();

        return $data;
    }

    function get_dMarginRate($invID){

    }

    function get_M_invTotal($day, $month, $year){
        $date = $year.'-'.sprintf('%02d',$month).'-'.sprintf('%02d',$day);
        $query = $this->db->query("SELECT COUNT(invoiceId) AS invoice FROM invoices_tbl
            WHERE created_date = '$date'
            ")->row();
        return $query->invoice;
    }

    function get_dailyReportsData($date) {
        $data = $this->db->query("SELECT * FROM invoices_tbl
            WHERE created_date BETWEEN '$date 00:00:00' AND '$date 23:59:59'
            AND active = true
            ");
        return $data;
    }

    function get_M_Total($day, $month, $year){
        $date = $year.'-'.sprintf('%02d',$month).'-'.sprintf('%02d',$day);
        $query = $this->db->query("SELECT * FROM invoices_tbl AS inv
            LEFT JOIN invoice_detail_tbl AS detail
            ON detail.invoiceId = inv.invoiceId
            LEFT JOIN items_price_tbl AS ip
            ON ip.codeNumber =  detail.itemCode
            LEFT JOIN count_type_tbl AS ct
            ON ct.related_item_id = ip.itemId
            WHERE inv.created_date BETWEEN '$date 00:00:00' AND '$date 23:59:59'
            AND ct.type = 'P'
            AND inv.active = true
            ")->result();

        $total = 0;
        $profit = 0;
        $pTotal = 0;
        foreach($query as $row){
            $total += $row->itemQty * $row->itemPrice;
            $profit +=  ($row->itemQty * $row->itemPrice)-(($row->itemQty * $row->price));
            $pTotal += $row->itemQty * $row->price;
        }

        $query2 = $this->db->query("SELECT * FROM invoices_tbl
                WHERE created_date BETWEEN '$date 00:00:00' 
                AND '$date 23:59:59'
                AND active = true
                ")->result();
        $disTotal = 0;
        foreach($query2 as $inv){
            $disTotal += $inv->discountAmt;
        }

        return ['total' => ($total-$disTotal), 'profit' => ($profit-$disTotal), 'pTotal' => $pTotal];
    }

    function get_Y_invTotal($month, $year){
        $start = $year.'-'.sprintf('%02d', $month).'-01';
        $end = $year.'-'.sprintf('%02d', $month).'-31';

        $query = $this->db->query("SELECT COUNT(invoiceId) AS invoice FROM invoices_tbl
            WHERE created_date BETWEEN '$start' AND '$end'
            ")->row();

        return $query->invoice;
    }

    function get_Y_Total($month, $year){
        $start = $year.'-'.sprintf('%02d', $month).'-01';
        $end = $year.'-'.sprintf('%02d', $month).'-31';

        $query = $this->db->query("SELECT * FROM invoices_tbl AS inv
            LEFT JOIN invoice_detail_tbl AS detail
            ON detail.invoiceId = inv.invoiceId
            LEFT JOIN items_price_tbl AS ip
            ON ip.codeNumber = detail.itemCode
            LEFT JOIN count_type_tbl AS ct
            ON ct.related_item_id = ip.itemId
            WHERE inv.created_date BETWEEN '$start' AND '$end'
            AND ct.type = 'P'
            ")->result();

        $total = 0;
        $gpTotal = 0;
        foreach($query as $row){
            $total += $row->itemQty * $row->itemPrice;
            $gpTotal += $row->itemQty * $row->price;
        }

        return ['total' => $total, 'gpTotal' => $gpTotal];
    }

    function get_yChart($year){
        $start = $year.'-01-01';
        $end = $year.'-12-31';
    
        $query = $this->db->query("SELECT * FROM invoices_tbl AS inv
            LEFT JOIN invoice_detail_tbl AS detail
            ON detail.invoiceId = inv.invoiceId
            LEFT JOIN items_price_tbl AS ip
            ON ip.codeNumber = detail.itemCode
            LEFT JOIN count_type_tbl AS ct
            ON ct.related_item_id = ip.itemId
            WHERE inv.created_date BETWEEN '$start' AND '$end'
            AND ct.type = 'P'
            ")->result();

        $total = 0;
        $gpTotal = 0;
        foreach($query as $row){
            $total += $row->itemQty * $row->itemPrice;
            $gpTotal += $row->itemQty * $row->price;
        }

        return ['total' => $total, 'gpTotal' => $gpTotal];
    }

    function checkPrice($itemId){
        $this->db->where('related_item_id', $itemId);
        $query = $this->db->get('count_type_tbl')->result();
        if(count($query) > 0){
            return true;
        }else{
            return false;
        }
    }

    function getCountType($itemId){
        $this->db->where('related_item_id', $itemId);
        $this->db->where('type', 'P');
        $this->db->order_by('qty', 'asc');
        $query = $this->db->get('count_type_tbl');
        return $query;
    }

    function get_invoiceDetail($invID){
        $query = $this->db->query("SELECT * FROM invoices_tbl AS ivtbl
                        LEFT JOIN invoice_detail_tbl AS ivdetail
                        ON ivtbl.invoiceId = ivdetail.invoiceId
                        WHERE ivtbl.invoiceId = $invID
                        ");
        return $query;
    }

    function get_referInvDetail($invId) {
        $query = $this->db->query("SELECT itemCode AS code, itemName AS name, itemPrice AS price, itemQty AS qty FROM invoice_detail_tbl WHERE invoiceId = $invId");
        return $query;
    }

    function getAllInvoices() {
        $data = $this->db->query("SELECT * FROM invoices_tbl
            WHERE active = true
            ORDER BY created_date DESC
            ");
        return $data;
    }

    function getInvoicesByType($type) {
        $data = $this->db->query("SELECT * FROM invoices_tbl
            WHERE active = true
            AND paymentType = '$type'
            ORDER BY created_date DESC
            ");
        return $data;
    }

    function getCODinvoices() {
        $data = $this->db->query("SELECT * FROM invoices_tbl
            WHERE active = true
            AND paymentType = 'COD'
            AND delivered = false
            ORDER BY created_date DESC
            ");
        return $data;
    }

    function getMBKinvoices() {
        $data = $this->db->query("SELECT * FROM invoices_tbl
            WHERE active = true
            AND paymentType = 'MBK'
            AND pReceived = false
            ORDER BY created_date DESC
            ");
        return $data;
    }

    function get_invoice_items($invId) {
        $data = $this->db->query("SELECT * FROM invoice_detail_tbl AS inv
            LEFT JOIN items_price_tbl AS ip
            ON ip.codeNumber = inv.itemCode
            WHERE inv.invoiceId = $invId
            ");
        return $data;
    }

    function check_active_purchase($vrId) {
        $this->db->where('voucherId', $vrId);
        $data = $this->db->get('purchase_tbl')->result();

        $counter = count($data);
        $active = 0;
        foreach($data as $row) {
            if($row->active){
                $active ++;
            }
        }

        if($active == 0) {
            return 'empty';
        }
            elseif ($active < $counter) {
                return 'less';
            }
                elseif($active == $counter) {
                    return 'passed';
                }
    }

    function check_purchase($pId) {
        $this->db->where('purchaseId', $pId);
        $this->db->where('active', true);
        $data = $this->db->get('purchase_tbl')->row();

        if(empty($data)) {
            return false;
        }
            else{
                return true;
            }
    }

}