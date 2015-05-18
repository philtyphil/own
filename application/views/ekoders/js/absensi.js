$( document ).ready(function() {
	$('.selectpicker').selectpicker('show');
	$('.nama_user').selectpicker('show');

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