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
if($_GET['delete_type']=="delete"){

	mysql_select_db($database_vmi, $vmi);
	$query_update = "update temp_stock_receive_c set action='D' where hospcode='".$_SESSION['hospcode']."' and c_id !=''";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());

	mysql_select_db($database_vmi, $vmi);
	$query_update = "delete from temp_stock_receive_c where hospcode='".$_SESSION['hospcode']."' and c_id is NULL";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());	
}
if($_GET['delete_type']=="undelete"){
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update temp_stock_receive_c set action =NULL where hospcode='".$_SESSION['hospcode']."' and c_id !='' and action='D'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());
}
		echo "<script>parent.formSubmit('edit','form1','stock_receive_list.php','divload','indicator','edit');parent.$.fn.colorbox.close();</script>";
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>