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
	function json_datatable($request,$lokasi,$golongan,$status_pegawai,$tahun)
	{
		$columns = array('gol','fld_empnik','fld_empnm','fld_empbod','sex','pddk','stskel','loknm','lokkerja');
		
		// order
		$this->db->order_by('gol',"desc");
		$this->db->order_by('fld_empnm',"asc");
		$this->db->limit(0,10);
		//single where
		if(isset($request['search']['value']) && $request['search']['value'] != "")
		{
			for($i=0;$i<count($request['columns']);$i++)
			{
				$this->db->like($columns[$i],$request['search']['value']);
			}
		}
		
		
		
		$this->db->select($columns);
		$output = $this->db->get('views_data_pegawai');
		foreach($output->result_array() as $key=> $value)
		{
			$row 	= array();
			$row[] 	= $value['gol'];
			$row[] 	= $value['fld_empnik'];
			$row[] 	= $value['fld_empnm'];
			$row[] 	= $value['fld_empbod'];
			$row[] 	= $value['sex'];
			$row[] 	= $value['pddk'];
			$row[] 	= $value['stskel'];
			$row[] 	= $value['loknm'];
			$row[] 	= $value['lokkerja'];
			$set[] 	= $row;
			
		}
		$all = $this->db->count_all_results('view_data_pegawai');
	
		return array(
			"draw"            => intval( $request['draw'] ),
			"recordsTotal"    => intval( $all ),
			"recordsFiltered" => intval( $output->num_rows() ),
			"data"            => $set
		);
		return $output;
		
	}
	
	function json_dt($request,$lokasi,$golongan,$status_pegawai,$pendidikan,$masa_kerja)
	{
		// Define Showing Field - @philtyphils
		$columns = array('id_golongan','fld_empnik','fld_empnm','fld_empbod','sex','id_status_peg','id_pendidikan','id_status_keluarga','loknm','masa_kerja','gol','stskel','stspeg','pddk');
		
		// Define Selected Table - @philtyphils
		$sTable  = "view_data_pegawai";
		
		// Define Limit - @philtyphils
		if(isset($request['start']) && $request['start'] != "" && $request['length'] != '')
		{
			$limit = " LIMIT ".intval($request['start']).", ".intval($request['length']);
		}

		// Define Order - @philtyphils
		$order = "";
		if ( isset($request['order']) && count($request['order']) ) 
		{
			$order = " ORDER BY ";
			for($i=0;$i<count($request['order']);$i++)
			{
				
				$col 	= $request['order'][$i]['column'];
				$type	= $request['order'][$i]['dir'];
				$order .= $columns[$col] . " " . $type .", ";
			}
			$order = substr_replace( $order, "", -2 );
			
			if(trim($order) == "ORDER BY")
			{
				$order = " ORDER BY id_golongan desc, fld_empnm asc ";
			}
		}
		unset($col);
		
		// Define WHERE - @philtyphils
		$where = "";
		if(isset($request['search']['value']) && $request['search']['value'] != '')
		{
			$where = " WHERE (";
			
			
			// Multiple Where - @philtyphils
			for($i=0;$i<count($request['columns']);$i++)
			{
				$str = $request['columns'][$i]['searchable'];
				
				if($str == "true")
				{
					$col	= $request['columns'][$i]['data'];
					$val	= $request['search']['value'];
					$where .= $columns[$col] ." LIKE '%".mysql_real_escape_string($val)."%' OR ";
				}
			}
			$where = substr_replace( $where, "", -3 );
			$where .= " ) ";
			
		}
		
		/*
		* Extra Condition From Selection of Form - @philtyphils
		*/
		if($where == "") $where =  " WHERE (";
		// Including Condition Golongan - @philtyphils
		if(isset($golongan) && $golongan != "" && strpos($golongan,"ALL") === false)
		{
			if($where != " WHERE (")
			{
				$where .= " AND (";
			}
			
			$exp = explode("|",$golongan);
			
			for($i=0;$i<count($exp) - 1;$i++)
			{
				$gol	= explode("-",$exp[$i]);
				$where .= " id_golongan = ".$gol[0] . " OR ";
			}
			$where = substr_replace( $where, "", -4 );
			$where .= " ) ";
		}
		
		// Including Condition Status Pegawai - @philtyphils
		if(isset($status_pegawai) && $status_pegawai != "" && strpos($status_pegawai,"ALL") === false)
		{
			if($where != " WHERE (")
			{
				$where .= " AND (";
			}
		
			$exp = explode("|",$status_pegawai);

			for($i=0;$i<count($exp) - 1;$i++)
			{
				
				$where .= "id_status_peg = " .$exp[$i].  " OR ";
			}
			$where = substr_replace( $where, "", -4 );
			$where .= " ) ";
		}
		
		// Including Condition Lokasi Pegawai - @philtyphils
		if(isset($lokasi) && $lokasi != "")
		{
			if($where != " WHERE (")
			{
				$where .= " AND (";
				$where .= "id_lokasi = ".$lokasi . " AND ";
			}
			else
			{
				$where .= "id_lokasi = ".$lokasi . ") AND ";
			}

			
			$where = substr_replace( $where, "", -4 );
		}

		// Including Condition Pendidikan Pegawai - @philtyphils
		if(isset($pendidikan) && $pendidikan != "" && strpos($status_pegawai,"ALL") === false)
		{
			if($where != " WHERE (")
			{
				$where .= " AND (";
			}
		
			$exp = explode("|",$pendidikan);

			for($i=0;$i<count($exp) - 1;$i++)
			{
				
				$where .= "id_pendidikan = " .$exp[$i].  " OR ";
			}
			$where = substr_replace( $where, "", -4 );
			$where .= " ) ";
		}

		// Including Condition Masa Kerja Pegawai - @Philtyphils
		if(isset($masa_kerja))
		{
			$tahun = explode("-",$masa_kerja);
			
			if((isset($tahun[0]) && $tahun[0] != "") && (isset($tahun[1]) && $tahun[1] != ""))
			{
				if($where != " WHERE (")
				{
					$where .= " AND (";
				}
				$where .= " FLOOR(masa_kerja) >= ".$tahun[0]." AND FLOOR(masa_kerja) <= ".$tahun[1];
				$where .= " ) ";
			}
 		}

 		//Execution Query
		unset($exp);
		$query = "SELECT ".str_replace(" , ", " ", implode(", ", $columns))." FROM ".$sTable." 
			$where
			$order
			$limit
			";
		$execution = $this->db->query($query);
		$set = array();
		foreach($execution->result_array() as $key=> $value)
		{
			// Prepare For Count Masa Kerja - @philtyphils
			if(isset($value['masa_kerja']))
			{
				$time = explode(".",$value['masa_kerja']);
				if(count($time) > 0)
				{
					$tahun = $time[0] . " Tahun<br/>";
					$bulan = substr($time[1],1,1) . " Bulan";
				}

				$masa_kerja =  $tahun . $bulan;

			}


			$row 	= array();
			$row[] 	= "<small>" ."Gol. ". $value['gol'];
			$row[] 	= "<small>" . $value['fld_empnik'] . "</small>";
			$row[] 	= "<small>" . $value['fld_empnm'] . "</small>";
			$row[] 	= "<small>" . $value['fld_empbod'] . "</small>";
			$row[] 	= "<small>" . $value['sex'] . "</small>";
			$row[] 	= "<small>" . $value['stspeg'] . "</small>";
			$row[] 	= "<small>" . $value['pddk'] . "</small>";
			$row[] 	= "<small>" . $value['stskel'] . "</small>";
			$row[] 	= "<small>" . $value['loknm'] . "</small>";
			$row[] 	= "<small>" . $masa_kerja."</small>";
			$set[] 	= $row;
			
		}
	
		$iTotal = $execution->num_rows();
      
		$sQueryTotal = $query = "SELECT ".str_replace(" , ", " ", implode(", ", $columns))." FROM ".$sTable."  $where";
        $FetchsQuery = $this->db->query($sQueryTotal);
        $iFilteredTotal = $FetchsQuery->num_rows();
      
		unset($where);
		return array(
			"draw"            => intval( $request['draw'] ),
			"recordsTotal"    => intval( $iTotal ),
			"recordsFiltered" => intval( $iFilteredTotal ),
			"data"            => $set
		);
		
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

	function get_pendidikan_pegawai()
	{
		$data = $this->db->where('fld_tyid',"13")->order_by('fld_tyvalcd')->get('tbl_tyval');
		
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