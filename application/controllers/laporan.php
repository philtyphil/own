<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Laporan extends CI_Controller {
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
			show_error('Dissalowed Page',"404",$heading = "Autherized is failed. No Page Found!");
		}
		header('content-type:text/html;charset=utf-8');
	}
	
	
	public function index()
	{
		$this->load->helper('url');
		$this->load->library('menuroleaccess');
		$auth_page = $this->menuroleaccess->check_access("laporan");
		if($auth_page) 
		{
			$this->load->model('laporan_model');
			/** Success Login **/
			$data['sForm'] 			= "Laporan";
			$data['title'] 			= "Laporan";
			$data['lokasi']			= $this->laporan_model->get_lokasi();
			$data['golongan']		= $this->laporan_model->get_golongan();
			$data['status_pegawai']	= $this->laporan_model->get_status_pegawai();
			$data['pendidikan']		= $this->laporan_model->get_pendidikan_pegawai();
			render('laporan',$data,"laporan");
			
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
		$this->form_validation->set_rules('lokasi', 'Lokasi ', 'trim|required|alpha_numeric|min_length[3]');
		$this->form_validation->set_rules('tahun', 'Tahun', 'trim|required|max_length[4]');
		
		if($this->form_validation->run() == FALSE)
		{
			if(validation_errors('lokasi')!=NULL){
				$view['error_lokasi'] = strip_tags(form_error('lokasi'));
			}
			if(validation_errors('bulan')!=NULL){
				$view['error_tahun'] = strip_tags(form_error('tahun'));
			}
			
		}
		else
		{
			$buff['lokasi'] 	= encode($this->input->post('lokasi'));
			$buff['tahun']		= encode($this->input->post('tahun'));
			$buff['base_url']	= config_item('base_ur');
			$buff['template']	= template();
			$this->load->model('laporan_model');
			$buff['data']		= $this->laporan_model->get_laporan_2_dimensi($this->input->post('lokasi'),$this->input->post('tahun'));
			$view['table_absensi']  = $this->parser->parse(template().'/jLoadpage/laporan_2_dimensi_content.html',$buff);
			
		}
	
		header('content-type:application/json');
		echo json_encode($view);
		exit();
	}
	
	/**
	 * Get Laporan Dari Datatable
	 */
	public function get_laporan_datatable()
	{
		$lokasi = decode($this->uri->segment(3));
		$tahun	= decode($this->uri->segment(4));
		$this->load->model('laporan');
		$data 	= $this->laporan->data_pegawai_datatable($lokasi,$tahun);
		bug($data);
	}	
	
	public function insert()
	{
		
		$this->load->library('menuroleaccess');
		$auth_page = $this->menuroleaccess->check_access("laporan");
		if($auth_page && $this->input->post())
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nip', 'NIP', 'trim|required|min_length[5]');
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('jam_datang', 'Jam Datang', 'trim|required|max_length[5]|callback_check_time');
			$this->form_validation->set_rules('jam_pulang', 'Jam Pulang', 'trim|required|max_length[5]|callback_check_time');
			
			if($this->form_validation->run() == FALSE)
			{
				if(validation_errors('nip')!=NULL){
					$view['error_nip'] = strip_tags(form_error('nip'));
				}
				if(validation_errors('tanggal')!=NULL){
					$view['error_tanggal'] = strip_tags(form_error('tanggal'));
				}
				if(validation_errors('jam_datang')!=NULL){
					$view['error_jam_datang'] = strip_tags(form_error('jam_datang'));
				}
				if(validation_errors('jam_pulang')!=NULL){
					$view['error_jam_pulang'] = strip_tags(form_error('jam_pulang'));
				}
				
				
				
			}
			else
			{
				$this->db->where('fld_absensidt',$this->input->post('tanggal'))->where('fld_empnik',$this->input->post('nip'))->delete('tbl_absensi');
				$dataInsert = array(
					array(
						"fld_empnik" => $this->input->post('nip'),
						"fld_absensidt" => $this->input->post('tanggal'),
						"fld_absensihr" => $this->input->post('jam_datang'),
						"fld_absensity" => 1,
						"fld_lup" => date("Y-m-d H:i:s")
					),
					array(
						"fld_empnik" => $this->input->post('nip'),
						"fld_absensidt" => $this->input->post('tanggal'),
						"fld_absensihr" => $this->input->post('jam_pulang'),
						"fld_absensity" => 2,
						"fld_lup" => date("Y-m-d H:i:s")
					)
				);
				$data = $this->db->insert_batch('tbl_absensi',$dataInsert);
				$view['error_insert'] = "";
				if(!$data)
				{
					$view['error_insert'] = "Insert Error. Silakan Laporankan Ke bagian IT";
				}
				else
				{
					
					$tahun 		= date('Y',strtotime($this->input->post('tanggal')));
					$bulan 		= date('m',strtotime($this->input->post('tanggal')));
					$nip		= $this->input->post('nip');
					$this->load->model('absensi_model');
					$tanggal 	= $tahun."-".$bulan."-01";
					$max_date 	= date('t',strtotime($tanggal));
					$absensi_data 			= $this->absensi_model->get_absensi($nip,$tahun,$bulan,$max_date);
					$buff['absensi_data'] 	= $absensi_data;
					$buff['hari']			= array('Sunday' 	=> "Minggu",
													'Monday' 	=> "Senin", 
													'Tuesday'	=>"Selasa",
													'Wednesday' => "Rabu",
													'Thursday' 	=> "Kamis", 
													'Friday' 	=> "Jumat",
													'Saturday' 	=> "Sabtu");
					
					$view['table_absensi']  = $this->parser->parse(template().'/jLoadpage/absensi_content.html',$buff);
				}
				
			}
			header('content-type:application/json');
			echo json_encode($view);
			exit();
			
		}
		
	}
	
	public function json()
	{
		$lokasi			= decode($this->uri->segment(3));
		$golongan 		= decode($this->uri->segment(4));
		$status_pegawai	= decode($this->uri->segment(5));
		$pendidikan		= decode($this->uri->segment(6));
		$masa_kerja		= decode($this->uri->segment(6));
		
		// Prepare Data to Load Datatable
		$this->load->model('laporan_model');
		$data = $this->laporan_model->json_dt($_GET,$lokasi,$golongan,$status_pegawai,$pendidikan,$masa_kerja);
		
		if(is_array($data))
		{
		
			header('content-type:application/json');
			echo json_encode($data);
			exit();
		}
		else
		{
			show_error("Data Return Isn't Array Value","501",$heading="Eror Load Datatable - @philtyphils");
			return false;
		}
	}
	
	/**
	 * Validasi input jam
	 * @param  string $str ex 08:00
	 * @return boolean  true/false
	 */
	function check_time($str)
	{
		$data = preg_match('/^([0-9]{2}):([0-9]{2})/',$str);
		if($data)
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('check_time', 'Format Jam Yang Anda Masukan Tidak Sesuai.');
			return false;
		}
	}

	
	/**
	 * Fungsi Menampilkan Data Table (tablenya aja!)
	 */
	public function rekap()
	{
		$this->load->library('menuroleaccess');
		$auth_page = $this->menuroleaccess->check_access("laporan");
		
		if($auth_page && $this->input->post())
		{
		
			$this->load->library('form_validation');
			$this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|required|max_length[3]|numeric');
			$this->form_validation->set_rules('golongan[]', 'Golongan', 'trim|required');
			$this->form_validation->set_rules('status_pegawai[]', 'Status Pegawai', 'trim|required');
			$this->form_validation->set_rules('awal', 'Masa Kerja (Awal)', 'trim|max_length[2]|numeric');
			$this->form_validation->set_rules('akhir', 'Masa Kerja (Akhir)', 'trim|max_length[2]|numeric');
			$this->form_validation->set_rules('pendidikan[]', 'Pendidikan', 'trim|required');
		
			if($this->form_validation->run() == FALSE)
			{
				$view = "";
				if(validation_errors('lokasi')!=NULL){
					$view['error_lokasi'] = strip_tags(form_error('lokasi'));
				}
				if(validation_errors('golongan')!=NULL){
					$view['error_golongan'] = strip_tags(form_error('tanggal'));
				}
				if(validation_errors('status_pegawai')!=NULL){
					$view['error_status_pegawai'] = strip_tags(form_error('status_pegawai'));
				}
				if(validation_errors('awal')!=NULL){
					$view['error_awal'] = strip_tags(form_error('awal'));
				}
				if(validation_errors('akhir')!=NULL){
					$view['error_akhir'] = strip_tags(form_error('akhir'));
				}
				if(validation_errors('pendidikan')!=NULL){
					$view['error_pendidikan'] = strip_tags(form_error('pendidikan'));
				}
			}
			else
			{
				// Ubah Array Golongan Menjadi String - @philtyphils
				$golongan = $this->input->post('golongan');
				if(is_array($this->input->post('golongan')))
				{
					$golongan = "";
					foreach($this->input->post('golongan') as $key => $value)
					{
						$golongan .= $value ."|";
					}
				}
		
				// Ubah Array Status Pegawai Menjadi String - @philtyphils
				$status_pegawai = $this->input->post('status_pegawai');
				if(is_array($this->input->post('status_pegawai')))
				{
					$status_pegawai = "";
					foreach($this->input->post('status_pegawai') as $key => $value)
					{
						$status_pegawai .= $value . "|";
					}
				}

				// Ubah Array Pendidikan Pegawai Menjadi String - @philtyphils
				$pendidikan = $this->input->post('pendidikan');
				if(is_array($this->input->post('pendidikan')))
				{
					$pendidikan = "";
					foreach($this->input->post('pendidikan') as $key => $value)
					{
						$pendidikan .= $value ."|";
					}
				}
		
				$masa_kerja = ($this->input->post('akhir') == "" && $this->input->post('awal') != "") ? $this->input->post('awal')."-".$this->input->post('awal') : $this->input->post('awal')."-".$this->input->post('akhir');
			
				$buff['lokasi']					= encode($this->input->post('lokasi'));
				$buff['golongan']				= encode($golongan);
				$buff['status_pegawai']			= encode($status_pegawai);
				$buff['pendidikan']				= encode($pendidikan);
				$buff['masa_kerja']				= encode($masa_kerja);
				$buff['base_url']				= config_item('base_url');
				$buff['template']				= template();
				$view['table_rekap_pegawai']	= $this->parser->parse(template().'/jLoadpage/laporan_content.html',$buff);
				$view['success']				= "UYEAY";
				
			}
			header('content-type:application/json');
			echo json_encode($view);
			exit();
			
		}
		else
		{
			show_error("Auth Failed To Access This URL","501",$heading="Auth Access Failed");
		}
	}
	
	/**
	 * Fungsi untuk cetak file format excel!
	 */
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
	
	/**
	 * Print to pdf with PHP tcpdf
	 */
	public function print_pdf()
	{	
		$this->load->library('menuroleaccess');
		$auth_page = $this->menuroleaccess->check_access("adm_kepegawaian");
		if($auth_page)
		{
			$this->load->library('outpdf');$this->load->model('absensi_model');
			$nip 		= $this->uri->segment(3);
			$bulan 		= $this->uri->segment(4);
			$tahun 		= $this->uri->segment(5);
			$tanggal 	= $tahun."-".$bulan."-01";
			$max_date 	= date('t',strtotime($tanggal));
			$data		= $this->absensi_model->get_absensi($nip,$tahun,$bulan,$max_date);
			$cetak 		= cetak_absen($nip,$bulan,$tahun,$data);
			$pdf 		= new Outpdf();
			$pdf->out($cetak, FALSE, 'absensi_bulanan.pdf', 'P');
			exit();
		}
		else
		{
			show_error("Auth Failed To Print",'502',$heading = "AUTH PRINT PDF FAILED");
		}
	}
	
	/**
	 * Print HTML
	 */
	public function print_html()
	{
		$this->load->model('absensi_model');
		$nip 		= decode($this->uri->segment(3));
		$bulan 		= $this->uri->segment(4);
		$tahun 		= $this->uri->segment(5);
		$tanggal 	= $tahun."-".$bulan."-01";
		$max_date 	= date('t',strtotime($tanggal));
		$data		= $this->absensi_model->get_absensi($nip,$tahun,$bulan,$max_date);
		$cetak 		= cetak_absen($nip,$bulan,$tahun,$data);
		echo $cetak;
		exit();
	}
}
/* End of file laporan.php */
/* Location: ./application/controllers/laporan.php */
/* Contact : philtyphils@gmail.com;08118779995 */