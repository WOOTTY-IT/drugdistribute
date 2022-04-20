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
if(isset($_POST['action_do'])&&$_POST['action_do']=="delete"){
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from  user where id='".$_POST['action_id']."'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
		echo "<script>parent.$.fn.colorbox.close();</script>";
	exit();	
}

mysql_select_db($database_vmi, $vmi);
$query_rs_user = "select concat(h.hosptype,h.name) as deptname,u.*,t.hospcode_type_name from user u left outer join hospcode h on h.hospcode=u.hospcode left outer join hospcode_type t on t.id=u.hospcode_type_id where h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' order by u.id DESC";
$rs_user = mysql_query($query_rs_user, $vmi) or die(mysql_error());
$row_rs_user = mysql_fetch_assoc($rs_user);
$totalRows_rs_user = mysql_num_rows($rs_user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="800" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
  <tr class="table_head_small_bord" >
    <td width="39" align="center">ลำดับ</td>
    <td width="127" align="center">ชื่อผู้ใช้</td>
    <td width="96" align="center">รหัสผ่าน</td>
    <td width="356" align="left" style="padding-left:10px">หน่วยงาน</td>
    <td width="101" align="center">สถานะ</td>
    <td width="45" align="center">&nbsp;</td>
  </tr>
    <?php $i=0; do { $i++; ?>
  <tr class="dashed">
      <td align="center"><?php echo $i; ?></td>
      <td align="center"><span><?php print $row_rs_user['username']; ?></span></td>
      <td align="center"><span><?php print $row_rs_user['password']; ?></span></td>
      <td align="left" style="padding-left:10px"><span><?php print $row_rs_user['deptname']; ?></span></td>
      <td align="center"><span><?php print $row_rs_user['member_status']; ?></span></td>
      <td align="center"><a href="javascript:poppage('user_add.php?id=<?php echo $row_rs_user['id']; ?>&amp;do=edit','500','500','','show','user_list.php','user_list','');"><img src="images/Pencil-icon.png" width="20" height="20" border="0" /></a><a href="javascript:dosubmit('<?php echo $row_rs_user['id']; ?>','delete','user_list.php','user_list','');"><img src="images/delete.png" width="20" height="18" border="0" /></a></td>
  </tr>
      <?php } while ($row_rs_user = mysql_fetch_assoc($rs_user)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($rs_user);
?>
