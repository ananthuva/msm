<?php
/**
*@if(CheckPermission('crm', 'read'))
**/
 function CheckPermission($moduleName="", $method=""){
	 $CI = get_instance();
    $permission = isset($CI->session->get_userdata()['user_details'][0]->user_type)?$CI->session->get_userdata()['user_details'][0]->user_type:'';
    if(isset($permission) && $permission != "" ) 
	{
		
        if($permission == 'admin' || $permission == 'Admin') 
		{
          return true;
        } 
		else 
		{	
			$getPermission = array();
			$getPermission = json_decode(getRowByTableColomId('permission',$permission,'user_type','data'));
			
			
			if (isset($getPermission->$moduleName)) 
			{	
			 
			  if(isset($moduleName) && isset($method) && $moduleName != "" && $method != "" )
			  {		
			  		
			  	   $method_arr = explode(',',$method);
			  	   foreach($method_arr as $method_item){ 
				   if(isset($getPermission->$moduleName->$method_item))
				   {  
						return $getPermission->$moduleName->$method_item;
					}
				   
				} 
				//return 0;
              } 
			  else
			  {
                return 0;
              }
			}
			else{return 0;}
       }
    } 
	else 
	{
      return 0;
    }
  }

	function setting_all($keys='')
	{  
		$CI = get_instance();
		if(!empty($keys)){
			$CI->db->select('*');
			$CI->db->from('setting');
			$CI->db->where('keys' , $keys);
			$query = $CI->db->get();
			$result = $query->row();
			if(!empty($result)){
				 $result = $result->value;
				return $result;
			}
			else
			{
				return false;
			}
		}
		else{
			$CI->load->model('setting/setting_model');
			$setting= $CI->setting_model->get_setting();
			return $setting;
		}
		
	}


	function settings() {
		$CI = get_instance();
		$CI->load->model('setting/setting_model');
		$setting= $CI->setting_model->get_setting();
		$result = []; 
		foreach ($setting as $key => $value) {
			$result[$value->keys] = $value->value;
		}
		return $result;
	}

	function getRowByTableColomId($tableName='',$id='',$colom='id',$whichColom='')
	{  
		if($colom == 'id' && $tableName != 'users') {
			$colom = $tableName.'_id';
		}
		$CI = get_instance();
		$CI->db->select('*');
		$CI->db->from($tableName);
		$CI->db->where($colom , $id);
		$query = $CI->db->get();
		$result = $query->row();
			if(!empty($result))
			{	
				if(!empty($whichColom)){
				 $result = $result->$whichColom;
				 return $result;
				}
				else
				{
					return $result;
				}
			}
			else
			{
				return false;
			}
	
	}


	function getOptionValue($keys='')
	{  
		$CI = get_instance();
		$CI->db->select('*');
		$CI->db->from('setting');
		$CI->db->where('keys' , $keys);
		$query = $CI->db->get();
		
		if(!empty($query->row())){return $result = $query->row()->value;}else{return false;}

	}
	
	function getNameByColomeId($tableName='',$id='',$colom='id')
	{ 
		if($colom == 'id') {
			$colom = $tableName.'_id';
		} 
		$CI = get_instance();
		$CI->db->select($colom);
		$CI->db->from($tableName);
		$CI->db->where($tableName.'_id' , $id);
		$query = $CI->db->get();
		return $result = $query->row();

	}
	
	function selectBoxDynamic($field_name='', $tableName='setting',$colom='value',$selected='',$attr='')
	{   
		$CI = get_instance();
		$CI->db->select('*');
		$CI->db->from($tableName);
		$query = $CI->db->get();
		if($query->num_rows() > 0) {
		   $catlog_data = $query->result();
		   $res = '';
			$res .='<select class="form-control" id="'.$field_name.'" name="'.$field_name.'" '.$attr.' >';
				foreach ($catlog_data as $catlogData){ 
				 $select_this = '';
				 	$tab_id = $tableName.'_id';
					if($catlogData->$tab_id == $selected){	$select_this = ' selected ';}
					$res .='<option value="'.$catlogData->$tab_id.'"  '.$select_this.' >'.$catlogData->$colom.'</option>';
				}
			$res .='</select>';
		}
		else 
		{
			$catlog_data = '';
			$res = '';
			$res .='<select class="form-control" id="'.$field_name.'" name="'.$field_name.'" '.$attr.' >';
			$res .='</select>';
		}
		return $res;
	}
		
	function fileUpload()
	{	
		$CI =& get_instance();
     	$CI->load->library('email');
		foreach($_FILES as $name => $fileInfo)
		{
			$filename=$_FILES[$name]['name'];
			$tmpname=$_FILES[$name]['tmp_name']; 
			$exp=explode('.', $filename);
			$ext=end($exp);
			$newname=  $exp[0].'_'.time().".".$ext; 
			$config['upload_path'] = 'assets/images/';
			$config['upload_url'] =  base_url().'assets/images/';
			$config['allowed_types'] = "gif|jpg|jpeg|png|iso|dmg|zip|rar|doc|docx|xls|xlsx|ppt|pptx|csv|ods|odt|odp|pdf|rtf|sxc|sxi|txt|exe|avi|mpeg|mp3|mp4|3gp";
			$config['max_size'] = '2000000'; 
			$config['file_name'] = $newname;
			$CI->load->library('upload', $config);
			move_uploaded_file($tmpname,"assets/images/".$newname);
			return $newname;
		}
	}
	
  function is_login(){ 
      if(isset($_SESSION['user_details'])){
          return true;
      }else{
         redirect( base_url().'user/login', 'refresh');
      }
  }
  function form_safe_json($json) {
    $json = empty($json) ? '[]' : $json ;
    $search = array('\\',"\n","\r","\f","\t","\b","'") ;
    $replace = array('\\\\',"\\n", "\\r","\\f","\\t","\\b", "&#039");
    $json = str_replace($search,$replace,$json);
    return strip_tags($json);
}
	function CallAPI($method, $url, $data = false)
  {   
	  $curl = curl_init();
	  switch ($method)
	  {   
		  case "POST":
			  curl_setopt($curl, CURLOPT_POST, 1);
			  if ($data)
				  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			  break;
		  case "PUT":
			  curl_setopt($curl, CURLOPT_PUT, 1);
			  break;
		  default:
			  if ($data)
				  $url = sprintf("%s?%s", $url, http_build_query($data));
	  }
	  curl_setopt($curl, CURLOPT_HTTPHEADER, array());
	  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	  curl_setopt($curl, CURLOPT_USERPWD, "");
	  curl_setopt($curl, CURLOPT_URL, $url);
	  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	  $result = curl_exec($curl);
	  curl_close($curl);
	  return $result;
  }
   	function getDataByid($tableName='',$columnValue='',$colume='')
	{  
		$CI = get_instance();
		$CI->db->select('*');
		$CI->db->from($tableName);
		$CI->db->where($colume , $columnValue);
		$query = $CI->db->get();
		return $result = $query->row();
	}
	function getAllDataByTable($tableName='',$columnValue='*',$colume='')
	{  
		$CI = get_instance();
		$CI->db->select($columnValue);
		$CI->db->from($tableName);
		$query = $CI->db->get();
		if($query->num_rows() > 0) {
		   $catlog_data = $query->result();
			return $catlog_data;
		}else {return false;}
	}

	function ajx_dataTable($table='',$columns=array(),$Join_condition='', $where = '')
	{

		$table = $table;
    	$primaryKey = $table.'_id';
		$CI = get_instance();
		$CI->load->database();
		$CI->load->library('Ssp');
    	
			if(empty($columns))
			{
				$columns = array(
					array( 'db' => 'name', 'dt' => 0 ),	
					array( 'db' => $table.'_id',  'dt' => 1 )
					);
			}
			$sql_details = array(
				'user' => $CI->db->username,
				'pass' => $CI->db->password,
				'db'   => $CI->db->database,
				'host' => $CI->db->hostname
			);


		
		$output_arr = SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $Join_condition, $where);
		
		foreach ($output_arr['data'] as $key => $value) 
		{
				$id = $output_arr['data'][$key][count($output_arr['data'][$key])  - 1];
				$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] = '';
				if(CheckPermission($table, "own_update")){
				$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] .= '<a sty id="btnEditRow" class="modalButton mClass"  href="javascript:;" type="button" data-src="'.$id.'" title="Edit"><i class="fa fa-pencil" data-id=""></i></a>';
				}
				if(CheckPermission($table, "own_delete")){
				$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] .= '<a data-toggle="modal" class="mClass" style="cursor:pointer;"  data-target="#cnfrm_delete" title="delete" onclick="setId('.$id.')"><i class="fa fa-trash-o" ></i></a>';}

		
			$result = getTemplatesByModule($CI->uri->segment(1));
			if(is_array($result) && !empty($result)) {
				$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] .= '<div class="btn-group"><a id="btnEditRow" class="mClass dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  href="javascript:;" type="button" data-src="" title="Edit"><i class="fa fa-download" data-id=""></i></a><ul class="dropdown-menu">';
				    foreach ($result as $value) {
					$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] .= '<li><a href="#">'.$value->template_name.'</a></li>';    	
				    }
				$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] .=  '</ul> </div>';
			}
		}
		echo json_encode($output_arr);
    }

	  function getTemplatesByModule($module){
	  	$CI = get_instance();
	  	$CI->db->select('*');
	  	$CI->db->from('templates');
	  	$CI->db->where('module', $module);
	  	$qr = $CI->db->get();
	  	return $qr->result();
	  }

