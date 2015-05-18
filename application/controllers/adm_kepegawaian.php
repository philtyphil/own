<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adm_kepegawaian extends CI_Controller {
	public $data;
	public function __construct()
	{
		parent::__construct();
		$this->data =  array(
			'template' => template(),
			'base_url' => config_item('base_url'),
		
		);
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
		$auth_page = $this->menuroleaccess->check_access("adm_kepegawaian");
		if($auth_page) 
		{
			/** Success Login **/
			$data['sForm'] 		= "Absensi";
			$data['title'] 		= "Absensi";
			$data['tanggal']	= array(''=>'','01' => "Januari",'02' => "Febuari", '03' => "Maret", '04' => "April",'05' => "Mei", '06' => "Juni", '07' => "July", '08'=> "Agustus", '09' => "September", '10' => "Oktober", '11' => "November", '12' => "Desember");
			
			if(in_array($this->session->userdata('datusergrpid'),array("1",true)))
			{
				$this->load->model('absensi_model');
				$data['pegawai'] = $this->absensi_model->data_pegawai();
				
			}
			render('absensi',$data,"absensi");
			
		}
		else
		{
			$this->load->library('sess');
			$this->sess->session_destroy();
			redirect(config_item('base_url'));
		}
	}
	
	public function search()
	{
		$data['data'] 	= ""; 
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nip', 'NIP', 'trim|required|alpha_numeric|min_length[3]n');
		$this->form_validation->set_rules('bulan', 'Bulan', 'trim|required|max_length[3]');
		$this->form_validation->set_rules('tahun', 'Tahun', 'trim|required|numeric|max_length[5]');
		
		if($this->form_validation->run() == FALSE)
		{
			if(validation_errors('nip')!=NULL){
				$view['error_nip'] = strip_tags(form_error('nip'));
			}
			if(validation_errors('bulan')!=NULL){
				$view['error_bulan'] = strip_tags(form_error('bulan'));
			}
			if(validation_errors('tahun')!=NULL){
				$view['error_tahun'] = strip_tags(form_error('tahun'));
			}
			
		}
		else
		{
			$tahun 	= $this->input->post('tahun');
			$bulan 	= $this->input->post('bulan');
			$nip	= $this->input->post('nip');
			$this->load->model('absensi_model');
			$tanggal = $tahun."-".$bulan."-01";
			
			$max_date = date('t',strtotime($tanggal));
		
			$absensi_data 			= $this->absensi_model->get_absensi($nip,$tahun,$bulan,$max_date);
			$buff['absensi_data'] 	= $absensi_data;
			$buff['hari']			= array('Sunday' => "Minggu",'Monday' => "Senin", 'Tuesday'=>"Selasa",'Wednesday' => "Rabu",'Thursday' => "Kamis", 'Friday' => "Jumat",'Saturday' => "Sabtu");
			$view['table_absensi']  = $this->parser->parse(template().'/jLoadpage/absensi_content.html',$buff);
			
		}
		unset($absensi_data);
		header('content-type:application/json');
		echo json_encode($view);
		exit();
	}
	
	public function cuti()
	{
		
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
