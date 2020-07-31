<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migrate extends CI_Model {
   
    function table_migrate(){

       $this->load->dbforge();

       $this->dbforge->add_field(array(
           'accId' => array(
               'type' => 'INT',
               'constraint' => 8,
               'auto_increment' => TRUE
           ),
           'username' => array(
               'type' => 'VARCHAR',
               'constraint' => 128
           ),
           'secret' => array(
               'type' => 'VARCHAR',
               'constraint' => 128
           ),
           'role' => array(
               'type' => 'INT',
               'constraint' => 8
           ),
           'accountState' => array(
               'type' => 'BOOLEAN'
           ),
       ));

       $this->dbforge->add_key('accId', TRUE);
       $this->dbforge->create_table('accounts_tbl', TRUE);

       $num_row = $this->db->count_all_results('accounts_tbl');
        if($num_row < 1){
            $this->load->library('libigniter');
            $this->load->library('auth');
            $insert = array(
                'username' => 'system',
                'secret' => $this->auth->hash_password('Professional87'),
                'accountState' => 1,
                'role' => 0,
                );
            $this->db->insert('accounts_tbl',$insert);
        }

        // Create link_structure_tbl

        $this->dbforge->add_field(array(
           'linkId' => array(
              'type' => 'INT',
              'constraint' => 8,
              'auto_increment' => TRUE
           ),
           'machine' => array(
              'type' => 'VARCHAR',
              'constraint' => 255
           ),
           'linkName' => array(
              'type' => 'VARCHAR',
              'constraint' => 255
           ),
           'lang_name' => array(
              'type' => 'VARCHAR',
              'constraint' => 255,
           ),
           'icon_class' => array(
              'type' => 'VARCHAR',
              'constraint' => 255
           ),
           'color' => array(
              'type' => 'VARCHAR',
              'constraint' => 255
           ),
           'sub_menu' => array(
              'type' => 'BOOLEAN',
           ),
           'description' => array(
              'type' => 'TEXT'
           ),
        ));

        $this->dbforge->add_key('linkId', TRUE);
        $this->dbforge->create_table('link_structure_tbl', TRUE);

        $num_row = $this->db->count_all_results('link_structure_tbl');
        if($num_row < 1){
           $arr = array(
              array(
                'machine' => 'home',
                'name' => 'POS',
                'lang_name' => 'home',
                'icon_class' => 'dolly',
                'color' => 'olive',
                'sub_menu' => false,
                'description' => 'Main Screen of the Point of Sales system'
              ),
              array(
                'machine' => 'stock-balance',
                'name' => 'Stock Balance',
                'lang_name' => 'stocks',
                'icon_class' => 'table',
                'color' => 'blue',
                'sub_menu' => false,
                'description' => 'Balance sheet for stocks ..'
              ),
              array(
                'machine' => 'purchase/0',
                'name' => 'Stocks In',
                'lang_name' => 'purchase',
                'icon_class' => 'plus square',
                'color' => 'green',
                'sub_menu' => false,
                'description' => 'Incoming Stocks and that store in which warehouse'
              ),
              array(
                'machine' => 'sales',
                'name' => 'Stocks Out',
                'lang_name' => 'sales',
                'icon_class' => 'minus square',
                'color' => 'orange',
                'sub_menu' => false,
                'description' => 'Outgoing Or Transfer Stocks ..'
              ),
              array(
                'machine' => 'reports',
                'name' => 'Reports',
                'lang_name' => 'reports',
                'icon_class' => 'clipboard outline',
                'color' => 'olive',
                'sub_menu' => false,
                'description' => 'Reports for sales, stocks in and stock out etc'
              ),
              array(
                'machine' => 'users',
                'name' => 'Accounts',
                'lang_name' => 'accounts',
                'icon_class' => 'user circle',
                'color' => '',
                'sub_menu' => true,
                'description' => 'User management for Inventory System'
              ),
              array(
                'machine' => 'warehouse',
                'name' => 'Warehouse',
                'lang_name' => 'warehouse',
                'icon_class' => 'warehouse',
                'color' => '',
                'sub_menu' => true,
                'description' => 'Define warehouses for Inventory system'
              ),
              array(
                'machine' => 'supplier',
                'name' => 'Supplier',
                'lang_name' => 'supplier',
                'icon_class' => 'cart plus',
                'color' => '',
                'sub_menu' => true,
                'description' => 'Define suppliers for import goods'
              ),
              array(
                'machine' => 'currency',
                'name' => 'Currency',
                'lang_name' => 'currency',
                'icon_class' => 'pound sign',
                'color' => '',
                'sub_menu' => true,
                'description' => 'Define currency for inventory system'
              ),
              array(
                'machine' => 'category',
                'name' => 'Category',
                'lang_name' => 'category',
                'icon_class' => 'list ol',
                'color' => '',
                'sub_menu' => true,
                'description' => 'Define category list for import items'
              ),
              array(
                'machine' => 'brand',
                'name' => 'Brand',
                'lang_name' => 'brand',
                'icon_class' => 'trademark',
                'color' => '',
                'sub_menu' => true,
                'description' => 'Define for import items'
              ),
              array(
                'machine' => 'items-price/0',
                'name' => 'Items and Price',
                'lang_name' => 'itemPrice',
                'icon_class' => 'clipboard list',
                'color' => '',
                'sub_menu' => true,
                'description' => 'Define prices for items'
              ),
           );
           foreach($arr as $row){
              $this->db->insert('link_structure_tbl', ['machine' => $row['machine'], 'linkName' => $row['name'], 'lang_name' => $row['lang_name'], 'icon_class' => $row['icon_class'], 'color' => $row['color'], 'sub_menu' => $row['sub_menu'], 'description' => $row['description']]);
           }
        }


        // End of link_structure_tbl

        // Create permission_tbl

        $this->dbforge->add_field(array(
           'permissionId' => array(
              'type' => 'INT',
              'constraint' => 8,
              'auto_increment' => TRUE
           ),
           'accId' => array(
              'type' => 'INT',
              'constraint' => 8
           ),
           'link_accept' => array(
              'type' => 'VARCHAR',
              'constraint' => 255
           ),
           'created_at' => array(
              'type' => 'DATETIME'
           ),
           'updated_at' => array(
              'type' => 'DATETIME'
           ),
        ));

        $this->dbforge->add_key('permissionId', TRUE);
        $this->dbforge->create_table('permission_tbl', TRUE);

        // End of permission_tbl

        // Warehouse Table
        $this->dbforge->add_field(array(
          'warehouseId' => array(
              'type' => 'INT',
              'constraint' => 8,
              'auto_increment' => TRUE
          ),
          'warehouseName' => array(
            'type' => 'VARCHAR',
            'constraint' => 128
          ),
          'serial' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'remark' => array(
            'type' => 'TEXT'
          ),
          'activeState' => array(
            'type' => 'BOOLEAN'
          ),
        ));

        $this->dbforge->add_key('warehouseId', TRUE);
        $this->dbforge->create_table('warehouse_tbl', TRUE);

        // Supplier Table
        $this->dbforge->add_field(array(
          'supplierId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
          ),
          'supplierName' => array(
            'type' => 'VARCHAR',
            'constraint' => 256
          ),
          'emailAddress' => array(
            'type' => 'VARCHAR',
            'constraint' => 128
          ),
          'contactPerson' => array(
            'type' => 'VARCHAR',
            'constraint' => 128
          ),
          'contactPhone1' => array(
            'type' => 'VARCHAR',
            'constraint' => 64
          ),
          'contactPhone2' => array(
            'type' => 'VARCHAR',
            'constraint' => 64
          ),
          'contactAddress1' => array(
            'type' => 'TEXT'
          ),
          'contactAddress2' => array(
            'type' => 'TEXT'
          ),
          'remark' => array(
            'type' => 'TEXT'
          ),
        ));

      $this->dbforge->add_key('supplierId', TRUE);
      $this->dbforge->create_table('supplier_tbl', TRUE);

      // Currency Table
      $this->dbforge->add_field(array(
        'currencyId' => array(
          'type' => 'INT',
          'constraint' => 8,
          'auto_increment' => TRUE
        ),
        'currency' => array(
          'type' => 'CHAR',
          'constraint' => 64
        ),
        'default' => array(
          'type' => 'BOOLEAN'
        ),
      ));

      $this->dbforge->add_key('currencyId', TRUE);
      $this->dbforge->create_table('currency_tbl', TRUE);

      $num_row = $this->db->count_all_results('currency_tbl');
        if($num_row < 1){
          $arr = ['USD' , 'MMK', 'SGD', 'THB'];
          foreach($arr as $currency){

            if($currency == 'MMK'){
              $def = true;
            }
            else{
              $def = false;
            }
            $insert = array(
                'currency' => $currency,
                'default' => $def
                );
            $this->db->insert('currency_tbl',$insert);
          }
        }

        // Category Table
        $this->dbforge->add_field(array(
          'categoryId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
          ),
          'categoryName' => array(
            'type' => 'VARCHAR',
            'constraint' => 256
          ),
          'remark' => array(
            'type' => 'TEXT'
          )
        ));

        $this->dbforge->add_key('categoryId', TRUE);
        $this->dbforge->create_table('categories_tbl', TRUE);

        // Brand Table
        $this->dbforge->add_field(array(
          'brandId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
          ),
          'brandName' => array(
            'type' => 'VARCHAR',
            'constraint' => '256'
          ),
          'remark' => array(
            'type' => 'TEXT'
          ),
        ));

        $this->dbforge->add_key('brandId', TRUE);
        $this->dbforge->create_table('brands_tbl', TRUE);

        // Items & Price Table
        $this->dbforge->add_field(array(
          'itemId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
          ),
          'itemName' => array(
            'type' => 'VARCHAR',
            'constraint' => 256
          ),
          'itemModel' => array(
            'type' => 'VARCHAR',
            'constraint' => 255
          ),
          'categoryId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'codeNumber' => array(
            'type' => 'VARCHAR',
            'constraint' => 64
          ),
          'brandId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'currency' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'purchasePrice' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'retailPrice' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'wholesalePrice' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'supplierId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'remark' => array(
            'type' => 'TEXT'
          ),
          'referId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'active' => array(
            'type' => 'BOOLEAN'
          ),
        ));

        $this->dbforge->add_key('itemId', TRUE);
        $this->dbforge->create_table('items_price_tbl', TRUE);

        // Purchase Table
        $this->dbforge->add_field(array(
          'purchaseId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
          ),
          'itemId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'warehouseId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'purchaseDate' => array(
            'type' => 'DATE'
          ),
          'quantity' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'remark' => array(
            'type' => 'TEXT'
          ),
        ));

        $this->dbforge->add_key('purchaseId', TRUE);
        $this->dbforge->create_table('purchase_tbl', TRUE);

        // Create Stocks Balance Table
        $this->dbforge->add_field(array(
          'balanceId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
          ),
          'itemId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'qty' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'warehouseId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
        ));

        $this->dbforge->add_key('balanceId', TRUE);
        $this->dbforge->create_table('stocks_balance_tbl', TRUE);

        // Create Stock Out Table
        $this->dbforge->add_field(array(
          'issueId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
          ),
          'itemId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'qty' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'warehouseId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'issueDate' => array(
            'type' => 'DATETIME'
          ),
          'remark' => array(
            'type' => 'TEXT'
          ),
        ));

        $this->dbforge->add_key('issueId', TRUE);
        $this->dbforge->create_table('stocks_out_tbl', TRUE);
        
        
        // ------------ End of Create Tables ---------------
    }


	
}