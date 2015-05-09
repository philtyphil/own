<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	public $raw;
	public function __construct()
	{
		parent::__construct();
		
		if(!$this->session->userdata('logged'))
		{
			show_error('Dissalowed Page',"404",$heading = "Autherized is failed");
		}
		header('content-type:text/html;charset=utf-8');
	}
	
	public function index()
	{
		$this->load->helper('url');
		$this->load->library('menuroleaccess');
		$auth_page = $this->menuroleaccess->check_access("home");
		if($auth_page)
		{
			
		}
		else
		{
			$this->load->library('sess');
			$this->sess->session_destroy();
			redirect(config_item('base_url'));
		}
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
