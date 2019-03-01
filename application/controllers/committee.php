<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Committee extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('committee_model');
        $this->load->model('AboutPartnersModel');
        $this->load->model('CommitteTagsModel');
        $this->load->model('eventmodel');
        $this->load->model('committeefilesmodel');
	}
    function index($uri_path){

        $id_lang            = id_lang();


        $data = $this->committee_model->fetchRow(array('a.uri_path'=>$uri_path));
    
        if (!$data) {
            not_found_page();
        } else {
            $data['banner_top']              = banner_top(); // pake banner top

            $data['id']                     = $data['id'];
            $data['name_committee']			= $data['name'];
            $data['teaser'] 				= $data['teaser'];
            $data['page_content']			= $data['page_content'];
            $data['uri_path']				= $data['uri_path'];
            $data['chair']					= $data['chair'];
            $data['co_chair'] 				= $data['co_chair']; 
            $data['chair_img'] 				= getImg($data['chair_img'],'large');
            $data['co_chair_img']			= getImg($data['co_chair_img'],'large');
            $data['seo_title'] 				= $data['name']; 
            $data['meta_description']		= $data['meta_description'];
            $data['meta_keywords']			= $data['meta_keywords'];
            $data['contact']                = $data['contact'];
            $data['hidden_contact']         = $data['contact'] != '' ? '' : 'hidden';
            $data['dsp_committee_chair']    = $data['chair'] != '' ? '' : 'hidden';
            $data['dsp_committee_cochair']  = $data['co_chair'] != '' ? '' : 'hidden';
            $data['dsp_committee_chair_all']= $data['chair'] != '' && $data['co_chair'] != '' ? '' : 'hidden';
 		
        }

        $data['list_year']  = list_year($y,10);
        $data['list_month'] = list_month($m);
    	
        $data['back_to_committee'] = base_url_lang().'/pages/committee';

        // tags
        $tags = $this->CommitteTagsModel->findBy(array('id_committee'=>$data['id']));
        foreach ($tags as $key => $value) 
        {
            $tag .=  ','.$value['id_tags'];
        }
        $data['tags']               = substr($tag,1);

        //partners
        $this->db->group_by('id_partners_category');
        $this->db->limit(3);
        $data['partners'] = $this->AboutPartnersModel->findby(array('id_status_publish' => 2));
        foreach ($data['partners'] as $key => $value1) {
            $data['partners'][$key]['img'] = image($value1['img'],'small');
            $data['partners'][$key]['url'] = $value1['url'] ? $value1['url'] : '#';
        }

        //related_articles
        $data['related_articles'] = $this->committee_model->getArtikelTerkait($data['id'])->result_array();
        $count_related_articles=0;
        foreach ($data['related_articles'] as $key => $value) {
            $count_related_articles++;
            $data['related_articles'][$key]['news_title']       = $value['news_title'];
            $data['related_articles'][$key]['uri_path']         = $value['uri_path'];
            $data['related_articles'][$key]['publish_date']     = $value['publish_date'];
            $data['related_articles'][$key]['teaser']           = $value['teaser'];
            $data['related_articles'][$key]['img']              = getImg($value['img'],'large');
        }
        $data['hidden_related_articles'] = $count_related_articles == 0 ? 'hidden' : '';

        //upcoming_events
        $where_event['a.is_not_available']  = 0;
        // $where_event['a.id_status_publish'] = 2;
        $where_event['a.end_date    >=']   	= date('Y-m-d');
        $where_event['a.id_lang']           = $id_lang;

        $this->db->select('c.name as subcategory');
        $this->db->order_by('a.start_date','desc');
        $this->db->join('event_category c', 'c.id = a.id_event_subcategory', 'left');
        $this->db->join('event_tags d', 'd.id_event = a.id', 'left');
        $data['events'] = $this->eventmodel->findBy($where_event);
        $count_event=0;
        foreach ($data['events'] as $key => $value) {
            $count_event ++;
            $data['events'][$key]['category']    = $value['subcategory'];
            $data['events'][$key]['url']         = base_url().'event/detail/'.$value['uri_path'];
            $data['events'][$key]['name_event']  = $value['name'];
            $data['events'][$key]['time']        = event_date($value['start_date'],$value['start_time'],$value['end_time']);
            $data['events'][$key]['place']       = $value['location_name'];
        }
        $data['hidden_events'] = $count_event == 0 ? 'hidden' : '';

        $user_sess_data             = $this->session->userdata('MEM_SESS');
        $count_committee_materials  = 0 ;

        $data['committee_materials'] = $this->committeefilesmodel->findBy(array('id_committee'=>$data['id']));  
        foreach ($data['committee_materials'] as $key => $value) {
            $value['name'] =  $value['name'] == '' ? 'Materials_'.++$count_committee_materials : $value['name'];
            $data['committee_materials'][$key]['linkddd']        =  '<li>Download '.$value['name'].'<a d-title="committee" id="'.md5($value['id']).'"  onclick="download_event_material(this)"> Click Here</a></li>';
        }

        if (count($data['committee_materials']) == 0) { // kalo gak ada filenya 
            $data['dsp_committee_materials'] = "hidden";
        }else{
            $data['dsp_committee_materials'] = "";
        }

        $data['widget_sidebar']  = widget_sidebar(); //pake sidebar
        
    	render('pages/committee-detail',$data);
    }

    function get_material_hits(){
        $post = $this->input->post();

        $g_data      = $this->db->get_where('committee_files',array('md5(id)'=>$post['idx']))->row_array();

        $ttl_hits    = intval($g_data['hits']);
        $upc['hits'] = $ttl_hits + 1;
        $this->committeefilesmodel->updateAll($upc,$g_data['id']);

        $file = db_get_one('committee_files','filename',array('md5(id)'=>$post['idx']));
        if ($file) {
            $file = preg_replace('/\s+/', '_', $file);
            $data['path'] =  base_url().'document/material/'.$file;
        } else {
            $data['path'] = 'error';
        }
        echo json_encode($data);
        exit();
    }

}