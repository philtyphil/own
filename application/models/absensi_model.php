<?php
class Absensi_model extends CI_Model{
	private $db;
	function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', TRUE);
	}
	
	function get_absensi($nip,$tahun,$bulan,$max_date)
	{
		$SQL = "SELECT r.date, 
				r.jam_datang, 
				r.jam_pulang,
				CASE WHEN r.jam_datang > '08:15:00' THEN (
				sec_to_time(time_to_sec(r.jam_datang) - time_to_sec('08:15:00'))
				) END as TL,
				CASE WHEN r.jam_pulang < '17:00:00' AND r.jam_pulang is not null THEN (
				sec_to_time(time_to_sec('17:00:00') - time_to_sec(r.jam_pulang))
				) END  as PSW
 FROM (
		SELECT 
		A.date,
		(SELECT MIN(fld_absensihr) FROM tbl_absensi WHERE fld_empnik = '$nip' AND fld_absensidt = a.date ) AS jam_datang,
		(SELECT MAX(fld_absensihr) FROM tbl_absensi WHERE fld_empnik = '$nip' AND fld_absensidt = a.date ) AS jam_pulang
		FROM
		(
		select
			date_format(
					adddate('$tahun-$bulan-01', @num:=@num+1),
					'$tahun-$bulan-%d'
				) date
			from
				tbl_absensi,    
				(select @num:=-1) num
			limit
					$max_date
		) as A
) as r
		";	
		$tanggal = $this->db->query($SQL);
		if($tanggal->num_rows > 0)
		{
			return $tanggal->result_array();
		}
		else
		{
			return false;
		}
		
	
	}
	
	function data_pegawai()
	{
		$this->db2 = $this->load->database('user',true);
		$data = $this->db2->select('personid,username')->get('datuser');
		$ret = array();
		if($data->num_rows() > 0)
		{
			
			foreach($data->result_array() as $key => $value)
			{
				$get_ = $this->db->limit("1")->where("fld_empid",$value['personid'])->select('fld_empnik,fld_empnm')->get('tbl_emp');
				if($get_->num_rows() > 0)
				{
					$get_ = $get_->result_array()[0];
					array_push($ret,$get_);
				}
			}
			
		}
		return $ret;
		
	}
	
	function get_absensi_rekap($lokasi,$bulan,$tahun)
	{
		
	}
	
	
	
	
}
?>