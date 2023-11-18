<?php


/**
 * @package Post
 * @category Post
 */
class Post extends Base_Api_Controller {

    function __construct() {
        parent::__construct();
    }

    public function top_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;  
        if (isset($data)) {             
            
            $day_filter = isset($data['day_filter']) ? $data['day_filter'] : 1;
            $post_type = isset($data['post_type']) ? $data['post_type'] : '';
            $post_type_array = array();
            
            $current_date = date("Y-m-d",strtotime("-1 days"));
            if($day_filter==2) {
                $current_date = date("Y-m-d",strtotime("-8 days"));
            }

            if($day_filter==3) {
                $current_date = date("Y-m-d",strtotime("-29 days"));
            }

            if($day_filter==4) {
                $current_date = date('Y-m-01', strtotime('1 months ago'));    
            }

            if($day_filter==5) {
                $current_date = date('Y-m-01', strtotime('3 months ago'));      
            }
            
            $this->load->model(array('post/Post_model'));
            $data['user_id'] = $user_id;
            $data['start_date'] = $current_date;
            
            $data['post_type'] = array(1,2,3);
            if($post_type == 1) {
                $data['post_type'] = array(1);
            } else if($post_type == 2) {
                $data['post_type'] = array(2);
            } else if($post_type == 3) {
                $data['post_type'] = array(3);
            }
            $return['data'] = $this->Post_model->top_post($data);   
            $settings =array(
                "imageurl" => $this->Post_model->image_url(),
                "profileImageurl" => $this->Post_model->profile_image_url()
            );  
            $return['settings'] =  $settings;          
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }  
    
    public function guest_timeline_post() {
        
        $return = $this->return;
        $data = $this->post_data;  
       
        if (isset($data)) {
            $this->load->model(array('post/Post_model'));       
            $return['data'] = $this->Post_model->guest_timeline($data); 
            $settings =array(
                "imageurl" => $this->Post_model->image_url(),
                "profileImageurl" => $this->Post_model->profile_image_url()
            );  
            $return['settings'] =  $settings;        
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }

    public function poll_near_you_post() {
        $return = $this->return;
        $data = $this->post_data;  
        if (isset($data)) {
            $this->load->model(array('post/Post_model'));       
            $return['data'] = $this->Post_model->poll_near_you($data); 
            $settings =array(
                "imageurl" => $this->Post_model->image_url(),
                "profileImageurl" => $this->Post_model->profile_image_url()
            );  
            $return['settings'] =  $settings;        
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }

    public function event_near_you_post() {
        $return = $this->return;
        $data = $this->post_data;  
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'latitude',
                    'label' => 'latitude',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'longitude',
                    'label' => 'longitude',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $this->load->model(array('post/Post_model'));       
                $return['data'] = $this->Post_model->event_near_you($data); 
                $settings =array(
                    "imageurl" => $this->Post_model->image_url(),
                    "profileImageurl" => $this->Post_model->profile_image_url()
                );  
                $return['settings'] =  $settings;    
            }    
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }

    public function get_post_type_post() {
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
                $this->load->model(array('post/Post_model'));  
                $post_id = $data['post_id'];     
                $return['data'] =  $this->Post_model->get_single_row('posttype', 'posts', array('id' => $post_id));
            }    
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }

    public function more_news_post() {
        $return = $this->return;
        $data = $this->post_data;  
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'post_id',
                    'label' => 'post id',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'post_owner_id',
                    'label' => 'post owner id',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $this->load->model(array('post/Post_model'));   
                $data['user_id'] = $this->user_id;
                $return['data'] =  $this->Post_model->more_news($data);
                $settings =array(
                    "imageurl" => $this->Post_model->image_url(),
                    "profileImageurl" => $this->Post_model->profile_image_url()
                );  
                $return['settings'] =  $settings;
            }    
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }
}