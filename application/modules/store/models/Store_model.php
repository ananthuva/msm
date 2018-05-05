<?php
class Store_model extends SYS_Model {       
    function __construct() {
        parent::__construct();
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
    }

    /**
      * This function is used to get stores by latitude and longitude
      */
    function getNearbyStores($dist = '') {
        $dist = ($dist != '') ? $dist : 5;
        $latitude = ($this->input->post('latitude')) ? (double)$this->input->post('latitude') : 0;
        $longitude = ($this->input->post('longitude')) ? (double)$this->input->post('longitude') : 0;
        $query = 'SELECT id, name , user_id, latitude, longitude,(6371 * ACOS(COS(RADIANS('.$latitude.')) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS('.$longitude.')) +
            SIN( RADIANS('.$latitude.') ) * SIN( RADIANS(latitude) ))) AS distance 
            FROM stores HAVING distance < '. $dist  .' ORDER BY distance LIMIT 0 , 20';
        return $result = $this->db->query($query)->result_array();
    }

    /**
     * This function is used to delete stores
     * @param: $id - id of user table
     */
    function delete($id = '') {
        $data = array('is_deleted'=>1);
        $this->db->where('id', $id);
        $this->db->update('stores', $data);
    }
    
    /**
     * This function is used to delete agreement
     * @param: $id - id of user table
     */
    function deleteAgreement($id,$name) {
        $this->db->where('id', $id);
        $this->db->select('agreement');
        $this->db->from('stores');
        $result = $this->db->get()->row();
        if(!empty($result)){
            $agreement = $result->agreement;
            $agreement = explode(',',$agreement);
            while(($i = array_search($name, $agreement)) !== false) {
                unset($agreement[$i]);
            }
            $agreement = implode(',',$agreement);
            $this->db->where('id', $id);
            $this->db->update('stores', array('agreement'=>$agreement));
            return true;
        }
        return false;
    }


    /**
     * This function is used to select data form table  
     */
    function get_data_by($tableName = '', $value = '', $colum = '',$select = '') {
        if ((!empty($value)) && (!empty($colum))) {
            $this->db->where($colum, $value);
        }
        if(empty($select)) {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        $this->db->from($tableName);
        $query = $this->db->get();
        return $query->result();
    }
    /**
     * This function is used to select data form table  
     */
    function getStoreUsers() {
        $this->db->where('user_type', 'pharmacist');
        $this->db->where('is_deleted', '0');
        $this->db->where('status', 'active');
        $this->db->select('CONCAT_WS(" ",name,lname ) AS user_name,user_id');
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
        $this->db->set('last_modified_on', 'NOW()', FALSE);
        $data['last_modified_by'] = $this->user_id ;
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }
    
    public function getAllStores(){
        $this->db->where('is_deleted', 0);
        $this->db->where('is_active', 1);
        $this->db->select('s.id, s.name, s.latitude as lat, s.longitude as lng, s.address, d.name as city,'
                . ' st.name as state, s.postal, s.contact_number_1 as phone');
        $this->db->from('stores s');
        $this->db->join('district d','s.district_id = d.id','left');
        $this->db->join('state st','s.state_id = st.id','left');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function getAlldata($tableName) {
        $this->db->select('*');
        $this->db->from($tableName);
        $query = $this->db->get();
        return $query->result();
    }
    
        /**
     * Function to get store list
     * @param type $friendly_url
     * @return type array
     */
    function getStoreList($data) {

        $keyword = $data['q'];
        $keyword = explode('(', $keyword);
        $keyword = trim($keyword[0]);

        $limit = $data['s'];
        $offset = ($data['p'] > 1) ? (($data['p'] - 1) * $data['s']) : 0;
        $this->db->select('id, name');
        $this->db->from('stores');
        if (!empty($keyword)) {
            $this->db->or_where("name LIKE '$keyword%'");
        }
        $this->db->where('user_id is NOT NULL', NULL, FALSE);
        $this->db->limit($limit, $offset);
        $qry = $this->db->get();
        $result = $qry->result();
        return $result;
    }

}