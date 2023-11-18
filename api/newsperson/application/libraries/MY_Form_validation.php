<?php

/**
 * Description of MY_Form_validation
 */
class MY_Form_validation extends CI_Form_validation {

    private $_rest_validation = false;
    public $_config_rules = '';

    function __construct($rules = array()) {
        parent::__construct($rules);
        
    }

    /**
     * Return error array for rest services
     * @return array
     */
    function rest_errors() {
        return $this->_error_array;
    }

    /**
     * Return error string for rest services
     * @return string
     */
    public function rest_first_error_string() {
        foreach ($this->_error_array as $key => $value) {
            return $value;
        }
    }
    
    /**
     * Return error string for rest services
     * @return string
     */
    public function rest_error_string() {
        $str = implode(' ', $this->_error_array);
        return $str;
    }

    /**
     * Set validation for rest sercices
     * @param type $_rest_validation
     * @param type $post
     */
    function set_rest_validation($_rest_validation, $post) {
        $this->_rest_validation = $_rest_validation;
        if (empty($post)) {
            $post['dummydata'] = "";
        }
        $_POST = $post;
    }

    function _reset_validation() {
       // Store current rules
       $rules = $this->_config_rules;
      //print_r($rules);
       // Create new validation object      
       $form_validation = new MY_Form_validation();
     
       // Reset rules
       $form_validation->_config_rules = $rules;
       return $form_validation;
    }
	

    

    
    function validate_date($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        if($d && $d->format($format) == $date)
        {
          return true;  
        }
        else
        {
           $this->set_message('validate_date', "Please provide the valid %s.");
            return false; 
        }
    }

}
