<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed - philtyphil');

class Sess
{
	public function sess_reg($user)
	{
		$CI=& get_instance();
			
		$CI->load->library('user_agent','encrypt');
		
		$CI->db->select("*");
		$CI->db->from('tbl_emp');
		$CI->db->where('tbl_emp.fld_empid',$user[0]['personid']);
		$CI->db->join('tbl_pekerjaan','tbl_pekerjaan.fld_pekerjaanid=tbl_emp.fld_emppos');
		$CI->db->join('tbl_posisi','tbl_posisi.PosID=tbl_pekerjaan.PosID');
		$CI->db->join('tbl_unit','tbl_unit.unitid=tbl_pekerjaan.unitid');
		$CI->db->join('tbl_lokasi','tbl_lokasi.lokcd=tbl_unit.lokcd');
		$data = $CI->db->get()->result_array();
		$data = $data[0];
		$data["logged"] = 1;
		$data["datusergrpid"] 	= $user[0]['datusergrpid'];
		$data["datusergrpnm"] 	= $user[0]['datusergrpnm'];
		
		$session =  array(
			"fld_browser"		=> $CI->agent->browser(),
			"fld_ip"			=> $_SERVER['REMOTE_ADDR'],
			"fld_lastlogin" 	=> date("Y-m-d H:i:s")
		);
		$data['validate'] = $session;
		
		
		$r = $this->update_login($data['fld_empid'],$session);
		if($r)
		{
			$define = $CI->db->where('user_group',$user[0]['datusergrpid'])->get('bgr_module');
			if($define->num_rows () > 0)
			{
				$define = $define->result_array();
				foreach($define as $key => $value)
				{
					
					$data["VIEW_".strtoupper($value['menu_access'])."_ACCESS"] = strtoupper($value['view']);
					$data["ADD_".strtoupper($value['menu_access'])."_ACCESS"] = strtoupper($value['add']);
					$data["EDIT_".strtoupper($value['menu_access'])."_ACCESS"] = strtoupper($value['edit']);
					$data["DELETE_".strtoupper($value['menu_access'])."_ACCESS"] = strtoupper($value['delete']);
					
				}
				
			}
			$CI->session->set_userdata($data);unset($define);
			return true;
		}
		else
		{
			show_error("Error Update","404",$heading="Update Sesson To Database Failed");
			return false;
		}
		
		
		
	}
	
	public function session_destroy()
	{
		$CI=& get_instance();
		$newdata = array(
		"ip" 		=> "",
		"browser" 	=> "",
		"os"		=> ""
		);
		$CI->session->sess_destroy();
		$CI->db->where('id',$CI->session->userdata(decode($CI->session->userdata('id'))))->update('bgr_user',$newdata);
		return false;
	}
	
	public function update_login($id,$var)
	{
		$CI=& get_instance();
		$update = $CI->db->where("fld_empid",$id)->update('tbl_emp',$var);
		if($update)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}