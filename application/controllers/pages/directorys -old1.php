<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Directorys extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('committee_model');
        $this->load->model('member_model');
        $this->load->model('company_model');
        $this->load->model('auth_member_committee_model');
        $this->load->model('AboutPartnersModel');
        $this->load->model('sector_model');
    }

    function index(){
        $post                     = purify($this->input->post());
        $user_sess_data           = $this->session->userdata('MEM_SESS');
        // $data["disablesignout"]   = ($user_sess_data) ? "" : "disabled";
        
        $temp_list['list_data']   = $this->get_list_data("A-M",1);
        // print_r($temp_list);exit;
        $data['first_content']    = $this->parser->parse("directory/search.html",$temp_list,true); 

        $data['seo_title']        = ($data['seo_title'] == "") ? "American Chamber of Commerce in Indonesia" : $data['seo_title'];
        $data['meta_description'] = ($data['meta_description'] == '') ? "American Chamber of Commerce in Indonesia" : $data['meta_description'];
        $data['meta_keywords']    = ($data['meta_keywords'] == '') ? "American Chamber of Commerce in Indonesia" : $data['meta_keywords'];
        
        $data['banner_top']       = banner_top();
        $data['widget_sidebar']   = widget_sidebar(); //pake sidebar

        render('directory/index',$data);
    }
    function get_list_data($range,$id,$ret){

        // print_r($ret);
        // exit();

        $post           = purify($this->input->post());
        $id             = ($id != "") ? $id :$post['category_directory'];
        $array_alphabet = $range;
        $alphabet       = explode('-',$array_alphabet);
        $lower          = '"'.strtolower($alphabet[0]) .'"'. ' and ' . '"'.strtolower($alphabet[1]).'"';
        $upper          = '"'.strtoupper($alphabet[0]) .'"'. ' and ' . '"'.strtoupper($alphabet[1]).'"';

        switch ($id) {
            case '1': // sector
                if ($post['title']){
<<<<<<< .mine
                $this->db->where("(
                    CONCAT(name) LIKE '%".$post['title']."%' 
                    or CONCAT(company_name_in,' ',company_name_out) LIKE '%".trim($post['title'])."%' 
                    or a.representative LIKE '%".trim($post['title'])."%'
                )");
                    // or CONCAT(member_name) LIKE '%".$post['title']."%'
||||||| .r268
                    $this->db->where('(name like ', '%'.$post['title'].'%' )
                             ->or_where('company_name like ', '%'.$post['title'].'%')
                             ->or_where('member_name like "%'.$post['title'].'%")');
                    // $this->db->or_like('name', $post['title'] );
=======
                    $this->db->where('(name like ', '%'.$post['title'].'%' )
                             ->or_where('company_name like ', '%'.$post['title'].'%')
                             ->or_where('member_name like "%'.$post['title'].'%")');

                    $this->db->where('(name like ', '%'.$post['title'].'%' )
                         ->or_where('company_name like ', '%'.$post['title'].'%')
                    // $this->db->or_like('name', $post['title'] );
>>>>>>> .r309
                }
<<<<<<< .mine
                $this->db->where("(substring(name FROM 1 FOR 1) between $lower or substring(name FROM 1 FOR 1) between $upper or is_other= 1)");            
||||||| .r268
                $this->db->where("(substring(name FROM 1 FOR 1) between $lower or substring(name FROM 1 FOR 1) between $upper)");            
=======
                $this->db->where('(is_invis_company = 0');
                $this->db->or_where('is_invis = 0)');
>>>>>>> .r309

                $this->db->order_by('name','asc');
                $this->db->group_by('name');
                if ($ret && $post['title'] !="") {
                    $this->db->where('member_is_delete', 0);
                    $this->db->where('member_is_block',0);
                    $this->db->where('is_delete', 0);
                    $this->db->where('is_delete_tag', 0);
                    $this->db->where('company_invis', 0);
                    $this->db->where('status_payment_id',1);
                }
                $where['id_status_publish'] = 2;

                // $this->db->where('((a.is_other = 0 and a.is_parent_other = 0) or (a.is_other = 1 and a.is_parent_other = 1))');

                $data['list_data']          = $this->sector_model->findviewBy($where);
            break;

            case '2': // company
                $this->db->select('b.status_payment_id,
                                    a.name_out as name,
                                    a.name_in,
                                    a.name_out,
                                    b.prefix_name,
                                    b.firstname,
                                    b.lastname,
                                    b.status_payment_id,
                                    b.is_invis,
                                    a.is_invis_company,
                                    b.is_delete,
                                    b.is_block');

                if ($post['title']){
                    $this->db->where("(CONCAT(a.name_in, ' ', a.name_out) LIKE '%".$post['title']."%' or
                                       CONCAT(b.prefix_name, ' ', b.firstname,' ', b.lastname) LIKE '%".$post['title']."%')");
                }
                $this->db->where("( (substring(a.name_out FROM 1 FOR 1) between $lower or substring(a.name_out FROM 1 FOR 1) between $upper) or (substring(a.name_in FROM 1 FOR 1) between $lower or substring(a.name_in FROM 1 FOR 1) between $upper) )");

                $this->db->where('b.status_payment_id',1); 
                $this->db->where('b.is_invis',0);
                $this->db->where('a.is_invis_company',0);
                $this->db->where('b.is_delete',0);
                $this->db->where('b.is_block',0);

                
                $this->db->join('auth_member b', 'b.id = a.member_id_create', 'left');

                $this->db->group_by('a.name_out');
                $this->db->order_by('a.name_out','asc');
                $data['list_data']             = $this->company_model->findBy();  
                // echo $this->db->last_query();exit();
            break;

            case '3': // individu
                if ($post['title']){
                    $this->db->where("(CONCAT(a.firstname,' ',a.lastname,' ',a.prefix_name) LIKE '%".trim($post['title'])."%'
                                        or
                                    concat(b.name_out,' ',b.name_in) LIKE '%".trim($post['title'])."%')");
                }
                $this->db->where("(substring(a.firstname FROM 1 FOR 1) between $lower or substring(a.firstname FROM 1 FOR 1) between $upper)");
     
                $this->db->where('a.member_category_id',2);                
                $this->db->where('a.status_payment_id',1);
                $this->db->where('a.is_invis',0);
                $this->db->where('b.is_invis_company',0);
                $this->db->where('a.is_delete',0);
                $this->db->where('a.is_block',0);

                $this->db->join('company b', 'b.id = a.company_id', 'left');               
                $this->db->select('a.firstname as name, 
                                    a.member_category_id, a.company_id,
                                    a.firstname,a.lastname,a.prefix_name,
                                    b.name_out,b.name_in,b.is_invis_company,
                                    a.member_category_id,
                                    a.status_payment_id,
                                    a.is_invis,
                                    b.is_invis_company,
                                    a.is_delete,
                                    a.is_block
                                    ');

                $this->db->order_by('a.firstname','asc');

                $data['list_data']              = $this->member_model->findBy($where);
            break;
            
            default:
                return false;
            break;
        }
                // print_r($this->db->last_query());exit;
        if ($id == 1) {
            $ada_other = false;
            foreach ($data['list_data'] as $key => $value) {
                if ($value['is_other']) {
                    $ada_other = true;
                    unset($data['list_data'][$key]);
                }
            }
            if ($ada_other) {
                $id_parent_sector  = $this->sector_model->get_idotherparent();
                $where['id']       = $id_parent_sector;
                $data_sector_other = $this->sector_model->findviewBy($where,1);
                if ($range == "N-Z") {
                    array_push($data['list_data'], $data_sector_other);
                }
            }
        }

        $i = 0;
        foreach ($data['list_data']  as $key => $value) {
            $value['name_out'] = trim($value['name_out']);

            $data['list_data'][$key]['sort_data'] = ++$i;
            $data['list_data'][$key]['name_data'] = $value['name'];
            $data['list_data'][$key]['data_id']   = $value['id'];

            if ($value['member_category_id'] == 2) { //individu
                $type_member = 'individu';
            }else{                
                $type_member = 'company';
            }

            switch ($id) {
                case '2':
                    $name_out                             = $value['name_out'];
                    $name_in                              = $value['name_in'];
                    $uri_path                             = generate_url($value['name_out']); 

                    // $data['list_data'][$key]['name_data'] = ($name_out != "")? $name_out:$name_in;
                    
                    $temp1                                = base_url_lang().'/directorys/member/'.$type_member.'/'.$uri_path ;
                    $data['list_data'][$key]['url_data']  = $temp1;
                break;

                case '3':
                    $uri_path                            = generate_url(full_name($value));
                    $temp2                               = base_url_lang().'/directorys/member/'.$type_member.'/'.$uri_path ;
                    $data['list_data'][$key]['url_data'] = $temp2;  
                    
                break;

                default:
                    $data['list_data'][$key]['url_data'] = "";  
                break;
            }

        }
                // print_r($data['list_data']);exit;


        if ($ret) {
            $temp = $this->parser->parse("directory/search.html",$data,true); 
            echo json_encode($temp);
        }else{
            return $data['list_data'];
        }
    }

    function uri_path_search_directory(){
        $post         = purify($this->input->post());
        $id_parent_sector = $this->sector_model->get_idotherparent();
        if ($post['id'] == $id_parent_sector) {
            $this->db->select('group_concat(a.id) as id_sector_all');
            $id_other_child = $this->sector_model->fetchRow(array('is_other'=>1))['id_sector_all'];
            $id_other_child = explode(',', $id_other_child);
            $this->db->where_in('a.id', $id_other_child);

        }else{
            $id           = $post['id'];        
            $sector_model = $this->sector_model->fetchRow(array('id'=>$id));
            $this->db->where('a.id', $sector_model['id']);
        }

        // if ($post['title']){
        //     $this->db->where("CONCAT(name) LIKE '%".$post['title']."%'", NULL, FALSE);
        //     $this->db->or_where("CONCAT(company_name) LIKE '%".$post['title']."%'", NULL, FALSE);
        //     $this->db->or_where("CONCAT(member_name) LIKE '%".$post['title']."%'", NULL, FALSE);
        //     // $this->db->where("CONCAT(b.firstname, ' ', b.lastname) LIKE '%".$post['title']."%'", NULL, FALSE);
        // }

        $this->db->where('member_is_delete', 0);
        $this->db->where('is_delete', 0);
        $this->db->where('is_delete_tag', 0);
        $this->db->where('company_invis', 0);
        // $this->db->where('a.member_invis', 0);
        // $this->db->group_by('member_category_id');
        // $this->db->order_by('uri_path','asc');
        // $this->db->join('auth_member b',"b.id = a.company_id",'left');
        // $this->db->join('company c',"c.id = b.company_id",'left');
        // $this->db->select("b.*,c.is_invis_company");
        $member_commiteee = $this->sector_model->findviewBy();
        // print_r($this->db->last_query());exit;
    
        $counter_member_committee = count($member_commiteee);

        if ($counter_member_committee == 0){
            $ret = '<div class="col-sm-4 col-md-4 col-xs-12">Not Found</div>';
        } else {
            foreach ($member_commiteee as $key => $value) {
                $member_commiteee[$key]['id']               = $value['id'];

                if ($value['member_category_id'] == 1) { // company
                    $img = $value['company_img'];
                    if ($img) {
                        $img_company  = imageProfile($img,'company');        
                    }else{
                        $img_company  = '';
                    }

                    $type_member = 'company';
                    $name_out    = $value['company_name_out'];
                    $name_in     = $value['company_name_in'];

                    $uri_path    = ($name_out != "")? generate_url($name_out):generate_url($name_in);
                    $name_show   = ($name_out != "")? $name_out:$name_in;

                }else{
                    $img_company  = '';
                    $type_member = 'individu';
                    $uri_path    = generate_url(full_name($value));
                    $name_show   = full_name($value);
                }

                $ret .= '<div class="col-sm-4 col-md-4 col-xs-12"><a href="'.base_url_lang().'/directorys/member/'.$type_member.'/'.$uri_path.'" target="_blank"><div class="thumbnail-amcham thumb-gallery">'.$name_show.'<img src="'.$img_company.'""></div></a></div>';                    
            }
        }

        echo json_encode($ret);
    }

    function member($type,$uri_path){
        $id_lang            = id_lang();
        if ($uri_path == "") {
            redirect('directorys','refresh');
        }

        if ($type == 'company') {
            $page         = 'detail_company';
            $temp_company = $this->company_model->findBy();
            foreach ($temp_company as $key => $value) {
                if($uri_path == generate_url($value["name_out"])){
                    $data_company = $temp_company[$key] ;
                }
            }      
            if (empty($data_company)) {
                redirect('directorys','refresh');       
            }
            $this->db->where('(member_category_id = 3 or member_category_id = 1)');
            $this->db->where('company_id', $data_company['id']);
            $this->db->where("is_invis = 0 and 
                              is_delete = 0 and 
                              is_block = 0 " );
            $this->db->order_by('member_category_id','asc');
            $data_re      = $this->member_model->findBy();
            if ($data_re) {
                foreach ($data_re as $key => $value) {
                    $temp_re['re_img']         = $value['img'] ? base_url().'images/member/representative/'.$value['img']:'';
                    $temp_re['re_name']        = full_name($value); 
                    $temp_re['re_job']         = $value['job']; 
                    $temp_re['re_m_t_number']  = $value['m_t_number']; 
                    $temp_re['re_email']       = $value['email']; 
                    $temp_re['re_linkedin_id'] = $value['linkedin_id']; 
                    $data['representative'][]  = $temp_re;
                }
            }else{
                $data['no_re'] = 'hide';
            }
            $data['bread_fullname'] = $data_company['name_out'];

        }else if($type == 'individu'){
            $temp_data = $this->member_model->findBy();
            foreach ($temp_data as $key => $value) {
                $raw_uri       = $value['prefix_name']."".$value['firstname']."".$value['lastname'];
                $uri_path_user = generate_url($raw_uri);
                // if ($value['firstname'] == "Danilo") {
                //     echo $uri_path_user;exit;
                // }
                if($uri_path   == $uri_path_user){
                    $data = $temp_data[$key] ;
                }
            }
            if (empty($data)) {
                redirect('directorys','refresh');       
            }
            // $data               = $this->member_model->fetchRow(array('a.uri_path'=>$uri_path));
            $data_view          = $this->member_model->findViewBy(array('member_id'=>$data['id']),1);
            $data_company       = $this->company_model->findBy(array('id'=>$data['company_id']),1);
     
            $data['bread_fullname'] = full_name($data);
            $page = 'detail_individu';
        }else{
            redirect('directorys','refresh');
        }
        
        $temp_com['company_fullname']    = $data_company['name_out'];
        $temp_com['company_address']     = $data_company['address'];
        $temp_com['company_website']     = $data_company['website'];
        $temp_com['company_img']         = $data_company['img'] ? imageProfile($data_company['img'],"company"):'';
        $temp_com['company_description'] = $data_company['description'];
        $data['data_company'][]          = $temp_com;
        
        $temp_mem['member_name']         = full_name($data);
        $temp_mem['member_img']          = $data['img'] ? imageProfile($data['img'],"individu"):'';
        $temp_mem['member_job']          = $data['job'];
        $temp_mem['member_email']        = $data['email'];
        $temp_mem['member_t_number']     = $data_company['t_number'];
        $temp_mem['member_m_number']     = $data_company['m_number'];
        $temp_mem['member_linkedin_id']  = $data['linkedin_id'];
        $temp_mem['member_address']      = $data_company['address'];
        $temp_mem['member_website']      = $data_company['website'];
        $data['data_member'][]           = $temp_mem;



      
        // $data['member_full_name'] = 1;

        //partners
        // $this->db->group_by('id_partners_category');
        // $data['partners'] = $this->AboutPartnersModel->findby(array('id_status_publish' => 2));
        //    foreach ($data['partners'] as $key => $value1) {
        //     $data['partners'][$key]['img'] = image($value1['img'],'small');
        //     $data['partners'][$key]['url'] = $value1['url'] ? $value1['url'] : '#';
        // }

        $data['seo_title']        = ($data['seo_title'] == "") ? "American Chamber of Commerce in Indonesia" : $data['seo_title'];
        $data['meta_description'] = ($data['meta_description'] == '') ? "American Chamber of Commerce in Indonesia" : $data['meta_description'];
        $data['meta_keywords']    = ($data['meta_keywords'] == '') ? "American Chamber of Commerce in Indonesia" : $data['meta_keywords'];
        $data['banner_top']       = banner_top();
        $data['widget_sidebar']   = widget_sidebar(); //pake sidebar
        render('directory/'.$page,$data);
        
    }

}