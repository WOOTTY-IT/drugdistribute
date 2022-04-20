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
///ถ้ามีการแก้ไขใบรับ
if($_POST['do']=="edit"&&$_POST['ps2']!=""&&$_POST['id']=="load"){	
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from temp_stock_withdraw_c where hospcode='".$_SESSION['hospcode']."'";
$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into temp_stock_withdraw_c (hospcode,r_id,working_code,qty_order,pack_ratio,lot,exp_date,c_id,buy_unit_cost) select hospcode,r_id,working_code,qty_order,pack_ratio,lot,exp_date,id,buy_unit_cost  from stock_withdraw_c where withdraw_no='".$_POST['ps2']."' ";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	}
// ค้นหาข้อมูลจาก temp

mysql_select_db($database_vmi, $vmi);
$query_rs_drug = "select c.action,c.id,d.drug_name,sum(c.qty_order/c.pack_ratio) as qty,c.pack_ratio,c.working_code from temp_stock_withdraw_c c left outer join drugs d on d.working_code=c.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' where c.hospcode='".$_SESSION['hospcode']."' group by c.working_code order by d.drug_name ASC";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
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
<?php if ($totalRows_rs_drug > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr class="table_head thsan-bold font20">
      <td width="8%" align="center">ลำดับ</td>
      <td width="24%" align="center">รายการยา</td>
      <td width="14%" align="center">จำนวน</td>
      <td width="22%" align="center">ขนาดบรรจุ</td>
      <td width="23%" align="center">&nbsp;</td>
    </tr>
    <?php $i=0; do { $i++; 
	if($bg == "#F9F9F9") { //ส่วนของการ สลับสี 
$bg = "#ededed";
} else {
$bg = "#F9F9F9";
}
	?>
    <tr class="table_body table_body1 thsan-light font18 grid" onClick="OpenPopup('stock_withdraw_add.php','700','500','1&do=edit&did=<?php echo $row_rs_drug['working_code']; ?>');" style="cursor:pointer;<?php if($row_rs_drug['action']=='D'){ echo "color:#F00"; } ?>" >
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $i; ?></td>
      <td align="left" bgcolor="<?php echo $bg; ?>"><?php echo $row_rs_drug['drug_name']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo number_format($row_rs_drug['qty']); ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $row_rs_drug['pack_ratio']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>">&nbsp;</td>
    </tr>
    <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
  </table>
    <input type="submit" name="save" id="save" value="บันทึก" style="margin-left:20px; margin-top:20px">
  <?php } // Show if recordset not empty ?>

</body>
</html>
<?php
mysql_free_result($rs_drug);
?>
