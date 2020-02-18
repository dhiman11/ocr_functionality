<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
// echo  FCPATH.'\vendor\autoload.php' ;

require  FCPATH.'/vendor/autoload.php' ; 
// use thiagoalessio\TesseractOCR\TesseractOCR;
use thiagoalessio\TesseractOCR\TesseractOCR;

class Ocr extends CI_Controller {



protected $depertment_list = array('Manager','manager','MANAGER');
protected $surname = array('Zheng');

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
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 public function __construct() {
		 parent::__construct();  
	 }

	public function index()
	{ 
		$this->load->view('ocr_view');
		
	}
	public function azure()
	{ 
		$this->load->view('ocr_azure');
		
	}
	
	//This function   process the data from an image
	public function process_ocr_data()
	{    
			$file = $_FILES['file']['tmp_name'];
 
		 	// ->executable('C:\Program Files\Tesseract-OCR\tesseract.exe')  
			
			$text_data =  (new TesseractOCR($file)) 
			->run();
		
 
 
		  
		 $data = preg_split('/\s+|[?!]/', trim($text_data));
 
	 
		
		$main_array = [];
		$main_array['phone_number'] =[];
		$main_array['email'] = [];
		$main_array['qq'] = [];
		$main_array['position'] = '';
		
		 
		foreach($data as $key=> $value){   
			
				$value  = $this->remove_last_symbold($value);
				$value  = $this->remove_first_symbold($value);
				$value = preg_replace('/[^(\x20-\x7F)]*/','', $value);
			 
				if(strlen($value)>0){  
						 
					
						// this maybe website 
						if(strpos($value, '.com') !== false && strpos($value, '@') !=true){
							$main_array['website'] = $value;
							unset($data[$key]);
						}
						
						
						// this maybe website 
						if(strpos($value, '@') !== false){
							array_push($main_array['email'],$this->detect_email($value));
							unset($data[$key]);

						}
						
						
						if(strpos($value, '86') !== false || (is_numeric($value) ==true && strlen($value)>9 || strpos($value, '-') == true && is_numeric(substr($value, 0, 2)) ==true )  ){
							array_push($main_array['phone_number'],$this->detect_number($value));
							unset($data[$key]);
				 
						}
						
					 
						
						 
						if(strpos($value, 'QQ') !== false || strpos($value, 'qq') !== false ){ 
							$next_key  = $key+1;
							array_push($main_array['qq'] , (isset($data[$key])?$data[$key]:'')." : ".(isset($data[$next_key])?$data[$next_key]:''));
							unset($data[$key]);
							unset($data[$next_key]);
						 
						}
						 if(strlen($value)<=3 && strpos($value, 'qq')  == false){
							 unset($data[$key]);
						 }
						 
						 if(in_array($value,$this->depertment_list)){
							$prev_key  = $key-1;
							$main_array['position'] =$data[$prev_key]." ".$value;
							unset($data[$key]);
							unset($data[$prev_key]);
						 }					

						 if(in_array($value,$this->surname)){
							$prev_key  = $key-1;
							$main_array['name'] =$data[$prev_key]." ".$value;
							unset($data[$key]);
							unset($data[$prev_key]);
						 }
				}
				
					
			 
		}
		
			  $main_array['remarks'] = $data;
			  header('Content-Type: application/json');
		      echo json_encode($main_array);
			 
		
		
		 
 
	}
	
	
	
	private function remove_last_symbold($value){
	
		$symbols_notallowed = array('@','©',":");
		$last_letter  = substr($value, -1);

		if(in_array($last_letter,$symbols_notallowed) ){
		$value = substr($value, 0, -1);  
		}  

		return $value;
		
	}
	
	private function remove_first_symbold($value){ 
		$symbols_notallowed = array('@','©',":","}","{",','); 
		if(isset($value[0])){
			$first_letter  =  $value[0];
		}else{
			$first_letter='';
		}   
		if(in_array($first_letter,$symbols_notallowed) ){
			$value = substr($value,1);  
		}  

		return $value; 
	}
	
	
	
	///This function will detect the wechat
	private function detect_wechat(){
		
	}
	///This function will detect the qq
	private function detect_qq(){
		
	}
	///This function will detect the number with text
	private function detect_number_and_text($value){
		$subject = $value;
		preg_match_all('/\b010-\d+/', $subject, $matches);
		$numbers = $matches[0];
		return $numbers;
	}
	
	///This function will detect the phone number
	private function detect_number($value){
		
		return  preg_replace("/[^0-9,+]/", "", $value);
	}
	///This function will detect the email
	private function detect_email($value){
		return  $value;
	}
	
	///This function will detect the Contact name
	private function detect_name(){
		
	}
	
	
	
	
	
}
