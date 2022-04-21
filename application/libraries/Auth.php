<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{
	protected $CI;

	public function __construct()
	{
        $this->CI =& get_instance();

        $this->CI->load->library('session');
        $this->CI->load->helper('url');
        $this->CI->load->dbforge();
    }

    

    // Password Hashing Function
    function hash_password($password){
        $options = [
            'cost' => 12,
        ];
        $psw = password_hash($password, PASSWORD_BCRYPT, $options);
        return $psw;
    }

    // Password Verifying Function
    function verify_password($password, $hash){
        
        if(password_verify($password, $hash)){
            return true;
        }
            else{
                return false;
            }
    }

    // Get User Agent
    function get_agent(){
        $this->CI->load->library('user_agent');

        if ($this->CI->agent->is_browser())
        {
                $agent = $this->CI->agent->browser().' '.$this->CI->agent->version();
        }
        elseif ($this->CI->agent->is_robot())
        {
                $agent = $this->CI->agent->robot();
        }
        elseif ($this->CI->agent->is_mobile())
        {
                $agent = $this->CI->agent->mobile();
        }
        else
        {
                $agent = 'Unidentified User Agent';
        }

        return $agent;
    }

    // Get Mac Address
    function get_macAddr(){
        ob_start(); // Turn on output buffering
        system('ipconfig /all'); //Execute external program to display output
        $mycom=ob_get_contents(); // Capture the output into a variable
        ob_clean(); // Clean (erase) the output buffer

        $findme = 'Physical';
        $pmac = strpos($mycom, $findme); // Find the position of Physical text
        $mac=substr($mycom,($pmac+36),17); // Get Physical Address

        return $mac;
    }

    // Adding Login User Info
    function add_loginInfo($userId){
        /* 
        * Create Table if not exist user_tracking_tbl
        */
        $this->CI->load->dbforge();

        $this->CI->dbforge->add_field(array(
            'trackId' => array(
                'type' => 'INT',
                'constraint' => 5,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 5,
                'null' => FALSE
            ),
            'sessionId' => array(
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => FALSE
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => 45
            ),
            'agent' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'os' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'start' => array(
                'type' => 'DATETIME'
            ),
            'end' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        ));

        $this->CI->dbforge->add_key('trackId', TRUE);
        $this->CI->dbforge->create_table('user_tracking_tbl', TRUE);

        $insert = array(
            'userId' => $userId,
            'sessionId' => session_id(),
            'ip_address' => $this->CI->input->ip_address(),
            'agent' => $this->get_agent(),
            'os' => $this->CI->agent->platform(),
            'start' => date('Y-m-d H:i:s')
        );

        if($this->CI->db->insert('user_tracking_tbl', $insert)){
            return true;
        }
    }

    /* 
    * Redirect user
    */
    function redirect($userId, $refer){
        $this->CI->db->where('accountId', $userId);
        $userInfo = $this->CI->db->get('accounts_tbl')->row_array();
        // if(!empty($refer)){
        //     redirect($refer);
        // }
        // else{

            if($userInfo['role'] == 0 || $userInfo['role'] == 1){
                redirect('Admin');
            } 
            elseif($userInfo['role'] == 2 || $userInfo['role'] == 3){
                redirect('User');
            }
            elseif($userInfo['role'] == 4){
                redirect('Dispatch');
            }
        // }
    }

    /* 
    * Check Authorize
    */
    function check_authorize($role,$accId){
        $this->CI->db->where('accountId', $accId);
        $userInfo = $this->CI->db->get('accounts_tbl')->row_array();
        if($role == 'admin' && $userInfo['role'] < 2){
            return true;
        }
        elseif($role == 'user' && $userInfo['role'] < 4){
            return true;
        }
        elseif(($role == 'dispatch' && $userInfo['role'] == 4) || $userInfo['role'] == 0){
            return true;
        }
        else{
            return false;
        }
    }

    // Check Permission
    function checkLinkAccess($userId, $link) {
        $user = $this->CI->ignite_model->get_limit_data('accounts_tbl', 'accId', $userId)->row();
        $access = $this->CI->ignite_model->get_limit_data('permission_tbl', 'accId', $userId)->row();

        $res = false;
        if($user->role > 0){
            $array = json_decode($access->link);

            foreach($array as $arr => $value){
                if($arr == $link && $value == true){
                    $res = true;
                }
            }
        }
            else{
                $res = true;
            }

        return $res;
    }

    // Check Permission
    function checkModify($userId, $machine) {
        $user = $this->CI->ignite_model->get_limit_data('accounts_tbl', 'accId', $userId)->row();
        $access = $this->CI->ignite_model->get_limit_data('permission_tbl', 'accId', $userId)->row();
        $link = $this->CI->ignite_model->get_limit_data('link_structure_tbl', 'machine', $machine)->row();

        $res = false;
        if($user->role > 0){
            $array = json_decode($access->modify);

            foreach($array as $arr => $value){
                if($arr == $link->linkId && $value == true){
                    $res = true;
                }
            }
        }
            else{
                $res = true;
            }

        return $res;
    }

}//end of Auth Class