<?php
class Login_model extends CI_Model{
	private $db;
	function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('user',true);
	}
	
	function doLogin($username,$password)
	{
		
		$crypt = crypt($password,'$1$ismail--$');
		$this->db2->where('username',$username);
		$this->db2->where('userpass',$crypt);
		$this->db2->limit('1');
		$this->db2->select('*');
		$this->db2->from('datuser');
		$this->db2->join('datusergrp','datusergrp.datusergrpid=datuser.fld_bgrsdmgrp','left');
		$data = $this->db2->get()->result_array();
		return $data;
	}
	
	
	
	
}
?>