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
if($_GET['action_do']=="delete"){
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from message_board where id='$_GET[action_id]'";
$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from message_reciever_hospcode where board_id='$_GET[action_id]'";
$delete = mysql_query($query_delete, $vmi) or die(mysql_error());
}
if($member_status=="admin"){
	$condition="";
	}
else {
	
	$condition="WHERE (r.hospcode='".$_SESSION['hospcode']."' or m.hospcode='".$_SESSION['hospcode']."')";
	}
mysql_select_db($database_vmi, $vmi);
$query_rs_msg_reciev = "SELECT m.*,concat(DATE_FORMAT(m.date_send,'%d/%m/'),(DATE_FORMAT(m.date_send,'%Y')+543)) as date_send,concat(h.hosptype,h.name) as hospname FROM message_board m left outer join hospcode h on h.hospcode=m.hospcode left outer join message_reciever_hospcode r on r.board_id=m.id GROUP BY m.id ORDER BY id DESC";
$rs_msg_reciev = mysql_query($query_rs_msg_reciev, $vmi) or die(mysql_error());
$row_rs_msg_reciev = mysql_fetch_assoc($rs_msg_reciev);
$totalRows_rs_msg_reciev = mysql_num_rows($rs_msg_reciev);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($totalRows_rs_msg_reciev > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small table_collapse">
    <tr class="table_head_small_bord">
      <td width="8%" height="28" align="center" style="border-bottom:solid 1px #CCCCCC" class="gray" >ลำดับ</td>
      <td width="42%" align="left" class="gray" style="border-bottom:solid 1px #CCCCCC" >หัวข้อ</td>
      <td width="19%" align="center" class="gray" style="border-bottom:solid 1px #CCCCCC" >หน่วยงานที่ส่ง</td>
      <td width="16%" align="center" class="gray" style="border-bottom:solid 1px #CCCCCC" >ผู้ส่ง</td>
      <td width="15%" align="center" class="gray" style="border-bottom:solid 1px #CCCCCC" >วันที่ส่ง</td>
    </tr>
    <?php $i=0; do { $i++; ?>
    <tr  class="grid">
      <td height="47" align="center"  style="border-bottom:solid 1px #CCCCCC"><?php echo $i; ?></td>
      <td align="left" onclick="alertload1('message_view.php?id=<?php echo $row_rs_msg_reciev['id']; ?>','90%','90%');"  style="border-bottom:solid 1px #CCCCCC;cursor:pointer" ><?php print $row_rs_msg_reciev['head']; ?> <?php if($row_rs_msg_reciev['file_upload']!=""){ ?><img src="images/attachment.png" width="37" height="37" border="0" align="absmiddle" /><?php } ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC" ><?php print $row_rs_msg_reciev['hospname']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC" ><?php print $row_rs_msg_reciev['sender']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC" ><?php print $row_rs_msg_reciev['date_send']; ?> <?php if($member_status=="admin"||$row_rs_msg_reciev['hospcode']==$hospcode){?><a href="javascript:poppage('message_board.php?do=edit&id=<?php echo $row_rs_msg_reciev['id']; ?>','700','700','','show','board_list.php','board_list','');"><img src="images/Pencil-icon.png" width="20" height="20" border="0" align="absmiddle" /></a><a href="javascript:dosubmit('<?php echo $row_rs_msg_reciev['id']; ?>','delete','board_list.php','board_list','');"><img src="images/delete.png" width="22" height="21" border="0" align="absmiddle" /></a><?php } ?></td>
    </tr>
    <?php } while ($row_rs_msg_reciev = mysql_fetch_assoc($rs_msg_reciev)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rs_msg_reciev);
?>
