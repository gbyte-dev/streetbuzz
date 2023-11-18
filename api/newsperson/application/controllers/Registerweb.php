<?php

/**
 * @package Reporter
 * @category Reporter
 */
class Registerweb extends CI_Controller {

     public function index() {
      
        $d=json_decode($_POST['arr']);
        $data['user_id']=$d->user_id;
        $data['registration_date']=$d->registration_date;
        $data['coverage_category']=$d->coverage_category;
        $data['coverage_location']=$d->coverage_location;
        $data['coverage_language']=$d->coverage_language;

        //$return = $this->return;
        $return =Array
(
   "response_code" => 200,
    "message" => "Success",
    "service_name" => "register",
    "error" => "stdClass Object
        (
        )",

    "data" => "stdClass Object
        (
        )"

);

        $user_id = $d->user_id;
      //  $data = $this->post_data;   
       //  print_r($data);
      //json_decode($data['arr']);
        if (isset($data)) {


            $config = array(
                array(
                    'field' => 'user_id',
                    'label' => 'user id',
                    'rules' => 'trim|nullable'
                ),
                array(
                    'field' => 'registration_date',
                    'label' => 'registration date',
                    'rules' => 'trim|nullable|validate_date[Y-m-d]'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) {
               
                $return['response_code'] = 'Failed';
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
               
                $coverage_category = isset($data['coverage_category']) ? $data['coverage_category'] : array();
                $coverage_location = isset($data['coverage_location']) ? $data['coverage_location'] : array();
                $coverage_language = isset($data['coverage_language']) ? $data['coverage_language'] : array();
                if(empty($coverage_category) || !is_array($coverage_category)) {
                    
                    $return['response_code'] = 'Failed';
                    $return['message'] = 'The coverage category field is required.';
                   // return ($return);
                   echo 0;
                   die;
                }
               
                if(empty($coverage_location) || !is_array($coverage_location)) {
                    $return['response_code'] ='Failed';
                    $return['message'] = 'The coverage location field is required.';
                 // return ($return);
                 echo 0;
                   die;
                }
                
                  if(empty($data['user_id'])) {
                    $return['response_code'] ='Failed';
                    $return['message'] = 'User id is required';
                   // return ($return);
                   echo 0;
                   die;
                } 
                  
                 if(empty($data['registration_date'])) {
                    
                    
                    $return['response_code'] = 'Failed';
                    $return['message'] = 'registration_date is required';
                   //return 0;
                   echo 0;
                   die;
                }
               
                if(empty($coverage_language) || !is_array($coverage_language)) {
                   
                    $return['response_code'] = 'Failed';
                    $return['message'] = 'The coverage language field is required.';
                   //return ($return);
                   echo 0;
                   die;
                }
               
                if($data['user_id']) {
                 

                    $this->load->model(array('RegisterWebModel'));

                    $reporter_id = $data['user_id'];
                    $user_data = $this->RegisterWebModel->get_single_row('id, is_reporter, reporter_status', 'users', array('id' => $reporter_id, 'active' => 1));
                   
                  
                    if($user_data) {
                       
                
                        $data['status'] = 1;
                        $data['is_reporter'] = $user_data['is_reporter'];
                        $data['reporter_current_status'] = $user_data['reporter_status'];                    
                        $this->RegisterWebModel->save_reporter_data($data);
                        $this->RegisterWebModel->save_reporter_coverage_category($coverage_category, $reporter_id);
                        $this->RegisterWebModel->save_reporter_coverage_location($coverage_location, $reporter_id);
                        $this->RegisterWebModel->save_reporter_coverage_language($coverage_language, $reporter_id);
                       
                       echo 1; 
                    } else {
                       
                        $return['response_code'] = 'Failed';
                        $return['message'] = 'Requested user not exist.';
                    }
                } else {
                  
                    $return['response_code'] = 'Failed';
                    $return['message'] = $this->lang->line('permission_denied');
                }    
            }
        } else {
            
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        return ($return);
    } 
 
    
}