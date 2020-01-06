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
    function upload_img($file,$path)
    {
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size']     = '1024';
        $config['file_name'] = 'upload_'.time();
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

    // Custom Functions ..

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
                ORDER BY cat.categoryName ASC, brand.brandName ASC
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
                WHERE ip.active = TRUE
                ORDER BY ip.itemName ASC
        ");
        return $data->result_array();
    }

    function get_purchaseItem(){
        $data = $this->db->query("SELECT * FROM purchase_tbl AS purchase
            LEFT JOIN items_price_tbl AS item
            ON item.itemId = purchase.itemId
            LEFT JOIN warehouse_tbl AS warehouse
            ON warehouse.warehouseId = purchase.warehouseId
            LEFT JOIN brands_tbl AS brand
            ON brand.brandId = item.brandId
            ORDER BY warehouse.serial ASC
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
        $data = $this->db->query("SELECT * FROM stocks_out_tbl AS so
            LEFT JOIN items_price_tbl AS ip
            ON ip.itemId = so.itemId
            LEFT JOIN brands_tbl AS bd
            ON bd.brandId = ip.brandId
            LEFT JOIN warehouse_tbl AS wh
            ON wh.warehouseId = so.warehouseId
            ORDER BY so.issueDate DESC
            ");
        return $data->result_array();
    }

}