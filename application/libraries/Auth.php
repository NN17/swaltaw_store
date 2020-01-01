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

    public function migrate(){
        
        $fields = array(
            'authId' => array(
                'type' => 'INT',
                'constraint' => 5,
                'auto_increment' => TRUE
            ),
            'authName' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'authLevel' => array(
                'type' => 'INT',
                'constraint' => 5
            ),
            'permissionSys' => array(
                'type' => 'VARCHAR',
                'constraint' => 5
            ),
            'permissionUsr' => array(
                'type' => 'VARCHAR',
                'constraint' => 5
            ),
            'permissionLyt' => array(
                'type' => 'VARCHAR',
                'constraint' => 5
            ),
            'permissionCnt' => array(
                'type' => 'VARCHAR',
                'constraint' => 5
            ),
            'createdDate' => array(
                'type' => 'DATETIME',
                'null' => NULL
            ),
            'createdBy' => array(
                'type' => 'INT',
                'constraint' => 5,
                'null' => TRUE
            )
        );

        $this->CI->dbforge->add_key('authId', TRUE);
        $this->CI->dbforge->add_field($fields);
        $this->CI->dbforge->create_table('auth_tbl', TRUE);
    }

    public function insert($array){
        $system = '';
        $user = '';
        $layout = '';
        $content = '';
        // System permission
        if(isset($array['readSys'])){
            $system .= 1;
        }else{
            $system .= 0;
        }    
        if(isset($array['writeSys'])){
            $system .= 1;
        }else{
            $system .= 0;
        }
        if(isset($array['editSys'])){
            $system .= 1;
        }else{
            $system .= 0;
        }
        if(isset($array['delSys'])){
            $system .= 1;
        }else{
            $system .= 0;
        }

        // User permission
        if(isset($array['readUsr'])){
            $user .= 1;
        }else{
            $user .= 0;
        }
        if(isset($array['writeUsr'])){
            $user .= 1;
        }else{
            $user .= 0;
        }
        if(isset($array['editUsr'])){
            $user .= 1;
        }else{
            $user .= 0;
        }
        if(isset($array['delUsr'])){
            $user .= 1;
        }else{
            $user .= 0;
        }

        // Layout Permission
        if(isset($array['readLyt'])){
            $layout .= 1;
        }else{
            $layout .= 0;
        }
        if(isset($array['writeLyt'])){
            $layout .= 1;
        }else{
            $layout .= 0;
        }
        if(isset($array['editLyt'])){
            $layout .= 1;
        }else{
            $layout .= 0;
        }
        if(isset($array['delLyt'])){
            $layout .= 1;
        }else{
            $layout .= 0;
        }

        //Content permission
        if(isset($array['readCnt'])){
            $content .= 1;
        }else{
            $content .= 0;
        }
        if(isset($array['writeCnt'])){
            $content .= 1;
        }else{
            $content .= 0;
        }
        if(isset($array['editCnt'])){
            $content .= 1;
        }else{
            $content .= 0;
        }
        if(isset($array['delCnt'])){
            $content .= 1;
        }else{
            $content .= 0;
        }
        $date = date('Y-m-d');
        $insert = array(
            'authName' => $array['authName'],
            'authLevel' => $array['priority'],
            'permissionSys' => $system,
            'permissionUsr' => $user,
            'permissionLyt' => $layout,
            'permissionCnt' => $content,
            'createdDate' => $date,
            'createdBy' => $this->CI->session->userdata('Id')
        );
        if($this->CI->db->insert('auth_tbl', $insert)){
            return true;
        }
            else{
                return false;
            }
    }//end of get_permission()

    function get_form($url, $prevUrl = ''){
        $data['url'] = $url;
        $data['prevUrl'] = $prevUrl;
        $this->CI->load->view('Auth/authform', $data);
    }

    function create_role($name, $style){
        $permissions = $this->CI->db->query("SELECT authLevel, authName FROM auth_tbl")->result_array();
        $options[''] = '-- Select Role --';
        foreach($permissions as $row){
           $options[$row['authLevel']] = $row['authName'];
        }
        echo form_dropdown($name, $options,'', 'class="'.$style.'" required');
       
    }

    // Check System user Permission (read == R, write == W, edit == E, Delete == D)
    function system_authority($id, $type){
        $userInfo = $this->CI->ignite_model->get_limit_data('users_tbl', 'userId', $id)->row_array();
        $query = $this->CI->ignite_model->get_limit_data('auth_tbl', 'authLevel', $userInfo['level'])->row_array();
        $authType = $this->authType($type);
        if($query['permissionSys'][$authType] == 1 || $userInfo['level'] == 0){
            return true;
        }
    }

    // Check User management permission (read == R, write == W, edit == E, Delete == D)
    function user_authority($id, $type){
        $userInfo = $this->CI->ignite_model->get_limit_data('users_tbl', 'userId', $id)->row_array();
        $query = $this->CI->ignite_model->get_limit_data('auth_tbl', 'authLevel', $userInfo['level'])->row_array();
        $authType = $this->authType($type);
        if($query['permissionUsr'][0] == 1 || $userInfo['level'] == 0){
            return true;
        }
    }

    // Check Layout management permission (read == R, write == W, edit == E, Delete == D)
    function layout_authority($id, $type){
        $userInfo = $this->CI->ignite_model->get_limit_data('users_tbl', 'userId', $id)->row_array();
        $query = $this->CI->ignite_model->get_limit_data('auth_tbl', 'authLevel', $userInfo['level'])->row_array();
        $authType = $this->authType($type);
        if($query['permissionLyt'][$authType] == 1 || $userInfo['level'] == 0){
            return true;
        }
    }

    // Check Content management permission (read == R, write == W, edit == E, Delete == D)
    function content_authority($id, $type){
        $userInfo = $this->CI->ignite_model->get_limit_data('users_tbl', 'userId', $id)->row_array();
        $query = $this->CI->ignite_model->get_limit_data('auth_tbl', 'authLevel', $userInfo['level'])->row_array();
        $authType = $this->authType($type);
        if($query['permissionCnt'][$authType] == 1 || $userInfo['level'] == 0){
            return true;
        }
    }

    // Authority Type
    function authType($type){
        switch ($type){
            case 'R': 
                return 0;
                break;
            case 'W':
                return 1;
                break;
            case 'E':
                return 2;
                break;
            case 'D':
                return 3;
                break;
        }
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
}