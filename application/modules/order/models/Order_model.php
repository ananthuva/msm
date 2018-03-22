<?php

class Order_model extends SYS_Model {

    function __construct() {
        parent::__construct();
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
    }

    /**
     * This function is used to delete
     * @param: $id - id of user table
     */
    function delete($id = '') {
        $this->db->where('user_id', $id);
        $this->db->delete('users');
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
     * This function is used to check is alredy exist or not  
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
        if (!empty($col2)) {
            $this->db->where($col2, $col2Val);
        }
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }
    
      /**
     * This function is used to get order list 
     */
    public function getOrderList($limit = '', $offset = '') {
        $limit = empty($limit) ? 100 : $limit;
        $offset = empty($limit) ? 0 : $offset;
        
        $this->db->select('o.id,o.order_bill_id,s.name as store_name,o.note,os.order_status_name,CONCAT_WS(" ",u.name,u.lname) as user_name,'
                . ' o.order_date,o.payment_type,o.payment_status,o.created_on');
        $this->db->from('order o');
        $this->db->join('table_order_status os', 'os.order_status_id = o.status', 'left');
        $this->db->join('stores s', 's.id = o.store_id', 'left');
        $this->db->join('users u', 'u.user_id = o.user_id', 'left');
        $this->db->order_by('o.created_on','DESC');
        $this->db->limit($limit,$offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * This function is used to get order details 
     */
    public function getOrderdetails($id) {
        $this->db->select('o.id,o.order_bill_id,s.name as store_name,o.note,os.order_status_name,CONCAT_WS(" ",u.name,u.lname) as user_name,'
                . ' o.order_date,o.payment_type,o.payment_status,o.created_on,o.user_id');
        $this->db->where('o.id', $id);
        $this->db->from('order o');
        $this->db->join('table_order_status os', 'os.order_status_id = o.status', 'left');
        $this->db->join('stores s', 's.id = o.store_id', 'left');
        $this->db->join('users u', 'u.user_id = o.user_id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * This function is used to get order BillingA ddress
     */
    public function getOrderBillingAddress($id) {
        $this->db->select('b.order_id,b.full_name,b.mobile,b.house_name,b.street,b.postoffice,b.pin,'
                . 'b.state as state_name,b.country');
        $this->db->where('b.order_id', $id);
        $this->db->from('billing_address b');
//        $this->db->join('state s', 's.id = b.state_id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * This function is used to get Delivery Address 
     */
    public function getOrderDeliveryAddress($id) {
        $this->db->select('d.order_id,d.full_name,d.mobile,d.house_name,d.street,d.postoffice,d.pin,'
                . 'd.state as state_name,d.country,d.latitude,d.longitude');
        $this->db->where('d.order_id', $id);
        $this->db->from('delivery_address d');
//        $this->db->join('state s', 's.id = d.state_id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }
    /**
     * This function is used to get order attachments 
     */
    public function getOrderAttachment($id) {
        $this->db->select('attachment');
        $this->db->where('order_id', $id);
        $this->db->from('attachment');
        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * This function is used to get order histories 
     */
    public function getOrderHistory($id) {
        $this->db->select('s.name as store_name,os.order_status_name,h.created_on,CONCAT_WS(" ",u.name,u.lname) as user_name');
        $this->db->where('h.order_id', $id);
        $this->db->from('order_history h');
        $this->db->join('table_order_status os', 'os.order_status_id = h.order_status', 'left');
        $this->db->join('stores s', 's.id = h.store_id', 'left');
        $this->db->join('users u', 'u.user_id = h.created_by', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * This function is used to get ShippingAddress
     */
    public function getShippingAddress($id) {
        $this->db->select('d.full_name,d.mobile,d.house_name,d.street,d.postoffice,d.pin,'
                . 'd.state as state_name');
        $this->db->where('d.user_id', $id);
        $this->db->from('user_shipping_address d');
//        $this->db->join('state s', 's.id = d.state_id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

}
