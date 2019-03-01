<?php
class PagesModel extends  CI_Model{
	var $table = 'pages';
	var $tableAs = 'pages a';
    function __construct(){
      parent::__construct();
      $this->load->model('committee_model');
    }
	function records($where=array(),$isTotal=0){
		$alias['search_title'] = 'a.name';
		// $ttl_row = $this->db->get($this->tableAs)->num_rows();

	 	query_grid($alias,$isTotal);
		$this->db->select("a.*");
		$this->db->where('a.is_delete',0);
		// $this->db->where('a.id_parent_lang !=',0);
		// $this->db->where('a.id_lang',1);
		$query = $this->db->get($this->tableAs);

		if($isTotal==0){
			$data = $query->result_array();
		}
		else{
			return $query->num_rows();
		}

		$ttl_row = $this->records($where,1);
		
		// echo $this->db->last_query();
		return ddi_grid($data,$ttl_row);
	}
	function insert($data){
		$data['create_date'] 	= date('Y-m-d H:i:s');
		$data['user_id_create'] = id_user();
		$this->db->insert($this->table,array_filter($data));
		return $this->db->insert_id();
	}
	function selectData($id,$is_single_row=0){
		$this->db->where('is_delete',0);
		$this->db->where('id',$id);
		$this->db->or_where('id_parent_lang',$id);
		if($is_single_row==1){
			return 	$this->db->get_where($this->table)->row_array();
		}else{
			return 	$this->db->get_where($this->table)->result_array();
		}
	}
	function update($data,$id){
		$where['id'] = $id;
		$data['user_id_modify'] = id_user();
		$data['modify_date'] 	= date('Y-m-d H:i:s');
		$this->db->update($this->table,$data,$where);
		return $id;
	}
	function updateKedua($data,$id){
		$where['id_parent_lang'] 	= $id;
		$data['user_id_modify'] 	= id_user();
		$data['modify_date'] 		= date('Y-m-d H:i:s');
		$this->db->update($this->table,$data,$where);
		return $id;
	}
	function delete($id){
		$data['is_delete'] = 1;
		$this->update($data,$id);
	}
	function delete2($id){
		$data = array(
            'is_delete' => 1,
            'user_id_modify' => id_user(),
            'modify_date' => date('Y-m-d H:i:s'),
        );
        $this->db->where('id_parent_lang', $id);
        $this->db->update($this->table, $data);
	}
	function findById($id){
		$where['a.id'] = $id;
		$where['is_delete'] = 0;
		return 	$this->db->get_where($this->table.' a',$where)->row_array();
	}
	function findBy($where,$is_single_row=0){
		$where['is_delete'] = 0;
		$this->db->select('*');
		if($is_single_row==1){
			return 	$this->db->get_where($this->tableAs,$where)->row_array();
		}
		else{
			return 	$this->db->get_where($this->tableAs,$where)->result_array();
		}
	}
	function fetchRow($where) {
		return $this->findBy($where,1);
	}
	function get_amcham_executive_office(){
		return '<hr class="line-content">
                  <h4 class="title-content">AmCham Executive Office</h4>
                  <div class="widget-white p15 mb15">
                    <div class="row">
                      <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Lin Neumann</h3>
                            <p>Managing Director</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="widget-white p15 mb15">
                    <div class="row">
                      <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('images/co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Astari Dyah S</h3>
                            <p>Executive Assistant to Managing Director</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('images/co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Sarah Howe</h3>
                            <p>Executive Director</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="widget-white p15 mb15">
                    <div class="row">
                      <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('images/co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Ruskiah Sjahrila N</h3>
                            <p>Membership Coordinator & HR Officer</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="widget-white p15 mb15">
                    <div class="row">
                      <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('images/co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Julia Lonan</h3>
                            <p>Events Manager</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('images/co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Evita Mardianti</h3>
                            <p>Events Coordinator Assistant</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="widget-white p15 mb15">
                    <div class="row">
                      <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('images/co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Gilang Ardana</h3>
                            <p>Policy Associate</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('images/co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Karmila Rizal Bain</h3>
                            <p>Junior Policy Associate</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="widget-white p15 mb15">
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-xs-12 mb15">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('images/co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Sartini</h3>
                            <p>Office Support Services</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('images/co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Admin Suherman</h3>
                            <p>Office Assistant</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="media media-cochair">
                          <div class="media-left">
                            <div class="thumnail-amcham thumb-cochair">' .getImg('images/co-chair.jpg','small'). '</div>
                          </div>
                          <div class="media-body">
                            <h3 class="title-cochair">Dedy Iskandar</h3>
                            <p>Office Assistant</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>';
	}
  
	function get_amcham_committe_list(){
    $data['list_committe'] =  $this->committee_model->findBy();
 
    $ret .= '<hr class="line-content">';
    $ret .= '<h4 class="title-content">Click on each Committee to learn more.</h4>';
    $ret .= '<div class="row">';
    $ret .= '<div class="col-sm-6 col-md-6 col-xs-6 list-column">';
    $i = 0;  
    foreach  ($data['list_committe'] as $key => $value) {
      $i++;
      $ret                         .= $i % 11 == 0 ? '</div><div class="col-sm-6 col-md-6 col-xs-6 list-column">': '' ;
      $ret                         .= '<a href="'.base_url_lang().'/pages/committee/'.$value['uri_path'].'">'.$value['name'].'</a>';
    }
     $ret .= '</div>';
    $ret .= '</div>';
    return $ret;
  }

	function pages_content($type){
    $CI =& get_instance();
    $CI->load->model('eventmodel');

    $data['lang']     = $CI->uri->segment(1);
    $data['base_url'] = base_url();
    $CI->data  = $data;

		switch ($type) {
			// case 'directory':
   //      $ret['page_content'] = $CI->parser->parse('pages/directory.html', $CI->data,true);
   //      $ret['page_name'] = 'Directory';
   //      $ret['seo_title'] = 'Directory';
   //      return $ret;
			// 	break;
			
      case 'committee':
        $ret['page_content'] = $CI->parser->parse('pages/committee.html', $CI->data,true);
        $ret['page_name'] = 'Committee';
        $ret['seo_title'] = 'Committee';
        return $ret;        
        break;

      case 'member-privileges':
        $ret['page_content'] = $CI->parser->parse('pages/member_privileges.html', $CI->data,true);
        $ret['page_name'] = 'Membership Privileges';
        $ret['seo_title'] = 'Membership Privileges';
        $ret['no_show_frontend'] = 1;
        return $ret;
        break;

      case 'members':
        $ret['page_content'] = $CI->parser->parse('pages/members.html', $CI->data,true);      
        $ret['page_name'] = 'Membership';
        $ret['seo_title'] = 'Membership';
        $ret['no_show_frontend'] = 1;
        return $ret;   

        break;

      case 'amcham-indonesia':
        $ret['page_content'] = $CI->parser->parse('pages/amcham_indonesia.html', $CI->data,true);      
        $ret['page_name'] = 'AmCham Indonesia';
        $ret['seo_title'] = 'AmCham Indonesia';
        return $ret;   

        break;

			default:
				# code...
				break;
		}
	}
 }
