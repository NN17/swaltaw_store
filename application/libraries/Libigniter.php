<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Libigniter
{
	protected $CI;

	public function __construct()
	{
        $this->CI =& get_instance();

        $this->CI->load->library('session');
        $this->CI->load->helper('url');
	}

	public function user($userId){
		$query = $this->CI->ignite_model->get_limit_data('users_tbl', 'userId', $userId)->row_array();
		echo $query['name'];
	}

	

	// Random Alpha Numeric function

	public function izrand($length) {

        $random_string="";
        while(strlen($random_string)<$length && $length > 0) {
                $randnum = mt_rand(0,61);
                $random_string .= ($randnum < 10) ?
                        chr($randnum+48) : ($randnum < 36 ? 
                                chr($randnum+55) : $randnum+61);
         }
        return $random_string;
	}


	// *********************** Create QR Codes ***************************

	public function create_qr($prodCode){
		$this->CI->load->library('ciqrcode');

		$config['cacheable']	= true; //boolean, the default is true
		$config['cachedir']		= ''; //string, the default is application/cache/
		$config['errorlog']		= ''; //string, the default is application/logs/
		$config['quality']		= true; //boolean, the default is true
		$config['size']			= ''; //interger, the default is 1024
		$config['black']		= array(224,255,255); // array, default is array(255,255,255)
		$config['white']		= array(70,130,180); // array, default is array(0,0,0)
		$this->CI->ciqrcode->initialize($config);

		header("Content-Type: image/png");

		$params['data'] = $prodCode;
		$params['level'] = 'H';
		$params['size'] = 10;
		$params['savename'] = FCPATH.'qrcodes/'.$prodCode.'.png';
		$this->CI->ciqrcode->generate($params);

		// echo '<img src="'.base_url().'tes.png" />';
	}

	// ************************************************************

	// ******************* Create Barcode **********************

	public function create_barcode($prodCode){
		$this->CI->load->library('zend');
		//load in folder Zend
		$this->CI->zend->load('Zend/Barcode');
		//generate barcode
		// Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
		$imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$prodCode), array())->draw();
		imagepng($imageResource, FCPATH.'barcodes/'.$prodCode.'.png');
	}

	// **********************************************************

	
}

/* End of file Ignite.php */
/* Location: ./application/libraries/Ignite.php */
