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
	
	public function print_excel()
	{
		$nip 	= $this->uri->segment(3);
		$bulan 	= $this->uri->segment(4);
		$tahun 	= $this->uri->segment(5);
		$this->load->library('menuroleaccess');
		$auth_page = $this->menuroleaccess->check_access("adm_kepegawaian");
		if($auth_page) 
		{
			$this->load->model('absensi_model');
			$tanggal 		= $tahun."-".$bulan."-01";
			$max_date 		= date('t',strtotime($tanggal));
			$data			= $this->absensi_model->get_absensi($nip,$tahun,$bulan,$max_date);
			
			$this->load->library('Excel');  
		
			// Create new PHPExcel object  
			$objPHPExcel = new PHPExcel();  
			/* Set Width column */
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',"LAPORAN ABSENSI PEGAWAI");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3',$this->session->userdata('fld_empnm'));
		
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7',"TANGGAL");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B7',"JAM DATANG");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C7',"JAM PULANG");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D7',"TL");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E7',"PSW");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F7',"CUTI");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G7',"TMB");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H7',"KET");
			
			/** Set Default style **/
			$styleArray = array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				)
			);
			
			// Set document properties  
			$objPHPExcel->getProperties()->setCreator("Sulistyo Nur Anggoro - @philtyphils")  
				->setLastModifiedBy("philtyphils@gmail.com")  
				->setTitle("Report Absensi Pegawai ".$this->session->userdata('fld_empnm'))  
				->setSubject("Office 2007 XLSX Test Document")  
				->setDescription("Absensi Pegawai")  
				->setKeywords("BGR - Report Absnesi")  
				->setCategory("Report");  
				
			$clm = "8";$row="A";
			foreach($data as $key => $value)
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$clm,$value['date']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(++$row.$clm,$value['jam_datang']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(++$row.$clm,$value['jam_pulang']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(++$row.$clm,$value['TL']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(++$row.$clm,$value['PSW']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(++$row.$clm,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(++$row.$clm,"");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(++$row.$clm,"");
				$clm++;$row="A";
				
			}
			
			// Rename worksheet (worksheet, not filename)  
			$objPHPExcel->getActiveSheet()->setTitle('Absensi_report_'.$bulan);  
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet  
			$objPHPExcel->setActiveSheetIndex(0); ob_end_clean();   
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment;filename="Absensi_'.$this->session->userdata('fld_empnm')."_".$bulan."_".$tahun.".xlsx");  
			header('Cache-Control: max-age=0');  
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
			$objWriter->save('php://output');  
			exit();
		}
		else
		{
			show_error("Auth Failed To Print",'502',$heading = "AUTH PRINT EXCEL FAILED");
		}
	}
}
/* End of file home.php */
/* Location: ./application/controllers/home.php */
