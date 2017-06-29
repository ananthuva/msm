<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Store extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Store_model');
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
    }

    /**
     * This function is redirect to users profile page
     * @return Void
     */
    public function index() {
        if (is_login()) {
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
        if(isset($content->token)){
            if(process_token($content->token)){
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
    
}
