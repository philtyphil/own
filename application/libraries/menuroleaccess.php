<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed - philtyphil');

class Menuroleaccess
{
	function __construct()
	{
		
	}
	
	function check_access($page)
	{
		$CI=& get_instance();
		$CI->load->library('user_agent');
		
		try{
		
			$data = $CI->db->limit("1")->where('id',decode($CI->session->userdata('encrypt')))->get('bgr_user');
		
			if($data->num_rows() > 0)
			{
				$data 	= $data->result_array();
				$sess 	= $CI->session->userdata();
				$group	= $CI->db->where('id',$sess['group'])->where('menu_access',strtolower($page))->limit(1)->get('bgr_acl')->result_array();
				
				if($CI->agent->browser() == $data[0]['browser'] && $CI->agent->platform() == $data[0]['os'] &&$sess['group'] == $group[0]['view'])
				{
					
					return true;
				}
				else
				{
					$time = $sess['time'] - strtotime(date("Y-m-d H:i:s"));
					if($time > 7200)
					{
						$CI->session->sess_destroy();
					}
					return false;
				}
			}
		} catch (Exception $e) {
			show_error($e->getMessage(),"404",$header="Error 500");
		}
		
		
	}

	
}
