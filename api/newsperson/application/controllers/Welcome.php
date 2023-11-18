<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends CI_Controller {
/**
* Index Page for this controller.
*
* Maps to the following URL
* 		http://example.com/index.php/welcome
*	- or -
* 		http://example.com/index.php/welcome/index
*	- or -
* Since this controller is set as the default controller in
* config/routes.php, it's displayed at http://example.com/
*
* So any other public methods not prefixed with an underscore will
* map to /index.php/welcome/
<method_name>
* @see https://codeigniter.com/user_guide/general/urls.html
*/
public function index()
{
$this->load->view('welcome_message');
}
public function mpfd()
{
/*  require_once($_SERVER['DOCUMENT_ROOT'].'/develop/api/newsperson/assets/fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial","",12);
$pdf->Cell(0,10,"hello",0,1,'C');
$pdf->output();
*/
include($_SERVER['DOCUMENT_ROOT'].'/newsapp/api/newsperson/assets/mpdf/vendor/autoload.php');
$magin=-500;	   $float='float-right';
$data = array();
$this->load->model(array('mpdf/Mpdf_model'));
$month = $_POST["month_report"];
$year = date("Y");
$presentmonth = date('m');
if($presentmonth == "01"){
    if($month == 11 || $month == 12){
        $year = $year-1;
    }
    
}
$fromdatae=$year."-".$month."-"."01";
$todate= $year."-".$month."-"."31";
$adid = $_POST["adid"];
$startuniqdate = strtotime($todate);
//$adid = 20;
$data["userresults"] = $this->Mpdf_model->getUserdetails($adid);
$adstartdate = $data["userresults"][0]["start_date"];
if($adstartdate !== ""){
    $adsfromdatae = date('Y-m-d', $adstartdate);
     $adsmonth = date('m', $adstartdate);
    if($adsmonth == $month){
        $fromdatae = $adsfromdatae;
    }
}


//print_r(date('Y-m-d', $adstartdate));exit;

$data["results"] = $this->Mpdf_model->getAdsRecords($adid,$fromdatae,$todate);

$html=$this->load->view('mpdf/ads_reports', $data, true);;
$mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'stretch']);
$mpdf->autoScriptToLang = true;
$mpdf->baseScript = 1;  // Use values in classes/ucdn.php  1 = LATIN
$mpdf->autoLangToFont = true;
$mpdf->WriteHTML($html);    	                                                  
$file='p1_'.time().'.pdf';
//$mpdf->Output($path.$file, "F");
//$mpdf->output($file,'I');
$mpdf->Output($file, "D");
 $filename = $file;
}
}