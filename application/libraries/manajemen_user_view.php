<link rel="stylesheet" href="<?php echo base_url();?>js/colorbox/colorbox.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/colorbox/jquery.colorbox-min.js"></script>

 <script type="text/javascript">
	$(document).ready(function() {
	$(".detail").die('click');
	$(".detail").live('click',function(){
		var id=$(this).attr('id');
		$.colorbox({
			href:'<?php echo site_url("surat_keluar/isi_surat_generate")?>',
			opacity:0.2,
			speed:100,
			title:'.: DETAIL :.',
			transition:'elastic',
			scrolling
			:false,
			overlayClose:false,
			innerWidth: 955, 
			
			data: function(){
				return "id="+id;
			}
		});
	});
  
   $("#cancel").click(function() {
    parent.$.fn.colorbox.close();
   });
  });
 </script>
<style type="text/css">
legend {
	font-size: 14px;
    background-color: #4f709f;
    border-radius: 4px;
    box-shadow: 2px 2px 4px #888;
    color: White;
    margin-bottom: 4px;
    padding: 4px;
}



#cboxTitle {
    color:#999999;
	
	border-bottom-style:double;
	border-color:#999999;
    font-size: 12px;
    font-weight: bold;
    left: 0;
    position: absolute;
    text-align: center;
    top: 4px;
    width: 100%;
}



.link {
	font-size: 12px;
    color: #FF0000;
    cursor: pointer;
    text-decoration: none;
}

