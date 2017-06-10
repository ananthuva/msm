<?php
class User_model extends CI_Model {       
    function __construct() {
        parent::__construct();
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
    }

    /**
      * This function is used authenticate user at login
      */
    function auth_user() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $this->db->where("is_deleted='0' AND (email='$email')");
        $result = $this->db->get('users')->result();
        if(empty($result)){
            if (substr($email, 0, 3) == '+91') {
                $email = substr($email, 3);
            } elseif (substr($email, 0, 2) == '91') {
                $email = substr($email, 2);
            }
            $email = '+91'.$email;
            $this->db->where("is_deleted='0' AND (mobile_no='$email')");
            $result = $this->db->get('users')->result();
        }
        if (!empty($result)) {
            if (password_verify($password, $result[0]->password)) {
                if ($result[0]->status != 'active') {
                    return 'not_verified';
                } else if ($result[0]->is_verified != 1){
                    return array('number_not_verified'=>$result[0]->user_id);
                }
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * This function is used to delete user
     * @param: $id - id of user table
     */
    function delete($id = '') {
        $this->db->where('user_id', $id);
        $this->db->delete('users');
    }

    /**
     * This function is used to load view of reset password and verify user too 
     */
    function mail_verify() {
        $ucode = $this->input->get('code');
        $this->db->select('email as e_mail');
        $this->db->from('users');
        $this->db->where('var_key', $ucode);
        $query = $this->db->get();
        $result = $query->row();
        if (!empty($result->e_mail)) {
            return $result->e_mail;
        } else {
            return false;
        }
    }

    /**
     * This function is used Reset password  
     */
    function ResetPpassword() {
        $email = $this->input->post('email');
        if ($this->input->post('password_confirmation') == $this->input->post('password')) {
            $npass = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $data['password'] = $npass;
            $data['var_key'] = '';
            return $this->db->update('users', $data, "email = '$email'");
        }
    }

    /**
     * This function is used to select data form table  
     */
    function get_data_by($tableName = '', $value = '', $colum = '') {
        if ((!empty($value)) && (!empty($colum))) {
            $this->db->where($colum, $value);
        }
        $this->db->select('*');
        $this->db->from($tableName);
        $query = $this->db->get();
        return $query->result();
    }
    /**
     * This function is used to verify mobile number from otp  
     */
    function verifyMobileNumber($otp, $user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('var_otp', $otp);
        $this->db->where('is_verified', 0);
        $this->db->select('*');
        $this->db->from('users');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to check user is alredy exist or not  
     */
    function check_exists($table = '', $colom = '', $colomValue = '') {
        $this->db->where($colom, $colomValue);
        $res = $this->db->get($table)->row();
        if (!empty($res)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * This function is used to get users detail  
     */
    function get_users($userID = '') {
        $this->db->where('is_deleted', '0');
        if (isset($userID) && $userID != '') {
            $this->db->where('user_id', $userID);
        } else if ($this->session->userdata('user_details')[0]->user_type == 'admin') {
            $this->db->where('user_type', 'admin');
        } else {
            $this->db->where('users.user_id !=', '1');
        }
        $result = $this->db->get('users')->result();
        return $result;
    }

    /**
     * This function is used to get email template  
     */
    function get_template($code) {
        $this->db->where('code', $code);
        return $this->db->get('templates')->row();
    }

    /**
     * This function is used to Insert record in table  
     */
    public function insertRow($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * This function is used to Update record in table  
     */
    public function updateRow($table, $col, $colVal, $data, $col2 = '', $col2Val = '') {
        $this->db->where($col, $colVal);
        if(!empty($col2)){
            $this->db->where($col2, $col2Val);
        }
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }
    
    /**
     * This function is used to Update record in table  
     */
    public function get_mobile_number($user_id) {
        $this->db->select('mobile_no');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get()->row();
        $query = (!empty($query)) ? ((array)$query) : array('mobile_no' => 0);
        return $query;
    }

}