<?php

/**
 * @package Cron
 * @category Cron
 */
class Viewership extends CI_Controller {

    public function index()
	{
		die('s');
	}
    
    public function reporter_viewership() {
        $this->benchmark->mark('code_start');
        $this->load->model(array('cron/Cron_model'));
        $this->Cron_model->reporter_viewership();
        $this->benchmark->mark('code_end');
        echo "reporter_viewership Execution Time: ".$this->benchmark->elapsed_time('code_start', 'code_end');  
    }

    public function day_wise_user_followers_count() {
        $this->benchmark->mark('code_start');
        $this->load->model(array('cron/Cron_model'));
        $this->Cron_model->user_followers_count();
        $this->benchmark->mark('code_end');
        echo "day_wise_user_followers_count Execution Time: ".$this->benchmark->elapsed_time('code_start', 'code_end');  
    }

    public function user_day_wise_news_engagement() {
        $this->benchmark->mark('code_start');
        $this->load->model(array('cron/Cron_model'));
        $this->Cron_model->user_day_wise_news_engagement();
        $this->benchmark->mark('code_end');
        echo "day_wise_news_engagement Execution Time: ".$this->benchmark->elapsed_time('code_start', 'code_end');  
    }
}