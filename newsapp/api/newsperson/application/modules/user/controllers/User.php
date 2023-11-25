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
            $settings =array(
                "imageurl" => $this->User_model->image_url(),
                "profileImageurl" => $this->User_model->profile_image_url()
            );  
            $return['settings'] =  $settings;   
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
                $is_profile_liked = 0;
                if($flag == 1) {
                    $is_profile_liked = 1;
                }
                $return['data'] = array('is_profile_liked' => $is_profile_liked);
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);  
    }

    function reset_password_post() {
               

        $return = $this->return;
        $data = $this->post_data;  
         if (isset($data)) {
            $config = array(
                array(
                    'field' => 'mobile',
                    'label' => 'mobile',
                    'rules' => 'trim|required|is_natural'
                ),
                array(
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'trim|required|min_length[4]|max_length[12]'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == TRUE) { 
                 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                 //die('11111111122222211111111');
                $mobile = $data['mobile'];
                $this->load->model(array('user/User_model'));     

                $user_detail = $this->User_model->get_single_row('id', 'users', array('phone_no' => $mobile, 'active' => 1));
                if(empty($user_detail)) {
                    $user_detail = $this->User_model->get_single_row('id', 'users', array('email' => $mobile, 'active' => 1));
                }
                if(!empty($user_detail) && isset($user_detail['id'])) {
                  
                    $data['user_id'] = $user_detail['id'];
                    //echo $data['user_id']."<br/>";
                   // die('11111111122222211111111');
                    $this->User_model->reset_password($data);
                    $return['message'] = $this->lang->line('password_reset_success');
                } else {
                   
                    $return['response_code'] = rest_controller::HTTP_INTERNAL_SERVER_ERROR;
                    $return['message'] = $this->lang->line('invalid_mobile');
                    $this->response($return);
                }
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);  
    }

    function change_password_post() {
        $return = $this->return;
        $data = $this->post_data;  
        $user_id = $this->user_id;
        if (isset($data)) {
            $config = array(
                array(
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'trim|required|min_length[4]|max_length[12]'
                )
            );
            $this->form_validation->set_rules($config);        
            if ($this->form_validation->run() == FALSE) { 
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $this->load->model(array('user/User_model'));     
                              $user_id=$data['user_id'];
                $user_detail = $this->User_model->get_single_row('id', 'users', array('id' => $user_id));                
                if(!empty($user_detail) && isset($user_detail['id'])) {
                    $data['user_id'] = $user_detail['id'];
                    $this->User_model->reset_password($data);
                    $return['message'] = $this->lang->line('password_reset_success');
                } else {
                   $return['response_code'] = rest_controller::HTTP_INTERNAL_SERVER_ERROR;
                    $return['message'] = $this->lang->line('invalid_mobile');
                    $this->response($return);
                }
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);  
    }
    
    /**
     * used for social login
     * @param
     * @return json array
     */
    public function social_login_post() {
        $return = $this->return;
        $data = $this->post_data; 
        if (isset($data)) {
/*$validation_rule    =   array(
                array(
                    'field' => 'fb_id',
                    'label' => 'facebook id',
                    'rules' => 'check_social_details|callback_validate_facebook_id'
                ),
                array(
                    'field' => 'gp_id',
                    'label' => 'google id',
                    'rules' => 'check_social_details|callback_validate_google_id'
                ),
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|valid_email'
                ),
                array(
                    'field' => 'mobile',
                    'label' => 'mobile',
                    'rules' => 'trim'
                )
            ); */
            $validation_rule    =   array(
               
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|valid_email'
                ),
                array(
                    'field' => 'mobile',
                    'label' => 'mobile',
                    'rules' => 'trim'
                )
            );
            $this->form_validation->set_rules($validation_rule); 
            if($this->form_validation->run() == FALSE)  { //validate post parameter
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $social_data = array();        
                if (isset($data['fb_id']) && $data['fb_id'] != "") {
                    $social_data = array("facebook_uid" => $data['fb_id']);
                } else if (isset($data['gp_id']) && $data['gp_id'] != "") {
                    $social_data = array("google_id" => $data["gp_id"]);
                }

                if (empty($social_data)) {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] =  'Please provide facebook or google id';
                    $this->response($return);  
                }

                $this->load->model(array('user/User_model'));     
                $user_data = $this->User_model->get_single_row("id as user_id,id, username, fullname,phone_no,email,language,timezone,gender,birthdate,active,is_network_admin,is_reporter,avatar as profile_image,num_followers as followers, num_favourites as `like`", 'users', $social_data);

                $auth_key                = '';
                if (empty($user_data) && !empty($data['email'])) {
                    $user_data = $this->User_model->get_single_row('id as user_id,id, username, fullname,phone_no,email,language,timezone,gender,birthdate,active,is_network_admin,is_reporter,avatar as profile_image,num_followers as followers, num_favourites as `like`', 'users', array('email' => $data['email']));
                

                }


                if (empty($user_data) && !empty($data['mobile'])) {
                    $user_data = $this->User_model->get_single_row('id as user_id,id, username, fullname,phone_no,email,language,timezone,gender,birthdate,active,is_network_admin,is_reporter,avatar as profile_image,num_followers as followers, num_favourites as `like`', 'users', array('phone_no' => $data['mobile']));
                }
                
                if (empty($user_data) && (!empty($data["email"]) || (!empty($data["mobile"]))) ) {
                    $data["refer_type"] = "camp";
                    $data["password"] = md5("12345");
                    $data["location_id"] = "105";
                    $data["gender"] = "m";
                     $userdata = $data;
                    
                    $usercretion= $this->social_user_creation($userdata);
                    if (empty($user_data) && !empty($data['email'])) {
                    $user_data = $this->User_model->get_single_row('id as user_id,id, username, fullname,phone_no,email,language,timezone,gender,birthdate,active,is_network_admin,is_reporter,avatar as profile_image,num_followers as followers, num_favourites as `like`', 'users', array('email' => $data['email']));
                

                }
                 if (empty($user_data) && !empty($data['mobile'])) {
                    $user_data = $this->User_model->get_single_row('id as user_id,id, username, fullname,phone_no,email,language,timezone,gender,birthdate,active,is_network_admin,is_reporter,avatar as profile_image,num_followers as followers, num_favourites as `like`', 'users', array('phone_no' => $data['mobile']));
                }
                    
                }
                
                


                //If user already exist than create auth key and return.
                if (!empty($user_data)) {
                    $auth_key = $this->User_model->generate_login_auth_key($user_data['user_id']);
                     $user_data["following"] =  $this->User_model->getUserFollowing($user_id);
                             $role = 'user';
                            if ($user_data["is_network_admin"] == 1) {
                                $role = 'admin';
                            } else if ($user_data["is_reporter"]  == 1) {
                                $role = 'reporter';
                            }
                             $user_data["role"] = $role;

                            $user_data["access_token"] = $auth_key;
                         $userreturn = [];
                         $singleuserdata =[];
                         array_push($singleuserdata, $user_data);
                       
                    $userreturn["user"]=$singleuserdata;
                    $userreturn["status"]["message"]= "Success";
                    $userreturn["status"]["statuscode"]= "0";
                    $userreturn["settings"]["imageurl"] ="storage/avatars/thumbs1/";
                     $userreturn["settings"]["profileImageurl"] ="storage/avatars/thumbs1/";
              
                    
	    
                    $this->response($userreturn); 
                     
                    
                    //$return['data']  = $user_data;
                   // $return['data'][AUTH_TOKEN]         = $auth_key; 
                } else  {
                    if ((isset($data['email']) && $data['email'] != "") || (isset($data['mobile']) && $data['mobile'] != "")) {
                        $data = $social_data;
                    
                        $data['lastlogin_date'] = time();
                        $data['reg_date']       = time();
                        $data['active']         = 1;
                        $data['username']       = ''; // generate
                        $data['reg_ip']         = get_user_ip_address();
                        $data['lastlogin_ip']   = get_user_ip_address();
                        $data["fullname"]       = isset($data['name']) ? $data['name'] : '';
                        $data["email"]          = isset($data['email']) ? $data['email'] : '';
                        $data['birthdate']      = isset($data['dob']) ? $data['dob'] : '';
                        if(isset($data['gender']) && in_array($data['gender'], array('m','f'))) {
                            $data['gender'] = $data['gender'];
                        }                        
                        $data['phone_no']  = isset($data['mobile']) ? $data['mobile'] : '';

                        $settings = $this->User_model->get_single_row('value', 'settings', array('word' => 'LANGUAGE'));
                        if($settings) {
                            $data['language'] = $settings['value'];
                        }

                        $settings = $this->User_model->get_single_row('value', 'settings', array('word' => 'DEF_TIMEZONE'));
                        if($settings) {
                            $data['timezone'] = $settings['value'];
                        }
                        
                        if(!empty($data["email"])) {
                            $data['username']  = $this->User_model->generate_user_name($data['email']);
                        }

                        $user_id = $this->User_model->sign_up($data);
                       
                        if ($user_id) {
                            $user_data['user_id'] = $user_id;
                            $user_data['email']     = $data['email'];                            
                            $user_data['username'] = $data['username'];
                            $user_data["following"] =  $this->User_model->getUserFollowing($user_id);
                             $role = 'user';
                            if ($user_data["is_network_admin"] == 1) {
                                $role = 'admin';
                            } else if ($user_data["is_reporter"]  == 1) {
                                $role = 'reporter';
                            }
                             $user_data["role"] = $role;

                            $auth_key = $this->User_model->generate_login_auth_key($user_data['user_id']);
                            $user_data["access_token"] = $auth_key;
                            $return['data']   = $user_data;
                            $return['data'][AUTH_TOKEN]         = $auth_key; 
                        }
                    } else {
                        $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                        $return['message'] = 'Please provide email or mobile number';
                        $userreturn = [];
                        $userreturn["status"]["message"]= "Please provide email or mobile number";
                         $userreturn["status"]["statuscode"]= "100";
                          $this->response($userreturn);  
                        
                    }                    
                }                
            }
        } else {
             $userreturn = [];
             $userreturn["status"]["message"]= "Please provide email or mobile number";
            $userreturn["status"]["statuscode"]= "100";
                          $this->response($userreturn);  
           /* $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');*/
        }
        $this->response($return);  
    }

    /**
     * used for social login
     * @param
     * @return json array
     */
   /* public function social_login_post() {
        $return = $this->return;
        $data = $this->post_data; 
        if (isset($data)) {
            $validation_rule    =   array(
               /* array(
                    'field' => 'fb_id',
                    'label' => 'facebook id',
                    'rules' => 'check_social_details|callback_validate_facebook_id'
                ),
                array(
                    'field' => 'gp_id',
                    'label' => 'google id',
                    'rules' => 'check_social_details|callback_validate_google_id'
                ),
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|valid_email'
                ),
                array(
                    'field' => 'mobile',
                    'label' => 'mobile',
                    'rules' => 'trim'
                )
            );
            $this->form_validation->set_rules($validation_rule); 
          
            if($this->form_validation->run() == FALSE  )  { //validate post parameter
                $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                $return['message'] = $this->form_validation->rest_first_error_string();
            } else {
                $social_data = array();        
                if (isset($data['fb_id']) && $data['fb_id'] != "") {
                    $social_data = array("facebook_uid" => $data['fb_id']);
                } else if (isset($data['gp_id']) && $data['gp_id'] != "") {
                    $social_data = array("google_id" => $data["gp_id"]);
                }

                if (empty($social_data)) {
                    $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                    $return['message'] =  'Please provide facebook or google id';
                    $this->response($return);  
                }
                

                $this->load->model(array('user/User_model'));     
                $user_data = $this->User_model->get_single_row("id as user_id, username, email", 'users', $social_data);

                $auth_key                = '';
                if (empty($user_data) && !empty($data['email'])) {
                    $user_data = $this->User_model->get_single_row('id as user_id, username, email', 'users', array('email' => $data['email']));
                }

                if (empty($user_data) && !empty($data['mobile'])) {
                    $user_data = $this->User_model->get_single_row('id as user_id, username, email', 'users', array('phone_no' => $data['mobile']));
                }

                //If user already exist than create auth key and return.
                if (!empty($user_data)) {
                    $auth_key = $this->User_model->generate_login_auth_key($user_data['user_id']);
                    $return['data']  = $user_data;
                    $return['data'][AUTH_TOKEN]         = $auth_key; 
                } else  {
                    if ((isset($data['email']) && $data['email'] != "") || (isset($data['mobile']) && $data['mobile'] != "")) {
                        $data = $social_data;
                    
                        $data['lastlogin_date'] = time();
                        $data['reg_date']       = time();
                        $data['active']         = 1;
                        $data['username']       = ''; // generate
                        $data['reg_ip']         = get_user_ip_address();
                        $data['lastlogin_ip']   = get_user_ip_address();
                        $data["fullname"]       = isset($data['name']) ? $data['name'] : '';
                        $data["email"]          = isset($data['email']) ? $data['email'] : '';
                        $data['birthdate']      = isset($data['dob']) ? $data['dob'] : '';
                        if(isset($data['gender']) && in_array($data['gender'], array('m','f'))) {
                            $data['gender'] = $data['gender'];
                        }                        
                        $data['phone_no']  = isset($data['mobile']) ? $data['mobile'] : '';

                        $settings = $this->User_model->get_single_row('value', 'settings', array('word' => 'LANGUAGE'));
                        if($settings) {
                            $data['language'] = $settings['value'];
                        }

                        $settings = $this->User_model->get_single_row('value', 'settings', array('word' => 'DEF_TIMEZONE'));
                        if($settings) {
                            $data['timezone'] = $settings['value'];
                        }
                        
                        if(!empty($data["email"])) {
                            $data['username']  = $this->User_model->generate_user_name($data['email']);
                        }

                        $user_id = $this->User_model->sign_up($data);
                        if ($user_id) {
                            $user_data['user_id'] = $user_id;
                            $user_data['email']     = $data['email'];                            
                            $user_data['username'] = $data['username'];
                            
                            $auth_key = $this->User_model->generate_login_auth_key($user_data['user_id']);
                            $return['data']   = $user_data;
                            $return['data'][AUTH_TOKEN]         = $auth_key; 
                        }
                    } else {
                        $return['response_code'] = self::HTTP_PRECONDITION_FAILED;
                        $return['message'] = 'Please provide email or mobile number';
                    }                    
                }                
            }
        } else {
            $return['response_code'] = self::HTTP_BAD_REQUEST;
            $return['message'] = $this->lang->line('invalid_request_format');
        }
        $this->response($return);  
    } */

    /**
     * check_social_details to validate social id
     */
    public function check_social_details($str, $field) {
        $data = $this->post_data;  

        if (!empty($data['fb_id']) || !empty($data['gp_id'])) {
            return TRUE;
        }

        $this->form_validation->set_message('check_social_details', 'Please provide facebook or google id');
        return FALSE;
    }

     /**
     * facebook_valid to validate facebook id
     * @param
     * @return json array
     */
    public function validate_facebook_id() {
        $data = $this->post_data;  
        if (empty($data['fb_id'])) {
            return TRUE;
        }

        if (!$data['fb_access_token']) {
            $this->form_validation->set_message('validate_facebook_id', 'Please provide access token.');
            return FALSE;
        }
        $facebook_id = $this->fb_auth();
        if ($data['fb_id'] == $facebook_id) {
            return TRUE;
        }

        $message = 'Invalid facebook id';

        if ($facebook_id) {
            $message = $facebook_id;
        }
        $this->form_validation->set_message('validate_facebook_id', $message);
        return FALSE;
    }

    /**
     * Facebook to check FB access token
     * @return json array
     */
    public function fb_auth() {
        $fb = new Facebook\Facebook([
            'app_id' => FB_API_KEY,
            'app_secret' => FB_API_SECRETE,
            'default_graph_version' => 'v2.8',
        ]);

        $response = FALSE;
        $error = FALSE;

        $data = $this->post_data; 
        $accessToken = $data['fb_access_token'];

        if (!isset($accessToken)) {
            return FALSE;
        }
        // Logged in!
        $accessToken = (string) $accessToken;

        // OAuth 2.0 client handler
        $oAuth2Client = $fb->getOAuth2Client();

        try {
            // Exchanges a short-lived access token for a long-lived one
            $long_lived_access_token = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            return $e->getMessage();
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            return $e->getMessage();
        }

        // Sets the default fallback access token so we don't have to pass it to each request
        $fb->setDefaultAccessToken($long_lived_access_token);

        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $fbnode = $fb->get('/me', $accessToken);
            $response = $fbnode->getGraphUser();
            $facebook_id = $response->getId();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            return $e->getMessage();
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            return $e->getMessage();
        }

        if ($response && isset($facebook_id) && $facebook_id) {
            return $facebook_id;
        }
        return FALSE;
    }

    /**
     * validate_google_id to validate google id
     * @return json array
     */
    public function validate_google_id() {
        $data = $this->post();
        if (empty($data['gp_id'])) {
            return TRUE;
        }

        if (!$data['gp_access_token']) {
            $this->form_validation->set_message('validate_google_id', 'Please provide access token.');
            return FALSE;
        }
        $google_id = $this->check_google_auth();

        if ($data['gp_id'] == $google_id) {
            return TRUE;
        }
        $this->form_validation->set_message('validate_google_id', 'Invalid google id');
        return FALSE;
    }

    /**
     * check_google_auth to check google access token
     * @return json array
     */
    public function check_google_auth() {
        $id_token = $this->input->post('gp_access_token');
        $client_id = ANDROID_GOOGLE_CLIENT_ID;

        $client = new Google_Client();
        $client->setApplicationName(GOOGLE_APPLICATION_NAME);
        $client->setClientId($client_id);
        $client->fetchAccessTokenWithAuthCode($id_token);
        $attributes = $client->verifyIdToken($id_token, $client_id);
        if (isset($attributes["sub"])) {
            return $attributes["sub"];
        }
        return FALSE;
    }
    public function social_user_creation($data){
        
            $data['lastlogin_date'] = time();
            $data['reg_date']       = time();
            $data['active']         = 1;
            $data['username']       = ''; // generate
            $data['reg_ip']         = get_user_ip_address();
            $data['lastlogin_ip']   = get_user_ip_address();
            $data["fullname"]       = isset($data['name']) ? $data['name'] : '';
            $data["email"]          = isset($data['email']) ? $data['email'] : '';
            $data['birthdate']      = isset($data['dob']) ? $data['dob'] : '';
            if(isset($data['gender']) && in_array($data['gender'], array('m','f'))) {
                $data['gender'] = $data['gender'];
            }                        
            $data['phone_no']  = isset($data['mobile']) ? $data['mobile'] : '';

            $settings = $this->User_model->get_single_row('value', 'settings', array('word' => 'LANGUAGE'));
            if($settings) {
                $data['language'] = $settings['value'];
            }

            $settings = $this->User_model->get_single_row('value', 'settings', array('word' => 'DEF_TIMEZONE'));
            if($settings) {
                $data['timezone'] = $settings['value'];
            }
            
            if(!empty($data["email"])) {
                $data['username']  = $this->User_model->generate_user_name($data['email']);
            }

            $user_id = $this->User_model->socialusersignup($data);
          
            $predictarr = [];
            $predictarr["User_id"]             = $user_id;
            $predictarr["Level"]             = 'BEGINNER';
        	$predictarr["Currency"]          = 'INR';
        	$predictarr["Hit"]              = 0;
        	$predictarr["miss"]               = 0;
        	$predictarr["AvailableEarnings"] =0;
        	$predictarr["WithdrawnEarnings"] =0;
        	$predictarr["TotalPrediction"] =0;
        	$predictarr["NotionalAmount"] =0;
        	$predictarr["CreatedDate"]  =date('Y-m-d h:i:s');
        	$predictarr["UpdateDate"]  =date('Y-m-d h:i:s');
        	$this->User_model->userextradata($predictarr,"user_predict_details");
        	$cats   = array(1,2,3,4,5,6,7,8,9,10,11,12);
         	$catids = 	implode(",",$cats);
         	$categeoryarr= [];
         	$categeoryarr["cat_ids"] =$catids;
         	$categeoryarr["user_id"] =$user_id;
         		$this->User_model->userextradata($categeoryarr,"user_categeory");
         	$dasharr = [];
         	$dasharr["user_id"] =$user_id;
         	$dasharr["tab"] ="notifications";
         	$dasharr["state"] ="1";
         	$dasharr["newposts"] ="1";
         		$this->User_model->userextradata($dasharr,"users_dashboard_tabs");
                     	
                     	

                        
    
    }
}
