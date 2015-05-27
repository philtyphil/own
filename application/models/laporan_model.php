<?php
class Laporan_model extends CI_Model{
	private $db;
	function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', TRUE);
	}
	
	function get_pegawai($where = "")
	{
		if($where == "" || empty($where))
		{
			$this->db->where('fld_emplokcd',$this->session->userdata('fld_emplokcd'));
		}
		$data = $this->db->order_by('fld_empnm',"asc")->get('view_data_absensi');
		if($data->num_rows() > 0)
		{
			return $data->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function get_lokasi()
	{
		$data = $this->db->order_by('loknm',"asc")->get('tbl_lokasi');
		if($data->num_rows() > 0)
		{
			return $data->result_array();
		}
		else
		{
			return false;
		}	
		
		
	}
	
	function data_pegawai_datatable($lokasi,$tahun)
	{
		
	}
	
	/**
	 * Menampilkan pegawai dari table view_data_pegawai
	 * @param [[Type]] $lokasi lokasinya
	 * @param [[Type]] $tahun  tahunNya
	 * @philtyphils - philtyphils@gmail.com;08118779995
	 */
	function get_laporan_2_dimensi($lokasi,$tahun)
	{
		$the_Query = "SELECT 
						A.fld_tyvalnm AS golongan,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE stspeg = 'Pensiun' AND gol = A.fld_tyvalnm) as pensiun,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE gol = A.fld_tyvalnm AND stspeg = 'Pegawai Tetap (PKWTT)') AS jml_golongan,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE gol = A.fld_tyvalnm AND stskel = 'T/K' AND stspeg = 'Pegawai Tetap (PKWTT)') AS TK,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE gol = A.fld_tyvalnm AND stskel = 'K/0' AND stspeg = 'Pegawai Tetap (PKWTT)') AS K0,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE gol = A.fld_tyvalnm AND stskel = 'K/1' AND stspeg = 'Pegawai Tetap (PKWTT)') AS K1,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE gol = A.fld_tyvalnm AND stskel = 'K/2' AND stspeg = 'Pegawai Tetap (PKWTT)') AS K2,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE gol = A.fld_tyvalnm AND stskel = 'K/3' AND stspeg = 'Pegawai Tetap (PKWTT)') AS K3,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE gol = A.fld_tyvalnm AND TIMESTAMPDIFF(YEAR,fld_empddwrk,now()) <= 10 AND stspeg = 'Pegawai Tetap (PKWTT)') AS dibwh10thn,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE gol = A.fld_tyvalnm AND TIMESTAMPDIFF(YEAR,fld_empddwrk,now()) > 10 AND stspeg = 'Pegawai Tetap (PKWTT)') AS diats10thn
						
						FROM
						(
						
						SELECT fld_tyvalnm FROM tbl_tyval
						WHERE fld_tyvalid < '112'
						AND fld_tyvalid > '99'
						) AS A";
		
		$db	= $this->db->query($the_Query);
		if($db->num_rows() > 0)
		{
			return $db->result_array();
		}
		else
		{
			return false;
		}
		
	}
	
	
	
	
}
?>