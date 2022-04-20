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
if(isset($_GET['action_do'])&&($_GET['action_do']=="countmonth")){
	// ค้นหา stock month เดิม
	mysql_select_db($database_vmi, $vmi);
$query_rs_dept_group = "select stock_month from dept_group where dept_group_id='".$_GET['action_id']."'";
$rs_dept_group = mysql_query($query_rs_dept_group, $vmi) or die(mysql_error());
$row_rs_dept_group = mysql_fetch_assoc($rs_dept_group);
$totalRows_rs_dept_group = mysql_num_rows($rs_dept_group);
$stock_month=$row_rs_dept_group['stock_month'];
	// update stock month ใหม่
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update  dept_group set stock_month='".$_GET['countmonth']."' where dept_group_id='".$_GET['action_id']."'";
	$rs_update = mysql_query($query_update, $vmi) or die(mysql_error());

	$query_update = "update  drug_dept_group set max_level=(max_level/$stock_month)*".$_GET['countmonth']." where dept_group_id='".$_GET['action_id']."'";
	$rs_update = mysql_query($query_update, $vmi) or die(mysql_error());
	
mysql_free_result($rs_dept_group);

}
if(isset($_GET['action_do'])&&($_GET['action_do']=="delete")){
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from  drug_dept_group where id='".$_GET['item_id']."'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());

	}
mysql_select_db($database_vmi, $vmi);
$query_rs_item_list = "SELECT g.id,g.max_level,g.on_demand,g.dept_group_id,d.drug_name,d.sale_unit,d.pack_ratio FROM drug_dept_group g left outer join drugs d on d.working_code=g.working_code WHERE g.dept_group_id='".$_GET['action_id']."' and d.item_type='".$_GET['item_type']."' and d.chwpart='".$_SESSION['chwpart']."' and d.amppart ='".$_SESSION['amppart']."' order by d.drug_name asc";
$rs_item_list = mysql_query($query_rs_item_list, $vmi) or die(mysql_error());
$row_rs_item_list = mysql_fetch_assoc($rs_item_list);
$totalRows_rs_item_list = mysql_num_rows($rs_item_list);

/*mysql_select_db($database_vmi, $vmi);
$query_rs_stock_month = "select * from dept_group where dept_group_id='".$_GET['action_id']."'";
$rs_stock_month = mysql_query($query_rs_stock_month, $vmi) or die(mysql_error());
$row_rs_stock_month = mysql_fetch_assoc($rs_stock_month);
$totalRows_rs_stock_month = mysql_num_rows($rs_stock_month);
*/
mysql_select_db($database_vmi, $vmi);
$query_rs_item_type = "select * from item_type order by id ASC";
$rs_item_type = mysql_query($query_rs_item_type, $vmi) or die(mysql_error());
$row_rs_item_type = mysql_fetch_assoc($rs_item_type);
$totalRows_rs_item_type = mysql_num_rows($rs_item_type);

mysql_select_db($database_vmi, $vmi);
$query_rs_sel_dep = "select * from dept_group where dept_group_id='".$_GET['action_id']."'";
$rs_sel_dep = mysql_query($query_rs_sel_dep, $vmi) or die(mysql_error());
$row_rs_sel_dep = mysql_fetch_assoc($rs_sel_dep);
$totalRows_rs_sel_dep = mysql_num_rows($rs_sel_dep);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover {
    background-color: #111;
}

li .nav_select {
	background-color: #111;
}
</style>

</head>

<body>
<div >


<ul>
<?php do{ ?>
  <li><a class="active <?php if($row_rs_item_type['id']==$_GET['item_type']){ echo "nav_select"; } ?>" href="javascript:deptshow('<?php echo $row_rs_sel_dep['dept_group_id']; ?>','<?php echo $row_rs_sel_dep['dept_group_name']; ?>','<?php echo $row_rs_sel_dep['stock_month']; ?>');page_load('item_list','item_add_list.php?item_type=<?php echo $row_rs_item_type['id']; ?>&action_id=<?php echo $row_rs_sel_dep['dept_group_id']; ?>');" ><?php echo $row_rs_item_type['item_type_name']; ?></a></li>
 <?php }while($row_rs_item_type = mysql_fetch_assoc($rs_item_type)); ?>
</ul>

</div>
<div style="margin-top:10px;">
<a href="javascript:alertload1('item_add2.php?dept_group_id=<?php echo $_GET['action_id']; ?>&item_type=<?php echo $_GET['item_type']; ?>','500','500');" id="add" name="add" class="button orange " style="padding:5px; padding-left:10px; padding-right:10px; height:30px; padding-button:5px; "><img src="images/add-drug2.png" width="30" height="30" align="absmiddle" / border="0">&nbsp; เพิ่มรายการยา</a>
<?php if ($totalRows_rs_item_list > 0) { // Show if recordset not empty ?>

  <table width="859" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
    <tr class="table_head_small_bord">
      <td width="38" align="center">ลำดับ</td>
      <td width="400" align="left">รายการยา</td>
      <td width="115" align="center">จำนวนเบิกได้สูงสุด</td>
      <td width="74" align="center">ขนาดบรรจุ</td>
      <td width="84" align="center">หน่วย</td>
      <td width="53" align="center">on demand</td>
      <td width="53" align="center">แก้ไข</td>
    </tr>
    <?php $i=0; do { $i++; ?>
    <tr class="dashed">
      <td align="center"><?php echo $i; ?></td>
      <td align="left"><span><?php echo $row_rs_item_list['drug_name']; ?></span></td>
      <td align="center"><span><?php print ($row_rs_item_list['max_level']/$row_rs_item_list['pack_ratio']); ?></span></td>
      <td align="center"><span><?php print $row_rs_item_list['pack_ratio']; ?></span></td>
      <td align="center"><span><?php print $row_rs_item_list['sale_unit']; ?></span></td>
      <td align="center"><?php print $row_rs_item_list['on_demand']; ?></td>
      <td align="center"><a href="javascript:alertload1('item_add2.php?dept_group_id=<?php echo $row_rs_item_list['dept_group_id']; ?>&edit_id=<?php echo $row_rs_item_list['id']; ?>&item_type=<?php echo $_GET['item_type']; ?>','500','500')"><img src="images/gtk-edit.png" width="16" height="16" border="0" align="absmiddle" /></a> &nbsp;<a href="javascript:valid(0);" onclick=" if(confirm('ต้องการลบรายการนี้จริงหรือไม่?')==true){ page_load('item_list','item_add_list.php?action_id=<?php echo $row_rs_item_list['dept_group_id']; ?>&item_id=<?php echo $row_rs_item_list['id']; ?>&action_do=delete&item_type=<?php echo $_GET['item_type']; ?>'); }"><img src="images/delete.png" width="17" height="16" border="0" align="absmiddle" /></a></td>
    </tr>
    <?php } while ($row_rs_item_list = mysql_fetch_assoc($rs_item_list)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
</body>
</html>
<?php
mysql_free_result($rs_item_list);

//mysql_free_result($rs_stock_month);

mysql_free_result($rs_item_type);

mysql_free_result($rs_sel_dep);

?>
