<?php require_once('Connections/vmi.php'); ?>
<?
ob_start();
session_start();
?>

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


if($_POST['id'])
{
$id=$_POST['id'];
mysql_select_db($database_vmi, $vmi);
$query_rs_max = "SELECT d.*,dd.drug_name,dd.pack_ratio,dd.buy_unit_cost,dd.sale_unit
from drug_dept_group d left outer join drugs dd on dd.working_code=d.working_code and dd.chwpart='".$_SESSION['chwpart']."' and dd.amppart='".$_SESSION['amppart']."'
left OUTER join dept_group g on g.dept_group_id=d.dept_group_id 
LEFT OUTER JOIN dept_list l on l.dept_group=g.dept_group_id
where l.dept_code='".$_SESSION['hospcode']."' and d.working_code='".$_POST['id']."'";
$rs_max = mysql_query($query_rs_max, $vmi) or die(mysql_error());
$row_rs_max = mysql_fetch_assoc($rs_max);
$totalRows_rs_max = mysql_num_rows($rs_max);

for($i=1;$i<=($row_rs_max['max_level']/$row_rs_max['pack_ratio']);$i++)
{
echo '<option value="'.$i.'">'.$i.'</option>';
}
}

mysql_free_result($rs_max);
?>