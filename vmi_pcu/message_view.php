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

mysql_select_db($database_vmi, $vmi);
$query_rs_message = "SELECT head,detail,sender,date_send,concat(hosptype,' ',name) as hospname,m.file_upload FROM message_board m left outer join hospcode h on h.hospcode=m.hospcode WHERE id  ='$id'";
$rs_message = mysql_query($query_rs_message, $vmi) or die(mysql_error());
$row_rs_message = mysql_fetch_assoc($rs_message);
$totalRows_rs_message = mysql_num_rows($rs_message);

mysql_select_db($database_vmi, $vmi);
$query_update = "update message_reciever_hospcode set review=1 WHERE board_id  ='$id' and hospcode='$hospcode'";
$update = mysql_query($query_update, $vmi) or die(mysql_error());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_function.php'); ?>
<script type="text/javascript">
$(document).ready(function() {
  	$('#div_post').hide();  
	
	$('.comment_button').click(function(){	$('#div_post').show();
	});
	$('#comment_cancel').click(function(){
	$('#comment').val('');
	$('#poster').val('');
  	$('#div_post').hide();  		
		});

});
</script>
</head>

<body onload="dosubmit('','show','comment_list.php?board_id=<?php echo $id; ?>','comment_list','');">
<p class="big_red16">กระดานส่งข้อความ</p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px #000000 solid">
  <tr>
    <td bgcolor="#CCCCCC" class="big_black16" style="padding:10px" >&quot;<?php echo $row_rs_message['head']; ?>&quot;</td>
  </tr>
  <tr>
    <td bgcolor="#F4F4F4" class="table_head_small_gray" style=" padding:5px;padding-left:30px; color:#666666"><?php echo $row_rs_message['detail']; ?><br />
      <?php if($row_rs_message['file_upload']!=""){ ?><span class="table_head_small_bord"><img src="images/attachment.png" width="25" height="25" align="absmiddle" />แนนไฟล์</span> : <span class="small_red_bord"><a href="upload/board/<?php echo $row_rs_message['file_upload']; ?>" class="small_red_bord"><?php echo $row_rs_message['file_upload']; ?></a></span><?php } ?></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#F4F4F4" class="table_head_small_gray" style=" padding:5px;padding-left:30px; color:#666666; border-top: 1px dotted #666666"><em>หน่วยงาน : <?php echo $row_rs_message['hospname']; ?>(ผู้ส่ง:<?php echo $row_rs_message['sender']; ?>),<?php echo $row_rs_message['date_send']; ?></em></td>
  </tr>
</table>
<p ><span class="button bargreen comment_button"> + แสดงความคิดเห็น</span></p>
<div id="div_post">
  <form id="form1" name="form1" method="post" action="">
    <table width="500" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
      <tr>
        <td align="right" valign="top" bgcolor="#DDECD9" class="rounded_top_left">&nbsp;</td>
        <td bgcolor="#DDECD9" class="rounded_top_right">&nbsp;</td>
      </tr>
      <tr>
        <td width="124" align="right" valign="top" bgcolor="#DDECD9">ข้อความ</td>
        <td width="364" bgcolor="#DDECD9"><textarea name="comment" cols="45" rows="5" class="table_head_small" id="comment" style="width:90%"></textarea></td>
      </tr>
      <tr>
        <td align="right" bgcolor="#DDECD9">ผู้ส่งข้อความ</td>
        <td bgcolor="#DDECD9"><input name="poster" type="text" class="table_head_small" id="poster" /></td>
      </tr>
      <tr>
        <td bgcolor="#DDECD9">&nbsp;</td>
        <td bgcolor="#DDECD9"><input type="button" name="comment_post" id="comment_post" value="บันทึก" class="button bargreen" onclick="dosubmit('<?php echo $id; ?>','post','comment_list.php','comment_list','form1');" />
        <input type="button" name="comment_cancel" id="comment_cancel" value="ยกเลิก" class="button bargreen"  /></td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="#DDECD9" class="rounded_bottom">&nbsp;</td>
      </tr>
    </table>
  </form>
<br /></div>

<div id="comment_list">&nbsp;</div>
</body>
</html>
<?php
mysql_free_result($rs_message);
?>
