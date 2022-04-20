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
$query_rs_department = "SELECT * FROM hospcode WHERE concat(chwpart,amppart)='$id' and hospcode not in (select dept_code from dept_list)";
$rs_department = mysql_query($query_rs_department, $vmi) or die(mysql_error());
//$row_rs_department = mysql_fetch_assoc($rs_department);
$totalRows_rs_department = mysql_num_rows($rs_department);

while($row_rs_department = mysql_fetch_assoc($rs_department))
{
$id=$row_rs_department['hospcode'];
$data=$row_rs_department['hosptype'].$row_rs_department['name'];
echo '<option value="'.$id.'">'.$data.'</option>';
}
}

mysql_free_result($rs_department);
?>