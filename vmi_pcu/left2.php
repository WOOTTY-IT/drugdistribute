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
<div id="footer" style=" position:absolute; bottom:0; width:250px">
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
    <td colspan="2" align="center" bgcolor="#999999"><img src="images/hslogo2.jpg" width="180" height="180" /><br /></td>
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
  <tr>
    <td align="right" class="table_small_white_gray" style="padding:5px"><span class="table_head_small_white"><strong>ใช้ล่าสุด</strong></span></td>
    <td align="left" class="table_small_white_gray" style="padding:5px">22 กุมภาพันธ์ 2557</td>
  </tr>
 <?php if($member_status=="admin"){ ?> <tr>
    <td colspan="2" align="left" class="blue table_small_white_gray" style="padding:5px" ><a href="index.php?sub_page=analyze" class=" table_small_white_gray" target="_parent">ทะเบียนการเบิกจ่าย</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="blue table_small_white_gray" style="padding:5px" ><a href="index2.php?sub_page=item_add" class=" table_small_white_gray" target="_parent">กำหนดรายการเวชภัณฑ์</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="blue table_small_white_gray" style="padding:5px" ><a href="index2.php?sub_page=dept_group" class=" table_small_white_gray" target="_parent">กำหนดหน่วยงาน</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="blue table_small_white_gray" style="padding:5px" ><a href="index2.php?sub_page=dept_group" class=" table_small_white_gray" target="_parent">กำหนดกลุ่มหน่วยงาน</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="blue table_small_white_gray" style="padding:5px" >จัดการผู้ใช้</td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="blue table_small_white_gray" style="padding:5px" >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="blue table_small_white_gray" style="padding:5px" >&nbsp;</td>
  </tr><? } ?>
</table>
</body>
</html>
<?php
mysql_free_result($rs_hosp);
?>
