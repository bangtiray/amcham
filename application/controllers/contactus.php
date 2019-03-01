<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contactus extends CI_Controller {
	function __construct(){
		parent::__construct();
		
	}
    function index(){
    	$post   = purify($this->input->post());
		$reload = false;
    	$this->data['recaptcha_site_key'] = GOOGLE_CAPTCHA_SITE_KEY;
    	if($post){
    		$this->form_validation->set_rules('name', '"Name"', 'required'); 
    		$this->form_validation->set_rules('email', '"Email"', 'required|valid_email'); 
    		$this->form_validation->set_rules('phone_number', '"Phone"', 'required'); 
    		$this->form_validation->set_rules('message', '"Message"', 'required'); 
			if ($this->form_validation->run() == FALSE){
				 $message = validation_errors();
				 $status  = 'error';
			}else{
				$secret   = GOOGLE_CAPTCHA_SECRET_KEY;
				$response = $post['g-recaptcha-response'];
				$ip       = $_SERVER['REMOTE_ADDR'];

				$Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$response."&remoteip=".$ip);

		        $Return   = json_decode($Response);

		        if($Return->success){
		        	if ($Return->score > 0.4) {
			    		$this->load->model('contactUsModel');
						// $post['topic_name'] = $this->db->select('name')->get_where('contact_us_topic',"id='$post[id_contact_us_topic]'")->row()->name;
						$post['contact_date'] = iso_date_custom_format(date('Y-m-d H:i:s'),'d-m-Y H:i:s');
						unset($post['g-recaptcha-response']);
			    		$proses               = $this->contactUsModel->insert($post);
			    		
			    		if($proses){
		            		sent_email_by_category(14,$post,'');

			    			$status  = 'success';
			    			$message = "Thanks, Your message  will be proses 5 day work.";
			    		}
			    		else{
			    			$status  = 'error';
			    			//error database
			    			$message = "Sorry, Please Try Again proses";
			    			$reload = true;
			    		}

		        	}else{
		    			$status  = 'error';
		    			//error validate
		    			$message = "Sorry, Please Try Again proses";
			    		$reload = true;
		    		}
		        }else{   
		        	$status  = 'error';
		        	//error post google 
			    	$message = "Sorry, Please Try Again";
		    		$reload = true;
		        }
	    		
			}

	        $data['message'] = "$message";
	        $data['status']  = $status;
	        $data['reload']  = $reload;
	        echo json_encode($data);

    	}
    	else{
    		$data['page_content'] = $this->parser->parse("layout/ddi/contact_us_form.html",$this->data,true); 

    		$data['hide_breadcrumb'] = 'hidden';
    		$data['page_name'] = 'Contact Us';

    		if($data['seo_title'] == ''){
    		    $data['seo_title'] = "American Chamber of Commerce in Indonesia";
    		}

    		$data['amcham_committe_list'] = '';
    		$data['meta_description'] = preg_replace('/<[^>]*>/', '', $data['meta_description']);
    		$data['banner_top']       = banner_top();
    		$data['widget_sidebar']   = widget_sidebar();

    		render('pages',$data); 
    	}
    }
}