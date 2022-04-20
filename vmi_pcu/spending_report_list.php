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
if(isset($_GET['action_do'])){

if($_GET['action_do']=="delete"){
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from  spending_report where id='$action_id'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
}
}
 if($_POST['do']=="edit"){
if($_POST['sub_po_no']==""){ $msg="**รุณากรอกเลขที่ใบตัดเบิก";}
else{
		mysql_select_db($database_vmi, $vmi);
		$query_update = "update spending_report set sub_po_no='$sub_po_no',month_year=concat('$year','-','$month'),hospcode='$dept' where id=".$_POST['id'];
		$rs_upate = mysql_query($query_update, $vmi) or die(mysql_error());
}
 }
 if($_POST['do']=="save"){
if($_POST['sub_po_no']==""){ $msg="**รุณากรอกเลขที่ใบตัดเบิก";}
else{
		mysql_select_db($database_vmi, $vmi);
		$query_update = "insert into spending_report (sub_po_no,month_year,hospcode) value ('$sub_po_no',concat('$year','-','$month'),'$dept')";
		$rs_upate = mysql_query($query_update, $vmi) or die(mysql_error());
}
 }
//แสดงข้อมูล
mysql_select_db($database_vmi, $vmi);
$query_rs_spending = "SELECT s.*,concat(h.hosptype,h.name) as hospname,concat(substr(month_year,6,2),' / ',(substr(month_year,1,4)+543)) as date_po,substr(month_year,1,4) as year,substr(month_year,6,2) as month FROM spending_report s left outer join hospcode h on h.hospcode=s.hospcode where h.chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' ORDER BY month_year,s.id DESC";
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
</head>

<body  >
<?php echo "<font color=\"#FF0000\">".$msg."</font>";?>
<?php if ($totalRows_rs_spending > 0) { // Show if recordset not empty ?>
  <table width="800" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
    <tr class="table_head_small_bord">
      <td width="56" align="center" style="border-bottom:solid 1px #CCCCCC">ลำดับ</td>
      <td width="201" align="center" style="border-bottom:solid 1px #CCCCCC">เลขที่ใบตัดเบิก</td>
      <td width="219" align="center" style="border-bottom:solid 1px #CCCCCC">ประจำเดือน</td>
      <td width="250" align="center" style="border-bottom:solid 1px #CCCCCC">หน่วยงาน</td>
      <td width="44" align="center" style="border-bottom:solid 1px #CCCCCC">&nbsp;</td>
    </tr>
    <?php $i=0; do { $i++; ?>
    <tr class="grid">
      <td height="34" align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $i; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_spending['sub_po_no']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_spending['date_po']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_spending['hospname']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><a href="javascript:edit('<?php echo $row_rs_spending['id']; ?>','<?php echo $row_rs_spending['sub_po_no']; ?>','<?php echo $row_rs_spending['month']; ?>','<?php echo $row_rs_spending['year']; ?>','<?php echo $row_rs_spending['hospcode']; ?>');"><img src="images/gtk-edit.png" width="16" height="16" border="0" /></a><a href="javascript:dosubmit('<?php echo $row_rs_spending['id']; ?>','delete','spending_report_list.php','spending_list','');"><img src="images/bin.png" width="16" height="16" border="0" /></a></td>
    </tr>
    <?php } while ($row_rs_spending = mysql_fetch_assoc($rs_spending)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rs_spending);
?>
