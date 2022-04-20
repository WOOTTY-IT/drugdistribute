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

mysql_select_db($database_vmi, $vmi);
$query_rs_msg_reciev = "select count(*) as count_record
from message_board m 
left outer join message_reciever_hospcode r on r.board_id=m.id
where r.hospcode='$hospcode' and r.review is null ";
$rs_msg_reciev = mysql_query($query_rs_msg_reciev, $vmi) or die(mysql_error());
$row_rs_msg_reciev = mysql_fetch_assoc($rs_msg_reciev);
$totalRows_rs_msg_reciev = mysql_num_rows($rs_msg_reciev);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#apDiv1 {
	position:absolute;
	left:0px;
	top:0px;
	width:156px;
	height:44px;
	z-index:1;
}
#apDiv2 {
	position:absolute;
	left:449px;
	top:36px;
	width:35px;
	height:32px;
	z-index:2;
}
</style>
</head>

<body>
<div id="apDiv1">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" height="40px">
    <tr>
      <td valign="middle" class="black_bar"><a href="index.php" target="_parent"><img src="images/distribution.png" width="103" height="35" /></a></td>
    </tr>
  </table>
</div>
<?php if($row_rs_msg_reciev['count_record']!=0){ ?>
<div id="apDiv2" align="center" style="background-image:url(images/read_news.gif); background-repeat:no-repeat; background-position:center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="31" align="center" valign="middle"><font style=" font-size:14px; color:#FFFFFF; font-weight:bolder; padding-left:8px; padding-right:8px"><?php echo "$row_rs_msg_reciev[count_record]"; ?></font></td>
    </tr>
  </table>
</div><? } ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td height="40" align="center" class="blue2 table_head_small_white">VMI drug distribution</td>
  </tr>
  <tr>
    <td  height="27" align="left" bgcolor="#EDEDED" ><table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
      <tr>
        <td class="button_gray_square<?php if($sub_page=="drug_take"){ echo 2; }?> table_head_small" onclick="window.open(
  'index2.php?sub_page=drug_take',
  '_parent' // <- This is what makes it open in a new window.
);">เบิกยา</td>
        <td class="button_gray_square<?php if($sub_page=="drug_take_profile"){ echo 2; }?> table_head_small" onclick="window.open(
  'index2.php?sub_page=drug_take_profile',
  '_parent' // <- This is what makes it open in a new window.
);">ประวัติการเบิกยา</td>
        <td class="button_gray_square table_head_small">อัตราการใช้ยา</td>
        <td class="button_gray_square<?php if($sub_page=="board"){ echo 2; }?> table_head_small" onclick="window.open(
  'index.php?sub_page=board',
  '_parent' // <- This is what makes it open in a new window.
);">กระดานข่าว  </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_msg_reciev);
?>
