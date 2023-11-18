<?php

class Follow extends Base_Api_Controller {    
    function __construct() {
        parent::__construct();        
    }

    function index_post() {
        $return = $this->return;
        $data = $this->post_data;  
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'whom_id',
                    'label' => 'user id',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $this->load->model(array('user/Follow_model')); 
                $row =  $this->Follow_model->get_single_row('id', 'users', array('id' => $data['whom_id']));
                if(!empty($row)) {
                    $data['user_id'] =  $this->user_id;
                    $flag = $this->Follow_model->toggle_follow($data);
                    $is_follow = 0;
                    if($flag == 1) {
                        $is_follow = 1;
                    }
                    $return['data'] = array('is_follow' => $is_follow);
                } else {
                    $return['response_code'] = rest_controller::HTTP_INTERNAL_SERVER_ERROR;
                    $return['message'] = sprintf($this->lang->line('valid_value'), 'user id'); 
                }                
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);  
    }



}
