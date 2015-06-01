$(document).ready(function() {
	$('.selectpicker').selectpicker('show');


});

function cari_click()
{
	$("#loading").fadeIn("slow");
	$("#bulan_select").html($("#bulan").val());
	var data = {
		"lokasi" 	: $("#lokasi").val(),
		"tahun"		: $("#tahun").val()
	}
	var url = "{base_url}laporan/search";
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		dataType: "JSON",
		success: function(e){
			$("#load_jml_pegawai").fadeOut("fast");
			$("#load_jml_pegawai").html(e.table_absensi);
			$("#load_jml_pegawai").fadeIn("slow");
			$("#loading").fadeOut("slow");
			
			
			
		}
	});
}


function cancel()
{
	window.location.href="{base_url}home";
}

function print_excel()
{
	var tahun = $("#tahun").val();
	var bulan = $("#bulan").val();
	var nip = $("#nip").val();
	$("#loading").fadeIn("slow");
	setTimeout(function(){
		window.open("{base_url}adm_kepegawaian/print_excel/"+nip+"/"+bulan+"/"+tahun,"Print Excel Absensi","width=200, height=100");
		$("#loading").fadeOut("fast");
	},2000);
}

function print_pdf()
{
	var tahun 	= $("#tahun").val();
	var bulan 	= $("#bulan").val();
	var nip 	= $("#nip").val();
	$("#loading").fadeIn("slow");
	setTimeout(function(){
		window.open("{base_url}adm_kepegawaian/print_pdf/"+nip+"/"+bulan+"/"+tahun,"Print Excel Absensi","width=400, height=400");
		$("#loading").fadeOut("fast");
	},2000);
}

function print_html()
{
	var tahun = $("#tahun").val();
	var bulan = $("#bulan").val();
	var nip = $("#nip").val();
	$("#loading").fadeIn("slow");
	setTimeout(function(){
		window.open("{base_url}adm_kepegawaian/print_html/"+nip+"/"+bulan+"/"+tahun,"_blank");
		$("#loading").fadeOut("fast");
	},2000);
}

function nama_insert_clicked()
{
	var nama = $("#username_insert").val();
	$("#nip_absensi_insert").val(nama);
}

function proses_insert()
{
	$("#button_input").fadeOut('slow');
	$("#loading_insert").fadeIn("slow");
	var data = {
		nip			: $("#nip_absensi_insert").val(),
		tanggal		: $("#dtp_input2").val(),
		jam_datang	: $("#jam_datang").val(),
		jam_pulang	: $("#jam_pulang").val(),
		
	}
	var url = "{base_url}adm_kepegawaian/insert";
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		dataType: "JSON",
		success: function(e){
			$("#load_absensi_after_insert").fadeOut("fast");
			
				if(e.error_nip != "" && typeof(e.error_nip) != "undefined")
				{
					$("#loading_insert").fadeOut("slow");
					bootbox.confirm(e.error_nip, function(result) {
					});
				}
				else if(e.error_jam_datang != "" && typeof(e.error_jam_datang) != "undefined")
				{
					$("#loading_insert").fadeOut("slow");
					bootbox.confirm(e.error_jam_datang, function(result) {
						$("#jam_datang").focus();
						$("#jam_pulang").focus();
								
					});
				}
				else if(e.error_tanggal != "" && typeof(e.error_tanggal) != "undefined")
				{
					$("#loading_insert").fadeOut("slow");
					bootbox.confirm(e.error_tanggal, function(result) {
								
					});
				}
				else if(e.error_jam_pulang != "" && typeof(e.error_jam_pulang) != "undefined")
				{
					$("#loading_insert").fadeOut("slow");
					bootbox.confirm(e.error_jam_pulang, function(result) {
						$("#jam_datang").focus();
						$("#jam_pulang").focus();
								
					});
				}
				else if(e.error_insert != "" && typeof(e.error_insert) != "undefined")
				{
					$("#loading_insert").fadeOut("slow");
					bootbox.confirm(e.error_insert, function(result) {
						
								
					});
				}
				else
				{
					
					$("#loading_text").html("Proses Menampilkan Data . . .");
					$("#load_absensi_after_insert").html(e.table_absensi);
					
					setTimeout(function(){
						$("#load_absensi_after_insert").fadeIn("slow");
						$("#loading_insert").fadeOut("fast");
						$("#loading_text").html("Proses Input Data . . .");
					},2400);
				}
				
			$("#button_input").fadeIn('slow');
			
			
		}
	});
}

function rekap_click()
{
	$("#bulan_select").html($("#bulan").val());
	var data = {
		"tokenUnit" 	: $("#unit").val(),
		"bulan"			: $("#bulan_rekap").val(),
		"tahun"			: $("#tahun_rekap").val()
	}
	var url = "{base_url}adm_kepegawaian/rekap";
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		dataType: "JSON",
		success: function(e){
			$("#load_absensi").fadeOut("fast");
			$("#loading").fadeIn("slow");
			setTimeout(function(){
				$("#load_absensi").html(e.table_absensi);
				$("#load_absensi").fadeIn("slow");
				$("#loading").fadeOut("slow");
			},2000);
			
			
		}
	});
}

function rekap_pegawai_click()
{
	var lokasi 			= $("#lokasi_rekap_pegawai").val();
	var golongan		= $("#golongan_rekap_pegawai").val();
	var status_pegawai	= $("#status_pegawai_detail").val();
	var tahun			= $("#tahun_rekap").val();
	var data = {
		lokasi : lokasi,
		golongan : golongan,
		status_pegawai : status_pegawai,
		tahun:tahun
	};
	var url = "{base_url}laporan/rekap";
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		dataType: "JSON",
		success: function(e){
			$("#load_rekap_pegawai").fadeOut("fast");
			$("#loading").fadeIn("slow");
			setTimeout(function(){
				$("#load_rekap_pegawai").html(e.table_rekap_pegawai);
				$("#load_rekap_pegawai").fadeIn("slow");
				$("#loading").fadeOut("slow");
			},2000);
			
			
		}
		//error:function()
		//{
		//	bootbox.confirm("<span class='text-center'>Gagal menampilkan!!.<br/> Contant the Administrator!</span>", function(result) {
		//		
		//	});
		//}
	});
	
}
