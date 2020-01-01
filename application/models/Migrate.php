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
        
        
        // ------------ End of Create Tables ---------------
    }


	
}