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

mysql_select_db($database_vmi, $vmi);
$query_rs_take = "select h.name,count(*) as count_order,v.order_month,v.hospcode from vmi_order v left outer join hospcode h on h.hospcode=v.hospcode where v.order_month ='".($_POST['year'])."-".$_POST['month']."' and h.chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' group by v.hospcode order by h.name ASC";
$rs_take = mysql_query($query_rs_take, $vmi) or die(mysql_error());
$row_rs_take = mysql_fetch_assoc($rs_take);
$totalRows_rs_take = mysql_num_rows($rs_take);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($totalRows_rs_take > 0) { // Show if recordset not empty ?>
  <table width="600" border="0" cellspacing="0" cellpadding="3">
    <tr class="font_bord">
      <td width="60" align="center" style="border-bottom:solid 1px #CCCCCC" >ลำดับ</td>
      <td width="322" align="center" style="border-bottom:solid 1px #CCCCCC">หน่วยงาน</td>
      <td width="177" align="center" style="border-bottom:solid 1px #CCCCCC">จำนวนรายการ</td>
      <td width="17" align="center" style="border-bottom:solid 1px #CCCCCC">&nbsp;</td>
    </tr>
    <?php $i=0; do {$i++; ?>
    <tr class="grid4">
      <td height="33" align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $i; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_take['name']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_take['count_order']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><img src="images/Printer and Fax.png" width="30" height="30" onclick="window.open('order_print.php?order_date=<?php echo $row_rs_take['order_month']; ?>&dept=<?php echo $row_rs_take['hospcode']; ?>','_blank');" style="cursor:pointer" /></td>
    </tr>
    <?php } while ($row_rs_take = mysql_fetch_assoc($rs_take)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rs_take);
?>
