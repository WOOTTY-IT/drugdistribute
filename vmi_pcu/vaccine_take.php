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
$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);
if($row_rs_setting['vaccine_method']==1){
	$condition="SUBSTR(DATE_ADD(CURDATE(),INTERVAL 1 MONTH),1,7)";
	}
if($row_rs_setting['vaccine_method']==2){
	$condition="SUBSTR(CURDATE(),1,7)";
	}
mysql_select_db($database_vmi, $vmi);
$query_rs_drug_confirm = "select * from vaccine_take where SUBSTR(vaccine_take_date,1,7)=".$condition." and hospcode='".$_SESSION['hospcode']."' limit 1";
$rs_drug_confirm = mysql_query($query_rs_drug_confirm, $vmi) or die(mysql_error());
$row_rs_drug_confirm = mysql_fetch_assoc($rs_drug_confirm);
$totalRows_rs_drug_confirm = mysql_num_rows($rs_drug_confirm);

mysql_select_db($database_vmi, $vmi);
$query_rs_month = "select substr(DATE_ADD(CURDATE(),interval 1 MONTH),1,7) as nextmonth";
$rs_month = mysql_query($query_rs_month, $vmi) or die(mysql_error());
$row_rs_month = mysql_fetch_assoc($rs_month);

$nextmonth=$row_rs_month['nextmonth'];
mysql_free_result($rs_month);

?>
<?php include('include/function.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="include/jquery.js"></script>

</head>

<body onload="dosubmit('','show','vaccine_take_list.php','drug_take_list','');">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="gray thsan-bold" style="font-size:20px; padding:10px" > เบิกวัคซีนประจำเดือน <?php if($row_rs_setting['vaccine_method']==1){ echo monthThai($nextmonth); } else if($row_rs_setting['vaccine_method']==2){ echo monthThai(date('Y-m')); }  ?> <span style="padding:10px; padding-left:20px;padding-top:20px">
      <?php  if((($row_rs_setting['vaccine_method']==1)&&(ltrim(date('d'),'0')<=($row_rs_setting['vaccine_end_date']+10)))||(($row_rs_setting['vaccine_method']==2)&&(ltrim(date('d'),'0')<=$row_rs_setting['end_date']))){ if($row_rs_drug_confirm['confirm']==NULL){ ?>
      <a href="javascript:poppage('vaccine_take_add.php','800','500','','show','vaccine_take_list.php','drug_take_list','');" class="thsan-light font14"><span class="button gray2 thsan-light font18" style="padding:5px; height:25px; padding-top:3px; padding-left:5px; padding-right:10px" ><img src="images/vaccine_white.png" width="25" height="25" border="0" align="absmiddle" /> เพิ่มวัคซีน</span></a>
      <?php }
	  }?>
    </span></td>
  </tr>

  <tr>
    <td style="padding-left:20px"><div id="drug_take_list" style="margin-top:20px"></div></td>
  </tr>
</table>
</body>
</html><?php
mysql_free_result($rs_setting);

mysql_free_result($rs_drug_confirm);

?>
