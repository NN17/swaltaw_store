<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Breadcrumb
{
	protected $CI;
	protected $arrLink;
    protected $arrUrl;

	public function __construct()
	{
        $this->CI =& get_instance();

        $this->CI->load->library('session');
        $this->CI->load->helper('url');
	}

	function add($name, $url=''){
        
        if(empty($this->arrLink)){
            $this->arrLink = array($name);
            $this->arrUrl = array($url);
        }
        else{
            array_push($this->arrLink, $name);
            array_push($this->arrUrl, $url);
        }
    }

    function show(){
    	if(!empty($this->arrLink)){

	        $count = count($this->arrLink);
	        for($i=0; $i<$count; $i++){
	        	if(empty($this->arrUrl[$i])){
	        		echo '<div class="active section">'.$this->arrLink[$i].'</div>';
	        	}else{
	            	echo '<a class="section" href="'.$this->arrUrl[$i].'">'.$this->arrLink[$i].'</a>';
	            	if($i < $count){
	            		echo '<i class="right angle icon divider"></i>';
	            	}
	        	}
	        }                   
    	}
    }

	
}

/* End of file Ignite.php */
/* Location: ./application/libraries/Ignite.php */
