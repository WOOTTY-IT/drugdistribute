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
if(isset($_GET['action_do'])&&$_GET['action_do']=="post"){
	$comment= str_replace("\n", "<br>\n", "$comment"); 

	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into message_comment (board_id,comment,hospcode,poster,comment_date) value ('$_GET[action_id]','$comment','$hospcode','$poster',NOW())";
	$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	$board_id=$_GET['action_id'];
}
mysql_select_db($database_vmi, $vmi);
$query_rs_comment = "SELECT m.*,concat(h.hosptype,h.name) as hospname FROM message_comment m left outer join hospcode h on h.hospcode=m.hospcode WHERE board_id='$board_id' order by id DESC";
$rs_comment = mysql_query($query_rs_comment, $vmi) or die(mysql_error());
$row_rs_comment = mysql_fetch_assoc($rs_comment);
$totalRows_rs_comment = mysql_num_rows($rs_comment);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php $i=0; do { $i++; ?>
<?php if ($totalRows_rs_comment > 0) { // Show if recordset not empty ?>
  <table width="600" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td class="table_head_small rounded_top"  style="border-top:1px #999999 solid; border-left:1px #999999 solid; border-right: 1px #999999 solid"><span class="small_red_bord">ความคิดเห็นที่ <?php echo $i; ?> : </span><?php echo $row_rs_comment['comment']; ?></td>
      </tr>
    <tr>
      <td align="left" class="table_head_small rounded_bottom" style=" border-bottom:1px #999999 solid; border-left:1px #999999 solid; border-right: 1px #999999 solid"><em>{หน่วยงาน :<?php echo $row_rs_comment['hospname']; ?>(<?php echo $row_rs_comment['poster']; ?>),<?php echo $row_rs_comment['comment_date']; ?>}</em></td>
      </tr>
    </table>
  <?php } // Show if recordset not empty ?>
<br />

  <?php } while ($row_rs_comment = mysql_fetch_assoc($rs_comment)); ?>
</body>
</html>
<?php
mysql_free_result($rs_comment);
?>
