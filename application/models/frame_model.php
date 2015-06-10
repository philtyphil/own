<?php
class Frame_model extends CI_Model{
	private $db;
	public $menu = array();
	function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', TRUE);
		
	}
	
	
	function set_menu()
	{
		$menuData = array(
			'items' => array(),
			'parents' => array()
		);
		$html = "";
		//$data = $this->get_menu_id(0);
		$write = $this->get_on_html(0);
		
		return $write;
	}
	
	private function get_on_array($array,$parent_id)
	{
		//bug($data->result_array());
		if(is_array($array))
		{
			$temp_array = array();
			foreach($array as $element)
			{
				
				$array = $this->db->where('fld_menuidp',$element['fld_menuid'])->get('tbl_menu');
				
					if($array->num_rows() > 0)
					{
						$array = $array->result_array();
						
						$element['subs'][] = $array;
						$this->get_on_array($array,$element['fld_menuid']);
					}
					else
					{
						$element['subs'] = "";
					}
					
					$temp_array[] = $element;
				
			}
			//bug($temp_array);
			return $temp_array;
		
		}
	}
	
	private function get_on_html($parent_id)
	{
		$wHtml = "";
		$data = $this->db->order_by('fld_menuorder',"ASC")->where('fld_menuidp',$parent_id)->get('tbl_menu');
		if($data->num_rows > 0)
		{
			foreach($data->result_array() as $key => $value)
			{
				$handler = $this->db->where('fld_menuidp',$value['fld_menuid'])->count_all_results('tbl_menu');
				$arrow 	 = ($handler > 0) ? '<li class="panel"><a href="javascript:;" class="accordion-toggle" data-toggle="collapse"  data-target="#'.$value['fld_menunm'].'"><i class="fa fa-angle-double-right"></i> '.$value['fld_menunm'].'  <span class="fa arrow"></span></a>' : '<li><a href="'.base_url($value['fld_menuurl']).'/" ><i class="fa fa-angle-double-right"></i> '.$value['fld_menunm'].'</a>';
				
				$wHtml .= $arrow;
				$wHtml .= ($handler > 0) ? '<ul class="collapse nav" id="'.$value['fld_menunm'].'">' : "";
				$wHtml .= $this->get_on_html($value['fld_menuid']);
				$wHtml .= ($handler > 0) ?  '</ul>' : "";
				$wHtml .= "</li>";
			}
		}
		
		
		return $wHtml;
	}
	
	private function write_to_html($data,$parent_id)
	{
		$html = "<li class='panel'>";
		foreach($data as $key => $value)
		{
			$html 		.= '<a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#'.$value['fld_menuid'].'">';
			$html 		.= '<i class="fa fa-edit"></i>'. $value['fld_menunm'].' <span class="fa arrow"></span>';
			$html 		.= '</a>';
			$pegawai 	= $this->get_menu_id($value['fld_menuidp'],$parent_id);
			if($pegawai->num_rows() > 0)
			{
				$pegawai = $pegawai->result_array();
				$html	.= "<li>";
				$html 	.= '<ul class="collapse nav" id="'.$value['fld_menuid'].'">';
				$html	.= $this->write_to_html($pegawai,$pegawai[0]['fld_menuidp']);
				$html 	.= '</ul>';
				$html	.= "</li>";
			}
			else
			{
				$html   .= "<li>";
				$html	.= "<a href='#'>NO SUB</a>";
				$html	.= "</li>";
			}
		}
		$html .= "</li>";
		return $html;
	}
	
	private function get_menu_id($id)
	{
		$data = $this->db->order_by("fld_menuorder","ASC")->where("fld_menuidp",$id)->get('tbl_menu');
		if($data->num_rows() > 0)
		{
			return $data->result_array();
		}
		else
		{
			return array();
		}
	}
	
	function get_menu($id = "")
	{
		
		$data = $this->db->from('tbl_menu')->order_by('tbl_menu.fld_menuorder')->join('tbl_acl','tbl_acl.fld_aclval=tbl_menu.fld_menuid')->where('tbl_acl.fld_aclgrp',$this->session->userdata('datusergrpid'))->where('tbl_menu.fld_menuidp',0)->get();
		if($data->num_rows () > 0)
		{
			return $data->result_array();
		}
		else
		{
			return false;
		}
	}
	
	private function buildmenu($parent_id,$menuData,$html ="")
	{
		
		if(isset($menuData['parents'][$parent_id]))
		{
			
			foreach($menuData['parents'][$parent_id] as $key => $item)
			{
				$html .= "<li class='panel'>";
				$html .= '<a href="javascript:;" class="accordion-toggle" data-parent="#side" data-toggle="collapse"  data-target="#'.strtolower($menuData['nama_menu'][$parent_id][$key]).'">';
				$html .= '<i class="fa fa-angle-double-right"></i>'.$menuData['nama_menu'][$parent_id][$key];
				$html .= '</a>';
				$data = $this->db->where('fld_menuidp',$item)->get('tbl_menu');
				if($data->num_rows() > 0 && $data)
				{
					
					$html .= '<ul class="collapse nav" id="'.strtolower($menuData['nama_menu'][$parent_id][$key]).'">';
					
					foreach($data->result_array() as $key => $value)
					{
						$html .= '<li><a href="'.$value['fld_menuurl'].'" title="'.$value['fold_menunm'].'"><i class="fa fa-angle-double-right"></i> '.$value['fld_menunm'].'</a></li>';
						
						$html .= $this->buildmenu($value['fld_menuidp'],$menuData,$html);
						
					}
					
					$html .= '</ul>';
				}
				else
				{
					$html .= '<li>
								<a href="tables.html">
									<i class="fa fa-list"></i> '.$menuData['nama_menu'][$parent_id][$key].'
								</a>
							</li>';
				}
				//$html .= $this->buildmenu($item,$menuData,$html);
				$html .= "</li>";
				
			}
		
			
			
			return $html;
		}
	}
	
	
	
	
	
}
?>