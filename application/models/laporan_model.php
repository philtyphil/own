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
	
	/**
	 * [[Description]]
	 * @param [[Type]] $lokasi         Lokasi Pegawai
	 * @param [[Type]] $golongan       Golongan pegawai (yg akan di tampilkan)
	 * @param [[Type]] $status_pegawai status Pegawai Contoh Pegawai Tetap
	 * @param [[Type]] $tahun          tahun
	 */
	function data_pegawai_datatable($lokasi,$golongan,$status_pegawai,$tahun)
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
						(SELECT COUNT(*) FROM view_data_pegawai WHERE stspeg = 'Pensiun' AND id_golongan = A.fld_tyvalid AND id_lokasi = '$lokasi') as pensiun,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE id_golongan = A.fld_tyvalid AND id_lokasi = '$lokasi') AS jml_golongan,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE id_golongan = A.fld_tyvalid AND id_status_keluarga = '94' AND id_lokasi = '$lokasi') AS TK,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE id_golongan = A.fld_tyvalid AND id_status_keluarga = '95' AND id_lokasi = '$lokasi' ) AS K0,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE id_golongan = A.fld_tyvalid AND id_status_keluarga = '96' AND id_lokasi = '$lokasi' ) AS K1,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE id_golongan = A.fld_tyvalid AND id_status_keluarga = '97' AND id_lokasi = '$lokasi') AS K2,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE id_golongan = A.fld_tyvalid AND id_status_keluarga = '98' AND id_lokasi = '$lokasi') AS K3,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE id_golongan = A.fld_tyvalid AND TIMESTAMPDIFF(YEAR,fld_empddwrk,now()) <= 10  AND id_lokasi = '$lokasi') AS dibwh10thn,
						(SELECT COUNT(*) FROM view_data_pegawai WHERE id_golongan = A.fld_tyvalid AND TIMESTAMPDIFF(YEAR,fld_empddwrk,now()) > 10   AND id_lokasi = '$lokasi') AS diats10thn
						
						FROM
						(
						
						SELECT fld_tyvalid,fld_tyvalnm FROM tbl_tyval
						WHERE fld_tyid= '24'
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
	
	function get_golongan()
	{
		$data = $this->db->select('fld_tyvalid,fld_tyvalnm')->where('fld_tyid',"24")->order_by('fld_tyvalid',"ASC")->get('tbl_tyval');
		
		if($data->num_rows > 0)
		{
			return $data->result_array();
		}
		else
		{
			return array();
		}
	}
	
	function get_status_pegawai()
	{
		$data = $this->db->where('fld_tyid',"19")->order_by('fld_tyvalnm',"ASC")->get('tbl_tyval');
		if($data->num_rows > 0)
		{
			return $data->result_array();
		}
		else
		{
			return array();
		}
	}
	
	
	
	
}
?>