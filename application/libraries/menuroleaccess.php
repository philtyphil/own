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
			$data = $CI->db->limit("1")->where('fld_empid',$CI->session->userdata('fld_empid'))->get('tbl_emp');
			
			if($data->num_rows() > 0)
			{
				$data 	= $data->result_array();
				$sess 	= $CI->session->userdata();
				$group	= $CI->db->where('user_group',$sess['datusergrpid'])->where('menu_access',strtolower($page))->limit(1)->get('bgr_module');
			
				if($group->num_rows > 0)
				{
					$group = $group->result_array();
				
					if($CI->agent->browser() == $data[0]['fld_browser'] &&  $_SERVER['REMOTE_ADDR'] == $data[0]['fld_ip'])
					{
						
						return true;
					}
					else
					{
						$time = $sess['__ci_last_regenerate']- strtotime(date("Y-m-d H:i:s"));
						if($time > 7200)
						{
							$CI->session->sess_destroy();
						}
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
			
		} catch (Exception $e) {
			show_error($e->getMessage(),"404",$header="Error 500");
		}
		
		
	}

	
}
