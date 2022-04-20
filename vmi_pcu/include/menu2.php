<script  src="../include/ajax_framework.js"></script>
		<link rel="stylesheet" href="../css/colorbox.css" />
		<script src="../include/jquery.js"></script>
		<script src="../include/jquery.colorbox.js"></script>
<script>
$(document).ready(function(){			
				//Examples of how to assign the ColorBox event to elements
		$("#report1").colorbox({iframe:true,href:"report/expenditure.php",width:"90%", height:"100%",scrolling:true,onClosed:function(){ ajaxLoad("post","main_table.php","", "displayTable","indicator");} });			
		$("#report2").colorbox({iframe:true,href:"report/insurance_report.php",width:"90%", height:"100%",scrolling:true,onClosed:function(){ ajaxLoad("post","main_table.php","", "displayTable","indicator");} });			
		$("#report3").colorbox({iframe:true,href:"report/invoice_report.php",width:"90%", height:"100%",scrolling:true,onClosed:function(){ ajaxLoad("post","main_table.php","", "displayTable","indicator");} });			
		$("#report4").colorbox({iframe:true,href:"report/receipt_report.php",width:"90%", height:"100%",scrolling:true,onClosed:function(){ ajaxLoad("post","main_table.php","", "displayTable","indicator");} });			
		$("#report5").colorbox({iframe:true,href:"report/revenue.php",width:"90%", height:"100%",scrolling:true,onClosed:function(){ ajaxLoad("post","main_table.php","", "displayTable","indicator");} });			

			});


function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>

<link href="../css/kohrx.css" rel="stylesheet" type="text/css" />
<table width="200" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td align="center" class="head_small_gray">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="head_small_gray"><strong class="big_red16">MENU</strong></td>
  </tr>
  <tr>
    <td align="left" class="head_small_gray"><a href="#" class="head_small_gray" style="padding-left:10px" id="report1">รายงานรายจ่ายของบริษัท</a></td>
  </tr>
  <tr>
    <td align="left" class="head_small_gray"><a href="#" class="head_small_gray"  style="padding-left:10px" id="report2">รายงานรายละเอียดรถประกัน</a></td>
  </tr>
  <tr>
    <td align="left" class="head_small_gray"  ><a href="#" class="head_small_gray" style="padding-left:10px" id="report3">รายงานการออกใบแจ้งหนี้</a></td>
  </tr>
  <tr>
    <td align="left" class="head_small_gray"  ><a href="#" class="head_small_gray" style="padding-left:10px" id="report4">รายงานการออกใบเสร็จ</a></td>
  </tr>
  <tr>
    <td align="left" class="head_small_gray"  ><a href="#" class="head_small_gray" style="padding-left:10px" id="report5">รายงานรายรับ</a></td>
  </tr>
  <tr>
    <td align="left" class="head_small_gray"  >&nbsp;</td>
  </tr>
</table>
