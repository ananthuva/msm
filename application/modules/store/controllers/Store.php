<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Store extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Store_model');
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
    }
    
     /**
     * This function is used for show shop list
     * @return Void
     */
    public function index() {
        is_login();
        if (CheckPermission("stores", "own_read")) {
            $this->load->view('include/header');
            $this->load->view('store_table');
            $this->load->view('include/footer');
        } else {
            $this->session->set_flashdata('messagePr', 'You don\'t have permission to access.');
            redirect(base_url() . 'user/profile', 'refresh');
        }
    }
    /**
     * This function is used to authenticate api
     * @return String
     */
    public function ws_api() {
        if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
             echo json_encode(array('result' => 'false','error' => 'Request method must be POST!'));
             exit;
        }
        $content = json_decode(file_get_contents("php://input"));
        if(isset($_SERVER['HTTP_TOKEN'])){
            if(process_token($_SERVER['HTTP_TOKEN'])){
                $this->process_ws_api();
            } else {
                echo json_encode(array('result' => 'false','error' => 'Invalid Request Token'));
                exit;
            }
        } else {
            echo json_encode(array('result' => 'false','error' => 'Unauthorized Request'));
            exit;
        }
    }
    /**
     * This function is used to process api
     * @return String
     */
    public function process_ws_api() {
        if($this->input->get('mod') && !empty($this->input->get('mod'))){
            switch($this->input->get('mod')) {
                case 'nearby_stores' : $this->ws_getNearbyStores(); break;
                case 'create_stores' : $this->ws_createStores(); break;
                default: echo json_encode(array('result' => 'false','error' => 'Request syntax error'));
            }
        } else {
            echo json_encode(array('result' => 'false','error' => 'Invalid call'));
        }
        exit;
    }
    /**
     * This function is used for getting nearest stores
     * @return String
     */
    public function ws_getNearbyStores() {
        $content = json_decode(file_get_contents("php://input"));
        if(!empty($content->latitude) && !empty($content->longitude)) {
            $_POST['latitude'] = $content->latitude;
            $_POST['longitude'] = $content->longitude;
            $return = $this->Store_model->getNearbyStores();
            if (empty($return)) {
                echo json_encode(array('result' => 'false','error' => 'No stores found'));
            } else {
                echo str_replace(':null',':""',json_encode(array('result' => 'true','StoreData' =>$return)));
            }
        } else {
            echo json_encode(array('result' => 'false','error' => 'Invalid latitude or longitude'));
        }
        exit;
    }
    
    public function ws_createStores() {
        $content = json_decode(file_get_contents("php://input"));
        if(empty($content->name)) {
            echo json_encode(array('result' => 'false','error' => 'Invalid name'));
        } else if(empty($content->address)){
            echo json_encode(array('result' => 'false','error' => 'Invalid address'));
        } else if(empty($content->license_no)){
            echo json_encode(array('result' => 'false','error' => 'Invalid license no'));
        } else if(empty($content->poc)){
            echo json_encode(array('result' => 'false','error' => 'Invalid percent of commission'));
        } else if(empty($content->user_id)){
            echo json_encode(array('result' => 'false','error' => 'Invalid user'));
        } else if(empty($content->city_id)){
            echo json_encode(array('result' => 'false','error' => 'Invalid city'));
        } else if(empty($content->state_id)){
            echo json_encode(array('result' => 'false','error' => 'Invalid state'));
        } else if(empty($content->latitude)){
            echo json_encode(array('result' => 'false','error' => 'Invalid latitude'));
        } else if(empty($content->longitude)){
            echo json_encode(array('result' => 'false','error' => 'Invalid longitude'));
        } else {
            
//            $checkValue = $this->User_model->check_exists('users', 'email', $content->email);
//            if ($checkValue == false) {
//                echo json_encode(array('result' => 'false','error' => 'Email Already Registered'));
//                exit;
//            }
            $data['is_active'] = 1;
            $data['name'] = $content->name;
            $data['address'] = $content->address;
            $data['license_no'] = $content->license_no;
            $data['poc'] = $content->poc;
            $data['user_id'] = $content->user_id;
            $data['city_id'] = $content->city_id;
            $data['state_id'] = $content->state_id;
            $data['latitude'] = $content->latitude;
            $data['longitude'] = $content->longitude;
            $store_id = $this->Store_model->create('stores', $data);
            $data['store_id'] = $store_id;
            unset($data['is_active']);
            unset($data['created_by']);
            unset($data['last_modified_by']);
            unset($data['created_on']);
            unset($data['last_modified_on']);
            echo str_replace(':null',':""',json_encode(array('result' => 'true','StoreData' => $data)));
        }
        exit;
    }
    
     /**
     * This function is used for add a shop
     * @return Void
     */
    public function createStores() {
        is_login();
        if (CheckPermission("stores", "own_create")) {
            $this->load->view('include/header');
            $this->load->view('add_store');
            $this->load->view('include/footer');
        } else {
            $this->session->set_flashdata('messagePr', 'You don\'t have permission to access.');
            redirect(base_url() . 'user/profile', 'refresh');
        }
    }
    
    /**
     * This function is used to create datatable in users list page
     * @return Void
     */
    public function getStoreList() {
        is_login();
        $table = 'stores';
        $primaryKey = 'id';
        $columns = array(
            array('db' => 'id', 'dt' => 0), array('db' => 'is_active', 'dt' => 1),
            array('db' => 'name', 'dt' => 2),
            array('db' => 'license_no', 'dt' => 3),
            array('db' => 'poc', 'dt' => 4),
            array('db' => 'id', 'dt' => 5)
        );

        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db' => $this->db->database,
            'host' => $this->db->hostname
        );
        $where = array("is_deleted != 1");
        $output_arr = SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where);
        
        foreach ($output_arr['data'] as $key => $value) {
            $id = $output_arr['data'][$key][count($output_arr['data'][$key]) - 1];
            $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] = '';
            if (CheckPermission($table, "all_update")) {
                $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] .= '<a id="btnEditRow" class="mClass"  href="javascript:;" type="button" title="View/Edit"><i class="fa fa-eye" data-id=""></i></a>';
            } else if (CheckPermission($table, "own_update") && (CheckPermission($table, "all_update") != true)) {
                $user_id = getRowByTableColomId($table, $id, 'user_id', 'user_id');
                if ($user_id == $this->user_id) {
                    $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] .= '<a id="btnEditRow" class="mClass"  href="javascript:;" type="button" title="View/Edit"><i class="fa fa-eye" data-id=""></i></a>';
                }
            }

            if (CheckPermission($table, "all_delete")) {
                $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] .= '<a style="cursor:pointer;" class="mClass" data-toggle="modal" onclick="setId(' . $id . ', \'store\')" data-target="#cnfrm_delete" title="delete"><i class="fa fa-trash-o" ></i></a>';
            } else if (CheckPermission($table, "own_delete") && (CheckPermission($table, "all_delete") != true)) {
                $user_id = getRowByTableColomId($table, $id, 'user_id', 'user_id');
                if ($user_id == $this->user_id) {
                    $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] .= '<a style="cursor:pointer;" class="mClass" data-toggle="modal" onclick="setId(' . $id . ', \'store\')" data-target="#cnfrm_delete" title="delete"><i class="fa fa-trash-o" ></i></a>';
                }
            }
            $output_arr['data'][$key][0] = '<input type="checkbox" name="selData" value="' . $output_arr['data'][$key][0] . '">';
        }

        echo json_encode($output_arr);
    }
    
    /**
     * This function is used to delete stores
     * @return Void
     */
    public function delete($id) {
        is_login();
        $ids = explode('-', $id);
        foreach ($ids as $id) {
            $this->Store_model->delete($id);
        }
        $this->session->set_flashdata('messagePr', 'Deleted Successfully');
        redirect(base_url() . 'store', 'refresh');
    }
    
}
