<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper('download');
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
     * This function is used to process api
     * @return String
     */
    public function ws_api() {
        if ($this->input->get('mod') && !empty($this->input->get('mod'))) {
            switch ($this->input->get('mod')) {
                case 'login' : $this->ws_login();
                    break;
                case 'register' : $this->ws_register();
                    break;
                case 'generate_otp' : $this->ws_sendOTPtoMobile();
                    break;
                case 'verify_otp' : $this->ws_verifyMobileNumber();
                    break;
                case 'get_token' : $this->ws_getToken();
                    break;
                case 'save_reg_id' : $this->ws_saveRegId();
                    break; 
                default: echo json_encode(array('status' => 'false', 'message' => 'Request syntax error'));
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid call'));
        }
        exit;
    }

    /**
     * This function is used for api login
     * @return String
     */
    public function ws_login() {
        $content = json_decode(file_get_contents("php://input"));
        if (!empty($content->email) && !empty($content->password)) {
            $_POST['email'] = $content->email;
            $_POST['password'] = $content->password;
            $firebase_reg_id = (isset($content->device_token)) ? $content->device_token : '';
            $return = $this->User_model->auth_user();
            if (empty($return)) {
                echo json_encode(array('status' => 'false', 'message' => 'Invalid username or password'));
            } else {
                if ($return == 'not_verified') {
                    echo json_encode(array('status' => 'false', 'message' => 'Accout not verified.'));
                } else if (isset($return['number_not_verified']) && !empty($return['number_not_verified'])) {
                    echo json_encode(array('status' => 'false', 'message' => 'User mobile not verified'));
                } else {
                    $UserData = (array) $return[0];
                    $key = $this->randomString();
                    $token = simple_crypt($UserData['email'], $key);
                    $hash = password_hash($UserData['email'], PASSWORD_DEFAULT);
                    $this->User_model->updateRow('users', 'user_id', $UserData['user_id'], array('hash' => $hash));
                    if(!empty($firebase_reg_id)){
                        $this->User_model->updateRow('users', 'user_id', $UserData['user_id'], array('firebase_reg_id' => $firebase_reg_id));
                    }
                    unset($UserData['password']);
                    unset($UserData['var_key']);
                    unset($UserData['is_deleted']);
                    unset($UserData['var_otp']);
                    unset($UserData['created_by']);
                    unset($UserData['hash']);
                    echo str_replace(':null', ':""', json_encode(array('status' => 'true', 'message' => 'Login successful', 'token' => $token . ':' . $key, 'Data' => $UserData)));
                }
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid username or password'));
        }
        exit;
    }
    
    /**
     * This function is used for genrating token file
     * @return String
     */
    public function ws_getToken() {
        $content = json_decode(file_get_contents("php://input"));
        if (!empty($content->email) && !empty($content->password)) {
            $_POST['email'] = $content->email;
            $_POST['password'] = $content->password;
            $return = $this->User_model->auth_user();
            if (empty($return)) {
                echo json_encode(array('status' => 'false', 'message' => 'Invalid username or password'));
            } else {
                if ($return == 'not_verified') {
                    echo json_encode(array('status' => 'false', 'message' => 'Accout not verified.'));
                } else if (isset($return['number_not_verified']) && !empty($return['number_not_verified'])) {
                    echo json_encode(array('status' => 'false', 'message' => 'User mobile not verified'));
                } else {
                    $UserData = (array) $return[0];
                    $key = $this->randomString();
                    $token = simple_crypt($UserData['email'], $key);
                    $hash = password_hash($UserData['email'], PASSWORD_DEFAULT);
                    $this->User_model->updateRow('users', 'user_id', $UserData['user_id'], array('hash' => $hash));
                    unset($UserData['password']);
                    unset($UserData['var_key']);
                    unset($UserData['is_deleted']);
                    unset($UserData['var_otp']);
                    unset($UserData['created_by']);
                    unset($UserData['hash']);
                    $data = str_replace(':null', ':""', json_encode(array('token' => $token . ':' . $key)));
                    force_download('token.json', $data);
                }
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid username or password'));
        }
        exit;
    }
    
    /**
     * This function is used for api registration
     * @return String
     */
    public function ws_register() {
        $content = json_decode(file_get_contents("php://input"));
        if (empty($content->first_name)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid first name'));
        } else if (empty($content->email)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid email'));
        } else if (empty($content->dob)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid dob'));
        } else if (empty($content->mobile_no)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid mobile number'));
        } else if (empty($content->password)) {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid password'));
        } else {
            if (!(filter_var($content->email, FILTER_VALIDATE_EMAIL))) {
                echo json_encode(array('status' => 'false', 'message' => 'Invalid Email'));
                exit;
            }
            if (preg_match('/^[0-9]{10}+$/', $content->mobile_no) == 0) {
                echo json_encode(array('status' => 'false', 'message' => 'Invalid Mobile Number'));
                exit;
            }
            $checkValue = $this->User_model->check_exists('users', 'email', $content->email);
            if ($checkValue == false) {
                echo json_encode(array('status' => 'false', 'message' => 'Email Already Registered'));
                exit;
            }
            $checkValue1 = $this->User_model->check_exists('users', 'mobile_no', '+91' . $content->mobile_no);
            if ($checkValue1 == false) {
                echo json_encode(array('status' => 'false', 'message' => 'Mobile Already Registered'));
                exit;
            }
            $password = password_hash($content->password, PASSWORD_DEFAULT);
            $data['status'] = 'active';
            $data['name'] = $content->first_name;
            $data['lname'] = $content->last_name;
            $data['mobile_no'] = '+91' . $content->mobile_no;
            $data['email'] = $content->email;
            $data['dob'] = date("Y-m-d", strtotime($content->dob));
            $data['user_type'] = ($content->user_type && !empty($content->user_type)) ? $content->user_type : 'Member';
            $data['password'] = $password;
            $data['profile_pic'] = 'user.png';
            $data['is_deleted'] = 0;
            $user_id = $this->User_model->create('users', $data);
            $data['user_id'] = $user_id;
            $key = $this->randomString();
            $token = simple_crypt($data['email'], $key);
            $hash = password_hash($data['email'], PASSWORD_DEFAULT);
            $this->User_model->updateRow('users', 'user_id', $user_id, array('hash' => $hash));
            unset($data['password']);
            unset($data['profile_pic']);
            unset($data['is_deleted']);
            unset($data['hash']);
            echo str_replace(':null', ':""', json_encode(array('status' => 'true', 'message' => 'Registration successful', 'token' => $token . ':' . $key, 'Data' => $data)));
        }
        exit;
    }

    /**
     * This function is used for opt request using api
     * @return String
     */
    public function ws_sendOTPtoMobile() {
        $content = json_decode(file_get_contents("php://input"));
        if (!empty($content->user_id) && !empty($content->mobile_number)) {
            $_POST['user_id'] = $content->user_id;
            $_POST['mobile_number'] = $content->mobile_number;
            $return = $this->sendOTPtoMobile('ws');
            if (empty($return)) {
                echo json_encode(array('status' => 'false', 'message' => 'Invalid userid'));
            } else {
                echo json_encode($return);
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid user_id or mobile number'));
        }
        exit;
    }

    /**
     * This function is used for otp verification using api
     * @return String
     */
    public function ws_verifyMobileNumber() {
        $content = json_decode(file_get_contents("php://input"));
        if (!empty($content->user_id) && !empty($content->otp)) {
            $user_id = $content->user_id;
            $otp = $content->otp;
            $res = $this->User_model->verifyMobileNumber($otp, $user_id);
            if (!empty($res)) {
                $this->User_model->updateRow('users', 'user_id', $user_id, array('is_verified' => 1));

                if (isset($content->mobile_number) && !empty($content->mobile_number))
                    $this->User_model->updateRow('users', 'user_id', $user_id, array('mobile_no' => $content->mobile_number));

                echo json_encode(array('status' => 'true', 'message' => 'Valid OTP'));
            } else {
                echo json_encode(array('status' => 'false', 'message' => 'Invalid OTP'));
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'Invalid user_id or otp'));
        }
        exit;
    }
    
      /**
     * This function is used for saving client firebase registration id
     * @return String
     */
    public function ws_saveRegId() {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0) {
            echo json_encode(array('status' => 'false', 'message' => 'Request method must be POST!'));
            exit;
        }
        $content = json_decode(file_get_contents("php://input"));

        if (isset($content->token)) {
            if (process_token($content->token)) {
                if (empty($content->user_id)) {
                    echo json_encode(array('status' => 'false', 'message' => 'Invalid user id'));
                } else if (empty($content->reg_id)) {
                    echo json_encode(array('status' => 'false', 'message' => 'Invalid registration id'));
                } else {
                    $user_id = $content->user_id;
                    $this->User_model->updateRow('users', 'user_id', $user_id, array('firebase_reg_id' => $content->reg_id));
                    echo json_encode(array('status' => 'true', 'message' => 'Registration id updated'));
                }
            } else {
                echo json_encode(array('status' => 'false', 'message' => 'Invalid Request Token'));
            }
        } else {
            echo json_encode(array('status' => 'false', 'message' => 'Unauthorized Request'));
        }
        exit;
    }

    /**
     * This function is used to load login view page
     * @return Void
     */
    public function login() {
        if (isset($_SESSION['user_details'])) {
            redirect(base_url() . 'user/profile', 'refresh');
        }
        $data = array('title' => 'Login');
        $this->load->view('include/script', $data);
        $this->load->view('login');
    }

    /**
     * This function is used to logout user
     * @return Void
     */
    public function logout() {
        is_login();
        $this->session->unset_userdata('user_details');
        redirect(base_url() . 'user/login', 'refresh');
    }

    /**
     * This function is used to registr user
     * @return Void
     */
    public function registration() {
        if (isset($_SESSION['user_details'])) {
            redirect(base_url() . 'user/profile', 'refresh');
        }
        //Check if admin allow to registration for user
        if (setting_all('register_allowed') == 1) {
            if ($this->input->post()) {
                $this->add_edit();
                $this->session->set_flashdata('messagePr', 'Successfully Registered..');
            } else {
                $data = array('title' => 'Registration');
                $this->load->view('include/script', $data);
                $this->load->view('register');
            }
        } else {
            $this->session->set_flashdata('messagePr', 'Registration Not allowed..');
            redirect(base_url() . 'user/login', 'refresh');
        }
    }

    /**
     * This function is used to authentify user
     * @return Void
     */
    public function authentify($user_id = '') {
        if (empty($user_id)) {
            redirect(base_url() . 'user/login', 'refresh');
        }
        if (isset($_SESSION['user_details'])) {
            redirect(base_url() . 'user/profile', 'refresh');
        }
        //Check if admin allow to registration for user
        if (setting_all('register_allowed') == 1) {
            $data = array('title' => 'Authentication');
            $mobile_number = $this->User_model->get_mobile_number($user_id);
            $mobile_number['user_id'] = $user_id;
            $this->load->view('include/script', $data);
            $this->load->view('authentify', $mobile_number);
        } else {
            $this->session->set_flashdata('messagePr', 'Registration Not allowed..');
            redirect(base_url() . 'user/login', 'refresh');
        }
    }

    /**
     * This function is used for user authentication ( Working in login process )
     * @return Void
     */
    public function auth_user($page = '') {
        $return = $this->User_model->auth_user();
        if (empty($return)) {
            $this->session->set_flashdata('messagePr', 'Invalid details');
            redirect(base_url() . 'user/login', 'refresh');
        } else {
            if ($return == 'not_verified') {
                $this->session->set_flashdata('messagePr', 'This accout is not verified. Please contact to your admin..');
                redirect(base_url() . 'user/login', 'refresh');
            } else if (isset($return['number_not_verified']) && !empty($return['number_not_verified'])) {
                redirect(base_url() . 'user/authentify/' . $return['number_not_verified'], 'refresh');
            } else {
                $this->session->set_userdata('user_details', $return);
            }
            redirect(base_url() . 'user/profile', 'refresh');
        }
    }

    /**
     * This function is used send mail in forget password
     * @return Void
     */
    public function forgetpassword() {
        $page['title'] = 'Forgot Password';
        if ($this->input->post()) {
            $setting = settings();
            $res = $this->User_model->get_data_by('users', $this->input->post('email'), 'email', 1);
            if (isset($res[0]->user_id) && $res[0]->user_id != '') {
                $var_key = $this->getVerificationCode();
                $this->User_model->updateRow('users', 'user_id', $res[0]->user_id, array('var_key' => $var_key));
                $sub = "Reset password";
                $email = $this->input->post('email');
                $data = array(
                    'user_name' => $res[0]->name,
                    'action_url' => base_url(),
                    'sender_name' => $setting['company_name'],
                    'website_name' => $setting['website'],
                    'verification_link' => base_url() . 'user/mail_verify?code=' . $var_key,
                    'url_link' => base_url() . 'user/mail_verify?code=' . $var_key,
                );
                $body = $this->User_model->get_template('forgot_password');
                $body = $body->html;
                foreach ($data as $key => $value) {
                    $body = str_replace('{var_' . $key . '}', $value, $body);
                }
                if ($setting['mail_setting'] == 'php_mailer') {
                    $this->load->library("send_mail");
                    $emm = $this->send_mail->email($sub, $body, $email, $setting);
                } else {
                    // content-type is required when sending HTML email
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: ' . $setting['EMAIL'] . "\r\n";
                    $emm = mail($email, $sub, $body, $headers);
                }
                if ($emm) {
                    $this->session->set_flashdata('messagePr', 'To reset your password, link has been sent to your email');
                    redirect(base_url() . 'user/login', 'refresh');
                }
            } else {
                $this->session->set_flashdata('forgotpassword', 'This account does not exist'); //die;
                redirect(base_url() . "user/forgetpassword");
            }
        } else {
            $data = array('title' => 'Forgot Password');
            $this->load->view('include/script', $data);
            $this->load->view('forget_password');
        }
    }

    /**
     * This function is used to load view of reset password and verify user too 
     * @return : void
     */
    public function mail_verify() {
        $return = $this->User_model->mail_verify();
        $data = array('title' => 'Verify mail');
        $this->load->view('include/script', $data);
        if ($return) {
            $data['email'] = $return;
            $this->load->view('set_password', $data);
        } else {
            $data['email'] = 'allredyUsed';
            $this->load->view('set_password', $data);
        }
    }

    /**
     * This function is used to reset password in forget password process
     * @return : void
     */
    public function reset_password() {
        $return = $this->User_model->ResetPpassword();
        if ($return) {
            $this->session->set_flashdata('messagePr', 'Password Changed Successfully..');
            redirect(base_url() . 'user/login', 'refresh');
        } else {
            $this->session->set_flashdata('messagePr', 'Unable to update password');
            redirect(base_url() . 'user/login', 'refresh');
        }
    }

    /**
     * This function is used to verify user mobile number
     * @return : void
     */
    public function verifyMobileNumber() {
        $otp = $this->input->post('otp_confirmation');
        $user_id = $this->input->post('user_id');
        $res = $this->User_model->verifyMobileNumber($otp, $user_id);
        if (!empty($res)) {
            $this->User_model->updateRow('users', 'user_id', $user_id, array('is_verified' => 1));
            $this->User_model->updateRow('users', 'user_id', $user_id, array('mobile_no' => $this->input->post('mobile_no')));
            $flash = 'Mobile number Verified';
            $this->session->set_flashdata('messagePr', $flash);
            redirect(base_url() . 'user/login', 'refresh');
        } else {
            $this->session->set_flashdata('messagePr', 'Invalid OTP. Please try again');
            redirect(base_url() . 'user/authentify/' . $user_id, 'refresh');
        }
    }

    /**
     * This function is used to send OTP to registered mobiles
     * @return : void
     */
    public function sendOTPtoMobile($ws = '') {
        $mobileNumber = $this->input->post('mobile_number');
        $user_id = $this->input->post('user_id');
        $otp = $this->getOTP();
        $mobileNumber = '+91' . substr($mobileNumber, -10);
        $result = $this->User_model->updateRow('users', 'user_id', $user_id, array('var_otp' => $otp), 'mobile_no', $mobileNumber);
        if ($result) {
            $message = urlencode($otp . " is your verification code");

            //Prepare you post parameters
            $postData = array(
                'mobiles' => $mobileNumber,
                'message' => $message,
                'sender' => 'MED-REGISTER',
                'route' => 4
            );
            $url = "https://control.msg91.com/api/v2/sendsms";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "$url",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_HTTPHEADER => array(
                    "authkey:150795AKEnh1ZS5906a523",
                    "content-type: multipart/form-data"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $result = array();
            if ($err) {
                $result['result'] = 'false';
                $result['error'] = "cURL Error #:" . $err;
            } else {
                $result['result'] = 'true';
                $result['otp'] = $otp;
            }
            if (!empty($ws)) {
                return $result;
            }
            echo json_encode($result);
        } else if (!empty($ws)) {
            return array('status' => 'false', 'message' => 'Invalid Mobile or UserId');
        } else {
            echo json_encode(array('result' => 'false', 'error' => 'Invalid Mobile or UserId'));
        }
    }

    /**
     * This function is generate hash code for random string
     * @return string
     */
    public function getVerificationCode() {
        $pw = $this->randomString();
        return $verificat_key = password_hash($pw, PASSWORD_DEFAULT);
    }

    /**
     * This function is generate 4 digit random number
     * @return string
     */
    public function getOTP() {
        $otp = mt_rand(1000, 9999);
        return $otp;
    }

    /**
     * This function is used for show users list
     * @return Void
     */
    public function userTable() {
        is_login();
        if (CheckPermission("users", "own_read")) {
            $this->load->view('include/header');
            $this->load->view('user_table');
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
    public function dataTable() {
        is_login();
        $table = 'users';
        $primaryKey = 'user_id';
        $columns = array(
            array('db' => 'user_id', 'dt' => 0), array('db' => 'status', 'dt' => 1),
            array('db' => 'name', 'dt' => 2),
            array('db' => 'email', 'dt' => 3),
            array('db' => 'user_id', 'dt' => 4)
        );

        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db' => $this->db->database,
            'host' => $this->db->hostname
        );
        $where = array("user_type != 'admin'");
        $output_arr = SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where);
        foreach ($output_arr['data'] as $key => $value) {
            $id = $output_arr['data'][$key][count($output_arr['data'][$key]) - 1];
            $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] = '';
            if (CheckPermission($table, "all_update")) {
                $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] .= '<a id="btnEditRow" class="modalButtonUser mClass"  href="javascript:;" type="button" data-src="' . $id . '" title="Edit"><i class="fa fa-pencil" data-id=""></i></a>';
            } else if (CheckPermission($table, "own_update") && (CheckPermission($table, "all_update") != true)) {
                $user_id = getRowByTableColomId($table, $id, 'user_id', 'user_id');
                if ($user_id == $this->user_id) {
                    $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] .= '<a id="btnEditRow" class="modalButtonUser mClass"  href="javascript:;" type="button" data-src="' . $id . '" title="Edit"><i class="fa fa-pencil" data-id=""></i></a>';
                }
            }

            if (CheckPermission($table, "all_delete")) {
                $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] .= '<a style="cursor:pointer;" data-toggle="modal" class="mClass" onclick="setId(' . $id . ', \'user\')" data-target="#cnfrm_delete" title="delete"><i class="fa fa-trash-o" ></i></a>';
            } else if (CheckPermission($table, "own_delete") && (CheckPermission($table, "all_delete") != true)) {
                $user_id = getRowByTableColomId($table, $id, 'user_id', 'user_id');
                if ($user_id == $this->user_id) {
                    $output_arr['data'][$key][count($output_arr['data'][$key]) - 1] .= '<a style="cursor:pointer;" data-toggle="modal" class="mClass" onclick="setId(' . $id . ', \'user\')" data-target="#cnfrm_delete" title="delete"><i class="fa fa-trash-o" ></i></a>';
                }
            }
            $output_arr['data'][$key][0] = '<input type="checkbox" name="selData" value="' . $output_arr['data'][$key][0] . '">';
        }

        echo json_encode($output_arr);
    }

    /**
     * This function is Showing users profile
     * @return Void
     */
    public function profile($id = '') {
        is_login();
        if (!isset($id) || $id == '') {
            $id = $this->session->userdata('user_details')[0]->user_id;
        }
        $data['user_data'] = $this->User_model->get_users($id);
        $this->load->view('include/header');
        $this->load->view('profile', $data);
        $this->load->view('include/footer');
    }

    /**
     * This function is used to show popup of user to add and update
     * @return Void
     */
    public function get_modal() {
        is_login();
        if ($this->input->post('id')) {
            $data['userData'] = getDataByid('users', $this->input->post('id'), 'user_id');
            echo $this->load->view('add_user', $data, true);
        } else {
            echo $this->load->view('add_user', '', true);
        }
        exit;
    }

    /**
     * This function is used to upload file
     * @return Void
     */
    function upload() {
        foreach ($_FILES as $name => $fileInfo) {
            $filename = $_FILES[$name]['name'];
            $tmpname = $_FILES[$name]['tmp_name'];
            $exp = explode('.', $filename);
            $ext = end($exp);
            $newname = $exp[0] . '_' . time() . "." . $ext;
            $config['upload_path'] = 'assets/images/';
            $config['upload_url'] = base_url() . 'assets/images/';
            $config['allowed_types'] = "gif|jpg|jpeg|png|iso|dmg|zip|rar|doc|docx|xls|xlsx|ppt|pptx|csv|ods|odt|odp|pdf|rtf|sxc|sxi|txt|exe|avi|mpeg|mp3|mp4|3gp";
            $config['max_size'] = '2000000';
            $config['file_name'] = $newname;
            $this->load->library('upload', $config);
            move_uploaded_file($tmpname, "assets/images/" . $newname);
            return $newname;
        }
    }

    /**
     * This function is used to add and update users
     * @return Void
     */
    public function add_edit($id = '') {
        $data = $this->input->post();
        $profile_pic = 'user.png';
        if ($this->input->post('user_id')) {
            $id = $this->input->post('user_id');
        }
        if (isset($this->session->userdata('user_details')[0]->user_id)) {
            if ($this->input->post('user_id') == $this->session->userdata('user_details')[0]->user_id) {
                $redirect = 'profile';
            } else {
                $redirect = 'userTable';
            }
        } else {
            $redirect = 'registration';
        }
        if ($this->input->post('fileOld')) {
            $newname = $this->input->post('fileOld');
            $profile_pic = $newname;
        } else {
            $data['name'] = '';
            $profile_pic = 'user.png';
        }
        foreach ($_FILES as $name => $fileInfo) {
            if (!empty($_FILES[$name]['name'])) {
                $newname = $this->upload();
                $data[$name] = $newname;
                $profile_pic = $newname;
            } else {
                if ($this->input->post('fileOld')) {
                    $newname = $this->input->post('fileOld');
                    $data[$name] = $newname;
                    $profile_pic = $newname;
                } else {
                    $data[$name] = '';
                    $profile_pic = 'user.png';
                }
            }
        }
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('mobile_no', 'Mobile Number', 'trim|numeric|exact_length[10]');
        $this->form_validation->set_rules('name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required');
        if ($id != '' && $this->input->post('register')) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
        }
        if ($this->form_validation->run() === TRUE) {
            $_POST['mobile_no'] = '+91' . $_POST['mobile_no'];
            if (isset($_POST['dob'])) {
                $_POST['dob'] = date("Y-m-d", strtotime($_POST['dob']));
            }
            if ($id != '') {
                $data = $this->input->post();
                if ($this->input->post('status') != '') {
                    $data['status'] = $this->input->post('status');
                }
                if ($this->input->post('user_id') == 1) {
                    $data['user_type'] = 'admin';
                }
                if ($this->input->post('password') != '') {
                    if ($this->input->post('currentpassword') != '') {
                        $old_row = getDataByid('users', $this->input->post('user_id'), 'user_id');
                        if (password_verify($this->input->post('currentpassword'), $old_row->password)) {
                            if ($this->input->post('password') == $this->input->post('confirmPassword')) {
                                $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                                $data['password'] = $password;
                            } else {
                                $this->session->set_flashdata('messagePr', 'Password Mismatching');
                                redirect(base_url() . 'user/' . $redirect, 'refresh');
                            }
                        } else {
                            $this->session->set_flashdata('messagePr', 'Incorrect Current Password');
                            redirect(base_url() . 'user/' . $redirect, 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('messagePr', 'Current password is required');
                        redirect(base_url() . 'user/' . $redirect, 'refresh');
                    }
                }
                $id = $this->input->post('user_id');
                unset($data['fileOld']);
                unset($data['currentpassword']);
                unset($data['confirmPassword']);
                unset($data['user_id']);
                unset($data['user_type']);
                if (isset($data['edit'])) {
                    unset($data['edit']);
                }
                if ($data['password'] == '') {
                    unset($data['password']);
                }
                $data['profile_pic'] = $profile_pic;
                $this->User_model->updateRow('users', 'user_id', $id, $data);
                $this->session->set_flashdata('messagePr', 'Data updated Successfully');
                redirect(base_url() . 'user/' . $redirect, 'refresh');
            } else {
                if ($this->input->post('user_type') != 'admin') {
                    $data = $this->input->post();
                    $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                    $checkValue = $this->User_model->check_exists('users', 'email', $this->input->post('email'));
                    if ($checkValue == false) {
                        $this->session->set_flashdata('messagePr', 'This Email Already Registered with us..');
                        redirect(base_url() . 'user/userTable', 'refresh');
                    }
                    $checkValue1 = $this->User_model->check_exists('users', 'mobile_no', $this->input->post('mobile_no'));
                    if ($checkValue1 == false) {
                        $this->session->set_flashdata('messagePr', 'Mobile Number Already Registered with us..');
                        redirect(base_url() . 'user/userTable', 'refresh');
                    }
                    $data['status'] = 'active';
                    if (setting_all('admin_approval') == 1) {
                        $data['status'] = 'deleted';
                    }

                    if ($this->input->post('status') != '') {
                        $data['status'] = $this->input->post('status');
                    }
                    //$data['token'] = $this->generate_token();
                    $data['password'] = $password;
                    $data['profile_pic'] = $profile_pic;
                    $data['is_deleted'] = 0;
                    if (isset($data['password_confirmation'])) {
                        unset($data['password_confirmation']);
                    }
                    if (isset($data['call_from'])) {
                        unset($data['call_from']);
                    }
                    unset($data['submit']);
                    $user_id = $this->User_model->create('users', $data);
                    $success = 'Successfully Registered..';
                    if ($redirect == 'registration') {
                        redirect(base_url() . 'user/authentify/' . $user_id, 'refresh');
                    } else {
                        $this->session->set_flashdata('messagePr', $success);
                        redirect(base_url() . 'user/' . $redirect, 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('messagePr', 'You Don\'t have this autherity ');
                    redirect(base_url() . 'user/' . $redirect, 'refresh');
                }
            }
        }
        $this->session->set_flashdata('messagePr', validation_errors());
        redirect(base_url() . 'user/' . $redirect, 'refresh');
    }

    /**
     * This function is used to delete users
     * @return Void
     */
    public function delete($id) {
        is_login();
        $ids = explode('-', $id);
        foreach ($ids as $id) {
            $this->User_model->delete($id);
        }
        redirect(base_url() . 'user/userTable', 'refresh');
    }

    /**
     * This function is used to send invitation mail to users for registration
     * @return Void
     */
    public function InvitePeople() {
        is_login();
        if ($this->input->post('emails')) {
            $setting = settings();
            $var_key = $this->randomString();
            $emailArray = explode(',', $this->input->post('emails'));
            $emailArray = array_map('trim', $emailArray);
            $body = $this->User_model->get_template('invitation');
            $result['existCount'] = 0;
            $result['seccessCount'] = 0;
            $result['invalidEmailCount'] = 0;
            $result['noTemplate'] = 0;
            if (isset($body->html) && $body->html != '') {
                $body = $body->html;
                foreach ($emailArray as $mailKey => $mailValue) {
                    if (filter_var($mailValue, FILTER_VALIDATE_EMAIL)) {
                        $res = $this->User_model->get_data_by('users', $mailValue, 'email');
                        if (is_array($res) && empty($res)) {
                            $link = (string) '<a href="' . base_url() . 'user/registration?invited=' . $var_key . '">Click here</a>';
                            $data = array('var_user_email' => $mailValue, 'var_inviation_link' => $link);
                            foreach ($data as $key => $value) {
                                $body = str_replace('{' . $key . '}', $value, $body);
                            }
                            if ($setting['mail_setting'] == 'php_mailer') {
                                $this->load->library("send_mail");
                                $emm = $this->send_mail->email('Invitation for registration', $body, $mailValue, $setting);
                            } else {
                                // content-type is required when sending HTML email
                                $headers = "MIME-Version: 1.0" . "\r\n";
                                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                $headers .= 'From: ' . $setting['EMAIL'] . "\r\n";
                                $emm = mail($mailValue, 'Invitation for registration', $body, $headers);
                            }
                            if ($emm) {
                                $darr = array('email' => $mailValue, 'var_key' => $var_key);
                                $this->User_model->create('users', $darr);
                                $result['seccessCount'] += 1;
                            }
                        } else {
                            $result['existCount'] += 1;
                        }
                    } else {
                        $result['invalidEmailCount'] += 1;
                    }
                }
            } else {
                $result['noTemplate'] = 'No Email Template Availabale.';
            }
        }
        echo json_encode($result);
        exit;
    }

    /**
     * This function is used to Check invitation code for user registration
     * @return TRUE/FALSE
     */
    public function chekInvitation() {
        if ($this->input->post('code') && $this->input->post('code') != '') {
            $res = $this->User_model->get_data_by('users', $this->input->post('code'), 'var_key');
            $result = array();
            if (is_array($res) && !empty($res)) {
                $result['email'] = $res[0]->email;
                $result['user_id'] = $res[0]->user_id;
                $result['result'] = 'success';
            } else {
                $this->session->set_flashdata('messagePr', 'This code is not valid..');
                $result['result'] = 'error';
            }
        }
        echo json_encode($result);
        exit;
    }

    /**
     * This function is used to registr invited user
     * @return Void
     */
    public function register_invited($id) {
        $data = $this->input->post();
        $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $data['password'] = $password;
        $data['var_key'] = NULL;
        $data['is_deleted'] = 0;
        $data['status'] = 'active';
        $data['created_by'] = 1;
        if (isset($data['password_confirmation'])) {
            unset($data['password_confirmation']);
        }
        if (isset($data['call_from'])) {
            unset($data['call_from']);
        }
        if (isset($data['submit'])) {
            unset($data['submit']);
        }
        $this->User_model->updateRow('users', 'user_id', $id, $data);
        $this->session->set_flashdata('messagePr', 'Successfully Registered..');
        redirect(base_url() . 'user/login', 'refresh');
    }

    /**
     * This function is used to check email is alredy exist or not
     * @return TRUE/FALSE
     */
    public function checEmailExist() {
        $result = 1;
        $res = $this->User_model->get_data_by('users', $this->input->post('email'), 'email');
        if (!empty($res)) {
            if ($res[0]->user_id != $this->input->post('uId')) {
                $result = 0;
            }
        }
        echo $result;
        exit;
    }

    /**
     * This function is used to Generate a token for verification
     * @return String
     */
    public function generate_token() {
        $alpha = "abcdefghijklmnopqrstuvwxyz";
        $alpha_upper = strtoupper($alpha);
        $numeric = "0123456789";
        $special = ".-+=_,!@$#*%<>[]{}";
        $chars = $alpha . $alpha_upper . $numeric;
        $token = '';
        $up_lp_char = $alpha . $alpha_upper . $special;
        $chars = str_shuffle($chars);
        $token = substr($chars, 10, 10) . strtotime("now") . substr($up_lp_char, 8, 8);
        return $token;
    }

    /**
     * This function is used to Generate a random string
     * @return String
     */
    public function randomString() {
        $alpha = "abcdefghijklmnopqrstuvwxyz";
        $alpha_upper = strtoupper($alpha);
        $numeric = "0123456789";
        $special = ".-+=_,!@$#*%<>[]{}";
        $chars = $alpha . $alpha_upper . $numeric;
        $pw = '';
        $chars = str_shuffle($chars);
        $pw = substr($chars, 8, 8);
        return $pw;
    }

}
