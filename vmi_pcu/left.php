<?php require_once('Connections/vmi.php'); ?>
<?
ob_start();
session_start();
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_vmi, $vmi);
$query_rs_hosp = "select t.full_name,concat(h.hosptype,h.name) as hsname,h.moopart from hospcode h  left outer join thaiaddress t on t.chwpart=h.chwpart and t.amppart=h.amppart and t.tmbpart=h.tmbpart where h.hospcode='".$_SESSION['hospcode']."'";
$rs_hosp = mysql_query($query_rs_hosp, $vmi) or die(mysql_error());
$row_rs_hosp = mysql_fetch_assoc($rs_hosp);
$totalRows_rs_hosp = mysql_num_rows($rs_hosp);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
body {
	background-color: #666;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
html, body {
    max-width: 100%;
    overflow-x: hidden;
}
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#apDiv1 {
	position:absolute;
	left:0px;
	top:348px;
	width:231px;
	height:29px;
	z-index:1;
}
#footer {
height:30px;
margin: 0;
clear: both;
width:250px;
position: relative;
}
</style>
</head>

<body>
<div id="footer" style=" position:fixed; bottom:0px; width:250px">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30" align="center" class="table_small_white_gray" ><input type="button" name="logout" id="logout" value="ออกจากระบบ"  class="button_black_bar" onclick="JavaScript:if(confirm('คุณต้องการออกจากระบบจริงหรือไม่')==true){window.open(
  'logout.php',
  '_parent');}" /></td>
    </tr>
  </table>
