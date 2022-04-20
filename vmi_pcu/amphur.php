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


if($_POST['id'])
{
$id=$_POST['id'];
mysql_select_db($database_vmi, $vmi);
$query_rs_amphur = "select * from amphur where province_id='$id'";
$rs_amphur = mysql_query($query_rs_amphur, $vmi) or die(mysql_error());
$row_rs_amphur = mysql_fetch_assoc($rs_amphur);
$totalRows_rs_amphur = mysql_num_rows($rs_amphur);
echo '<option value="">-- เลือกอำเภอ --</option>';

while($row_rs_amphur = mysql_fetch_assoc($rs_amphur))
{
$id=$row_rs_amphur['amphur_code'];
$data=$row_rs_amphur['amphur_name'];
echo '<option value="'.$id.'">'.$data.'</option>';
}
}

mysql_free_result($rs_amphur);
?>