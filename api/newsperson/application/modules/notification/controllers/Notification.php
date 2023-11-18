<?php

class Notification extends Base_Api_Controller {    
    function __construct() {
        parent::__construct();        
    }

    public function index_get() {
        $this->response(array(config_item('rest_status_field_name') => FALSE), rest_controller::HTTP_NOT_FOUND);
    }

    public function index_post() {
        $this->response(array(config_item('rest_status_field_name') => FALSE), rest_controller::HTTP_NOT_FOUND);
    }
    
    public function list_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;  
        if (isset($data)) {
            $this->load->model(array('notification/Notification_model'));
            $data['user_id'] = $user_id;
            $return['data'] = $this->Notification_model->list($data);   
            $settings =array(
                "profileImageurl" => $this->Notification_model->profile_image_url()
            );  
            $return['settings'] =  $settings;          
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }
}