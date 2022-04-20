<?php 
ob_start();
session_start();
?>
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

ob_start();
session_start();

mysql_select_db($database_vmi, $vmi);
$query_rs_spending = "select *,concat(substr(month_year,6,2),' / ',(substr(month_year,1,4)+543)) as date_po from spending_report where hospcode='$hospcode'";
$rs_spending = mysql_query($query_rs_spending, $vmi) or die(mysql_error());
$row_rs_spending = mysql_fetch_assoc($rs_spending);
$totalRows_rs_spending = mysql_num_rows($rs_spending);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(document).ready(function(){
$('tr:eq(2) td[id^=type]').hover(function() {
    var id = this.id.replace(/type/, ''),
        targetId = id.concat(id);
    $('#type' + targetId).andSelf().css('background', 'red');
}, function() {
    var id = this.id.replace(/type/, ''),
        targetId = id.concat(id);
    $('#type' + targetId).andSelf().css('background', 'transparent');
})​;
});
</script>
</head>

<body>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small table_collapse">
  <tr>
    <td height="42" class="gray"><img src="images/Icon-Document03-Blue.png" width="28" height="28" align="absmiddle" />&nbsp;<span class="table_head_small_bord"> ใบอนุมัติเจ่ายยา (เมนูนี้ยังไม่เปิดใช้)</span></td>
  </tr>
  <tr>
    <td style="padding:10px"><table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr><?php $intRows = 0; do { $intRows++; ?>
        <td align="center" style="width:220px" id="type.<?php echo $row_rs_spending['id']; ?>" onclick="alertload1('spending_report_link.php?sub_po_no=<?php echo $row_rs_spending['sub_po_no']; ?>','90%','90%');"><span style="font-size:20px; color:#999999; font-weight:bolder; cursor:pointer" ><img src="images/Icon-Document03-Blue.png" width="41" height="41" align="absmiddle" /><?php echo $row_rs_spending['sub_po_no']; ?></span><br /><?php echo $row_rs_spending['date_po']; ?>
</td>
    <?php 	if(($intRows)%5==0)
		{
		echo"</tr>";
		}
		else
		{
		echo "<td>";
		}} while ($row_rs_spending = mysql_fetch_assoc($rs_spending)); ?> </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_spending);
?>
