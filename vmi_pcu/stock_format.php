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
if($_POST['delete']&&$_POST['delete']=="Y"){
		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_card where hospcode='".$_SESSION['hospcode']."'";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_drugs where hospcode='".$_SESSION['hospcode']."'";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_drugs_c where hospcode='".$_SESSION['hospcode']."'";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_receive where hospcode='".$_SESSION['hospcode']."'";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_receive_c where hospcode='".$_SESSION['hospcode']."'";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_remain_snapshot where hospcode='".$_SESSION['hospcode']."'";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_report1_snapshot where hospcode='".$_SESSION['hospcode']."'";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_serial where hospcode='".$_SESSION['hospcode']."'";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_withdraw where hospcode='".$_SESSION['hospcode']."'";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_withdraw_c where hospcode='".$_SESSION['hospcode']."'";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

	echo "<script>alert('ล้างข้อมูลเรียบร้อยแล้ว');parent.$.fn.colorbox.close();</script>";	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>
</head>

<body>
<div align="center">
<form id="form1" name="form1" method="post" action="stock_format.php" class="thsan-light font16">
  ต้องการล้างข้อมูลคลังจริงหรือไม่
  <input type="submit" name="button" id="button" value="ล้างข้อมูล" onclick="return confirm('ต้องการล้างข้อมูลคลังจริงหรือไม่');" />
  <input name="delete" type="hidden" id="delete" value="Y" />
</form>
</div>
</body>
</html>