/*	  function geneeratePdf($module, $mid, $tid) {
	  	$CI = get_instance();
	  	$template_row = getDataByid('templates',$tid,'id');
	  	$module_row = getDataByid($module,$mid,'id');
	  	$CI->load->library('Mypdf');
	  	$html = $template_row->html;
	  	//print_r($module_row);
	  	foreach ($module_row as $key => $value) {
	  		$html = str_replace('{var_'.$key.'}', $value, $html);		
	  	}

		  $CI->dompdf->load_html($html);
		  $CI->dompdf->set_paper("A4", "portrait");
		  $CI->dompdf->render();
		  $filename = "mkaTestd.pdf";
		  $path = realpath(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/assets/images/pdf/';
		  if(file_exists($path.$filename)) {
			  unlink($path.$filename);
		  }
		  file_put_contents($path.$filename, $CI->dompdf->output());
		  return  $path.$filename;
	  }*/
          
          /**
     * simple_crypt()
     *
     * @param	string	$string
     * @param	string	$action
     * @return	string
     */
    function simple_crypt($string, $key, $action = 'e') {

        $secret_iv = 'my_simple_secret_iv';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
    
    function process_token($token = NULL){
        $return = false;
        if($token) {
            $key = substr($token, strrpos($token, ':' )+1);
            $crypt = str_replace(':'.$key,'',$token);
            $email = simple_crypt($crypt, $key, 'd');
            if(!empty($email)){
                $CI = get_instance();
	  	$CI->db->select('hash');
	  	$CI->db->from('users');
	  	$CI->db->where('email', $email);
	  	$qr = $CI->db->get();
	  	$result = $qr->result_array();
                if(!empty($result)){
                    $hash = $result[0]['hash'];
                    $return = password_verify($email,$hash);
                }
            }
        }
        return $return;
    }
    
    function push_notification($regIDs = array(),$body = NULL, $title = NULL){
        // API access key from Google FCM App Console
        define('API_ACCESS_KEY', 'AIzaSyDeCvJUnS54aC3vZGm5B5UUJeIhG71EVgQ');

        // generated via the cordova phonegap-plugin-push using "senderID" (found in FCM App Console)
        // this was generated from my phone and outputted via a console.log() in the function that calls the plugin
        // my phone, using my FCM senderID, to generate the following registrationId 
        $registrationIDs = $regIDs;

        // prep the bundle
        // to see all the options for FCM to/notification payload: 
        // https://firebase.google.com/docs/cloud-messaging/http-server-ref#notification-payload-support 
        // 'vibrate' available in GCM, but not in FCM
        $fcmMsg = array(
            'body' => $body,
            'title' => $title,
            'sound' => "default",
            'color' => "#203E78"
        );
        // I haven't figured 'color' out yet.  
        // On one phone 'color' was the background color behind the actual app icon.  (ie Samsung Galaxy S5)
        // On another phone, it was the color of the app icon. (ie: LG K20 Plush)
        // 'to' => $singleID ;  // expecting a single ID
        // 'registration_ids' => $registrationIDs ;  // expects an array of ids
        // 'priority' => 'high' ; // options are normal and high, if not set, defaults to high.
        $fcmFields = array(
            'registration_ids' => $registrationIDs,
            'priority' => 'high',
            'notification' => $fcmMsg
        );

        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
?>
