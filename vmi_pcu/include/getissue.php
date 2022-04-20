<?php require_once('../Connections/borndb.php'); ?>
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

mysql_select_db($database_borndb, $borndb);
$query_rs_claim = "SELECT driver_claim_no,date_operations,driver_insurance,bs,issue FROM born_work where send_pay is null ";
$rs_claim = mysql_query($query_rs_claim, $borndb) or die(mysql_error());
$row_rs_claim = mysql_fetch_assoc($rs_claim);
$totalRows_rs_claim = mysql_num_rows($rs_claim);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p>เลือกเลขที่เคลม</p>
<table width="300" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="142" align="center">วันที่ปฏิบัติหน้าที่</td>
    <td width="158" align="center">เลขเคลม</td>
  </tr>
  <tr>
    <td align="center"><?=$row_rs_claim['date_operations']; ?></td>
    <td align="center"><?=$row_rs_claim['driver_claim_no']; ?></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_claim);
?>
