<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('pagesModel');
		$this->load->model('languageModel');
	}
	function index(){
		// $data['list_cat'] = selectlist2(array('table'=>'ref_kategori_logframe','title'=>'All Category'));
		render('apps/pages/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			// $data = $this->pagesModel->findById($id);
			$datas 	= $this->pagesModel->selectData($id);

			if(!$datas){
				die('404');
			}
			$data 				= quote_form($datas);
			$data['judul']		= 'Edit';
			$data['proses']		= 'Update';
			$data['id']			= $id;
		}else{
			$data['judul']				= 'Add';
			$data['proses']				= 'Save';
			$data['page_name_data']		= '';
			$data['uri_path']			= '';
			$data['teaser']				= '';
			$data['page_content']		= '';
			$data['id'] 				= '';
			$data['id_parent_lang']		= '';
			$data['seo_title']			= '';
			$data['meta_description'] 	= '';
			$data['meta_keywords']		= '';
			$data['dsp_main_content'] 	= '';
			$data['required_main_content'] 	= 'true';
		}

		$data['list_lang']	= $this->languageModel->langName();

		foreach ($data['list_lang'] as $key => $value){
			$data['list_lang'][$key]['invis'] 			= ($key==0) ? '' : 'hide';
			$data['list_lang'][$key]['active'] 			= ($key==0) ? 'active in' : '';
			$data['list_lang'][$key]['validation'] 		= ($key==0) ? 'true' : 'false';
			$data['list_lang'][$key]['nomor'] 			= $key;

			$data['list_lang'][$key]['id']				= $datas[$key]['id'];
			$data['list_lang'][$key]['page_name_data']	= $datas[$key]['page_name'];
			$data['list_lang'][$key]['teaser'] 			= quote_form($datas[$key]['teaser']);
			$data['list_lang'][$key]['uri_path']		= $datas[$key]['uri_path'];
			$data['list_lang'][$key]['page_content']	= $datas[$key]['page_content'];
			$data['list_lang'][$key]['seo_title']		= $datas[$key]['seo_title'];
			$data['list_lang'][$key]['meta_description']= $datas[$key]['meta_description'];
			$data['list_lang'][$key]['meta_keywords']	= $datas[$key]['meta_keywords'];
			$data['list_lang'][$key]['dsp_main_content']	    = ($datas[$key]['is_main_content'] != 0) ? 'invis' :'';
			$data['list_lang'][$key]['required_main_content']	= ($datas[$key]['is_main_content'] != 0) ? 'false' :'true';

			$img_thumb											= image($datas[$key]['img'],'small');
			$imagemanager										= imagemanager('img',$img_thumb,320,180,$key,$datas[$key]['page_name'],'title'.$key);
			$data['list_lang'][$key]['img']						= $imagemanager['browse'];
			$data['list_lang'][$key]['imagemanager_config']		= $imagemanager['config'];
		}

		$data['list_lang2'] 	= $data['list_lang'];
		render('apps/pages/add',$data,'apps');
	}
	public function view($id=''){
		if($id){
			$datas 	= $this->pagesModel->selectData($id);
			// $data = $this->pagesModel->findById($id);
			if(!$datas){
				die('404');
			}
			$data['list_lang']	= $this->languageModel->langName();
			foreach ($data['list_lang'] as $key => $value){
				$data['list_lang'][$key]['invis'] 			= ($key==0) ? '' : 'hide';
				$data['list_lang'][$key]['active'] 			= ($key==0) ? 'active in' : '';
				$data['list_lang'][$key]['validation'] 		= ($key==0) ? 'true' : 'false';

				$data['list_lang'][$key]['img_thumb'] 		= image($datas[$key]['img'],'small');
				$data['list_lang'][$key]['img_ori'] 		= image($datas[$key]['img'],'large');
				$data['list_lang'][$key]['page_name'] 		= $datas[$key]['page_name'];
				$data['list_lang'][$key]['page_content'] 	= $datas[$key]['page_content'];
				$data['list_lang'][$key]['teaser'] 			= $datas[$key]['teaser'];
				$data['list_lang'][$key]['nomor'] 			= $key+1;
				$data['list_lang'][$key]['id_parent_lang'] 	= $datas[$key]['id_parent_lang']; 
				$data['list_lang'][$key]['seo_title']		= $datas[$key]['seo_title'];
				$data['list_lang'][$key]['meta_description']= $datas[$key]['meta_description'];
				$data['list_lang'][$key]['meta_keywords']	= $datas[$key]['meta_keywords'];
			}
			$data['list_lang2'] = $data['list_lang'];
		}
		render('apps/pages/view',$data,'apps');
	}
	function records(){
		$data = $this->pagesModel->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['is_main_content'] = ($value['is_main_content'] == 1)? 'invis':'';
		}
		render('apps/pages/records',$data,'blank');
	}	
	
	function proses($idedit=''){
		$id_user 				= id_user();
		$this->layout 			= 'none';
		$post					= purify($this->input->post());
		$ret['error']			= 1;
		$id_parent_lang 		= NULL;
		$this->db->trans_start(); 
		// $id_parent		= $this->languageModel->langId();

		foreach ($post['page_content'] as $key => $value){
			if(!$idedit){
				$where['uri_path']			= $post['uri_path'][$key];
				$unik 	= $this->pagesModel->findBy($where);
				$this->form_validation->set_rules('page_name', '"page Name"', 'required'); 
				$this->form_validation->set_rules('uri_path', '"Page URL"', 'required'); 
				$this->form_validation->set_rules('teaser', '"Teaser"', 'required'); 
				$this->form_validation->set_rules('page_content', '"Content"', 'required'); 
				$this->form_validation->set_rules('seo_title', '"SEO Title"', 'required'); 
				$this->form_validation->set_rules('meta_description', '"Meta Description"', 'required'); 
				$this->form_validation->set_rules('meta_keyword', '"Meta Keyword"', 'required'); 
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
			if($key==0){
				$idedit		= $post['id'][$key];
			}
				
			$data_save['page_name']			= $post['page_name'][$key];
			$data_save['teaser']			= $post['teaser'][$key];
			// $data_save['page_content'] 		= $post['page_content'][$key];
			$data_save['page_content'] 		= htmlspecialchars_decode(urldecode($post['page_content'.$key.'']));
			$data_save['uri_path'] 			= $post['uri_path'][$key];
			$data_save['id_lang']		 	= $post['id_lang'][$key];
			$data_save['id_parent_lang'] 	= $id_parent_lang;
			$data_save['seo_title']			= $post['seo_title'][$key];
			$data_save['meta_description']	= htmlspecialchars_decode(urldecode($post['meta_description'.$key.'']));
			$data_save['meta_keywords']		= $post['meta_keywords'][$key];
			
			if($post['imgDelete'][$key] == 0){
				if($idedit && $post['img'][$key]){
					$data_save['img']	= $post['img'][$key];
				}elseif($idedit){
					$datas 				= $this->pagesModel->selectData($idedit);
					$data_save['img']	= $datas[$key]['img'];
				}else{
					$data_save['img']	= $post['img'][$key];
				}
			} else{
				$data_save['img'] = NULL;
			}

			if($idedit){
				if($key==0){
					// print_r($idedit);
					auth_update();
					$ret['message'] = 'Update Success';
					$act			= "Update Pages";
					// if(!$post['img'][$key]){
					// 	unset($post['img'][$key]);
					// }
					$iddata 		= $this->pagesModel->update($data_save,$idedit);
				}else{
					auth_update();
					$ret['message'] = 'Update Success';
					$act			= "Update Pages";
					// if(!$post['img'][$key]){
					// 	unset($post['img'][$key]);
					// }
					$iddata 		= $this->pagesModel->updateKedua($data_save,$idedit);
				}
			}else{
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act			= "Insert Pages";
				$iddata 		= $this->pagesModel->insert($data_save);
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
		$this->pagesModel->delete($id);
		$this->pagesModel->delete2($id);
		detail_log();
		insert_log("Delete Pages");
	}
	function select_page(){
		render('apps/pages/select_page',$data,'blank');

	}
	function record_select_page(){
		$data = $this->pagesModel->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['page_name'] = quote_form($value['page_name']);
		}
		render('apps/pages/record_select_page',$data,'blank');
	}
}

/* End of file pages.php */
/* Location: ./application/controllers/apps/pages.php */