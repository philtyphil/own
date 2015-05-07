<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	public $raw;
	public function __construct()
	{
		parent::__construct();
		
		
		header('content-type:text/html;charset=utf-8');
	}
	
	public function index()
	{
		$view['template'] = template();
		$this->load->view(template().'/login.html',$view);
	}
	
	public function proses()
	{
		$this->load->library('form_validation');
	
		$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_lenth[4]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			if(validation_errors('username')!=NULL){
				$view['error_username'] = strip_tags(form_error('username'));
			}
			if(validation_errors('password')!=NULL){
				$view['error_password'] = strip_tags(form_error('password'));
			}
			
		}
		else
		{
			$this->load->model('login_model');
			if($data)
			{
				$view['notif'] = "Add User Success";
			}
			
		}
		header('content-type:application/json');
		echo json_encode($view);
		exit();
		
	}
	
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
