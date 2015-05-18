<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function render($view,$data='',$js='',$layout='frame'){
	$CI=& get_instance();
	$layout = template() ."/".$layout.".html";
	if(isset($js) && $js != "" && !empty($js))
	{
		$js = template() ."/js/".$js.".js";
		$CI->data['js']		 = $CI->parser->parse($js, $CI->data,true);
	}
	else
	{
		$CI->data['js']		 = $js;

	}
	
	if(is_array($data)){
		$CI->data = array_merge($CI->data,$data);
	}
	
	if(!$layout){
		$CI->parser->parse($view.'.html', $CI->data);
	}
	else
	{
		$CI->data['content'] = $CI->parser->parse(template()."/".$view.'.html', $CI->data,true);
		
		$CI->parser->parse($layout,$CI->data);
	}
}

function template()
{
	$CI=& get_instance();
	
	$template = $CI->db->limit('1')->where('status',"Y")->get('bgr_template');
	if($template->num_rows() > 0)
	{
		$r	= $template->result_array();
		return $r[0]['template'];
	}
	else
	{
		show_error("We Havent Template","404",$heading="Undefined Template - @philtyphils");
		return false;
	}
	
}
function encode($str)
{
	$CI=& get_instance();
	$CI->load->library('encrypt');
	$str_encode = $CI->encrypt->encode($str);
	$str 		= str_replace(array('+','/','='),array('-','_','.'),$str_encode);
	return $str;
	
}

function decode($str)
{
	$CI=& get_instance();
	$CI->load->library('encrypt');
	$data = str_replace(array('-','_','.'),array('+','/','='),$str);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
		$data .= substr('====', $mod4);
	}
	$str_decode = $CI->encrypt->decode($data);
	return $str_decode;
}

function bug($var)
{
	echo "<pre>";print_r($var);die(" <<<");
}

