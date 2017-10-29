<?php defined("BASEPATH") OR exit("No direct script access allowed");

class Ticket extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('Ticket_model');
    $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
  }

  /**
     * This function is used to load page view
     * @return Void
     */
  public function index(){
    is_login();
    $this->load->view("include/header");
    $this->load->view("index");
    $this->load->view("include/footer");
  }
  
   /**
     * This function is used to create datatable in index page
     * @return Void
     */
    public function dataTable() {
        is_login();
        $table = 'support_ticket';
        $primaryKey = 'ticket_id';

        $joinQuery = "FROM `support_ticket` AS `t` LEFT JOIN `users` AS `u` ON (`u`.`user_id`=`t`.`created_by`)"
                . " LEFT JOIN `users` AS `usr` ON (`usr`.`user_id`=`t`.`closed_by`)";
        
        
        $columns = array(
            array('db' => '`t`.`ticket_id`', 'dt' => 0, 'field' => 'ticket_id'),
            array('db' => '`t`.`ticket_number`', 'dt' => 1, 'field' => 'ticket_number'),
            array('db' => '`t`.`subject`', 'dt' => 2, 'field' => 'subject'),
            array('db' => '`t`.`priority`', 'dt' => 3, 'field' => 'priority'),
            array('db' => '`t`.`status`', 'dt' => 4, 'field' => 'status'),
            array('db' => '`u`.`name`', 'dt' => 5, 'field' => 'tkt_created_by', 'as' => 'tkt_created_by'),
            array('db' => '`t`.`created_on`', 'dt' => 6, 'field' => 'created_on'),
            array('db' => '`usr`.`name`', 'dt' => 7, 'field' => 'tkt_closed_by', 'as' => 'tkt_closed_by'),
            array('db' => '`t`.`closed_on`', 'dt' => 8, 'field' => 'closed_on')
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
            $output_arr['data'][$key][6] = date("d-m-Y h:i:s a", strtotime($output_arr['data'][$key][6]) );
            $output_arr['data'][$key][8] = (!empty($output_arr['data'][$key][8])) ? date("d-m-Y h:i:s a", strtotime($output_arr['data'][$key][8]) ): '';
            if($output_arr['data'][$key][4] != 'closed') {
                $html = '<a style="cursor:pointer;" data-toggle="modal" class="mClass" onclick="setTicketId(' . $id . ')" data-target="#cnfrm_close" title="Close Ticket"><i class="fa fa-lock" ></i></a>';
                if($output_arr['data'][$key][4] == 'open') {
                    $html .= '<a style="cursor:pointer;" class="mClass" onclick="holdTicket(' . $id . ',\'hold\')" title="Hold Ticket"><i class="fa fa-ban" ></i></a>';
                } else {
                    $html .= '<a style="cursor:pointer;" class="mClass" onclick="openTicket(' . $id . ',\'open\')" title="Open Ticket"><i class="fa fa-unlock" ></i></a>';
                }
                $output_arr['data'][$key][] = $html;
            } else{
                $output_arr['data'][$key][] = '';
            }
        }

        echo json_encode($output_arr);
    }
    
    public function changeStatus(){
        if($this->input->post("id") && !empty($this->input->post("id"))) {
            if($this->input->post("status") == 'closed'){
                $data = array(
                    'status' => 'closed',
                    'closed_on' => date('Y-m-d H:i:s'),
                    'closed_by' => $this->user_id
                );
            } else if($this->input->post("status") == 'hold'){
                $data = array(
                    'status' => 'hold'
                );
            } else {
                $data = array(
                    'status' => 'open'
                );
            }
            $this->Ticket_model->updateRow('support_ticket','ticket_id',$this->input->post("id"),$data);
        }
    }
    
}
?>