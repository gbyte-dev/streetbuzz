<?php

class Location extends Base_Api_Controller {    
    function __construct() {
        parent::__construct();        
    }

    /**
     * used to get all location
     * @param
     * @return json array
     */
    public function index_post() {
        $return = $this->return;
        $post_data = $this->post();               

        $this->load->model(array('location/Location_model'));
        $return['data'] = $this->Location_model->locations($post_data);
        $this->response($return);
    }

     /**
     * used to save location handle
     * @param
     * @return json array
     */
    public function save_handle_post() {
        $return = $this->return;
        $data = $this->post_data;  
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'location_id',
                    'label' => 'location id',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                if($this->is_network_admin) {
                    $this->load->model(array('location/Location_model'));    
                    $user_handles = check_array_key($data, 'user_handles', array());
                    $state_handles = check_array_key($data, 'state_handles', array());
                    $capital_handles = check_array_key($data, 'capital_handles', array());
                    $national_handles = check_array_key($data, 'national_handles', array());
                    $international_handles = check_array_key($data, 'international_handles', array());
                    $location_id = $data['location_id'];

                    if (empty($user_handles) && empty($state_handles) && empty($capital_handles) && empty($national_handles) && empty($international_handles)) {
                        $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                        $return['message'] = $this->lang->line('handle_required');
                        $this->response($return);  
                    }

                    $handle_detail = $this->Location_model->get_single_row('handle_id', 'sb_location_handle', array('location_id' => $location_id));

                    if (!empty($handle_detail)) {
                        $return['response_code'] = rest_controller::HTTP_INTERNAL_SERVER_ERROR;
                        $return['message'] = $this->lang->line('handle_exist');
                        $this->response($return);
                    }

                    $this->Location_model->save_handle($location_id, $user_handles, $state_handles, $capital_handles, $national_handles, $international_handles);
                    $return['message'] =  $this->lang->line('handle_added_success');
                
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


     /**
     * used to update location handle
     * @param
     * @return json array
     */
    public function update_handle_post() {
        $return = $this->return;
        $data = $this->post_data;  
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'handle_id',
                    'label' => 'handle id',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                if($this->is_network_admin) {
                    $this->load->model(array('location/Location_model'));    
                    $user_handles = check_array_key($data, 'user_handles', array());
                    $state_handles = check_array_key($data, 'state_handles', array());
                    $capital_handles = check_array_key($data, 'capital_handles', array());
                    $national_handles = check_array_key($data, 'national_handles', array());
                    $international_handles = check_array_key($data, 'international_handles', array());
                    $handle_id = $data['handle_id'];

                    if (empty($user_handles) && empty($state_handles) && empty($capital_handles) &&  empty($national_handles) && empty($international_handles)) {
                        $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                        $return['message'] = $this->lang->line('handle_required');
                        $this->response($return);  
                    }

                    $handle_detail = $this->Location_model->get_single_row('handle_id', 'sb_location_handle', array('handle_id' => $handle_id));

                    if (empty($handle_detail)) {
                        $return['response_code'] = rest_controller::HTTP_INTERNAL_SERVER_ERROR;
                        $return['message'] = sprintf($this->lang->line('valid_value'), 'handle id'); 
                        $this->response($return);
                    }

                    $this->Location_model->update_handle($handle_id, $user_handles, $state_handles, $capital_handles, $national_handles, $international_handles);
                    $return['message'] =  $this->lang->line('handle_update_success');
                
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

    /**
     * used to search coverage category
     * @param
     * @return json array
     */
    public function handles_post() {
        $return = $this->return;
        $post_data = $this->post();               

        $this->load->model(array('location/Location_model'));
        $return['data'] = $this->Location_model->handles($post_data);
        $this->response($return);
    }

    public function generate_timeline_post() {
        $return = $this->return;
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
                $user_id = $data['user_id'];
                $this->load->model(array('location/Location_model'));
                $user_detail = $this->Location_model->get_single_row('location_id', 'users', array('id' => $user_id));
                if (empty($user_detail)) {
                    $return['response_code'] = rest_controller::HTTP_INTERNAL_SERVER_ERROR;
                    $return['message'] = sprintf($this->lang->line('valid_value'), 'user id'); 
                    $this->response($return);
                }
                $location_id = $user_detail['location_id'];
                if(!empty($location_id)) {
                    $this->Location_model->generate_timeline($user_id, $location_id);
                }                                
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }
    
}
