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
                'machine' => 'purchase',
                'name' => 'Stocks In',
                'lang_name' => 'purchase',
                'icon_class' => 'plus square',
                'color' => 'green',
                'sub_menu' => false,
                'description' => 'Incoming Stocks and that store in which warehouse'
              ),
              array(
                'machine' => 'transfer',
                'name' => 'Stocks Out',
                'lang_name' => 'stock_out',
                'icon_class' => 'minus square',
                'color' => 'orange',
                'sub_menu' => false,
                'description' => 'Outgoing Or Transfer Stocks ..'
              ),
              array(
                'machine' => 'reports/daily',
                'name' => 'Reports',
                'lang_name' => 'reports',
                'icon_class' => 'chart bar outline',
                'color' => 'teal',
                'sub_menu' => false,
                'description' => 'Reports for sales, stocks in and stock out etc'
              ),
              array(
                'machine' => 'invoices/~',
                'name' => 'Invoices',
                'lang_name' => 'invoices',
                'icon_class' => 'folder outline',
                'color' => 'violet',
                'sub_menu' => false,
                'description' => 'Reports for credits list of by customers'
              ),
              array(
                'machine' => 'users',
                'name' => 'Accounts',
                'lang_name' => 'accounts',
                'icon_class' => 'user circle',
                'color' => 'yellow',
                'sub_menu' => true,
                'description' => 'User management for Inventory System'
              ),
              array(
                'machine' => 'warehouse',
                'name' => 'Warehouse',
                'lang_name' => 'warehouse',
                'icon_class' => 'warehouse',
                'color' => 'purple',
                'sub_menu' => true,
                'description' => 'Define warehouses for Inventory system'
              ),
              array(
                'machine' => 'supplier',
                'name' => 'Supplier',
                'lang_name' => 'supplier',
                'icon_class' => 'cart plus',
                'color' => 'violet',
                'sub_menu' => true,
                'description' => 'Define suppliers for import goods'
              ),
              array(
                'machine' => 'currency',
                'name' => 'Currency',
                'lang_name' => 'currency',
                'icon_class' => 'pound sign',
                'color' => 'pink',
                'sub_menu' => true,
                'description' => 'Define currency for inventory system'
              ),
              array(
                'machine' => 'categories',
                'name' => 'Category',
                'lang_name' => 'category',
                'icon_class' => 'list ol',
                'color' => 'grey',
                'sub_menu' => true,
                'description' => 'Define category list for import items'
              ),
              array(
                'machine' => 'brands',
                'name' => 'Brand',
                'lang_name' => 'brand',
                'icon_class' => 'trademark',
                'color' => 'black',
                'sub_menu' => true,
                'description' => 'Define for import items'
              ),
              array(
                'machine' => 'vouchers',
                'name' => 'Vouchers',
                'lang_name' => 'voucher',
                'icon_class' => 'file alternate outline',
                'color' => 'purple',
                'sub_menu' => true,
                'description' => 'Purchase Vouchers'
              ),
              array(
                'machine' => 'discounts',
                'name' => 'Discounts',
                'lang_name' => 'discounts',
                'icon_class' => 'tags',
                'color' => 'orange',
                'sub_menu' => true,
                'description' => 'Create, Modify and Delete discounts/discounts type for sale items.'
              ),
              array(
                'machine' => 'extra-charges',
                'name' => 'Extra Charges',
                'lang_name' => 'extra_charges',
                'icon_class' => 'money bill alternate outline',
                'color' => 'blue',
                'sub_menu' => true,
                'description' => 'Create, Modify and Delete  Chargs. Such as Transportion charges depend on the items purchase'
              ),
              array(
                'machine' => 'items-price/0',
                'name' => 'Items and Price',
                'lang_name' => 'itemPrice',
                'icon_class' => 'clipboard list',
                'color' => 'teal',
                'sub_menu' => true,
                'description' => 'Define prices for items'
              ),
              array(
                'machine' => 'damages',
                'name' => 'Damage',
                'lang_name' => 'damages',
                'icon_class' => 'trash',
                'color' => 'orange',
                'sub_menu' => true,
                'description' => 'Damages of the stocks'
              ),
              array(
                'machine' => 'customers',
                'name' => 'Customers',
                'lang_name' => 'customer',
                'icon_class' => 'address book outline',
                'color' => 'red',
                'sub_menu' => true,
                'description' => 'Define customer information'
              ),
              array(
                'machine' => 'setting',
                'name' => 'Settings',
                'lang_name' => 'setting',
                'icon_class' => 'cogs',
                'color' => 'black',
                'sub_menu' => true,
                'description' => 'Adjust settings for POS system, such as Discount %, GOV Tax, etc..'
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
           'link' => array(
              'type' => 'JSON',
           ),
           'modify' => array(
              'type' => 'JSON',
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
          'shop' => array(
            'type' => 'BOOLEAN'
          ),
        ));

        $this->dbforge->add_key('warehouseId', TRUE);
        $this->dbforge->create_table('warehouse_tbl', TRUE);

        // Insert Default Warehouse
        $num_row = $this->db->count_all_results('warehouse_tbl');
        if($num_row < 1){
            
            $insert = array(
                'warehouseName' => 'On Shop',
                'serial' => 1,
                'remark' => 'Default Warehouse Created by System',
                'activeState' => 1,
                'shop' => 1
                );
            $this->db->insert('warehouse_tbl',$insert);
        }

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
          'letterCode' => array(
            'type' => 'CHAR',
            'constraint' => 2
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
          'imgPath' => array(
            'type' => 'VARCHAR',
            'constraint' => 355
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
          'purchased' => array(
            'type' => 'BOOLEAN'
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
          'count_type_id' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'warehouseId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'voucherId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'supplierId' => array(
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
          'active' => array(
            'type' => 'BOOLEAN'
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
          'count_type_id' => array(
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
          'tranId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
          ),
          'itemId' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'count_type_id' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'qty' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'transferIn' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'transferOut' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'tranType' => array(
            'type' => 'CHAR',
            'constraint' => 15
          ),
          'issueDate' => array(
            'type' => 'DATETIME'
          ),
          'remark' => array(
            'type' => 'TEXT'
          ),
        ));

        $this->dbforge->add_key('tranId', TRUE);
        $this->dbforge->create_table('transfer_tbl', TRUE);

        // Create customers_tbl

        $this->dbforge->add_field(array(
          'customerId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
          ),
          'customerName' => array(
            'type' => 'VARCHAR',
            'constraint' => 255
          ),
          'email' => array(
            'type' => 'VARCHAR',
            'constraint' => 255
          ),
          'phone1' => array(
            'type' => 'VARCHAR',
            'constraint' => 15
          ),
          'phone2' => array(
            'type' => 'VARCHAR',
            'constraint' => 15
          ),
          'address1' => array(
            'type' => 'TEXT'
          ),
          'address2' => array(
            'type' => 'TEXT'
          ),
          'remark' => array(
            'type' => 'TEXT'
          ),
          'creditBalance' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
          'memberPoint' => array(
            'type' => 'INT',
            'constraint' => 8
          ),
        ));

        $this->dbforge->add_key('customerId', TRUE);
        $this->dbforge->create_table('customers_tbl', TRUE);

        // Create invoices_tbl

      $this->dbforge->add_field(array(
         'invoiceId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
         ),
         'invoiceSerial' => array(
            'type' => 'VARCHAR',
            'constraint' => 255
         ),
         'saleType' => array(
            'type' => 'CHAR',
            'constraint' => 1
         ),
         'paymentType' => array(
            'type' => 'CHAR',
            'constraint' => 3
         ),
         'byCustomer' => array(
            'type' => 'BOOLEAN'
         ),
         'customerId' => array(
            'type' => 'INT',
            'constraint' => 8
         ),
         'depositAmt' => array(
            'type' => 'INT',
            'constraint' => 8
         ),
         'discountAmt' => array(
            'type' => 'INT',
            'constraint' => 8
         ),
         'payment' => array(
            'type' => 'INT',
            'constraint' => 8
         ),
         'delivered' => array(
            'type' => 'BOOLEAN'
         ),
         'pReceived' => array(
            'type' => 'BOOLEAN'
         ),
         'referId' => array(
            'type' => 'INT',
            'constraint' => 8
         ),
         'created_date' => array(
            'type' => 'DATETIME'
         ),
         'created_time' => array(
            'type' => 'TIME'
         ),
         'created_by' => array(
            'type' => 'INT',
            'constraint' => 8
         ),
         'active' => array(
            'type' => 'BOOLEAN'
         )
      ));

      $this->dbforge->add_key('invoiceId', TRUE);
      $this->dbforge->create_table('invoices_tbl', TRUE);

      // End of invoices_tbl

      // Create invoice_detail_tbl

      $this->dbforge->add_field(array(
         'detailId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
         ),
         'invoiceId' => array(
            'type' => 'INT',
            'constraint' => 8
         ),
         'itemCode' => array(
            'type' => 'VARCHAR',
            'constraint' => 255
         ),
         'itemName' => array(
            'type' => 'VARCHAR',
            'constraint' => 255
         ),
         'itemPrice' => array(
            'type' => 'INT',
            'constraint' => 8
         ),
         'itemQty' => array(
            'type' => 'INT',
            'constraint' => 8
         ),
      ));

      $this->dbforge->add_key('detailId', TRUE);
      $this->dbforge->create_table('invoice_detail_tbl', TRUE);

      // End of invoice_detail_tbl

      // Create credits_tbl

      $this->dbforge->add_field(array(
          'creditId' => array(
              'type' => 'INT',
              'constraint' => 8,
              'auto_increment' => true
          ),
          'customerId' => array(
              'type' => 'INT',
              'constraint' => 8
          ),
          'invoiceId' => array(
              'type' => 'INT',
              'constraint' => 8
          ),
          'Amount' => array(
              'type' => 'INT',
              'constraint' => 8
          ),
          'depositAmt' => array(
              'type' => 'INT',
              'constraint' => 8
          ),
          'balance' => array(
              'type' => 'INT',
              'constraint' => 8
          ),
          'created_date' => array(
              'type' => 'DATETIME'
          ),
      ));

      $this->dbforge->add_key('creditId', TRUE);
      $this->dbforge->create_table('credits_tbl', TRUE);

      // End of Credit table

      // Create count_type_tbl

      $this->dbforge->add_field(array(
        'count_type_id' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
        ),
        'related_item_id' => array(
            'type' => 'INT',
            'constraint' => 8
        ),
        'type' => array(
          'type' => 'CHAR',
          'constraint' => 1
        ),
        'count_type' => array(
            'type' => 'VARCHAR',
            'constraint' => 255
        ),
        'qty' => array(
            'type' => 'INT',
            'constraint' => 8
        ),
        'price' => array(
            'type' => 'INT',
            'constraint' => 8
        ),
        'remark' => array(
          'type' => 'TEXT'
        ),
        'created_at' => array(
          'type' => 'DATETIME'
        )
      ));

      $this->dbforge->add_key('count_type_id', TRUE);
      $this->dbforge->create_table('count_type_tbl', TRUE);

      // End of count_type_tbl


      // Creating Discounts Tables
      $this->dbforge->add_field(array(
        'discountId' => array(
            'type' => 'INT',
            'constraint' => 8,
            'auto_increment' => TRUE
        ),
        'discountTitle' => array(
            'type' => 'VARCHAR',
            'constraint' => 255
        ),
        'discountType' => array(
            'type' => 'CHAR',
            'constraint' => 2
        ),
        'discountRate' => array(
            'type' => 'INT',
            'constraint' => 8
        ),
        'remark' => array(
            'type' => 'TEXT'
        ),
        'active' => array(
            'type' => 'BOOLEAN'
        ),
        'created_at' => array(
            'type' => 'DATETIME'
        )
      ));

      $this->dbforge->add_key('discountId', TRUE);
      $this->dbforge->create_table('discounts_tbl', TRUE);

      // Create extra_charges_tbl
      $this->dbforge->add_field(array(
        'chargeId' => array(
          'type' => 'INT',
          'constraint' => 8,
          'auto_increment' => TRUE
        ),
        'chargeTitle' => array(
          'type' => 'VARCHAR',
          'constraint' => 255
        ),
        'chargeAmount' => array(
          'type' => 'INT',
          'constraint' => 8
        ),
        'remark' => array(
          'type' => 'TEXT'
        ),
        'active' => array(
          'type' => 'BOOLEAN'
        ),
        'created_at' => array(
          'type' => 'DATETIME'
        ),
      ));

      $this->dbforge->add_key('chargeId', TRUE);
      $this->dbforge->create_table('extra_charges_tbl', TRUE);

      // Create vouchers_tbl
      $this->dbforge->add_field(array(
        'voucherId' => array(
          'type' => 'INT',
          'constraint' => 8,
          'auto_increment' => TRUE
        ),
        'vDate' => array(
          'type' => 'DATETIME'
        ),
        'vSerial' => array(
          'type' => 'VARCHAR',
          'constraint' => 22
        ),
        'extCharge' => array(
          'type' => 'INT',
          'constraint' => 8
        ),
        'chargeAmt' => array(
          'type' => 'INT',
          'constraint' => 8
        ),
        'supplier' => array(
          'type' => 'INT',
          'constraint' => 8
        ),
        'remark' => array(
          'type' => 'TEXT'
        ),
        'created_at' => array(
          'type' => 'DATETIME'
        )
      ));
        
      $this->dbforge->add_key('voucherId', TRUE);
      $this->dbforge->create_table('vouchers_tbl', TRUE);

      // Create damages_tbl
      $this->dbforge->add_field(array(
        'damageId' => array(
          'type' => 'INT',
          'constraint' => 8,
          'auto_increment' => TRUE
        ),
        'related_item_id' => array(
          'type' => 'INT',
          'constraint' => 8
        ),
        'qty' => array(
          'type' => 'INT',
          'constraint' => 8
        ),
        'remark' => array(
          'type' => 'TEXT'
        ),
        'created_at' => array(
          'type' => 'DATETIME'
        )
      ));

      $this->dbforge->add_key('damageId', TRUE);
      $this->dbforge->create_table('damages_tbl', TRUE);

      // Create printer_profile_tbl
      $this->dbforge->add_field(array(
        'printerId' => array(
          'type' => 'INT',
          'constraint' => 8,
          'auto_increment' => TRUE
        ),
        'printerType' => array(
          'type' => 'CHAR',
          'constraint' => 3
        ),
        'connectionType' => array(
          'type' => 'CHAR',
          'constraint' => 3
        ),
        'printerAddress' => array(
          'type' => 'VARCHAR',
          'constraint' => 255
        ),
      ));

      $this->dbforge->add_key('printerId', TRUE);
      $this->dbforge->create_table('printer_profile_tbl', TRUE);

      // Create payments_tbl
      $this->dbforge->add_field(array(
        'paymentId' => array(
          'type' => 'INT',
          'constraint' => 8,
          'auto_increment' => TRUE
        ),
        'customerId' => array(
          'type' => 'INT',
          'constraint' => 8
        ),
        'payAmt' => array(
          'type' => 'INT',
          'constraint' => 8
        ),
        'remark' => array(
          'type' => 'TEXT'
        ),
        'created_at' => array(
          'type' => 'DATETIME'
        ),
      ));

      $this->dbforge->add_key('paymentId', TRUE);
      $this->dbforge->create_table('payments_tbl', TRUE);
        
        // ------------ End of Create Tables ---------------
    }


	
  }
