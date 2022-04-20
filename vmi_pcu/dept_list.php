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

if(isset($_POST['action_do'])&&$_POST['action_do']=="add"){
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into dept_list (dept_group,dept_code) value ('".$_POST['id']."','".$_POST['department']."')";
	$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
echo "<script>clearForm('chw','amp','department');</script>";
}
if(isset($_POST['action_do'])&&$_POST['action_do']=="delete"){
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from dept_list where dept_code='$action_id' and dept_group='".$_POST['id']."'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
}
mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "SELECT d.dept_group,d.dept_code,concat(h.hosptype,h.name) as hospname FROM dept_list d left outer join hospcode h on h.hospcode=d.dept_code WHERE dept_group='".$_GET['id']."' ORDER BY id DESC";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="800" border="0" cellpadding="3" cellspacing="0" class=" thsan-light">
  <tr>
    <td width="64" align="center" class=" font_bord font16">ลำดับ</td>
    <td width="724" align="left" class="font_bord font16">ชื่อหน่วยงาน</td>
  </tr>
    <?php $i=0; do { $i++; ?>
  <tr class="dashed">
      <td align="center"><?php echo $i; ?></td>
      <td align="left"><span><?php echo $row_rs_dept['hospname']; ?></span>&nbsp; <a href="javascript:dosubmit('<?php echo $row_rs_dept['dept_code']; ?>','delete','dept_list.php','dept_list','form1');"><img src="images/delete.png" width="19" height="18" border="0" align="absmiddle" /></a></td>
  </tr>
      <?php } while ($row_rs_dept = mysql_fetch_assoc($rs_dept)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($rs_dept);
?>
