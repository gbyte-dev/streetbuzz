<?php

/**
 * Auth for user authentication
 * @package Auth
 * @category Auth
 */
class Auth extends Base_Api_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index_get() {
        $this->response(array(config_item('rest_status_field_name') => FALSE), rest_controller::HTTP_NOT_FOUND);
    }

    public function index_post() {
        $this->response(array(config_item('rest_status_field_name') => FALSE), rest_controller::HTTP_NOT_FOUND);
    }    
}