<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Order extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
    }

    /**
     * This function is redirect to users profile page
     * @return Void
     */
    public function index() {
        is_login();
        if (CheckPermission("orders", "own_read")) {
            $this->load->view('include/header');
            $this->load->view('order_table');
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
        $this->process_ws_api();
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
                case 'order_medicine' : $this->ws_oderMedicine();
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
    public function ws_oderMedicine() {
        //{"a":"1","attachment":["s","t"]}
        $content = json_decode(file_get_contents("php://input"));
        $_POST = (array) $content;
        $rules = array(
            [ 'field' => 'store_id', 'label' => 'store id', 'rules' => 'required'],
            [ 'field' => 'user_id', 'label' => 'user id', 'rules' => 'required',],
            [ 'field' => 'delivery_full_name', 'label' => 'Name in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_mobile', 'label' => 'mobile in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_house_name', 'label' => 'House Name in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_street', 'label' => 'Street in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_post', 'label' => 'Postoffice in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_pin', 'label' => 'PIN in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_state_id', 'label' => 'State Id in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'latitude', 'label' => 'latitude', 'rules' => 'required',],
            [ 'field' => 'longitude', 'label' => 'longitude', 'rules' => 'required',],
            [ 'field' => 'billing_full_name', 'label' => 'Name in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_mobile', 'label' => 'Mobile in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_house_name', 'label' => 'House Name in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_street', 'label' => 'Street in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_post', 'label' => 'Postoffice in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_pin', 'label' => 'PIN in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_state_id', 'label' => 'State Id in Billing Address', 'rules' => 'required',],
            [ 'field' => 'note', 'label' => 'note', 'rules' => 'required',],
        );
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            $errors = preg_replace("/\r|\n/", "", validation_errors(" ", " "));
            $errors = ltrim(explode('.',$errors)[0]);
            echo json_encode(array('status' => 'false', 'message' => $errors));
        } else {
            $error = '';
            if(isset($_POST['attachment'])) {
                foreach($_POST['attachment'] as $attach){
                    $error = $this->verify_attachment($attach);
                    if(!empty($error)){
                        echo json_encode(array('status' => 'false', 'message' => $error ));
                        break;
                    }
                }
            }
            if(empty($error)) {
                $order['order_bill_id'] = 'Ord-' . date('YmdHis');
                $order['store_id'] = $content->store_id;
                $order['note'] = $content->note;
                $order['user_id'] = $content->user_id;
                $order['order_date'] = date('Y-m-d');
                $order_id = $this->Order_model->create('order', $order);
                $delivery['order_id'] = $order_id;
                $delivery['full_name'] = $content->delivery_full_name;
                $delivery['mobile'] = $content->delivery_mobile;
                $delivery['house_name'] = $content->delivery_house_name;
                $delivery['street'] = $content->delivery_street;
                $delivery['postoffice'] = $content->delivery_post;
                $delivery['pin'] = $content->delivery_pin;
                $delivery['state_id'] = $content->delivery_state_id;
                $delivery['latitude'] = $content->latitude;
                $delivery['longitude'] = $content->longitude;
                $this->Order_model->insertRow('delivery_address', $delivery);
                $billing['order_id'] = $order_id;
                $billing['full_name'] = $content->billing_full_name;
                $billing['mobile'] = (isset($content->billing_mobile)) ? $content->billing_mobile : '';
                $billing['house_name'] = $content->billing_house_name;
                $billing['street'] = $content->billing_street;
                $billing['postoffice'] = $content->billing_post;
                $billing['pin'] = $content->billing_pin;
                $billing['state_id'] = $content->billing_state_id;
                $this->Order_model->insertRow('billing_address', $billing);
                if(isset($_POST['attachment'])) {
                    foreach($_POST['attachment'] as $attach){
                        $file = $this->upload_attachment($attach);
                        if(!empty($file)) {
                            $attachment['order_id'] = $order_id;
                            $attachment['attachment'] = $file;
                            $this->Order_model->insertRow('attachment', $attachment);
                        }
                    }
                }
                echo json_encode(array('status' => 'true','message' => 'Order successful', 'orderId' => $order_id));
            }
        }
        exit;
    }

    function upload_attachment($file) {
        $data = base64_decode($file);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $data, FILEINFO_MIME_TYPE);
        switch ($mime_type) {
            case "image/png":
                $extension = ".png";
                break;
            case "image/jpeg":
                $extension = ".jpg";
                break;
            case "image/gif":
                $extension = ".gif";
                break;
            case "application/pdf":
                $extension = ".pdf";
                break;
            case "application/zip":
                $extension = ".zip";
                break;
            case "application/xls":
                $extension = ".xls";
                break;
            case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                $extension = ".xlsx";
                break;
            case "application/msword":
                $extension = ".doc";
                break;
            case "application/octet-stream":
                $extension = ".xls";
                break;
            case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
                $extension = ".xls";
                break;
            case "text/plain":
                $extension = ".txt";
                break;
        }
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $uploads_dir = "uploads/orders/$year/$month/$day/";
        if (!is_dir($uploads_dir)) {
            $old_umask = umask(0);
            mkdir($uploads_dir, 0777, TRUE);
            umask($old_umask);
        }
        $file_name = "attachment" . uniqid() . $extension;
        $content = file_put_contents($uploads_dir . $file_name, $data);
        if ($content !== FALSE) {
            return $uploads_dir . $file_name;
        } else {
            return 0;
        }
    }

    function verify_attachment($file){
        $data = base64_decode($file);
        $file_byte_size = strlen($data);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $data, FILEINFO_MIME_TYPE);
        $error_upload = "";
        if($file_byte_size > 10485760){
            $error_upload = "Attachment exceeds allowed file size";
        }else{
            $allowed_type = array("image/png","image/jpeg","image/gif","application/pdf","application/zip","application/xls","text/plain","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/msword","application/octet-stream","application/vnd.openxmlformats-officedocument.wordprocessingml.document");
            if(!in_array($mime_type,$allowed_type)){
                $error_upload = "Attachment has an invalid extension";
            }
        }
        return $error_upload;
    }

    /**
     * This function is used to create datatable in order list page
     * @return Void
     */
    public function dataTable() {
        is_login();
        $table = 'order';
        $primaryKey = 'id';

        $joinQuery = "FROM `order` AS `o` LEFT JOIN `users` AS `u` ON (`u`.`user_id`=`o`.`user_id`)"
                . " LEFT JOIN `stores` AS `s` ON (`s`.`id`=`o`.`store_id`)";
        $columns = array(
            array('db' => '`o`.`id`', 'dt' => 0, 'field' => 'id'),
            array('db' => '`u`.`name`', 'dt' => 1, 'field' => 'name'),
            array('db' => '`s`.`name`', 'dt' => 2, 'field' => 'store_name', 'as' => 'store_name'),
            array('db' => '`o`.`order_date`', 'dt' => 3, 'field' => 'order_date'),
            array('db' => '`o`.`status`', 'dt' => 4, 'field' => 'status')
        );

        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db' => $this->db->database,
            'host' => $this->db->hostname
        );

        $output_arr = SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery);
        foreach ($output_arr['data'] as $key => $value) {
            $output_arr['data'][$key][0] = '<input type="checkbox" name="selData" value="' . $output_arr['data'][$key][0] . '">';
            $output_arr['data'][$key][3] = date("d-m-Y", strtotime($output_arr['data'][$key][3]) );
        }

        echo json_encode($output_arr);
    }

}
