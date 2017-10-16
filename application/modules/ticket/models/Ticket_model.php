<?php
class Ticket_model extends SYS_Model {       
    function __construct() {
        parent::__construct();
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
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

}