.pembatas {
	
	background: -moz-linear-gradient(right, #ffffff, #f0f0f6);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#f0f0f6);
	-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#f0f0f6)";
	padding: 0 0 0 0;

}

.style1 {font-size: x-small}
.style30 {font-size: 9px; color: #FF6600; }
.style31 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style37 {color: #FFFFFF ; font-size: 13px}
.style38 {font-size: xx-small}
.style39 {color: #0033CC}
.style40 {
	color: #666666;
	font-weight: bold;
}
.style41 {
	color: #FF3300;
	font-weight: bold;
}
.style49 {font-size: 12px; color: #666666;}
.style51 {font-size: 12px}
.style52 {color: #666666}
.style56 {color: #999999; }
.garis_bawah {border-bottom: 1px solid #f0f0f6; }
.garis_samping {border-left: 2px dotted #f0f0f6 ;  }
.style58 {font-size: 12}
.style60 {
	font-size: 14px;
	font-weight: bold;
}
.style73 {
	font-size: 21px;
	font-style: italic;
}
.style80 {font-size: 14px; font-weight: bold; color: #FF3300; }
.style81 {color: #FF3300}
.style90 {color: #666666; font-size: 18px; }
.style93 {font-size: 18px; color: #999999;}
.style95 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 21px;}
-->
</style>
<div align="center"> <table border="0" bordercolor="#FFFFFF"  width="1100px" align="center"  cellpadding="0" cellspacing="0" style="border-left-style:dotted; border-left-color:#CCCCCC;border-right-style:dotted; border-right-color:#CCCCCC; border-collapse:collapse; border-width:1px"> <tr>
<td valign="top" bgcolor="#FFFFFF" style="background-repeat:repeat-x"  colspan="2"> <div id="content2">
<table width="1017" border="1px" align="center" cellpadding="0" cellspacing="0" bordercolor="#fffffa">


<tr class="content" bgcolor="#FFFFFF"> <td align="center"width="28%" height="118" valign="top"  >
		      <table width="90%" class="garis_bawah" style=" border-style:dotted; border-width:1px;  border-color:#CCCCCC"> <tr> <td valign="middle"width="57%"><div align="left" class="style49"> <img src="<?php echo base_url();?>Image/icon_user.gif" alt="" /> <strong>IDENTITAS USER</strong></div></td> 
			</tr></table>
			<table align="center" width="90%" border="1px" bordercolor="#CCCCCC" style="border-left-style:dotted; border-left-color:#CCCCCC;border-right-style:dotted; border-right-color:#CCCCCC; border-width:1px"> 
			<tr class="garis_bawah"><td class="garis_bawah" width="31%" align="left" valign="top" bgcolor="#ededf1"><div align="left" class="style56"><span class="style51">&nbsp;&nbsp;PN</span></div></td>
			  <td class="garis_bawah" width="69%"><div align="left"><span class="style51"><span class="style52">
			  <?php echo $_SESSION['NIP'] ; ?>
			  </span></span></div></td>
			</tr > <tr>
			  <td class="garis_bawah" align="left" valign="top" bgcolor="#ededf1"><div align="left" class="style56"><span class="style51">&nbsp;&nbsp;Nama</span></div></td>
			  <td class="garis_bawah" ><div align="left"><span class="style51"><span class="style52"><?php echo $_SESSION['nama_lengkap'] ; ?></span></span></div></td>
			 </tr><tr>
			    <td class="garis_bawah" align="left" valign="top" bgcolor="#ededf1"><div align="left" class="style56"><span class="style51"> &nbsp;&nbsp;Jabatan</span></div></td>
			    <td class="garis_bawah"><div align="left"><span class="style51"><span class="style52"><?php echo $_SESSION['jabatan'] ; ?></span></span></div></td>
			 </tr><tr>
			      <td class="garis_bawah" align="left" valign="top" bgcolor="#ededf1"><div align="left" class="style56"><span class="style51">&nbsp;&nbsp;Unit Kerja</span></div></td>
			      <td class="garis_bawah"><div align="left"><span class="style51"><span class="style52"><?php echo $_SESSION['bagian']; ?></span></span></div></td>
			 </tr><tr>
			      <td class="garis_bawah" align="left" valign="top" bgcolor="#ededf1"><div align="left" class="style56"><span class="style51">&nbsp;&nbsp;Area</span></div></td>
			      <td class="garis_bawah"><div align="left"><span class="style51"><span class="style52"><?php echo $_SESSION['divisi'] ; ?></span></span></div></td>
			 </tr>
             <tr>
			      <td class="garis_bawah" align="left" valign="top" bgcolor="#ededf1"><div align="left" class="style56"><span class="style51">&nbsp;&nbsp;ID Uker</span></div></td>
			      <td class="garis_bawah"><div align="left"><span class="style51"><span class="style52"><?php echo $_SESSION['orgeh'] ; ?></span></span></div></td>
			 </tr>
			 </table>
			<p></p></td> <td width="2%" height="236" rowspan="2"align="right" valign="top" bordercolor="#ededf1"  background="<?php echo base_url();?>Image/pembatas.gif" border="1px" >   </td>
   <td width="74%" rowspan="2" align="center" valign="top" >
 <p align="center" class="style52"><strong>&nbsp; &nbsp; </strong>&nbsp;<span class="style73">.: </span><span class="style95"><span class="style93">Selamat Datang di Aplikasi Perkantoran</span> <span class="style90">E-OFFICE KEMENTERIAN BUMN</span></span> <span class="style73">:.</span></p>
 <p><?php if ($jumlah_data!=0) { ?>
   <table width="516">
     <tr><td width="180"><div align="left" class="style40">Total Surat Keluar Ditolak</div></td>
   <td width="324"><div align="left" class="style41">: <?php echo $jumlah_data ?></div></td>
   </tr></table>
   </p>
   <table width="83%" border="0" align="center" bordercolor="#999999" class="content" style="border-collapse:collapse">
    <thead>
        <tr>
          <th width="18" height="22" bgcolor="#ededf1" class="style100"><div align="center" class="style52 style37 style38 style23 style17 style9"><strong>NO.</strong></div></th>
         
            <th width="67" bgcolor="#ededf1" class="style100"><div align="center" class="style52 style37 style38 style100"><strong><span class="style9 style17 style23 ">TANGGAL PENGIRIMAN</span></strong></div></th>
      
          <th width="94" bgcolor="#ededf1" class="style100"><div align="center" class="style52 style37 style38 style23 style17 style9"><strong>PENERIMA</strong></div></th>
          <th width="102" bgcolor="#ededf1" class="style100"><div align="center" class="style52 style37 style38 style23 style17 style9"><strong>PERIHAL </strong></div></th>
            <th width="53" bgcolor="#ededf1" class="style100"><div align="center" class="style52 style37 style38 style23 style17 style9"><strong>STATUS </strong></div></th>
         
		  <th width="103" bordercolor="#FFFFFF" bgcolor="#ededf1" class="style100"><div align="center" class="style9"></div></th>
        </tr>
    </thead>
    <tbody>
       <?php 
	         $i=1;
             if($this->uri->segment(3) == '' || $this->uri->segment(3) == 0) {
                $i = 1;
            }else {
                $i = $this->uri->segment(3)+1;
            }?>
        <?php foreach($suratkeluar as $m): ; ?>
        <tr  height="20">
          <td  class="style102 garis_bawah"><div align="center" class="style17 style30 style31 style38 style39 style52"><?php echo $i++ ;?>.</div> </td>
          <td  class="style102 garis_bawah"><div align="center" class="style17 style30 style31 style38 style39 style52"> <?php echo $this->convertion->date3datestring($m->tanggal); ?></div></td>
          <td  class="style102 garis_bawah"><div align="center" class="style17 style30 style31 style38 style39 style52"><?php if($m->label_group!=NULL){echo $m->label_group ;} else {echo $m->kepada ; } ?></div></td>
		  <td  class="style102 garis_bawah"><div align="center" class="style17 style30 style31 style38 style39 style52"><?php echo $m->perihal ;?></div></td>
          <td  class="style102 garis_bawah"><div align="center" class="style15 style28 style38 style39 style52"><?php echo $m->status ;?></div></td>
         
		  <td class="garis_bawah"> <div align="center"> <span class="detail link" id="<?=$m->id;?>">Detail</span></div></td>
      </tr>
        <?php endforeach ?>
    </tbody>
</table>
   <?php } ?>   <br/></td>
</tr>
<tr class="content" bgcolor="#FFFFFF">
 <td valign="bottom" align="center">&nbsp;</td>
</tr>

<tr>
<td></td>
</tr>


<tr> 
  <TD colspan="4" rowspan="2" align="left" valign="top">&nbsp;</TD>
</tr>
</table>
</div></td>
</tr></table>
</div>

<div align="center">
<table width="1100" cellpadding="0" cellspacing="0" border="1px" bordercolor="#FFFFFF" align="center">
  <tr>
    <td class="lengkung" height="20" bgcolor="#FFFFFF" align="left" background="<?php echo base_url();?>Image/footer.jpg">
      <hr size="1px" style="border-style:dotted"  color="#CCCCCC"/><div align="center" class="style1"><span style="color:#666666">Copyright &copy; 2012 BUMN. All rights reserved.   </span></div></td></tr></table></div>
</body>
</html>