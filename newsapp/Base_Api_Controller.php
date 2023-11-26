<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Base class for all rest api
 * @package Core
 * @category Rest
 */
class Base_Api_Controller extends REST_Controller {

    public $data = array();
    public $post_data;
    public $headers = array();
    public $email = FALSE;
    public $user_id = FALSE;
    public $is_network_admin = 0;
    public $language = FALSE;
    public $lang_abbr = FALSE;
    public $is_app = 0;
    public $return = array('response_code' => self::HTTP_OK,
            'message'      => 'Success',
            'data'         => array(),
            'service_name'  => "",
            "error" => array(),
            "data" => array()
        );
    public $auth_token;

    public function __construct() {
        parent::__construct();
        
        $this->post_data = $this->post();
        if(empty($this->post_data))  {
            $this->post_data = $this->get();
        }

        //set service name
        $method = ($this->router->method == 'index') ? NULL : '/' . $this->router->method;
        $this->return['service_name'] = $this->router->class . $method;

        $this->set_cors_header();
        $this->set_app_lang();
        
        //securiy xss clean
        $this->post_data = $this->security->xss_clean($this->post_data, TRUE);
        if (isset($this->post_data['page_no'])) {
            //$this->post_data['page_no'] = is_integer($this->post_data['page_no']) ? $this->post_data['page_no'] : 1;
        }
        if (isset($this->post_data['page_size'])) {
            //$this->post_data['page_size'] = is_integer($this->post_data['page_size']) ? $this->post_data['page_size'] : 20;
        }

        //Set REST API Validation Configuration
        $this->form_validation->set_rest_validation(TRUE, $this->post_data);


        $this->auth_override = $this->_check_auth_override();
        $this->auth_token = $this->input->get_request_header(AUTH_TOKEN);
        //this condition for uc browser lowecase issue
        if (!$this->auth_token) {
            $this->auth_token = $this->input->get_request_header(strtolower(AUTH_TOKEN));
        }
        
        $this->headers[AUTH_TOKEN] = $this->auth_token;

        //Do your magic here
        if ($this->auth_override === FALSE || $this->auth_token) {
            $this->_prepare_basic_auth();
        }
    }

    /**
     * set cross origin header
     * @return boolean
     */
    protected function set_cors_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization," . AUTH_TOKEN.",Version");
        // If the request HTTP method is 'OPTIONS', kill the response and send it to the client
        if ($this->input->method() === 'options') {
            exit;
        }
    }

    /**
     * set application language based of user request
     * @return boolean
     */
    public function set_app_lang($lang = FALSE) {
        $language_list = $this->config->item('language_list');
        if (!$lang) {
            $header_language = $this->input->get_request_header('Accept-Language');

            if ($header_language && isset($language_list[$header_language])) {
                $lang = $language_list[$header_language];
            } else {
                $lang = $this->config->item('language');
            }
        } else {
            if ($lang && isset($language_list[$lang])) {
                $lang = $language_list[$lang];
            } else if ($lang && in_array($lang, $language_list)) {
                $lang = trim($lang);
            } else {
                $lang = $this->config->item('language');
            }
        }

        $this->language = $lang;
        $this->lang_abbr = array_search($lang, $language_list);
        $this->config->set_item('language', $this->language);
        $this->lang->load('general', $this->language);
        $this->lang->load('form_validation', $this->language, TRUE);
        return TRUE;
    }

    /**
     * Retrieve the validation errors array and send as response.
     * @return none
     */
    public function send_validation_errors($return_only = FALSE) {
        $errors = $this->form_validation->error_array();
        $return['response_code'] = REST_Controller::HTTP_INTERNAL_SERVER_ERROR;
        $return['error'] = $errors;
        $return['service_name'] = '';
        $return['message'] = '';
        $return['data'] = '';

        if ($return_only === TRUE) {
            return $return;
        }

        $this->response($return, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Check if there is a specific auth type set for the current class/method/HTTP-method being called
     *
     * @access protected
     * @return bool
     */
    protected function _check_auth_override() {
        // Assign the class/method auth type override array from the config
        $auth_override_class_method = $this->config->item('auth_override_class_method');

        // Check to see if the override array is even populated
        if (!empty($auth_override_class_method)) {
            // check for wildcard flag for rules for classes
            if (!empty($auth_override_class_method[$this->router->class]['*'])) { // Check for class overrides
                // None auth override found, prepare nothing but send back a TRUE override flag
                if ($auth_override_class_method[$this->router->class]['*'] === 'none') {
                    return TRUE;
                }

                // Basic auth override found, prepare basic
                if ($auth_override_class_method[$this->router->class]['*'] === 'custom') {
                    $this->_prepare_basic_auth();

                    return TRUE;
                }
            }

            // Check to see if there's an override value set for the current class/method being called
            if (!empty($auth_override_class_method[$this->router->class][$this->router->method])) {
                // None auth override found, prepare nothing but send back a TRUE override flag
                if ($auth_override_class_method[$this->router->class][$this->router->method] === 'none') {
                    return TRUE;
                }

                // Basic auth override found, prepare basic
                if ($auth_override_class_method[$this->router->class][$this->router->method] === 'custom') {
                    $this->_prepare_basic_auth();

                    return TRUE;
                }
            }
        }

        // Assign the class/method/HTTP-method auth type override array from the config
        $auth_override_class_method_http = $this->config->item('auth_override_class_method_http');

        // Check to see if the override array is even populated
        if (!empty($auth_override_class_method_http)) {
            // check for wildcard flag for rules for classes
            if (!empty($auth_override_class_method_http[$this->router->class]['*'][$this->request->method])) {
                // None auth override found, prepare nothing but send back a TRUE override flag
                if ($auth_override_class_method_http[$this->router->class]['*'][$this->request->method] === 'none') {
                    return TRUE;
                }

                // Basic auth override found, prepare basic
                if ($auth_override_class_method_http[$this->router->class]['*'][$this->request->method] === 'custom') {
                    $this->_prepare_basic_auth();

                    return TRUE;
                }
            }

            // Check to see if there's an override value set for the current class/method/HTTP-method being called
            if (!empty($auth_override_class_method_http[$this->router->class][$this->router->method][$this->request->method])) {
                // None auth override found, prepare nothing but send back a TRUE override flag
                if ($auth_override_class_method_http[$this->router->class][$this->router->method][$this->request->method] === 'none') {
                    return TRUE;
                }

                // Basic auth override found, prepare basic
                if ($auth_override_class_method_http[$this->router->class][$this->router->method][$this->request->method] === 'custom') {
                    $this->_prepare_basic_auth();

                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Prepares for basic authentication
     *
     * @access protected
     * @return void
     */
    protected function _prepare_basic_auth($auth_token = FALSE) {
        if (!$this->auth_token && $auth_token)
        $this->auth_token = $auth_token;

        $token = $this->auth_token;        
       
        $this->load->model("auth/Auth_model");
        $auth_user_detail = $this->Auth_model->check_auth_token($token);
        

        if (!empty($auth_user_detail)) {
            $this->user_id = $auth_user_detail['user_id'];
            $this->is_network_admin = $auth_user_detail['is_network_admin'];
            return TRUE;
        } else {
            if ($this->auth_override) {
                return TRUE;
            }
            $this->response([
                $this->config->item('rest_status_field_name') => FALSE,
                "response_code" => self::HTTP_UNAUTHORIZED,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_unauthorized')
                    ], self::HTTP_UNAUTHORIZED);
        }
    } 

}