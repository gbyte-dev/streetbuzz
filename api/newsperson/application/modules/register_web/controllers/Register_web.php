<?php

/**
 * @package Register
 * @category Register
 */
class Register extends Base_Api_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index_post() {
        
        die('======111111111======');
/*        echo '<pre>';
        print_r(json_decode($_POST['arr']));
        die('sssssssssssssss');*/
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;   
         
      //print_r (json_decode(($data['arr']))); die;
        
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'user_id',
                    'label' => 'user id',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'registration_date',
                    'label' => 'registration date',
                    'rules' => 'trim|required|validate_date[Y-m-d]'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $coverage_category = isset($data['coverage_category']) ? $data['coverage_category'] : array();
                $coverage_location = isset($data['coverage_location']) ? $data['coverage_location'] : array();
                $coverage_language = isset($data['coverage_language']) ? $data['coverage_language'] : array();
                if(empty($coverage_category) || !is_array($coverage_category)) {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = 'The coverage category field is required.';
                    $this->response($return);
                }
                if(empty($coverage_location) || !is_array($coverage_location)) {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = 'The coverage location field is required.';
                    $this->response($return);
                }
                if(empty($coverage_language) || !is_array($coverage_language)) {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = 'The coverage language field is required.';
                    $this->response($return);
                }
                if($this->is_network_admin) {
                    $this->load->model(array('register/Register_model'));

                    $reporter_id = $data['user_id'];
                    $user_data = $this->Register_model->get_single_row('id, is_reporter, reporter_status', 'users', array('id' => $reporter_id, 'active' => 1));
                    if($user_data) {
                        $data['status'] = 1;
                        $data['is_reporter'] = $user_data['is_reporter'];
                        $data['reporter_current_status'] = $user_data['reporter_status'];                    
                        $this->Register_model->save_reporter_data($data);
                        $this->Register_model->save_reporter_coverage_category($coverage_category, $reporter_id);
                        $this->Register_model->save_reporter_coverage_location($coverage_location, $reporter_id);
                        $this->Register_model->save_reporter_coverage_language($coverage_language, $reporter_id);
                    } else {
                        $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                        $return['message'] = 'Requested user not exist.';
                    }
                } else {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = $this->lang->line('permission_denied');
                }    
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }   
    
    public function registerWeb_post() {
       die('=======');
        $d=json_decode($_POST['arr']);
        $data['user_id']=$d->user_id;
        $data['registration _date']=$d->registration_date;
        $data['coverage_category']=$d->coverage_category;
        $data['coverage_location']=$d->coverage_location;
        $data['coverage_language']=$d->coverage_language;

        $return = $this->return;
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
               
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
               
                $coverage_category = isset($data['coverage_category']) ? $data['coverage_category'] : array();
                $coverage_location = isset($data['coverage_location']) ? $data['coverage_location'] : array();
                $coverage_language = isset($data['coverage_language']) ? $data['coverage_language'] : array();
                if(empty($coverage_category) || !is_array($coverage_category)) {
                    
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = 'The coverage category field is required.';
                    $this->response($return);
                }
                if(empty($coverage_location) || !is_array($coverage_location)) {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = 'The coverage location field is required.';
                    $this->response($return);
                }
                  if(empty($data['user_id'])) {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = 'User id is required';
                    $this->response($return);
                } 
                
                if(empty($data['registration_date'])) {
                    
                    
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = 'registration_date is required';
                    $this->response($return);
                }
                if(empty($coverage_language) || !is_array($coverage_language)) {
                   
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = 'The coverage language field is required.';
                    $this->response($return);
                }
                if($data['user_id']) {
                    $this->load->model(array('register/Register_model'));

                    $reporter_id = $data['user_id'];
                    $user_data = $this->Register_model->get_single_row('id, is_reporter, reporter_status', 'users', array('id' => $reporter_id, 'active' => 1));
                    if($user_data) {
                        $data['status'] = 1;
                        $data['is_reporter'] = $user_data['is_reporter'];
                        $data['reporter_current_status'] = $user_data['reporter_status'];                    
                        $this->Register_model->save_reporter_data($data);
                        $this->Register_model->save_reporter_coverage_category($coverage_category, $reporter_id);
                        $this->Register_model->save_reporter_coverage_location($coverage_location, $reporter_id);
                        $this->Register_model->save_reporter_coverage_language($coverage_language, $reporter_id);
                        
                        
                    } else {
                       
                        $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                        $return['message'] = 'Requested user not exist.';
                    }
                } else {
                  
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = $this->lang->line('permission_denied');
                }    
            }
        } else {
            
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }   
    
    
    public function suspend_post() {
       
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;      
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'user_id',
                    'label' => 'user id',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {                
                if($this->is_network_admin) {
                    $this->load->model(array('register/Register_model'));

                    $reporter_id = $data['user_id'];
                    $user_data = $this->Register_model->get_single_row('id, is_reporter, reporter_status', 'users', array('id' => $reporter_id, 'active' => 1, 'is_reporter' => 1));
                    if($user_data) {
                        $data['is_reporter'] = $user_data['is_reporter'];
                        $data['reporter_current_status'] = $user_data['reporter_status'];   
                        $data['status'] = 0;                 
                        $this->Register_model->save_reporter_data($data);
                    } else {
                        $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                        $return['message'] = 'Requested user not exist.';
                    }
                } else {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = $this->lang->line('permission_denied');
                }    
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    } 


    public function resume_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;      
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'user_id',
                    'label' => 'user id',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {                
                if($this->is_network_admin) {
                    $this->load->model(array('register/Register_model'));

                    $reporter_id = $data['user_id'];
                    $user_data = $this->Register_model->get_single_row('id, is_reporter, reporter_status', 'users', array('id' => $reporter_id, 'active' => 1, 'is_reporter' => 1));
                    if($user_data) {
                        $data['is_reporter'] = $user_data['is_reporter'];
                        $data['reporter_current_status'] = $user_data['reporter_status'];   
                        $data['status'] = 1;                 
                        $this->Register_model->save_reporter_data($data);
                    } else {
                        $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                        $return['message'] = 'Requested user not exist.';
                    }
                } else {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = $this->lang->line('permission_denied');
                }    
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }

    public function demote_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;      
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'user_id',
                    'label' => 'user id',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {                
                if($this->is_network_admin) {
                    $this->load->model(array('register/Register_model'));

                    $reporter_id = $data['user_id'];
                    $user_data = $this->Register_model->get_single_row('id, is_reporter, reporter_status', 'users', array('id' => $reporter_id, 'active' => 1, 'is_reporter' => 1));
                    if($user_data) {
                        $data['is_reporter'] = $user_data['is_reporter'];
                        $data['reporter_current_status'] = $user_data['reporter_status'];   
                        $data['status'] = 2;                 
                        $this->Register_model->save_reporter_data($data);
                    } else {
                        $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                        $return['message'] = 'Requested user not exist.';
                    }
                } else {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = $this->lang->line('permission_denied');
                }    
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    } 
}