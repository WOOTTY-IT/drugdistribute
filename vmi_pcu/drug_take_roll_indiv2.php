<?
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

mysql_select_db($database_vmi, $vmi);
$query_rs_drug = "SELECT d.working_code,d.drug_name,sum(t.qty) as sumamong,t.pack_ratio  FROM vmi_order t left outer join drug_stdcode s on s.stdcode=t.stdcode and s.chwpart='".$_SESSION['chwpart']."' and s.amppart='".$_SESSION['amppart']."' left outer join drugs d on d.working_code=s.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' left outer join hospcode h on h.hospcode=t.hospcode WHERE t.order_month='".$_GET['month']."' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."'  GROUP BY d.working_code ORDER BY d.drug_name";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<?php include('include/function.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_function.php'); ?>
<script src="include/jquery.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('.tbdiv').hide();
});
</script>
</head>

<body>
<span class="big_black16">รายการยาเบิกแยกรายการยาประจำเดือน<?php echo monthThai($month); ?></span>
<br />
<br />
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small table_collapse">
  <tr class="gray">
    <td height="26" align="center" class=" table_head_small_bord">ลำดับ</td>
    <td align="left" class="table_head_small_bord" style="padding-left:10px">รายการยา</td>
    <td align="center" class="table_head_small_bord">จำนวน</td>
    <td align="center" class="table_head_small_bord">ขนาดบรรจุ</td>
    <td align="center" >&nbsp;</td>
  </tr>
    <?php $i=0; do { $i++; ?>
  <tr class=" dashed" >
      <td align="center" class=" table_border_gray"  ><?php echo $i; ?></td>
      <td align="left" style="padding-left:10px" class=" table_border_gray" ><span><?php echo $row_rs_drug['drug_name']; ?></span></td>
      <td align="center" class=" table_border_gray"><span><?php echo $row_rs_drug['sumamong']; ?></span></td>
      <td align="center" class=" table_border_gray"><span><?php echo $row_rs_drug['pack_ratio']; ?></span></td>
      <td align="center" class=" table_border_gray"><a href="javascript:divhide('<?php echo $row_rs_drug['working_code']; ?>');dosubmit('<?php echo $row_rs_drug['working_code']; ?>&month=<?php echo $month; ?>','show','drug_take_roll_indiv_detail3.php','<?php echo $row_rs_drug['working_code']; ?>','');" ><img src="images/Icon-Document03-Blue.png" width="24" height="24" border="0" /></a></td>
  </tr>
  <tr id="<?php echo $row_rs_drug['working_code']; ?>1" class="tbdiv">
    <td colspan="5" align="left" ><div id="<?php echo $row_rs_drug['working_code']; ?>"></div></td>
  </tr>
  
      <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
</table>
</body>
</html><?php
mysql_free_result($rs_drug);
?>
