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
	$date11=explode("/",$_GET['date1']);
	$edate1=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

	$date11=explode("/",$_GET['date2']);
	$edate2=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

mysql_select_db($database_vmi, $vmi);
$query_rs_search = "select s.*,d.drug_name from stock_card s left outer join drugs d on d.working_code=s.working_code where s.working_code='".$_GET['item']."' and s.hospcode='".$_SESSION['hospcode']."' and s.operation_date between '".$edate1."' and '".$edate2."' order by s.id ASC";
$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

include('include/function.php');
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	left:12px;
	top:16px;
	width:284px;
	height:41px;
	z-index:1;
}
</style>
</head>

<body>
<div id="apDiv1"><?php echo $row_rs_search['drug_name']; ?></div>
<div style="margin-top:100px"></div>
</body>
</html>