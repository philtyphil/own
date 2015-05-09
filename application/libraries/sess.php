<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed - philtyphil');

class Sess
{
	public function sess_reg($data)
	{
		$CI=& get_instance();
			
		$CI->load->library('user_agent','encrypt');
		
		$newdata = array(
			"logged" 		=> 1,
			"encrypt"		=> encode($data[0]['id']),
			"username"		=> $data[0]['username'],
			"time"			=> strtotime(date("Y-m-d H:i:s")),
			"browser"		=> $CI->agent->browser(),
			"ip"			=> $_SERVER['REMOTE_ADDR'],
			"last_login" 	=> date("Y-m-d H:i:s"),
			"os"			=> $CI->agent->platform(),
			"group"			=> $data[0]['group'],
			
			
		);
		$CI->session->set_userdata($newdata);
		
		$sesson = array(
			"browser"		=> $CI->agent->browser(),
			"ip"			=> $_SERVER['REMOTE_ADDR'],
			"last_login"	=> date("Y-m-d H:i:s"),
			"os"			=> $CI->agent->platform(),
			"group"			=> $data[0]['group']
			
			
		);
		$data = $this->update_login($data[0]['id'],$sesson);
		if($data)
		{
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
		$update = $CI->db->where("id",$id)->update('bgr_user',$var);
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