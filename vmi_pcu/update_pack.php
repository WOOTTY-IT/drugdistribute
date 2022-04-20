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
if(isset($_POST['button'])&&($_POST['button']=="update")){

mysql_select_db($database_vmi, $vmi);
$query_rs_inv_md = "select * from inv_md where drug_name not like '*%' order by drug_name";
$rs_inv_md = mysql_query($query_rs_inv_md, $vmi) or die(mysql_error());
$row_rs_inv_md = mysql_fetch_assoc($rs_inv_md);
$totalRows_rs_inv_md = mysql_num_rows($rs_inv_md);
 

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p>ระบบอัพเดทขนาดบรรจุยา
</p>
<form id="form1" name="form1" method="post" action="update_pack.php">
  <input type="submit" name="button" id="button" value="update" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
if(isset($_POST['button'])&&($_POST['button']=="update")){

mysql_free_result($rs_inv_md);
}
?>
