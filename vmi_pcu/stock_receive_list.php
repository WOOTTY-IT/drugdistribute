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
if($_POST['do']=="edit"&&$_POST['pr2']!=""&&$_POST['id']=="load"){	
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from temp_stock_receive_c where hospcode='".$_SESSION['hospcode']."'";
$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into temp_stock_receive_c (hospcode,working_code,qty,pack_ratio,lot,exp_date,buy_unit_cost,c_id) select hospcode,working_code,qty,pack_ratio,lot,exp_date,buy_unit_cost,id from stock_receive_c where receive_no='".$_POST['pr2']."' ";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	}
// ค้นหาข้อมูลจาก temp
mysql_select_db($database_vmi, $vmi);
$query_rs_drug = "select c.id,d.drug_name,(c.qty/c.pack_ratio) as qty,c.pack_ratio,c.lot,c.exp_date,c.working_code,c.action,c.buy_unit_cost from temp_stock_receive_c c left outer join drugs d on d.working_code=c.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' where c.hospcode='".$_SESSION['hospcode']."' order by d.drug_name ASC";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_vmi, $vmi);
$query_rs_delete = "select * from temp_stock_receive_c where action is NULL and hospcode='".$_SESSION['hospcode']."'";
$rs_delete = mysql_query($query_rs_delete, $vmi) or die(mysql_error());
$row_rs_delete = mysql_fetch_assoc($rs_delete);
$totalRows_rs_delete = mysql_num_rows($rs_delete);

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
      <td width="6%" align="center">ลำดับ</td>
      <td width="26%" align="center">รายการยา</td>
      <td width="12%" align="center">จำนวน</td>
      <td width="12%" align="center">ขนาดบรรจุ</td>
      <td width="10%" align="center">lot</td>
      <td width="20%" align="center">วันหมดอายุ</td>
      <td width="14%" align="center">ราคา/หน่วย</td>
    </tr>
    <?php $i=0; do { $i++; 
	if($bg == "#F9F9F9") { //ส่วนของการ สลับสี 
$bg = "#ededed";
} else {
$bg = "#F9F9F9";
}
	?>
    <tr class="table_body table_body1 thsan-light font18 grid" onClick="OpenPopup('stock_receive_add.php','600','400','1&do=edit&id=<?php echo $row_rs_drug['id'] ?>&working_code=<?php echo $row_rs_drug['working_code']; ?>');" style="cursor:pointer; <?php if($row_rs_drug['action']=='D'){ echo "color:#F00"; } ?>" >
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $i; ?></td>
      <td align="left" bgcolor="<?php echo $bg; ?>"><?php echo $row_rs_drug['drug_name']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo number_format($row_rs_drug['qty']); ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $row_rs_drug['pack_ratio']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $row_rs_drug['lot']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $row_rs_drug['exp_date']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php print str_replace('.00','',number_format($row_rs_drug['buy_unit_cost'],2)); ?></td>
    </tr>
    <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
  </table>
    <input type="submit" name="save" id="save" value="บันทึก" style="margin-left:20px; margin-top:20px">
    <input type="button" name="delete" id="delete" value="<?php if($totalRows_rs_delete==0){ echo "ยกเลิกลบทั้งหมด"; }else { echo "ลบทั้งหมด"; } ?>" style="margin-left:20px; margin-top:20px" onClick="OpenPopup('stock_receive_delete.php','0','0','&delete_type=<?php if($totalRows_rs_delete==0){ echo "undelete"; }else { echo "delete"; } ?>');">
	<?php } ?>
</body>
</html>
<?php
mysql_free_result($rs_drug);

mysql_free_result($rs_delete);
?>
