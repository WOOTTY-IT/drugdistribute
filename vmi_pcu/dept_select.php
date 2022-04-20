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
$query_rs_dept = "SELECT h.hospcode as deptcode,concat(h.hosptype,h.name) as deptname FROM dept_list d left outer join hospcode h on h.hospcode=d.dept_code WHERE dept_group='".$id."'";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
//$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);
echo '<option value="">-- เลือกหน่วยงาน --</option>';

while($row_rs_dept = mysql_fetch_assoc($rs_dept))
{
$id=$row_rs_dept['deptcode'];
$data=$row_rs_dept['deptname'];
echo '<option value="'.$id.'">'.$data.'</option>';
}
}

mysql_free_result($rs_dept);
?>