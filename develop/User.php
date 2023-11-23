<?php

class User extends Base_Api_Controller {    
    function __construct() {
        parent::__construct();        
    }

    /**
     * index
     * @param
     * @return json array
     */
    public function index_post() {
        $this->response(array(config_item('rest_status_field_name') => FALSE), rest_controller::HTTP_NOT_FOUND);
    }

    /**
     * save_bank_account_detail used to update bank detail for reporter
     * @param
     * @return json array
     */
    function save_bank_account_detail_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;      
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'account_name',
                    'label' => 'account name',
                    'rules' => 'trim|required|max_length[50]'
                ),
                array(
                    'field' => 'account_number',
                    'label' => 'account number',
                    'rules' => 'trim|required|numeric|max_length[50]'
                ),                
                array(
                    'field' => 'bank_name',
                    'label' => 'bank name',
                    'rules' => 'trim|required|max_length[50]'
                ),
                array(
                    'field' => 'ifsc_code',
                    'label' => 'ifsc code',
                    'rules' => 'trim|required|alpha_numeric|max_length[50]'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $this->load->model(array('user/User_model'));    

                 $reporter_detail = $this->User_model->get_single_row('is_bank_detail_validated', 'users', array('id' => $this->user_id));

                if (isset($reporter_detail['is_bank_detail_validated']) && $reporter_detail['is_bank_detail_validated'] == 1) {
                    $return['response_code'] = rest_controller::HTTP_INTERNAL_SERVER_ERROR;
                    $return['message'] = $this->lang->line('bank_detail_change_error');
                    $this->response($return);
                }

                $reporter_bank_detail = $this->User_model->get_single_row('user_id', 'sb_reporter_bank_detail', array('user_id' => $this->user_id));

                $bank_data = array();
                $today = time();
                $bank_data['account_name'] = $data['account_name'];
                $bank_data['bank_name'] = $data['bank_name'];
                $bank_data['account_number'] = $data['account_number'];
                $bank_data['ifsc_code'] = $data['ifsc_code'];
                $bank_data['modified_date'] = $today;
                $user_data = array();

                $message = $this->lang->line('bank_detail_added_success');
                if ($reporter_bank_detail) {
                    $this->User_model->update('sb_reporter_bank_detail', $bank_data, array('user_id' => $this->user_id));
                    $this->User_model->update('users', array('is_bank_detail_validated' => 0), array('id' => $this->user_id));
                
                    $message = $this->lang->line('bank_detail_update_success');
                } else {
                    $bank_data['added_date'] = $today;
                    $bank_data['user_id'] = $this->user_id;
                    $this->db->insert('sb_reporter_bank_detail', $bank_data);
                }
                $return['message'] = $message;  
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);        
    }  

    /**
     * verify_bank_details used to verify/reject bank detail for reporter
     * @param
     * @return json array
     */
    function verify_bank_details_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;  
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'user_id',
                    'label' => 'user id',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'status',
                    'label' => 'status',
                    'rules' => 'trim|required|in_list[1,2]'
                ),
                array(
                    'field' => 'rejected_reason',
                    'label' => 'account number',
                    'rules' => 'trim|max_length[200]'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                if($this->is_network_admin) {
                    $this->load->model(array('user/User_model'));    

                    $reporter_id = $data['user_id'];
                    $status = $data['status'];
                    $rejected_reason = $data['rejected_reason'];

                    $update_data = array(
                        'is_bank_detail_validated' => $status,
                        'bank_rejected_reason' => $rejected_reason
                    );  
                    $return['message'] = $status == 1 ? 'Bank details verified successfully' : 'Bank details rejected successfully';
                    $this->User_model->update('users', $update_data, array('id' => $reporter_id));
                
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
     * bank_details used to user bank details
     * @param
     * @return json array
     */
    function bank_details_post() {
        $return = $this->return;
        $this->load->model(array('user/User_model'));  
        $bank_details = $this->User_model->get_single_row('account_name, bank_name, account_number, ifsc_code', 'sb_reporter_bank_detail', array('user_id' => $this->user_id));
        if(!empty($bank_details)) {
            $return['data'] = $bank_details;
        }
        
        $this->response($return);   

    }


    /**
     * delete_bank_details used to delete bank details
     * @param
     * @return json array
     */
    function delete_bank_details_post() {
        $return = $this->return;
        $this->load->model(array('user/User_model'));  
        $this->User_model->delete_row('sb_reporter_bank_detail',array('user_id'=> $this->user_id));
        $this->User_model->update('users',array('is_bank_detail_validated'=> 0),array('id' => $this->user_id));
         
        $return['message'] = $this->lang->line('bank_detail_deleted');
        $this->response($return);   

    }

    /**
     * viewership_earning used to get user viewership earning
     * @param
     * @return json array
     */
    function viewership_earning_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;  
        if (isset($data)) {            
            $this->load->model(array('user/User_model'));
            $data['user_id'] = $user_id;
            $return['data'] = $this->User_model->viewership_earning($data);
            $return['total_earning'] = $this->User_model->total_earning($user_id);
            $return['total_payment'] = $this->User_model->total_payment($user_id);
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);        
    } 

    /**
     * get_reporter_pending_payment used to get pending payment
     * @param
     * @return json array
     */
    function get_reporter_pending_payment_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;  
        if (isset($data)) {    
            if($this->is_network_admin) {                   
                $this->load->model(array('user/User_model'));
                $return['data'] = $this->User_model->get_reporter_pending_payment($data);
            } else {
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->lang->line('permission_denied');
            }  
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);        
    }

    /**
     * update_payment used to update payment status for reporter
     * @param
     * @return json array
     */
    function update_payment_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;  
        if (isset($data)) {    
            if($this->is_network_admin) {                   
                $this->load->model(array('user/User_model'));
                $return['data'] = $this->User_model->update_payment_status($data);
            } else {
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->lang->line('permission_denied');
            }  
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);        
    }

    function viewership_details_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;  
        if (isset($data)) {                 
            $this->load->model(array('user/User_model'));
            $data['user_id'] = $user_id;
            $return['data'] = $this->User_model->viewership_details($data);
            
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }

    function top_news_persons_post() {
        $return = $this->return;
        $user_id = $this->user_id;
        $data = $this->post_data;  
        if (isset($data)) {
            $data['user_id'] = $user_id;
            $this->load->model(array('user/User_model'));       
            $return['data'] = $this->User_model->top_news_persons($data); 
            $settings =array(
                "profileImageurl" => $this->User_model->profile_image_url()
            );  
            $return['settings'] =  $settings;        
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);
    }

    function toggle_favourite_post() {
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
                $this->load->model(array('user/User_model')); 
                $data['logged_in_user_id'] =  $this->user_id;
                $flag = $this->User_model->toggle_favourite($data);
                $return['data']['is_profile_liked'] = 0;
                if($flag == 1) {
                    $return['data']['is_profile_liked'] = 1;
                }
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);  
    }
}
