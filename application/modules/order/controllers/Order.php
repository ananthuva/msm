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

        if (isset($content->token)) {
            if (process_token($content->token)) {
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
                case 'order_list' : $this->ws_oderList();
                    break;
                case 'order_details' : $this->ws_oderDetails();
                    break;
                default: echo json_encode(array('status' => 'false', 'message' => 'Request syntax error'));
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid call'));
        }
        exit;
    }

    /**
     * This function is used for ordering Medicine
     * @return String
     */
    public function ws_oderMedicine() {

        $content = json_decode(file_get_contents("php://input"));
        $_POST = (array) $content;
        $rules = array(
            [ 'field' => 'user_id', 'label' => 'user id', 'rules' => 'required',],
            [ 'field' => 'delivery_full_name', 'label' => 'Name in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_mobile', 'label' => 'mobile in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_house_name', 'label' => 'House Name in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_street', 'label' => 'Street in Delivery Address', 'rules' => 'required',],
            //[ 'field' => 'delivery_post', 'label' => 'Postoffice in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_pin', 'label' => 'PIN in Delivery Address', 'rules' => 'required',],
            [ 'field' => 'delivery_state_id', 'label' => 'State Id in Delivery Address', 'rules' => 'required',],
            //[ 'field' => 'latitude', 'label' => 'latitude', 'rules' => 'required',],
            //[ 'field' => 'longitude', 'label' => 'longitude', 'rules' => 'required',],
            [ 'field' => 'billing_full_name', 'label' => 'Name in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_mobile', 'label' => 'Mobile in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_house_name', 'label' => 'House Name in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_street', 'label' => 'Street in Billing Address', 'rules' => 'required',],
            //[ 'field' => 'billing_post', 'label' => 'Postoffice in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_pin', 'label' => 'PIN in Billing Address', 'rules' => 'required',],
            [ 'field' => 'billing_state_id', 'label' => 'State Id in Billing Address', 'rules' => 'required',],
            //[ 'field' => 'note', 'label' => 'note', 'rules' => 'required',],
        );
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            $errors = preg_replace("/\r|\n/", "", validation_errors(" ", " "));
            $errors = ltrim(explode('.', $errors)[0]);
            echo json_encode(array('status' => 'false', 'message' => $errors));
        } else {
            $error = '';
            if (isset($_POST['attachment'])) {
                foreach ($_POST['attachment'] as $attach) {
                    $error = $this->verify_attachment($attach);
                    if (!empty($error)) {
                        echo json_encode(array('status' => 'false', 'message' => $error));
                        break;
                    }
                }
            }
            if (empty($error)) {
                $order['order_bill_id'] = 'Ord-' . date('YmdHis');
                $order['store_id'] = (isset($content->store_id)) ? $content->store_id : '';
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
                if (isset($_POST['attachment'])) {
                    foreach ($_POST['attachment'] as $attach) {
                        $file = $this->upload_attachment($attach);
                        if (!empty($file)) {
                            $attachment['order_id'] = $order_id;
                            $attachment['attachment'] = $file;
                            $this->Order_model->insertRow('attachment', $attachment);
                        }
                    }
                }
                echo json_encode(array('status' => 'true', 'message' => 'Order successful', 'orderId' => $order_id));
            }
        }
        exit;
    }

    /**
     * This function is used for getting list of orders
     * @return String
     */
    public function ws_oderList() {
        $content = json_decode(file_get_contents("php://input"));
        $limit = property_exists($content, 'limit') ? $content->limit : '';
        $offset = property_exists($content, 'offset') ? $content->offset : '';
        $orders = $this->Order_model->getOrderList($limit, $offset);
        $result = str_replace(':null', ':""', json_encode(array('status' => 'true', 'message' => 'Request successful', 'Data' => $orders)));
        echo str_replace('[]', '{}',str_replace('}]', '}}', str_replace('[{', '{{', $result)));
        exit;
    }

    /**
     * This function is used for getting an order details
     * @return String
     */
    public function ws_oderDetails() {
        $content = json_decode(file_get_contents("php://input"));
        $id = property_exists($content, 'order_id') ? $content->order_id : '';
        if (empty($id)) {
            echo json_encode(array('status' => 'false', 'message' => 'order_id is required'));
        } else {
            $data['order'] = $this->Order_model->getOrderdetails($id);
            if (empty($data['order'])) {
                echo json_encode(array('status' => 'false', 'message' => 'Invalid order_id'));
            } else {
                $data['billing_details'] = $this->Order_model->getOrderBillingAddress($id);
                $data['delivery_details'] = $this->Order_model->getOrderDeliveryAddress($id);
                $data['attachments'] = $this->Order_model->getOrderAttachment($id);
                $data['history'] = $this->Order_model->getOrderHistory($id);
                $result = str_replace(':null', ':""', json_encode(array('status' => 'true', 'message' => 'Request successful', 'Data' => $data)));
                echo str_replace('[]', '{}',str_replace('}]', '}}', str_replace('[{', '{{', $result)));
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

    function verify_attachment($file) {
        $data = base64_decode($file);
        $file_byte_size = strlen($data);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $data, FILEINFO_MIME_TYPE);
        $error_upload = "";
        if ($file_byte_size > 10485760) {
            $error_upload = "Attachment exceeds allowed file size";
        } else {
            $allowed_type = array("image/png", "image/jpeg", "image/gif", "application/pdf", "application/zip", "application/xls", "text/plain", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/msword", "application/octet-stream", "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
            if (!in_array($mime_type, $allowed_type)) {
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
                . " LEFT JOIN `stores` AS `s` ON (`s`.`id`=`o`.`store_id`)"
                . " LEFT JOIN `table_order_status` AS `os` ON (`os`.`order_status_id`=`o`.`status`)";
        $columns = array(
            array('db' => '`o`.`id`', 'dt' => 0, 'field' => 'id'),
            array('db' => '`u`.`name`', 'dt' => 1, 'field' => 'name'),
            array('db' => '`s`.`name`', 'dt' => 2, 'field' => 'store_name', 'as' => 'store_name'),
            array('db' => '`o`.`order_date`', 'dt' => 3, 'field' => 'order_date'),
            array('db' => '`os`.`order_status_name`', 'dt' => 4, 'field' => 'order_status', 'as' => 'order_status')
        );

        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db' => $this->db->database,
            'host' => $this->db->hostname
        );

        $output_arr = SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery);
        foreach ($output_arr['data'] as $key => $value) {
            $id = $output_arr['data'][$key][0];
            $output_arr['data'][$key][0] = '<input type="checkbox" name="selData" value="' . $output_arr['data'][$key][0] . '">';
            $output_arr['data'][$key][3] = date("d-m-Y", strtotime($output_arr['data'][$key][3]));
            $output_arr['data'][$key][5] = '<a id="btnEditRow" class="mClass"  href="' . base_url() . 'order/viewOrder/' . $id . '" type="button" title="View Order"><i class="fa fa-eye"></i></a>';
        }

        echo json_encode($output_arr);
    }

    /**
     * This function is used for view an order
     * @return Void
     */
    public function viewOrder($id = '') {
        is_login();
        if (CheckPermission("order", "own_read") && !empty($id)) {
            $this->load->view('include/header');
            $data['order'] = $this->Order_model->getOrderdetails($id);
            $data['order_status'] = isset($data['order']['order_status_name']) ? $this->getStatusName($data['order']['order_status_name']) : '';
            $data['billing'] = $this->Order_model->getOrderBillingAddress($id);
            $data['delivery'] = $this->Order_model->getOrderDeliveryAddress($id);
            $data['attachments'] = $this->Order_model->getOrderAttachment($id);
            $data['history'] = $this->Order_model->getOrderHistory($id);
            if (!empty($data['history'])) {
                foreach ($data['history'] as &$history) {
                    $history['status'] = isset($history['order_status_name']) ? $this->getStatusName($history['order_status_name']) : '';
                }
            }
            $this->load->view('order_details', $data);
            $this->load->view('include/footer');
        } else {
            $this->session->set_flashdata('messagePr', 'You don\'t have permission to access.');
            redirect(base_url() . 'user/profile', 'refresh');
        }
    }

    public function getStatusName($status = "") {
        $statusName = '';
        if (!empty($status)) {
            switch ($status) {
                case 'Send Prescription' :
                    $statusName = 'Placed by User';
                    break;
                case 'Get Quote' :
                    $statusName = 'Got Quote';
                    break;
                case 'Confirmed Order' :
                    $statusName = 'Confirmed';
                    break;
                case 'Rejected Order' :
                    $statusName = 'Rejected';
                    break;
                case 'Done Payment' :
                    $statusName = 'Payment Completed';
                    break;
                case 'Out For Delivery' :
                    $statusName = 'is Out For Delivery';
                    break;
                case 'Delivered' :
                    $statusName = 'Delivered';
                    break;
                default : break;
            }
        }
        return $statusName;
    }

}
