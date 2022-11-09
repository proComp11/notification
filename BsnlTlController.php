<?php 

defined('BASEPATH') or exit('No direct script access allowed');
class BsnlTlController extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Retention_model');
	}

	public function bsnlTlView(){
		$data['page_heading'] = 'BSNL TL';
	    $data['active_page'] = 'BSNL TL';
	    $data['page'] = 'BsnlTl/bsnltlview';
	    $data['title'] = 'BSNL TL Page';
	    $this->load->view('common/index', $data);
	}

	public function fetchDetails(){
		if($this->input->is_ajax_request()){
			$mssid = $this->input->post('mssid');
			$url = 'http://59.91.63.210:9099/getlocation?username=CTD_112&password=Kolkatapolice1@&msisdn='.$mssid;
	        $crl = curl_init();
	        curl_setopt($crl, CURLOPT_URL, $url);
	        curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
	        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
	         
	        $response = curl_exec($crl);
	        if(!$response){
	           die('Error: "' . curl_error($crl) . '" - Code: ' . curl_errno($crl));
	        }else{
	        	header('Content-Type: application/json; charset=utf-8');
	        	$city = $this->getCityCode($response);
	        	$finaldata = [
	        		'city' => $city,
	        		'result' => $response
	        	];
	        	echo json_encode($finaldata);
	        } 
	        curl_close($crl);
		}
	}

	public function getCityCode($data){
		$respData = json_decode($data, true);
		$imsiCode = $this->Retention_model->getAllBsnlImsi();
		foreach($imsiCode as $key => $value){

			if(strlen($value->msc_code) == 5){

				if($value->msc_code == substr($respData['imsi'],0,5)){
					return $value->city;
					break;
				}
			}
		}
	}

}
?>