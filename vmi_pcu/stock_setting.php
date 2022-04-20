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
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
div.divhead{
	
	position:fixed;
	top:0;
	width:100%;
	font-size:23px;
	padding:10px;
	border-bottom:solid 1px #CCCCCC;
	background-color:#ededed;
	}
div.divcontent{
	margin-top:60px;
	padding:10px;
	width:98%;	
	}
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
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
</style>

<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>

</head>

<body>
<div class="thsan-light divhead "><img src="images/setting2.png" width="30" height="30" align="absmiddle"> &nbsp;ตั้งค่า ( Stock Setting)</div>
<div class="divcontent thsan-light font18" >
<div><a href="javascript:valid(0);" class="thsan-light" style="text-decoration:none; padding-top:5px; color:#000000" onClick="OpenPopup('stock_withdraw_dept.php','100%','100%','');">1. ตั้งค่าหน่วยที่เบิก</a><br>
<a href="javascript:valid(0);" class="thsan-light" style="text-decoration:none; padding-top:5px; color:#000000" onClick="OpenPopup('stock_format.php','400','150','');">2. ลบข้อมูลคลัง</a><br>
</div>
</div>
</body>
</html>

