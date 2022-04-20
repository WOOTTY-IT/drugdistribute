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
if(isset($_POST['action_do'])){

if($_POST['action_do']=="delete"){
	//// delete dept_group
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from  dept_group where dept_group_id='".$_POST['action_id']."'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
	//// delete dept_list
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from  dept_list where dept_group='".$_POST['action_id']."'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
	}
}
if(isset($_POST['save'])&&$_POST['save']=="บันทึก"){
if($_POST['do']=="edit"){
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update dept_group set dept_group_name='".$_POST['dept']."',hospmain='".$_SESSION['hospcode']."' where dept_group_id='".$_POST['id']."'";
	$rs_update = mysql_query($query_update, $vmi) or die(mysql_error());
}
if($_POST['do']==""){
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into dept_group (dept_group_name,hospmain) value ('".$_POST['dept']."','".$_SESSION['hospcode']."')";
	$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
}
	}
mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "SELECT * FROM dept_group where hospmain='".$_SESSION['hospcode']."' ORDER BY dept_group_id DESC";
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
<table width="500" border="0" cellpadding="3" cellspacing="0" class="thsan-light">
  <tr>
    <td width="68" align="center" ><span class=" thsan-semibold font20">ลำดับ</span></td>
    <td width="400" align="left" ><span class="thsan-semibold font20">ชื่อกลุ่มของหน่วยงาน</span></td>
  </tr>
    <?php $i=0; do { $i++; ?>
  <tr >
    <td align="center"><span class="thsan-light font20"><?php echo $i; ?></span></td>
    <td align="left"><span class="thsan-light"><a href="javascript:dept_group_edit('<?php echo $row_rs_dept['dept_group_id']; ?>','<?php echo $row_rs_dept['dept_group_name']; ?>','edit');" class="link_red  thsan-light font20" style="text-decoration:none"><?php echo $row_rs_dept['dept_group_name']; ?></a>&nbsp;&nbsp;<a href="javascript:dosubmit('<?php echo $row_rs_dept['dept_group_id']; ?>','delete','dept_group_list.php','preview','');" ><img src="images/delete.png" width="20" height="19" border="0" align="absmiddle" /></a></span></td>
  </tr>
      <?php } while ($row_rs_dept = mysql_fetch_assoc($rs_dept)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($rs_dept);
?>
