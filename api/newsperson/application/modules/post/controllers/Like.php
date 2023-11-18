<?php

/**
 * @package Post
 * @category Like
 */
class Like extends Base_Api_Controller {

    function __construct() {
        parent::__construct();
    }

    function index_post() {
        $return = $this->return;
        $data = $this->post_data;  
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'post_id',
                    'label' => 'post id',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $this->load->model(array('post/Like_model')); 
                $row =  $this->Like_model->get_single_row('user_id', 'posts', array('id' => $data['post_id']));
                if(!empty($row)) {
                    $data['user_id'] =  $this->user_id;
                    $data['post_owner_id'] =  $row['user_id'];
                    $flag = $this->Like_model->toggle_like($data);
                    $is_liked = 0;
                    if($flag == 1) {
                        $is_liked = 1;
                    }
                    $return['data'] = array('is_liked' => $is_liked);
                } else {
                    $return['response_code'] = rest_controller::HTTP_INTERNAL_SERVER_ERROR;
                    $return['message'] = sprintf($this->lang->line('valid_value'), 'post id'); 
                }                
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);  
    }
}