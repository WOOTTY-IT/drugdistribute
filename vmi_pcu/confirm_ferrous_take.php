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
if($_GET['confirm']=="Y"){
mysql_select_db($database_vmi, $vmi);
$query_rs_update = "update iodine_ferrous set confirm='Y' where SUBSTR(order_date, 1,7)='$_GET[month]' and hospcode='".$_GET['deptcode']."'";
$rs_update = mysql_query($query_rs_update, $vmi) or die(mysql_error());
}
if($_GET['confirm']=="N"){
mysql_select_db($database_vmi, $vmi);
$query_rs_update = "update iodine_ferrous set confirm=NULL where SUBSTR(order_date, 1,7)='".$_GET['month']."' and hospcode='".$_GET['deptcode']."'";
$rs_update = mysql_query($query_rs_update, $vmi) or die(mysql_error());
}

?>

<html><head>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
</head>
<bordy>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle"><img src="images/clock_time_loading.png" width="102" height="102" align="absbottom"><br>
    <span class="big_red16">กำลังประมวลผล</span></td>
  </tr>
</table>
</bordy>
</html>
<?php 
echo "<script>parent.$.fn.colorbox.close();</script>";	?>
