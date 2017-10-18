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
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0) {
            echo json_encode(array('status' => 'false', 'message' => 'Request method must be POST!'));
            exit;
        }
        $content = json_decode(file_get_contents("php://input"));
        if (isset($_SERVER['HTTP_TOKEN'])) {
            if (process_token($_SERVER['HTTP_TOKEN'])) {
                $this->process_ws_api();
            } else {
                echo json_encode(array('status' => 'false', 'message' => 'Invalid Request Token'));
                exit;
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'Unauthorized Request'));
            exit;
        }
    }

    /**
     * This function is used to process api
     * @return String
     */
    public function process_ws_api() {
        if ($this->input->get('mod') && !empty($this->input->get('mod'))) {
            switch ($this->input->get('mod')) {
                case 'nearby_stores' : $this->ws_getNearbyStores();
                    break;
                case 'create_stores' : $this->ws_createStores();
                    break;
                default: echo json_encode(array('status' => 'false', 'message' => 'Request syntax error'));
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid call'));
        }
        exit;
    }

    /**
     * This function is used for getting nearest stores
     * @return String
     */
    public function ws_getNearbyStores() {
        $content = json_decode(file_get_contents("php://input"));
        if (!empty($content->latitude) && !empty($content->longitude)) {
            $_POST['latitude'] = $content->latitude;
            $_POST['longitude'] = $content->longitude;
            $return = $this->Store_model->getNearbyStores();
            if (empty($return)) {
                echo json_encode(array('status' => 'false', 'message' => 'No stores found'));
            } else {
                echo str_replace(':null', ':""', json_encode(array('status' => 'true', 'Data' => $return)));
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid latitude or longitude'));
        }
        exit;
    }

    public function ws_createStores() {
        $content = json_decode(file_get_contents("php://input"));
        if (empty($content->name)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid name'));
        } else if (empty($content->address)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid address'));
        } else if (empty($content->license_no)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid license no'));
        } else if (empty($content->poc)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid percent of commission'));
        } else if (empty($content->user_id)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid user'));
        } else if (empty($content->city_id)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid city'));
        } else if (empty($content->state_id)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid state'));
        } else if (empty($content->latitude)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid latitude'));
        } else if (empty($content->longitude)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid longitude'));
        } else {

//            $checkValue = $this->User_model->check_exists('users', 'email', $content->email);
//            if ($checkValue == false) {
//                echo json_encode(array('status' => 'false','message' => 'Email Already Registered'));
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
            echo str_replace(':null', ':""', json_encode(array('status' => 'true', 'Data' => $data)));
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
            $data['users'] = $this->Store_model->getStoreUsers();
            $data['states'] = $this->Store_model->get_data_by('state');
            $this->load->view('add_store', $data);
            $this->load->view('include/footer');
        } else {
            $this->session->set_flashdata('messagePr', 'You don\'t have permission to access.');
            redirect(base_url() . 'user/profile', 'refresh');
        }
    }

    /**
     * This function is used for edit a shop
     * @return Void
     */
    public function editStores($id = '') {
        is_login();
        if (CheckPermission("stores", "own_update")) {
            $this->load->view('include/header');
            $data['users'] = $this->Store_model->getStoreUsers();
            $data['states'] = $this->Store_model->get_data_by('state');
            $storeData = $this->Store_model->get_data_by('stores', $id, 'id');
            $data['storeData'] = (!empty($storeData)) ? $storeData[0] : '';
            $this->load->view('add_store', $data);
            $this->load->view('include/footer');
        } else {
            $this->session->set_flashdata('messagePr', 'You don\'t have permission to access.');
            redirect(base_url() . 'user/profile', 'refresh');
        }
    }

    /**
     * This function is used for getting districts
     * @return Void
     */
    public function getDistricts() {
        if (isset($_POST["state_id"]) && !empty($_POST["state_id"])) {
            $state_id = $_POST["state_id"];
            $district = $this->Store_model->get_data_by('district', $state_id, 'state_id');
            $jsonData = array();
            foreach ($district as $key => $value) {
                $jsonData[$key]['id'] = $value->id;
                $jsonData[$key]['name'] = $value->name;
            }
            echo json_encode($jsonData);
            exit;
        }
    }

    /**
     * This function is used to add and update stores
     * @return Void
     */
    public function add_edit($id = '') {
        $data = $this->input->post();
        $files = array();
        
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $redirect = 'editStores';
        if (empty($id)) {
            $redirect = 'createStores';
        }
        
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('user_id', 'Owner', 'trim|required');
        $this->form_validation->set_rules('license_no', 'License Number', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('is_active', 'Status', 'trim|required');
        $this->form_validation->set_rules('state_id', 'State', 'trim|required');
        $this->form_validation->set_rules('district_id', 'District', 'trim|required');
        $this->form_validation->set_rules('poc', 'Percent of Commission', 'trim|required|callback_numeric_val');

        if ($this->form_validation->run() === TRUE) {
            
            $agreement = $_FILES['agreement'];
            if(!empty($agreement)) {
                $agreement = $this->rearrange($agreement);
                foreach ($agreement as $file) {
                    if (!empty($file['name'])) {
                        $newname = $this->upload($file);
                        $files[] = $newname;
                    }
                }
            }
            $agreements = implode(',', $files);
            if(!empty($agreements)) {
                if(!empty($id)) {
                    $agr = $this->Store_model->get_data_by('stores', $id, 'id', 'agreement');
                    if(isset($agr[0]->agreement) && !empty($agr[0]->agreement)) {
                        $agreements = $agr[0]->agreement.','.$agreements;
                    }
                }
                $data['agreement'] = $agreements;
            }
            
            if ($id != '') {
                unset($data['edit']);
                $this->Store_model->updateRow('stores', 'id', $id, $data);
                $this->session->set_flashdata('messagePr', 'Data updated Successfully.');
                redirect(base_url() . 'store', 'refresh');
            } else {
                unset($data['store_id']);
                unset($data['submit']);
                $user_id = $this->Store_model->create('stores', $data);
                $this->session->set_flashdata('messagePr', 'Store added Succesfully.');
                redirect(base_url() . 'store', 'refresh');
            }
        }
        $this->session->set_flashdata('messagePr', validation_errors());
        redirect(base_url() . 'store/' . $redirect, 'refresh');
    }

    function numeric_val($str) {
        $check = preg_match('/^[0-9.]+$/', $str);
        if ($check == FALSE) {
            $this->form_validation->set_message('numeric_val', 'The {field} field must be a number');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function rearrange( $file ){
        $file_ary = array();
        $file_count = count($file['name']);
        $file_key = array_keys($file);

        for($i=0;$i<$file_count;$i++)
        {
            foreach($file_key as $val)
            {
                $file_ary[$i][$val] = $file[$val][$i];
            }
        }
        return $file_ary;
    }

    /**
     * This function is used to upload file
     * @return Void
     */
    function upload($file) {
        $filename = $file['name'];
        $tmpname = $file['tmp_name'];
        $exp = explode('.', $filename);
        $ext = end($exp);
        $newname = str_replace(',', '', $exp[0]) . '_' . time(). mt_rand(1,100) . "." . $ext;
        if (!file_exists('uploads/agreement')) {
            mkdir('uploads/agreement', 0777, true);
        }
        $config['upload_url'] = base_url() . 'uploads/agreement/';
        $config['allowed_types'] = "jpg|jpeg|png|doc|docx|xls|xlsx|ppt|pptx|csv|ods|odt|odp|pdf|rtf|sxc|sxi|txt";
        $config['max_size'] = '2000000';
        $config['file_name'] = $newname;
        $this->load->library('upload', $config);
        move_uploaded_file($tmpname, "uploads/agreement/" . $newname);
        return $newname;
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
                $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] .= '<a id="btnEditRow" class="mClass"  href="' . base_url() . 'store/editStores/' . $id . '" type="button" title="View/Edit"><i class="fa fa-eye"></i></a>';
            } else if (CheckPermission($table, "own_update") && (CheckPermission($table, "all_update") != true)) {
                $user_id = getRowByTableColomId($table, $id, 'user_id', 'user_id');
                if ($user_id == $this->user_id) {
                    $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] .= '<a id="btnEditRow" class="mClass"  href="' . base_url() . 'store/editStores/' . $id . '" type="button" title="View/Edit"><i class="fa fa-eye"></i></a>';
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
    /**
     * This function is used to delete Agreement
     * @return Void
     */
    public function deleteAgreement($id,$name) {
        is_login();
        $delete = $this->Store_model->deleteAgreement($id,$name);
        if($delete){
            unlink('uploads/agreement/'.$name);
            $this->session->set_flashdata('messagePr', 'Deleted Successfully');
        } else {
            $this->session->set_flashdata('messagePr', 'Unable to delete');
        }
        redirect(base_url() . 'store/editStores/'.$id, 'refresh');
    }
    
    /**
     * This function is used to search Stores with google map
     * @return Void
     */
    public function searchStoresMap() {
        is_login();
        $this->load->view('include/header');
        $this->load->view('storeLocator');
        $this->load->view('include/footer');
    }
    
    /**
     * This function is used to get List of Stores
     * @return Void
     */
    public function getStoresMap() {
        is_login();
        $stores = $this->Store_model->getAllStores();
        echo json_encode($stores); 
        /*echo '[{
        "id": "1",
        "name": "Chipotle Minneapolis",
        "lat": "44.947464",
        "lng": "-93.320826",
        "address": "3040 Excelsior Blvd",
        "address2": "",
        "city": "Minneapolis",
        "state": "MN",
        "postal": "55416",
        "phone": "612-922-6662",
        "web": "http:\/\/www.chipotle.com",
        "hours1": "Mon-Sun 11am-10pm",
        "hours2": "",
        "hours3": ""
    }]';*/
        exit;
    }

}
