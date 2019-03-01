<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('eventModel');
		$this->load->model('languageModel');
		$this->load->model('eventCategoryModel');
	}
	function index(){
		// $data['list_cat'] = selectlist2(array('table'=>'ref_kategori_logframe','title'=>'All Category'));
		render('apps/event/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			// echo $id;exit;
			// $data = $this->eventModel->findById($id);
			$id_lang_default	= $this->languageModel->langId();
			$datas 	= $this->eventModel->selectData($id);
			// echo "<pre>";
			// print_r($datas);
			// echo "</pre>";
			if(!$datas){
				die('404');
			}
			$data 					= quote_form($datas);
			$data['judul']			= 'Edit';
			$data['proses']			= 'Update';
		}else{
			$data['judul']			= 'Add';
			$data['proses']			= 'Save';
			$data['name_data']		= '';
			$data['start_time']		= '';
			$data['end_time']		= '';
			$data['uri_path']		= '';
			$data['teaser']			= '';
			$data['location']		= '';
			$data['content']		= '';
			$data['start_date']		= '';
			$data['brochures']		= '';
			$data['material1']		= '';
			$data['material2']		= '';
			$data['material3']		= '';
			$data['end_date']		= '';
			$data['speaker']		= '';
			$data['id'] 			= '';
			$data['id_parent_lang']	= '';
		}

		$data['list_lang']	= $this->languageModel->langName();

		foreach ($data['list_lang'] as $key => $value){
			$data['list_lang'][$key]['invis'] 			= ($key==0) ? '' : 'hide';
			$data['list_lang'][$key]['active'] 			= ($key==0) ? 'active in' : '';
			$data['list_lang'][$key]['validation'] 		= ($key==0) ? 'true' : 'false';
			$data['list_lang'][$key]['nomor'] 			= $key;

			$data['list_lang'][$key]['id']				= $datas[$key]['id'];
			$data['list_lang'][$key]['name_data']		= $datas[$key]['name'];
			$data['list_lang'][$key]['start_time']		= $datas[$key]['start_time'];
			$data['list_lang'][$key]['end_time']		= $datas[$key]['end_time'];
			$data['list_lang'][$key]['teaser'] 			= quote_form($datas[$key]['teaser']);
			$data['list_lang'][$key]['uri_path']		= $datas[$key]['uri_path'];
			$data['list_lang'][$key]['is_open']			= ($datas[$key]['is_open']==1) ? 'checked' : '';
			$data['list_lang'][$key]['content']			= $datas[$key]['content'];
			$data['list_lang'][$key]['speaker']			= $datas[$key]['speaker'];
			$data['list_lang'][$key]['location']		= $datas[$key]['location'];
			$data['list_lang'][$key]['brochures']		= $datas[$key]['brochures'];
			$data['list_lang'][$key]['material1']		= $datas[$key]['material1'];
			$data['list_lang'][$key]['material2']		= $datas[$key]['material2'];
			$data['list_lang'][$key]['material3']		= $datas[$key]['material3'];
			$data['list_lang'][$key]['start_date']		= iso_date($datas[$key]['start_date']);
			$data['list_lang'][$key]['end_date']		= iso_date($datas[$key]['end_date']);

			$img_thumb											= image($datas[$key]['img'],'small');
			$imagemanager										= imagemanager('img',$img_thumb,320,180,$key);
			$data['list_lang'][$key]['img']						= $imagemanager['browse'];
			$data['list_lang'][$key]['imagemanager_config']		= $imagemanager['config'];
			$data['list_lang'][$key]['list_event_category'] 	= selectlist2(array('table'=>'event_category','where'=> array('id_lang'=>$value['lang_id']),'title'=>'Select Category','selected'=>$datas[$key]['id_event_category']));

		}

		$data['list_lang2'] 	= $data['list_lang'];
		render('apps/event/add',$data,'apps');
	}
	public function view($id=''){
		if($id){
			$datas 	= $this->eventModel->selectData($id);
			// $data = $this->eventModel->findById($id);
			if(!$datas){
				die('404');
			} 
			else {
				foreach ($datas as $key => $value) {
					$data['name'] =  $value['name'];
				}
			}
		}
		$data['id_event'] = $id;
		render('apps/event/view',$data,'apps');
	}
	function records(){
		$data = $this->eventModel->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['name'] = quote_form($value['name']);
			$data['data'][$key]['start_date'] = iso_date($value['start_date']);
			$data['data'][$key]['end_date'] = iso_date($value['end_date']);
			$data['data'][$key]['category'] = $value['category'];
		}
		render('apps/event/records',$data,'blank');
	}	
	function records_participant($id){
		$data = $this->eventModel->records_participant($id);
			foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['name'] = quote_form($value['firstname']);
			$data['data'][$key]['occupation'] = $value['occupation'];
			$data['data'][$key]['institution'] = $value['institution'];
			$data['data'][$key]['email'] = $value['email'];
			$data['data'][$key]['status'] = ($value['is_approve'] == 1 ? 'Approved' : '-');
			$data['data'][$key]['id'] = $value['id'];
			$data['data'][$key]['event_id'] = (!empty($value['parent_lang']) ? $value['parent_lang'] :  $value['event_id']);
			$data['data'][$key]['st']		= ($value['is_approve'] == 1 ? 'invis' : '' );
		}
		render('apps/event/records_participant',$data,'blank');
	}	
	function prosesdocument($field){
		$file = $_FILES[$field];
		// echo json_encode($_FILES);
		 // print_r($file);
		$fname = rand(100,999).'-'.$file['name'];
		move_uploaded_file($file['tmp_name'],"document/temp/$fname");
		$ret['fname'] = "$fname";
		echo json_encode($ret);
	}
	function proses($idedit=''){
		$id_user 				= id_user();
		$this->layout 			= 'none';
		$post					= purify($this->input->post());
		$ret['error']			= 1;
		$id_parent_lang 		= NULL;
		$this->db->trans_start(); 
		// $id_parent		= $this->languageModel->langId();

		foreach ($post['teaser'] as $key => $value){
			if($key==0){
				$id_event_category 	= $post['id_event_category'][$key];
			}
			else{
				$id_event_category 	= db_get_one('event_category','id',array('id_parent_lang'=>$post['id_event_category'][0],'id_lang'=>$post['id_lang'][$key])); 
			}
			if(!$idedit){
				// $where['a.uri_path']			= $post['uri_path'][$key];
				// $unik 	= $this->eventModel->findBy($where);
				$this->form_validation->set_rules('name', '"page Name"', 'required'); 
				$this->form_validation->set_rules('uri_path', '"Page URL"', 'required'); 
				$this->form_validation->set_rules('teaser', '"Teaser"', 'required'); 
				$this->form_validation->set_rules('content', '"Content"', 'required'); 
				if ($this->form_validation->run() == FALSE){
					$ret['message']  	= validation_errors(' ',' ');
				}
				if($unik){
					$ret['message']		= "Page URL $value already taken";
				}
			}
			
			if($idedit){
				$where['id !=']		= $idedit;
			}
			$idedit		= $post['id'][$key];
				
			$data_save['id_event_category']		= $id_event_category;
			$data_save['name']					= $post['name'][$key];
			$data_save['location']				= $post['location'][0];
			$data_save['start_time']			= $post['start_time'][0];
			$data_save['end_time']				= $post['end_time'][0];
			$data_save['start_date']			= iso_date($post['start_date'][0]);
			$data_save['end_date']				= iso_date($post['end_date'][0]);
			$data_save['speaker']				= $post['speaker'][0];
			$data_save['is_open']				= ($post['is_open'][0]==1) ? 1 : 0;
			$data_save['teaser']				= $post['teaser'][$key];
			$data_save['content'] 				= $post['content'][$key];
			$data_save['uri_path'] 				= $post['uri_path'][$key];
			$data_save['id_lang']				= $post['id_lang'][$key];
			$data_save['id_parent_lang']= $id_parent_lang;
			$brochures = $post['brochures'][0];
			$data_save['brochures']		= $brochures;
			if(is_file("document/temp/$brochures")){
				rename("document/temp/$brochures","document/brochures/$brochures");
			}
			$material1 = $post['material1'][0];
			$data_save['material1']		= $material1;
			if(is_file("document/temp/$material1")){
				rename("document/temp/$material1","document/material/$material1");
			}
			$material2 = $post['material2'][0];
			$data_save['material2']		= $material2;
			if(is_file("document/temp/$material2")){
				rename("document/temp/$material2","document/material/$material2");
			}

			$material3 = $post['material3'][0];
			$data_save['material3']		= $material3;
			if(is_file("document/temp/$material3")){
				rename("document/temp/$material3","document/material/$material3");
			}
			
			if($idedit && $post['img'][$key]){
				$data_save['img']	= $post['img'][$key];
			}elseif($idedit){
				$datas 				= $this->eventModel->selectData($idedit);
				$data_save['img']	= $datas[$key]['img'];
			}else{
				$data_save['img']	= $post['img'][$key];
			}

			if($idedit){
					auth_update();
					$ret['message'] = 'Update Success';
					$act			= "Update Event";
					// if(!$post['img'][$key]){
					// 	unset($post['img'][$key]);
					// }
					$iddata 		= $this->eventModel->update($data_save,$idedit);
			}else{
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act			= "Insert Event";
				$iddata 		= $this->eventModel->insert($data_save);
				// print_r($unik);
			}
			if($key==0){
				$id_parent_lang	= $iddata;
			}
			detail_log();
			insert_log($act);
			$this->db->trans_complete();
			set_flash_session('message', $ret['message']);
			$ret['error'] = 0;
		}
		echo json_encode($ret);
	}
	function del(){
		auth_delete();
		$id = $this->input->post('iddel');
		$this->eventModel->delete($id);
		$this->eventModel->delete2($id);
		detail_log();
		insert_log("Delete Event");
	}
	function select_category(){
		render('apps/event/select_category',$data,'blank');
	}
	function record_select_category(){
		$data = $this->eventCategoryModel->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['name'] = quote_form($value['name']);
		}
		render('apps/event/record_select_category',$data,'blank');
	}

	function record_select_page(){
		$data = $this->eventModel->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['name'] = quote_form($value['name']);
		}
		render('apps/event/record_select_page',$data,'blank');
	}
	public function approve($event_id='',$id=''){
		if($id){
			$datas 	= $this->eventModel->selectDataParticipant($id);
			if(!$datas){
				die('404');
			} 
			else {
				foreach ($datas as $key => $value) {
					$data['name'] 		=  $value['firstname'];
					$data['id'] 		=  $id;
					$data['email'] 		=  $value['email'];
					$data['occupation'] 		=  $value['occupation'];
					$data['institution'] 	=  $value['institution'];
					$data['dob'] 		=  iso_date($value['dob']);
					$data['gender'] 	=  ($value['gender'] = 1 ? 'Male' : 'Female');
				}
			}
		}
		$data['event_id'] = $event_id;
		render('apps/event/view_participant',$data,'apps');
	}

	function proses_approve($event_id='',$id=''){

		if (!empty($id)){
			$data_save['is_approve'] = 1;
			$update_status = $this->eventModel->updateApprovaalParticipant($data_save,$id);
			if ($update_status){
				//redirect ke halaman participant
				redirect(base_url("apps/event/view/$event_id"));
			}
		}
	}
}

/* End of file event.php */
/* Location: ./application/controllers/apps/event.php */