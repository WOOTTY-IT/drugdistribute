<?
ob_start();
session_start();
?>
<?php require_once('Connections/vmi.php'); ?>
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
if($_POST['do']=="search"){
	$date11=explode("/",$_POST['date1']);
	$edate1=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

	$date11=explode("/",$_POST['date2']);
	$edate2=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

mysql_select_db($database_vmi, $vmi);
$query_rs_search = "select * from stock_card where working_code='".$_POST['item']."' and hospcode='".$_SESSION['hospcode']."' and operation_date between '".$edate1."' and '".$edate2."' order by id ASC";
$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);
}
include('include/function.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<style>
tr.table_head td{
	border-bottom:solid 1px #E1E1E1;
	border-top:solid 1px #E1E1E1;
	padding:5px;
	background-color:#F0F0F0;
	}
tr.table_body td{
	padding:5px;
	border-bottom:solid 1px #E1E1E1;

}
tr.bordunder td{
	border-bottom:solid 1px #CCCCCC;
	border-top:solid 1px #CCCCCC;
	padding-top:5px;
	padding-bottom:5px;

}
</style>
<?php include('java_function.php'); ?>

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
<?php if ($totalRows_rs_search > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr class="font_bord font19 bordunder">
      <td align="center">วันที่</td>
      <td align="center">operation no</td>
      <td align="center">หน่วยงาน</td>
      <td align="center">รับ</td>
      <td align="center">จ่าย</td>
      <td align="center">คงเหลือ</td>
      <td align="center">lot no</td>
      <td align="center">exp&nbsp;date</td>
      <td align="center">มูลค่า(บาท)</td>
    </tr>
    <?php do { ?>
      <tr class="table_body table_body1 thsan-light font18 grid" <?php if($row_rs_search['r_s_status']=="R"){ ?>style="background-color:#CCCCCC"<?php }?>>
        <td align="center"><?php print dateThai($row_rs_search['operation_date']); ?></td>
        <td align="center"><?php print $row_rs_search['r_s_number']; ?></td>
        <td align="center"><?php print $row_rs_search['dept_id']; ?></td>
        <td align="center"><?php if($row_rs_search['r_s_status']=="R"){ print ($row_rs_search['qty']/$row_rs_search['pack_ratio'])."X".$row_rs_search['pack_ratio']; } ?></td>
        <td align="center"><?php if($row_rs_search['r_s_status']=="S"){ print ($row_rs_search['qty']/$row_rs_search['pack_ratio'])."X".$row_rs_search['pack_ratio']; } ?></td>
        <td align="center"><?php print $row_rs_search['remain_qty']/$row_rs_search['pack_ratio']."X".$row_rs_search['pack_ratio']; ?></td>
        <td align="center"><?php print $row_rs_search['lot']; ?></td>
        <td align="center"><?php if($row_rs_search['exp_date']!=NULL){print dateThai($row_rs_search['exp_date']); } else {echo "";} ?></td>
        <td align="center"><?php print str_replace('.00','',$row_rs_search['cost']); ?></td>
      </tr>
      <?php } while ($row_rs_search = mysql_fetch_assoc($rs_search)); ?>
  </table><br>
<a href="javascript:valid(0);" onClick="btn_print();" class="thsan-light font16 button gray" style="text-decoration:none; color:#000; padding-left:10px; padding-right:10px"><img src="images/Printer and Fax.png" width="25" height="25" border="0" align="absmiddle">&nbsp;&nbsp;พิมพ์</a>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rs_search);
?>
