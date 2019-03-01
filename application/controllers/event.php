<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Event extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('eventModel');
        $this->load->model('NewsTagsModel');
        $this->load->model('eventFilesModel');
        $this->load->model('eventImagesModel');
        $this->load->model('eventCategoryModel');
        $this->load->model('frontendmenumodel');
        $this->load->model('template_tipe_input_form_register_model');
        $this->load->model('pagesmodel');
        $this->load->model('eventPriceModel');
        $this->load->model('bank_account_model');
        $this->load->model('paymentconfirmation_model');
    }

    function index(){
        $lang                         = $this->uri->segment(1);
        $uri_path                     = $this->uri->segment(4); 
        $page                         = $this->uri->segment(5);
        $id_lang                      = id_lang();
        $today                        = date('Y-m-d');
      
        $view                         = 'index';
        switch ($uri_path) {

            case 'upcoming-events':
                $data['seo_title']              = 'Upcoming Event';
                $data['page_heading']           = 'Upcoming Event';
                $where['a.end_date    >=']      = $today;
                // $where['a.is_close ']        = 0;
                $this->db->order_by('start_date','asc');
            break;

            case 'past-events':
                $data['seo_title']          = 'Past Event';
                $data['page_heading']       = 'Past Event';
                
                $id_event_category = id_child_event(26,1,1);
                $this->db->where_in('a.id_event_category', $id_event_category);
                $where['a.end_date    <=']      = $today;
                // $this->db->or_where('a.is_close', 1);
                $this->db->order_by('start_date','desc'); 
            break;

            case 'annual-golf-turnament':
                $data['pages_annual'] = $this->pagesmodel->findBy(array('uri_path' => 'annual-golf-tournament'),1)['page_content'];
                $data['seo_title']          = 'Annual Golf Tournament';
                $data['page_heading']       = 'Annual Golf Tournament';
                // $where['a.is_close_1']      = '1';
                $id_annual                  = id_child_event(40,1,1);
                $view                       = 'annual_golf';
                $this->db->where_in('a.id_event_category', $id_annual);
                $this->db->order_by('publish_date', 'desc');
            break;
            
            default: 
                not_found_page();
            break;
        }
        $this->db->limit(PAGING_PERPAGE);

        $where['a.id_lang']           = $id_lang;
        $where['a.is_not_available']  = 0;
        $where['a.id_status_publish'] = 2;
        $where['a.publish_date <= '] = $today; 
        $data_events                  = $this->eventModel->findViewBy($where); 

        $id_cat_amcham_event = array('26','40');
        foreach ($data_events as $key => $value) {
            unset($temp);
            $temp['category']      = ($value['subcategory'] != '') ? $value['subcategory'].' ' : '';
            $temp['link']          = site_url('event/detail/'.$value['uri_path']);
            $temp['title']         = $value['name'];
            if ($uri_path == 'annual-golf-turnament') {
                $temp['time']          = event_date($value['publish_date'],'','','-',1);
            }else{
                $temp['time']   = ($value['start_time'] == '00:00' || $value['start_time'] == '00:00:00' || $value['end_time'] == '00:00') ? event_date($value['start_date']) : event_date($value['start_date'],$value['start_time'],$value['end_time']);
            }

            if ($uri_path == 'past-events') {
                $data['hide_events_list'] = 'hidden';

            }
            if ($uri_path == 'upcoming-events') {
                $data['button_tab'] = '<li><span class="blue-back"></span> AmCham events</li>
                        <li><span class="red-back"></span> Non-AmCham events</li>';

            }else{
                $data['button_tab'] = '<li><span class="blue-back"></span> AmCham events</li>';  
                
            }


            $temp['address']       = (!$value['location_name']) ? '-' : $value['location_name'];
            $temp['teaser']        = character_limiter($value['teaser'],140,'...');
            $temp['color']         = in_array($value['id_event_category'],$id_cat_amcham_event)?'widget-blue-left':' widget-red-left';
            if ($uri_path == 'annual-golf-turnament') {
                $temp['img']           = getImg('Golf Turkey Logo.jpg','large');
            }else{
                $temp['img']           = getImg($value['img'],'large');
            }
            $data['events_list'][] = $temp;
        }
        if (!$data_events) {
            $data['no_events_list'] = 'hide';
        }       


        // buat kalender
        $data_events_calendar       = $this->eventModel->data_amcham();  

        foreach ($data_events_calendar as $key => $value) {
            $data_calendar['title']      = $value['content_title'];
            $data_calendar['type_event'] = $value['id_event_category'];
            $data_calendar['color']      = (
                in_array($value['id_event_category'], array('26', '40' )))
                 ? '#132539!important' : '#be1e2d!important' ;

            if($value['start_date'] != $value['end_date']){
                $data_calendar['start']      = $value['start_date'].' '.remove_whitespace($value['start_time']);
                $data_calendar['end']        = iso_date_custom_format($value['end_date'], 'Y-m-d').' '.remove_whitespace($value['end_time']);
            }
            else{
                $data_calendar['start']      = $value['start_date'].' '.remove_whitespace($value['start_time']);
                $data_calendar['end']        = iso_date_custom_format($value['end_date'] . '+ 1 day', 'Y-m-d') .' '.remove_whitespace($value['end_time']);
            }

            $data_calendar['start_time']= $value['start_time'];
            $data_calendar['allDay']    = false;
            $data_calendar['className'] = 'info';            
            $data_calendar['url']       = site_url('event/detail/'.$value['uri_path']);            
            $array_event_calendar[]     = $data_calendar;
        }

        $data['event_data']     = json_encode($array_event_calendar);

        $data['button_tab']  = empty($data['button_tab']) ? '<li><span class="blue-back"></span> AmCham events</li>
                        <li><span class="red-back"></span> Non-AmCham events</li>' : $data['button_tab'];
        $data['events_list'] = empty($data['events_list'])  ? array() :  $data['events_list'];
        $data['paging']         = PAGING_PERPAGE;
        $data['uri_path']       = ($uri_path == '' ? 'index' : $uri_path); 
        $data['dsp_load_more']  = $this->more($uri_path,$data['paging'],1) ? '' : 'hide';

        $data['banner_top']     = banner_top();
        $data['widget_sidebar'] = widget_sidebar();
        if($data['seo_title'] == ''){
            $data['seo_title'] = "American Chamber of Commerce in Indonesia";
        }
        $data['meta_description'] = preg_replace('/<[^>]*>/', '', $data['meta_description']);
        render('event/'.$view,$data);
    }
    
    function more($uri_path,$page,$ret=0,$year,$month){
        $id_lang = id_lang();
        $today   = date('Y-m-d');
        $menu = $this->frontendmenumodel->fetchRow(array('a.id_language'=>$id_lang,'extra_param'=>$uri_path));
        if ($uri_path == 'index') {

        } else{
            if ($uri_path == "upcoming-events") {
                $where['a.end_date >=']    = date('Y-m-d');
                $this->db->order_by('start_date','desc');
            }else if($uri_path == "past-events"){
                $id_event_category = id_child_event(26,1,1);
                $this->db->where_in('a.id_event_category', $id_event_category);
                $where['a.end_date    <=']      = $today;
                // $where['a.is_close_1']   = '1';
                $this->db->order_by('start_date','desc'); 
            }else if ($uri_path == "annual-golf-turnament") {
                // $where['a.is_close_1']       = '1';
                $id_annual                  = id_child_event(40,1,1);
                $this->db->where_in('a.id_event_category', $id_annual);
                $this->db->order_by('publish_date', 'desc');
            }
        }

        $id_cat_amcham_event             = array('26','40');
        $where['a.is_not_available']     = 0;
        $where['a.id_status_publish'] = 2;
        $where['a.id_lang']              = $id_lang;

        if ($year != 0 && $month != 0) {
            if ($month < 10) {
                $month  = zero_first($month,2);
            }
            $this->db->like('start_date', $year."-".$month);
        }else if ($year != 0) {
            $this->db->like('start_date', $year);
        }else if ($month != 0) {
            if ($month < 10) {
                $month  = zero_first($month,2);
            }
            $this->db->like('start_date', "-".$month."-");
        }
        $this->db->limit(PAGING_PERPAGE,($page)); 
        $this->db->group_by('id');
        $this->db->order_by('start_date','desc');
        $data = $this->eventModel->findViewBy($where);
        if($ret==1){
            return $data ? 1 : 0;
        }
        
        $bulan  = array(1=>'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        foreach ($data as $key => $value) { 
            if ($uri_path == 'annual-golf-turnament') {
                $img           = getImg('Golf Turkey Logo.jpg','large');
            }else{
                $img           = getImg($value['img'],'large');
            }
            $event_year       = date("Y", strtotime($value['start_date']));
            $location_name    = $value['location_name'];
            $location_address = $value['location_name'].'<br>'.$value['location_address'];
            if ($uri_path == 'annual-golf-turnament') {
                $time        = event_date($value['publish_date'],'','','-',1);
            }else{
                $time       = event_date($value['start_date'],$value['start_time'],$value['end_time']);
            }

            $color         = in_array($value['id_event_category'],$id_cat_amcham_event)?'widget-blue-left':' widget-red-left';

            if ($uri_path == "annual-golf-turnament") {
            ?>
                <div class="media media-latest">
                    <div class="media-left">
                      <div class="thumbnail-amcham thumb-top-art"><?=$img?></div>
                    </div>
                    <div class="media-body">
                      <div class="widget-white">
                        <div class="event-type"><?=$time?></div>
                        <div class="title-event-list"><a href="<?=site_url("event/detail/$value[uri_path]")?>"><?=$value['name']?></a></div>
                        <p><?=$value['teser']?></p>
                      </div>
                    </div>
                </div>
            <?php
            } else{
                ?>
                <div class="widget-white <?=$color?> mb15">
                     <div class="event-type"><?=$value['category']?></div>
                     <div class="title-event-list mb20"><a href="<?=site_url("event/detail/$value[uri_path]")?>"><?=$value['name']?></a></div>
                     <div class="row">
                       <div class="col-sm-5 col-md-5 col-xs-12">
                         <div class="media">
                           <div class="media-left media-middle"><span class="icon-time"></span></div>
                           <div class="media-body media-middle"><?=$time?> <!-- 18:00 - 20:30 --></div>
                         </div>
                       </div>
                       <div class="col-sm-7 col-md-7 col-xs-12">
                         <div class="media">
                           <div class="media-left media-middle"><span class="icon-maps"></span></div>
                           <div class="media-body media-middle"><?=$location_address?><!-- Ayana Hotel, Midplaza --></div>
                         </div>
                       </div>
                     </div>
                   </div>
                <?php
            }
        }

        $data = $this->more($uri_path,$page+PAGING_PERPAGE_MORE,1,$year,$month);
        // $this->db->limit(PAGING_PERPAGE,($page+PAGING_PERPAGE_MORE));
        // $this->db->order_by('start_date','desc');
        // $data = $this/->eventModel->findBy($where);
        if($data){
            echo "<div class='text-center'><a href='".site_url('event/more/'.$uri_path.'/'.(PAGING_PERPAGE_MORE+$page).'/0/'.$year.'/'.$month)."' class='load-more'>".language('load_more')."</a></div>";
        }
        exit;
    }

    function detail($uri_path, $test_view){
        $this->load->model('eventModel');
        $lang     = $this->uri->segment(1);
        $uri_path = $this->uri->segment(4); 
        $page     = $this->uri->segment(5);
        $user_sess_data = $this->session->userdata('MEM_SESS');
        $id_lang  = id_lang();
        $today    = date('Y-m-d');

        $where['a.uri_path']          = $uri_path;
        $where['a.id_lang']           = $id_lang;
        $where['a.is_not_available']  = 0;
        if (!$test_view) {
            $where['a.id_status_publish'] = 2;
        }


        $data     = $this->eventModel->fetchRow($where);

        if(!$data){ 
            $where2 = ['a.uri_path'=>$uri_path,'a.is_not_available'=>0];

            if (!$test_view) {
                $where2 += ['a.id_status_publish'=>2];
            }
            $data = $this->eventModel->fetchRow($where2);
            if (!$data) {
                not_found_page();
            }
        }

        $data['MITRANS_SERVER_KEY'] = MIDTRANS_SERVER_KEY;
        $data['MITRANS_CLIENT_KEY'] = MIDTRANS_CLIENT_KEY;
        $data['MITRANS_MERCHANT_ID'] = MIDTRANS_MERCHANT_ID;

        $data['user_publish'] =  ($data['user_id_publisher'])
                                 ? ' | <span> by '.db_get_one('auth_user','username','id_auth_user = '.$data['user_id_publisher']).'</span>'  
                                 : '';

        $data['lang_not_available'] = '';
        if($id_lang != $data['id_lang']){
            $id = $data['id_parent_lang'] ? $data['id_parent_lang'] : $data['id'];
            $this->db->where("(a.id_parent_lang = '$id' or a.id = '$id')");
            $datas = $this->eventModel->findBy();
            foreach ($datas as $key => $value) {
                if($value['id_lang'] == $id_lang && $value['is_not_available']==0){
                    redirect(base_url("$lang/event/detail/$value[uri_path]/$page"));
                }
            }
            $data['is_not_available'] = 1;
        } 

        if($data['is_not_available']==1){
            $bahasa = db_get_one('language','name',array('id'=>$id_lang));
             $data['lang_not_available'] = '<div>'.language('not_available_language').' ' .$bahasa.'.</div><hr>';
             $this->db->select("a.name as nama");
             $this->db->join("event b", "b.id_event_category = a.id");
             $fieldID = ($id_lang == 1 ? "id_parent_lang" : "id");
             $fieldSelect = ($id_lang == 1 ? "b.id" : "b.id_parent_lang");
             $newCat = $this->db->get_where("event_category a", array($fieldSelect => $data[$fieldID]))->row_array();
             $data['category'] = $newCat['nama'];
        }
        
        // image image image image image image image image image
        $detail_img = $this->eventImagesModel->findBy(array('id_event'=>$data['id'],'filename !='=>""));
        if (!empty(array_filter($detail_img) )){
             foreach ($detail_img as $key => $value) {
             unset($temp);
             $temp['detail_image_img']         = getImg($value['filename'],'large');
             $temp['detail_image_description'] = $value['description'];
             $data['detail_image'][]           = $temp;
        }

        }
        $data['dsp_detail_image'] = (!empty(array_filter($data['detail_image'])) && $data['detail_image'] !="NULL" ) ? ''  : 'hide';

        $share_img                = $this->eventImagesModel->findBy(array('id_event'=>$data['id'],'is_share'=>1),1);
      
        /* start untuk material */
        $data['materials'] = array();
        $materials = $this->eventFilesModel->listFiles(array('id_event' => $data['id']));
        foreach ($materials as $key => $value) {
            $materials[$key]['materials_filename'] = $value['name_file'];
            $materials[$key]['mat_idx']       = md5plus($value['idFile']);
        }
        $data['materials'] = $materials;
        $data['dsp_materials']          = count($materials) > 0 ? '' : 'hidden'; 
        // print_r($data);exit;
        // $data['dsp_materials_expired']  = $user_sess_data['status'] == 5 ? '' : 'hidden'; 
        /* end untuk material */        

        $data['event_id']         = $data['id'];
        $data['page_content']     = $data['content'];
        $data['news_title']       = $data['name'];;
        $data['news_date']        = event_date($data['start_date'],$data['start_time'],$data['end_time']);


        // $data['event_date']    = event_date($data['start_date'],'','','-',1);
        $data['event_date']       = periode(iso_date($data['start_date']),iso_date($data['end_date']));
        $data['event_time']       = event_time($data['start_date'],$data['start_time'],$data['end_time']);
        $data['location_name']    = $data['location_name'];
        $data['location_address'] = $data['location_address'];

        $id_tags_price = db_get_one('event_price','group_concat(id_price)','id_event = '.$data['id'].' and is_delete = 0');
        if ($id_tags_price) {
            $data['list_event_price']  = selectlist2(array('table'=>'price','title'=>'Select Price','where'=> 'id in('.$id_tags_price.') and is_delete = 0'));  //             
            $data['dsp_list_event_price']  = "";
            $data['req_list_event_price']  = "required";
        }else{
            $data['list_event_price']  = "";         
            $data['dsp_list_event_price']  = "hidden";
            $data['req_list_event_price']  = "";
        }
        $list_bank  = $this->db->get('bank_account')->result_array();
        foreach ($list_bank as $key2 => $value2) {
            $data['list_bank'][$key2] = array(
                'list_bank_id' => $value2['id'],
                'list_bank_name' => $value2['bank_name'],
             );
        }
        $data['hdn_event_golf'] = $data['id_event_category'] == 40 ? 'hidden':'';
        $data['modal_event_register']  = register_now($data);
        $data['is_past_event']         = check_date_future($data['end_date']) ? '' : 'hidden';
        $data['is_upcoming_event']     = !check_date_future($data['end_date']) || $data['is_close'] == 1? '' : 'hidden';

        $prices = $this->eventPriceModel->findBy(array('id_event'=>$data['id']));
        $price  = '';
        foreach ($prices as $k => $v){
            $price .=  ', '.ucfirst($v['tags']);
        }
        $data['event_price']              = substr($price,1);
        $data['dsp_price']              = $prices ?"":"hidden";
        $this->db->select('group_concat(id_tags) as group_id_tags');
        $tags       = $this->NewsTagsModel->findBy(array('id_news'=>$data['id']),1)['group_id_tags']; //ambil tag 
        $tags       = explodeable(',', $tags);

        $id_gallery = get_event_gallery_id($data['id']) ? get_event_gallery_id($data['id']) : 0 ; 
        if ($data['id_gallery']) {
            $id_gallery_colomn = explodeable(',', $data['id_gallery']);
            $id_gallery        = array_filter(array_merge(array($id_gallery),$id_gallery_colomn));
        }
        if ($id_gallery) {
            $where_listphoto['backurl']     = 'event/index/past-events'; 
            $where_listphoto['hide_button'] = '1'; 
            $where_listphoto['id_tags']     = $tags ; 

            $imagelist = listphoto($id_gallery,$where_listphoto);
            if ($imagelist['status'] == 0) {
                $data['imagelist']       = $imagelist['imagelist'];
                $data['modal_imagelist'] = $imagelist['modal_imagelist'];
                $data['dsp_gallery']     = '';
            } else {
                $data['imagelist']       = '';
                $data['modal_imagelist'] = '';
                $data['dsp_gallery']     = 'hidden';
            }
            
        } else {
            $data['imagelist']       = '';
            $data['modal_imagelist'] = '';
            $data['dsp_gallery']     = 'hidden';
        }


        if($data['seo_title'] == ''){
            $data['seo_title'] = "American Chamber of Commerce in Indonesia";
        }

        $data['share']            = share_widget();
        $data['banner_top']       = banner_top();
        $data['widget_sidebar']   = widget_sidebar();

        $data['meta_img']   = (!empty(array_filter($share_img) )) ? getImgLink($share_img['filename'],'large') : '';
        $data['head_title'] = $data['news_title'];
        $data['full_url']   = current_url();
        $data['head_title'] = $data['seo_title'];

        $data['meta_description'] = preg_replace('/<[^>]*>/', '', $data['meta_description']);

        $this->db->select('a.is_required,a.is_row,b.name,b.parameter,b.id_ref_tipe_input,c.nama as tipe_input');
        $this->db->join('ref_tipe_form_register b',"b.id = a.id_tipe_input",'left');
        $this->db->join('ref_tipe_input_form_register c',"c.id = b.id_ref_tipe_input",'left');
        $this->db->order_by('a.sort','asc');
        $data['list_tipe_input_form']=$this->template_tipe_input_form_register_model->findBy(array('a.id_template'=>$data['id_template_form_register']));

        foreach ( $data['list_tipe_input_form'] as $key => $value) {

            $is_row         = $value['is_row'] == 1 ? "col-sm-12 col-md-12 col-xs-12" : "col-sm-6 col-md-6 col-xs-12";
            $is_required    = $value['is_required'] == 1 ? "required" : "";
            $label          = $value['is_required'] == 1 ? '*' : '';

            $field = '<div class="'.$is_row.'"><label class="label-amcham text-left">'.$value['name'].$label.'</label>';
            
            switch ($value['tipe_input']) {
                case 'text':
                    $field .=  '<input type="'.$value['tipe_input'].'" name="'.$value['parameter'].'" id="'.$value['parameter'].'" class="form-control input-amcham" '.$is_required.' >';
                break;

                case 'textarea':
                    $field .= ' <textarea class="form-control input-amcham" rows="5" name="'.$value['parameter'].'" id="'.$value['parameter'].'" '.$is_required.'></textarea>';

                     switch ($value['parameter']) {
                        case 'participant_name':
                        case 'participant_email':
                        case 'participant_t_mobile':
                        // $field .= '<label>separated by comma</label>';
                        break;
                        
                        default:
                            $field .= "";
                            break;
                    }

                break;

                case 'email':
                    $field .=  '<input type="email" name="'.$value['parameter'].'" id="'.$value['parameter'].'" class="form-control input-amcham" '.$is_required.' >';
                break;

                case 'dropdown':
                    switch ($value['parameter']) {
                        case 'id_ref_handicap':
                            $field  .=  '<select name="'.$value['parameter'].'" id="'.$value['parameter'].'" class="form-control input-amcham" '.$is_required.' >';
                            $field  .=  selectlist2(array('table'=>'ref_handicap','title'=>'Select Handicap'));
                            $field  .=  '</select>';
                        break;

                        case 'id_ref_gender':
                            $field  .=  '<select name="'.$value['parameter'].'" id="'.$value['parameter'].'" class="form-control input-amcham" '.$is_required.' >';;
                            $field  .=  selectlist2(array('table'=>'ref_gender','title'=>'Select Gender'));
                            $field  .=  '</select>';
                        break;

                        case 'id_ref_member':
                            $field  .=  '<select name="'.$value['parameter'].'" id="'.$value['parameter'].'" class="form-control input-amcham" '.$is_required.' >';                            
                            $field  .=  selectlist2(array('table'=>'ref_member','title'=>'Select Member'));
                            $field  .=  '</select>';
                            
                        break;

                        case 'id_ref_meal':
                            $field  .=  '<select name="'.$value['parameter'].'" id="'.$value['parameter'].'" class="form-control input-amcham" '.$is_required.' >';
                            $field  .=  selectlist2(array('table'=>'ref_meal','title'=>'Select Meal'));
                            $field  .=  '</select>';
                            
                        break;
                        
                        default:
                            $field .= "";
                            break;
                    }
                break;
                
                default:
                    $field.="";
                    break;
            }

            $field  .= '</div>';
            $data['list_tipe_input_form'][$key]['field']                = $field;


        }

        $this->db->join('tags b',"b.id= a.id_price",'left');
        $data['list_event_price'] = selectlist2(array('table'=>'event_price a','title'=>'Select Event Price','id'=>'a.id','name'=>'b.name','where'=>array('a.id_event'=>$data['id'])));

        
        $data['event']      = $data['id'];
        // ada template
        if ($data['id_template_form_register'] != 0) { 
            // kalo event end dibawah hari ini dan sudah di close
           if($data['end_date'] < date('Y-m-d') || $data['is_close'] == 1 ){
                $data['join_event'] = '';
           }else{
                $data['join_event'] = '<a href="#" class="btn-red btn-link-price mt30" data-toggle="modal" data-target="#myModalEvents">Join Our Event</a>';
           }

        }else{
            $data['join_event'] = '';
        }  

        $id_annual = id_child_event(40,1,1);
        if (in_array($data['id_event_category'], $id_annual) ) {
            render('event/detail_annual',$data);
        }else{
            render('event/detail',$data);
        }
    }

    function get_download(){
        $post = purify($this->input->post());
        
        $file = db_get_one('event','filename',array(md5field('id')=>$post['idx']));
        if ($file) {
            $file = preg_replace('/\s+/', '_', $file);
            $data['path'] =  base_url().'file_upload/'.$file;
        } else {
            $data['path'] = 'error';
        }

        echo json_encode($data);
        exit();
    }

    function get_material_hits(){
        $post = purify($this->input->post());

        $g_data      = $this->db->get_where('event_files',array(md5field('id')=>$post['idx']))->row_array();
        $ttl_hits    = intval($g_data['hits']);
        $upc['hits'] = $ttl_hits + 1;
        $this->eventFilesModel->updateAll($upc,$g_data['id']);
        
            $ret['modalname']  = 'myModalNewsletter';
        $file = db_get_one('event_files','filename',array(md5field('id')=>$post['idx']));
        if ($file) {
            $file = preg_replace('/\s+/', '_', $file);
            $data['path'] =  base_url().'document/material/'.$file;
        } else {
            $data['path'] = 'error';
        }

        echo json_encode($data);
        exit();
    }

    function register($type_input){
        $post              = purify(purify($this->input->post()));
        $uri_path          = end(explode("/",$post['full_url']));
        $data_event        = $this->eventModel->findBy(array('a.uri_path'=>$uri_path),1);
        unset($post['full_url']);

        $event_id          = $data_event['id'];
        $event_name        = $data_event['name'];

        $user_sess_data    = $this->session->userdata('MEM_SESS');
        $data_form         = $post;
        $post['member_id'] = $user_sess_data['id'];
        // $post['lastname'= $post['lastname'];
        $post['event_id']  = $post['event_id'];
        
        $ret['error']      = 1;
        if ($type_input) {
            unset($data_form['event_id']);
            unset($data_form['full_url']);
            unset($data_form['bank_name']);
            unset($data_form['payment_method']);
            unset($data_form['success_invoice']);
            // content untuk email admin
            $content .= '<table border="1">';
            foreach ($data_form as $key => $value) {
                $content .= '<tr>';
                $content .= '<td>';
                if ($key == 'invoice_number') {
                    $content .= 'Invoice Number';
                }else if($key == 'id_ref_event_price'){
                    $content .= "Price";
                }else{
                    $content .= db_get_one('ref_tipe_form_register','name', array('parameter'=>$key));
                }
                $content .= '</td>';

                $content .= '<td>';

                $content .= template_event_value($key,$value);
                $content .= '</td>';
                $content .= '</tr>';
            }
            $content .= '</table>';            

            $email = array('event_name' =>$event_name,
            "content"=> $content,
            "link"=> base_url().'apps/login'
            );

            // sent email admin
            
            if (db_get_one('event','id_event_category',array('id' =>  $event_id )) == '40' ) {
                $email_golf = db_get_one('contact_us_receive','group_concat(email)',array('id_email_category' => 20));
                $admin_email = $email_golf ? EMAIL_ADMIN_TO_SEND.','.$email_golf : EMAIL_ADMIN_TO_SEND;
            }else{
                $admin_email =  EMAIL_ADMIN_TO_SEND;
            }
            sent_email_by_category(8,$email,$admin_email);
            $ret['error']      = 0;
            $ret['clearform']  = 1;
        }

        if (!$post) {
            $ret['msg']    = 'error' ;
        }else if(!$type_input){
            $this->load->model('eventprice_model');
            $price_data  = $this->eventprice_model->findBy(array('id' => $post['id_ref_event_price']),1);

            if ($post['payment_method'] == 'bank_transfer' || $post['payment_method'] == 'onsite_payment') {
                // name 
                if ($post['fullname'] != '') {
                    $participant_name = $post['fullname'] ; 
                }else if($post['firstname'] != ''){
                    $participant_name = $post['firstname'] ; 
                }else{
                    $participant_name = $post['lastname'] ; 
                    
                }

                // email
                if ($post['email_1'] != '') {
                   $participant_email = $post['email_1'];
                }else if ($post['email_2'] != '') {
                    $participant_email = $post['email_2'];
                }else{
                    $participant_email = '';
                }

                $invoice_number = 'E-'.rand(10000000000,99999999999);


               if($post['payment_method'] == 'bank_transfer'){
                   // jika bank transfer maka akan kirim emaili guide pembayaran
                   
                   $bank_data                                      = $this->bank_account_model->bank_list();
                   $bank_page_content                              = bank_page_email($bank_data);

                   $data_email_event_register['name']              = $participant_name;
                   $data_email_event_register['event_name']        = $event_name;
                   $data_email_event_register['name_price']        = $price_data['alias'] == '' ? $price_data['name'] : $price_data['alias'];
                   $data_email_event_register['price']             = number_format($price_data['amount']);
                   $data_email_event_register['link']              = base_url_lang().'/payment_confirmation/'.$invoice_number;
                   $data_email_event_register['invoice_number']    = $invoice_number;
                   $data_email_event_register['grand_total']       = number_format($price_data['amount']);
                   $data_email_event_register['bank_page_content'] = $bank_page_content;
                   $id_ref_payment_type            = '2'; 

               }else if($post['payment_method'] == 'onsite_payment'){

                   $data_email_event_register['event_name']        = $data_event['name'] ;
                   $data_email_event_register['event_date']        = 
                                    date('l j F Y',strtotime($data_event['start_date'])).' at '. 
                                    $data_event['start_time'] . ' - ' . $data_event['end_time'] ;

                   $data_email_event_register['before_event_date'] = 
                                    date('l j F Y',strtotime('-1 days', strtotime($data_event['start_date'])));

                   $data_email_event_register['event_place']       = $data_event['location_name'];


                   $data_email_event_register['event_name_2']      = $data_email_event_register['event_name'] ;
                   $data_email_event_register['event_place_2']     = $data_email_event_register['event_place'] ;
                   $data_email_event_register['event_date_2']      = 
                                   indonesia_datetime($data_event['start_date'],'l j F Y','').' pukul '. 
                                   $data_event['start_time'] . ' - ' . $data_event['end_time'] ;

                   $data_email_event_register['before_event_date_2'] = indonesia_datetime(
                    strtotime('-1 days',strtotime($data_event['start_date'])),'l j F Y','');

                   $data_email_event_register['invoice_number']    = $invoice_number;
                   $data_email_event_register['event_place_2']     = $data_email_event_register['event_place'];

                   $id_ref_payment_type            = '3'; 

               }

               $ret['modalname'] = 'myModalNewsletter';

            }else if($post['payment_method'] == 'payment_online'){
                $id_ref_payment_type            = '1'; 
            }else{
                $ret['modalname']               = 'myModalNewsletter';
                $id_ref_payment_type            = '4'; 
            }

            $payment_method      = $post['payment_method'];
            $invoice_number_post = $post['invoice_number'];

            $post['event_price']        = $price_data['name'];
            $post['event_price_amount'] = $price_data['amount'];

            $post['invoice_number'];
            unset($post['payment_method']);
            unset($post['bank_name']);
            unset($post['full_url']);
            unset($post['invoice_number']);
            unset($post['success_invoice']);
            $insert            = $this->eventModel->insertParticipant($post);
            
           if (!empty($price_data)) {
               
                //jika ada harganya kirim email payment guide
                if($post['payment_method'] == 'bank_transfer'){
                    sent_email_by_category(13,$data_email_event_register,$participant_email);
                }else if ($price_data['amount'] == 0) {
                    event_approve($event_id,$insert,'','nothing');
                }else{
                    sent_email_by_category(19,$data_email_event_register,$participant_email);
                }
                
           }
            if($payment_method != 'payment_online' ){
                $data_invoice['id_ref_payment_category'] = 1; 
                $data_invoice['invoice_number']          = ($price_data['amount'] == 0) ? '-' :$invoice_number; 
                $data_invoice['member_id']               = $insert; 
                $data_invoice['id_ref_payment_type']     = $id_ref_payment_type; 
                $data_invoice['event_id']                = $event_id; 
                $data_invoice['is_paid']                 = ($price_data['amount'] == 0) ? '1' : '0'; 
                $this->paymentconfirmation_model->insert_frontend($data_invoice);

            }else{
                $this->db->set('member_id', $insert);
                $this->db->where('invoice_number', $invoice_number_post);
                $this->db->update('payment_confirmation');
            }
            // $ret['show_modal_member'] = $data_form['id_ref_member'] == 2  ? true : false;

            $ret['error']      = 0;
            $ret['modalclose'] = 'myModalEvents';
        }
        echo json_encode($ret);
    }



    function proses_event($id,$uri_path){
        $post                   = purify($this->input->post());  
        $post['event_id']       = $id;
        
        
        // $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|email');
        if ($this->form_validation->run() == FALSE){
            echo ' '.validation_errors(' ','<br>');
        }
        else{ 
            $this->db->trans_start();
                $insert = $this->db->insert('event_participant',$post);
                $this->load->helper('mail');
                $rep['eventname']   = db_get_one('event','name',array('id'=>$id));
                $rep['firstname']   = $post['firstname'];
                $rep['lastname']    = $post['lastname'];
                $email              = mail_tpt('REG_EVENT',$rep);
                $email['to']        = $post['email'];
                sent_mail($email);
            $this->db->trans_complete(); 
            $msg = 'Insert Success'; 
            // echo $msg;
        }
        $this->session->set_flashdata('suksesRegister','terimakasih telah mendaftar. kami akan segera menghubungi anda.');
        redirect(site_url("event/detail/$uri_path"));
    }

    function search() {
        $post = $this->input->post();
        $uri_path                     = $this->uri->segment(4); 
        $page                         = $this->uri->segment(5);

        if ($post['search_amchamEvent'])
        {
            $this->db->where_in('a.id_event_category', $post['search_amchamEvent']);
            $where['a.end_date    <=']      = $today;
            // $this->db->order_by('start_date','desc'); 

            $data_amcham_event = $this->eventModel->data_amcham_event();
            $i = 1;
            if ($data_amcham_event)
            {
                foreach ($data_amcham_event as $key => $value) 
                {
                
                    $data['list_event'][$key]['no']      = $i;
                    $data['list_event'][$key]['id']      = $value['id'];
                    $data['list_event'][$key]['title']   = $value['name'];
                    $data['list_event'][$key]['time']    = ($value['start_time'] == '00:00' || $value['start_time'] == '00:00:00' || $value['end_time'] == '00:00') ? event_date($value['start_date']) : event_date($value['start_date'],$value['start_time'],$value['end_time']);
                    $data['list_event'][$key]['link']    = site_url('event/detail/'.$value['uri_path']);
                    $data['list_event'][$key]['address'] = (!$value['location_name']) ? '-' : $value['location_name'];
                    $data['list_event'][$key]['color']   = 'widget-blue-left';
                
                    $i++;
                }

            }
        }

        elseif ($post['search_nonamchamEvent']) {
            $this->db->where_in('a.id_event_category', $post['search_nonamchamEvent']);
            $where['a.end_date    <=']      = $today;
            // $this->db->order_by('start_date','desc'); 

            $data_nonamcham_event = $this->eventModel->data_nonamcham_event();
            $i = 1;
            if ($data_nonamcham_event)
            {
                foreach ($data_nonamcham_event as $key => $value) 
                {
                    $data['list_event'][$key]['no']      = $i;
                    $data['list_event'][$key]['id']      = $value['id'];
                    $data['list_event'][$key]['title']   = $value['name'];
                    $data['list_event'][$key]['time']    = ($value['start_time'] == '00:00' || $value['start_time'] == '00:00:00' || $value['end_time'] == '00:00') ? event_date($value['start_date']) : event_date($value['start_date'],$value['start_time'],$value['end_time']);
                    $data['list_event'][$key]['link']    = site_url('event/detail/'.$value['uri_path']);
                    $data['list_event'][$key]['address'] = (!$value['location_name']) ? '-' : $value['location_name'];
                    $data['list_event'][$key]['color']   = 'widget-red-left';
                    
                    $i++;
                }

            }

        }

       
        echo json_encode($data);

    }

    function  check_price()
    {
        $this->load->model('eventprice_model');
        $post        = $this->input->post();
        $price_data  = $this->eventprice_model->findBy(array('id' => $post['id_price']),1);
        if($price_data['amount'] == '0'){
            echo 'true';exit;
        }else{    
            echo 'false';exit;
        }
    }

} 

/*
Past Event Total 290 record dengan id_event_category=26 
- a.is_close dimatikan karena belum membuat query is_close di view_content_event
- sangat berpengaruh kepada id_status_publish 2
- $where['a.is_not_available']  = 0; dimatikan karena tidak tahu fungsinya

End Date Upcoming Events dimatikan

Annnual Golf 
IS Close tidak ada di query
*/