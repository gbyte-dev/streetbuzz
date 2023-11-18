<?php

/**
 * @package Reporter
 * @category Reporter
 */
class Reporter extends CI_Controller {

    public function index() {
		die('s');
	}
    
    public function mark_deactive() {
        $this->load->model(array('cron/Cron_model'));
        $this->Cron_model->update_reporter_status();
    }

    public function top_newspersons() {
        $this->load->model(array('cron/Cron_model'));
        $this->Cron_model->top_newspersons();
    }
    
}