<?php require_once('Connections/ubuntu.php'); ?>
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

mysql_select_db($database_ubuntu, $ubuntu);
$query_rs_sm_po = "SELECT i.drug_name,c.qty_rcv1,c.pack_ratio1,c.cost1,sub_po_date FROM SM_PO_C c left outer join INV_MD i on i.working_code=c.working_code left outer join SM_PO s on s.sub_po_no=c.sub_po_no  WHERE c.sub_po_no='$_GET[sub_po_no]' order by drug_name";
$rs_sm_po = mysql_query($query_rs_sm_po, $ubuntu) or die(mysql_error());
$row_rs_sm_po = mysql_fetch_assoc($rs_sm_po);
$totalRows_rs_sm_po = mysql_num_rows($rs_sm_po);
?>
<?php include('include/function.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p><span class="big_black16">เลขที่ใบอนุมัติจ่ายยา</span> <span class="big_red16"><?php echo $_GET['sub_po_no']; ?></span><br />
  <span class="table_head_small_bord">วันที่ดำนเนิการ :</span> <span class="table_head_small"><?php echo dateThai($row_rs_sm_po['sub_po_date']); ?></span></p>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table_head_small">
  <tr class="table_head_small_bord">
    <td height="32" align="center" style="border-bottom:dashed 1px #999999; border-right:dashed 1px #999999">ลำดับ</td>
    <td align="center" style="border-bottom:dashed 1px #999999; border-right:dashed 1px #999999">รายการ</td>
    <td align="center" style="border-bottom:dashed 1px #999999; border-right:dashed 1px #999999">จำนวน</td>
    <td align="center" style="border-bottom:dashed 1px #999999; border-right:dashed 1px #999999">ราคา/หน่วย</td>
    <td align="center" style="border-bottom:dashed 1px #999999; ">ราคารวม</td>
  </tr>
     <?php $i=0; $sumprice=0; do { $i++; $sumprice1=$row_rs_sm_po['cost1']; ?>
 <tr>
    <td height="34" align="center" style="border-bottom:dashed 1px #999999; border-right:dashed 1px #999999"><?php echo $i; ?></td>
    <td align="left" style="border-bottom:dashed 1px #999999; border-right:dashed 1px #999999"><?php echo $row_rs_sm_po['drug_name']; ?></td>
    <td align="center" style="border-bottom:dashed 1px #999999;border-right:dashed 1px #999999 "><?php echo $row_rs_sm_po['qty_rcv1']/$row_rs_sm_po['pack_ratio1']; ?>x <?php echo $row_rs_sm_po['pack_ratio1']; ?></td>
    <td align="center" style="border-bottom:dashed 1px #999999;border-right:dashed 1px #999999 "><?php echo number_format($row_rs_sm_po['cost1']/($row_rs_sm_po['qty_rcv1']/$row_rs_sm_po['pack_ratio1']),2); ?></td>
    <td align="center" style="border-bottom:dashed 1px #999999; "><?php echo number_format($row_rs_sm_po['cost1'],2); ?></td>
  </tr>      <?php $sumprice+=$sumprice1; } while ($row_rs_sm_po = mysql_fetch_assoc($rs_sm_po)); ?>

</table>
<p><span class="big_black16">รวมทั้งหมด</span> <span class="big_red16"><?php echo $totalRows_rs_sm_po; ?></span> <span class="big_black16">รายการ</span>&nbsp; <span class="big_black16">เป็นเงินจำนวน</span> <span class="big_red16"><?php echo number_format($sumprice,2); ?></span>&nbsp;บาท</p>
</body>
</html>
<?php
mysql_free_result($rs_sm_po);
?>
