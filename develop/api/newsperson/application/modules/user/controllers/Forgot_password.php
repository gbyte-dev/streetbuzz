<?php

class Forgot_password extends Base_Api_Controller {    
    function __construct() {
        parent::__construct();        
    }

    /**
     * used to send forgot password otp
     * @param
     * @return json array
     */
    function index_post() {
    
        $return = $this->return;
        $data = $this->post_data;  
        //var_dump(json_decode($data));
        //print_r($data);
        //echo $data['value'];
        //die();
        if (isset($data)) {
            
            $type = isset($data['type']) ? $data['type'] : 2;
            $validation = "trim|required|numeric";
            $label = 'mobile';
            $field = 'phone_no';
            if ($type == 2) {
                
               $validation = "trim|required|valid_email"; 
               $field = $label = 'email';
            }
            $config = array(
                
                array(
                    'field' => 'value',
                    'label' => $label,
                    'rules' => $validation
                )
            );

            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
              
                $this->load->model(array('user/User_model'));    

                $user_data = $this->User_model->get_single_row("id, email, fullname, phone_no, active", 'users', array($field => $data['value']));

                if (empty($user_data)) {
                    $return['response_code'] = rest_controller::HTTP_INTERNAL_SERVER_ERROR;
                    $return['message'] = sprintf($this->lang->line('not_exist'), $label);
                    $this->response($return);
                }

                $user_data['otp'] = $this->User_model->generate_otp($data);
              
                if ($type == 2) {
                    $email = $data['value'];
                    $subject  = SITE_NAME . " Password Assistance";
                    $message  = $this->load->view('emailer/forgot-password', $user_data, TRUE);
                    $this->load->helper('mail_helper');
                    send_email_new($email, $subject, $message);
                }
                
                $return['message'] = $this->lang->line('forgot_password_message');  
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);        
    }  

    public function validate_otp_post() {
        $return = $this->return;
        $data = $this->post_data;      
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'OTP',
                    'label' => 'confirmation code',
                    'rules' => 'trim|required|max_length[6]'
                )
            );

            $type = isset($data['type']) ? $data['type'] : 2;
            $validation = "trim|required|numeric";
            $label = 'mobile';
            $field = 'phone_no';
            if ($type == 2) {
               $validation = "trim|required|valid_email"; 
               $field = $label = 'email';
            }
            $config[] = array(
                            'field' => 'value',
                            'label' => $label,
                            'rules' => $validation
                        );

            $this->form_validation->set_rules($config);
            if ($this->form_validation->run() == FALSE) {
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $this->load->model(array('user/User_model'));    
                $user_data = $this->User_model->get_single_row("id as user_id, email, username", 'users', array($field => $data['value']));

                if (empty($user_data)) {
                    $return['response_code'] = rest_controller::HTTP_INTERNAL_SERVER_ERROR;
                    $return['message'] = sprintf($this->lang->line('not_exist'), $label);
                    $this->response($return);
                }

                $verification_status = $this->User_model->check_otp($data);
                if ($verification_status == 2) {
                
                    //generate login session                        
                    $auth_key = $this->User_model->generate_login_auth_key($user_data['user_id']);
                    $return['data'] = array('user' => $user_data, AUTH_TOKEN => $auth_key);                    
                } else if ($verification_status == 1) {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = $this->lang->line('invalid_otp');
                } else if ($verification_status == 3) {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] = $this->lang->line('otp_expired');
                }
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('input_invalid_format');
        }
        $this->response($return);
    }
    
}
