<?php

class Search extends Base_Api_Controller {    
    function __construct() {
        parent::__construct();        
    }

    /**
     * used to search user
     * @param
     * @return json array
     */
    public function index_post() {
       
        $return = $this->return;
        $post_data = $this->post(); 
        

        $this->load->model(array('search/Search_model'));
        $return['data'] = $this->Search_model->users($post_data);
        $this->response($return);
    }

    /**
     * used to search coverage category
     * @param
     * @return json array
     */
    public function coverage_category_post() {
        $return = $this->return;
        $post_data = $this->post();               

        $this->load->model(array('search/Search_model'));
        $return['data'] = $this->Search_model->coverage_category($post_data);
        $this->response($return);
    }

    /**
     * used to search coverage location
     * @param
     * @return json array
     */
    public function coverage_location_post() {
        $return = $this->return;
        $post_data = $this->post();               
        if (isset($post_data)) {
            $config = array(
                array(
                    'field' => 'search_key',
                    'label' => 'search keyword',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $this->load->model(array('search/Search_model'));
                $return['data'] = $this->Search_model->coverage_location($post_data);
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);  
    }
    /**
     * used to search coverage language
     * @param
     * @return json array
     */
    public function coverage_language_post() {
        $return = $this->return;
        $post_data = $this->post();               

        $this->load->model(array('search/Search_model'));
        $return['data'] = $this->Search_model->coverage_language($post_data);
        $this->response($return);
    }

    /**
     * used to get trending tags
     * @param
     * @return json array
     */
    public function trending_tags_post() {
        $return = $this->return;
        $post_data = $this->post();               

        $this->load->model(array('search/Search_model'));
        $return['data'] = $this->Search_model->trending_tags($post_data);
        $this->response($return);
    }
    /**
     * used to get trending tags
     * @param
     * @return json array
     */
    public function locationtrending_tags_post() {
        $return = $this->return;
        $post_data = $this->post();               

        $this->load->model(array('search/Search_model'));
        $return['data'] = $this->Search_model->locationtrending_tags($post_data);
        $this->response($return);
    }
}
