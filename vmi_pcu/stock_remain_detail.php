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
$query_rs_stock = "select * from stock_drugs_c where working_code='".$_GET['working_code']."' and hospcode='".$_GET['hospcode1']."' order by exp_date ASC";
$rs_stock = mysql_query($query_rs_stock, $vmi) or die(mysql_error());
$row_rs_stock = mysql_fetch_assoc($rs_stock);
$totalRows_rs_stock = mysql_num_rows($rs_stock);

include('include/function.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<style>
tr.table_head td{
	border-bottom:solid 1px #E1E1E1;
	border-top:solid 1px #E1E1E1;
	padding:5px;
	background-color:#F0F0F0;
	}
tr.table_body td{
	padding:5px;
	border-bottom:solid 1px #E1E1E1;

}
</style>

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr class="table_head thsan-semibold font19">
    <td width="9%" align="center">ลำดับ</td>
    <td width="18%" align="center">lot</td>
    <td width="18%" align="center">expire</td>
    <td width="12%" align="center">คงเหลือ</td>
    <td width="14%" align="center">ขนาดบรรจุ</td>
    <td width="15%" align="center">มูลค่า/หน่วย</td>
    <td width="14%" align="center">มูลค่ารวม</td>
  </tr>
  <?php $i=0; do { $i++; ?>
  <tr class="table_body thsan-light">
    <td align="center"><?php echo $i; ?></td>
    <td align="center"><?php print $row_rs_stock['lot']; ?></td>
    <td align="center"><?php if($row_rs_stock['exp_date']!=NULL){print dateThai($row_rs_stock['exp_date']);} else {echo "";} ?></td>
    <td align="center"><?php print $row_rs_stock['remain_qty']/$row_rs_stock['pack_ratio']; ?></td>
    <td align="center"><?php print $row_rs_stock['pack_ratio']; ?></td>
    <td align="center"><?php print str_replace('.00','',number_format($row_rs_stock['total_cost']/($row_rs_stock['remain_qty']/$row_rs_stock['pack_ratio']),2)); ?></td>
    <td align="center"><?php print str_replace('.00','',number_format($row_rs_stock['total_cost'],2)); ?></td>
  </tr>
  <?php } while ($row_rs_stock = mysql_fetch_assoc($rs_stock)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($rs_stock);
?>