</div>
<table width="250" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" align="center" bgcolor="#999999"><img src="images/new_logo2.gif" width="83" height="83" align="absmiddle" /><img src="images/hslogo2.jpg" width="99" height="99" align="absmiddle" /><br /></td>
  </tr>
  <tr>
    <td height="27" colspan="2" align="center" class="black_bar table_head_small_white"><strong>ยินดีต้อนรับเข้าสู่ระบบ</strong></td>
  </tr>
  <tr>
    <td width="69" align="right"  style="padding:5px"><span class="table_head_small_white"><strong>หน่วยงาน </strong></span></td>
    <td width="181" align="left"  style="padding:5px"><span class="table_small_white_gray"><?php echo $row_rs_hosp['hsname']; ?></span></td>
  </tr>
  <tr>
    <td align="right" style="padding:5px"><span class="table_head_small_white"><strong>ที่อยู่ </strong></span></td>
    <td align="left" style="padding:5px"><span class="table_small_white_gray"><?php echo $row_rs_hosp['moopart']; ?> <?php echo $row_rs_hosp['full_name']; ?></span></td>
  </tr>
 <?php if($_SESSION['member_status']=="admin"||$_SESSION['member_status']=="super_user"){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=inv_import" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">อัพโหลดรายการยาจาก INV</a></td>
 </tr>
 <?php } ?>
 <?php if($_SESSION['member_status']=="admin"){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=datacenter_import" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">นำเข้าข้อมูลจาก datacenter</a></td>
 </tr>
 <?php } ?>
  <?php if($_SESSION['member_status']=="admin"||$_SESSION['member_status']=="super_user"){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=items" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent" >ทะเบียนรายการ</a></td>
 </tr>
 <?php } ?>
  <?php if($_SESSION['member_status']=="admin"){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=dept_group" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent" >กำหนดกลุ่มหน่วยงาน</a></td>
 </tr>
 <?php } ?>
 <?php if($_SESSION['member_status']=="admin"){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=dept" target="_parent" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" >กำหนดหน่วยงาน</a></td>
 </tr>
 <?php } ?>
  <?php if($_SESSION['member_status']=="admin"||$_SESSION['member_status']=="super_user"){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=drug_type" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">กำหนดประเภทเวชภัณฑ์</a></td>
 </tr>
 <?php } ?>
  <?php if($_SESSION['member_status']=="admin"||$_SESSION['member_status']=="super_user"){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=item_add" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">กำหนดรายการเวชภัณฑ์</a></td>
 </tr>
<?php } ?>
  <?php if(($_SESSION['member_status']=="admin"||$_SESSION['member_status']=="super_user")&&($_SESSION['user_item_type']=='1'||$_SESSION['user_item_type']=='')){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=vaccine_add" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">กำหนดรายการวัคซีน</a></td>
 </tr>
 <?php } ?>
 <tr>
  <?php if(($_SESSION['member_status']=="admin"||$_SESSION['member_status']=="super_user")&&($_SESSION['user_item_type']=='1'||$_SESSION['user_item_type']=='')){ ?> 

   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=iodine_setting" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">กำหนดรายการยาเสริมไอโอดีน</a></td>
 </tr>
 <?php } ?>
   <?php if(($_SESSION['member_status']=="admin"||$_SESSION['member_status']=="super_user")){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=drug_stdcode" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">กำหนดรหัสมาตรฐานยา</a></td>
 </tr>
 <?php } ?>
   <?php if($_SESSION['member_status']=="admin"){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=user" class="thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">จัดการผู้ใช้</a></td>
 </tr>
 <?php } ?>
   <?php if($_SESSION['member_status']=="admin"){ ?> 
 <tr>
   <td colspan="2" align="left" class="blue thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=setting" class="thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">ตั้งค่าการทำงาน</a></td>
 </tr>
 <?php } ?>
   <?php if(($_SESSION['member_status']=="admin"||$_SESSION['member_status']=="super_user")&&($_SESSION['user_item_type']=='1'||$_SESSION['user_item_type']=='')){ ?> 
 <tr>
    <td colspan="2" align="left" class="gray2 thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=analyze" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">ดำเนินการเบิก-จ่าย</a></td>
  </tr>
  <?php } ?>
  <?php if(($_SESSION['member_status']=="admin"||$_SESSION['member_status']=="super_user")){ ?> 
 <tr>
    <td colspan="2" align="left" class="gray2 thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=drug_take_roll" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">ทะเบียนเบิกเวชภัณฑ์ [On demand]</a></td>
  </tr>
  <?php } ?>
   <?php if(($_SESSION['member_status']=="admin"||$_SESSION['member_status']=="super_user")&&($_SESSION['user_item_type']=='1'||$_SESSION['user_item_type']=='')){ ?>  
 <tr>
   <td colspan="2" align="left" class="gray2 thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=ferrous_take_roll" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">ทะเบียนเบิกยาเสริมธาตุเหล็ก</a></td>
 </tr>
 <tr>
    <td colspan="2" align="left" class="gray2 thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=vaccine_take_roll" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">ทะเบียนเบิกวัคซีน</a></td>
  </tr>
 <tr>
   <td colspan="2" align="left" class=" gray2 thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=upload_history" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">ประวัติการอัพโหลดข้อมูล รพ.สต.</a></td>
 </tr>
     <tr>
    <td colspan="2" align="left" class="gray2 thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=vaccine_lot" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">บันทึก lot จ่ายวัคซีน</a></td>
  </tr>
 <tr>
   <td colspan="2" align="left" class="gray2 thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=drug_forecast" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">ประมาณรายการเบิกทังหมด</a></td>
 </tr>
  <tr>
    <td colspan="2" align="left" class="gray2 thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=drug_take" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">ทะเบียนการเบิก-จ่ายยา</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="gray2 thsan-light font16"  style="padding:3px;color:white;" ><a href="index.php?sub_page=spending_report_add" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">บันทึกรายงานการจ่ายยา<a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="gray2 thsan-light font18"  style="padding:3px;color:white;" ><a href="index.php?sub_page=rate" class=" thsan-light font16 link_yellow" style="text-decoration:none;color:white;" target="_parent">อัตราการใช้ยาของแต่ละหน่วยเบิก</a></td>
  </tr>
 <?php } ?>
</table>
</body>
</html>
<?php
mysql_free_result($rs_hosp);
?>
