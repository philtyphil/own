$( document ).ready(function() {
	$('.selectpicker').selectpicker('show');
	$('.nama_user').selectpicker('show');
	$('.nama_user_insert').selectpicker('show');
	
	$('.form_time').datetimepicker({
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
	});
	
	$(".form_time_sec").datetimepicker({
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
		
	});

});

function cari_click()
{
	$("#bulan_select").html($("#bulan").val());
	var data = {
		"nip" 	: $("#nip").val(),
		"bulan"	: $("#bulan").val(),
		"tahun"	: $("#tahun").val()
	}
	var url = "{base_url}adm_kepegawaian/search";
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

function nama_clicked()
{
	var nama = $("#username").val();
	$("#nip").val(nama);
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
				else
				{
					
					$("#loading_text").html("Proses Menampilkan Data . . .");
					$("#load_absensi_after_insert").html(e.table_absensi);
					
					setTimeout(function(){
						$("#load_absensi_after_insert").fadeIn("slow");
						$("#loading_insert").fadeOut("slow");
						$("#loading_text").html("Proses Input Data . . .");
					},2000);
				}
				
			$("#button_input").fadeIn('slow');
			
			
		}
	});
}
