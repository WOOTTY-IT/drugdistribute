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
$query_rs_dept = "select * from dept_group where hospmain='".$_SESSION['hospcode']."' order by dept_group_name ASC";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
//$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);

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
	
if($_SESSION['user_item_type']==""){
if(!isset($_GET['item_type'])){
	$_GET['item_type']=1;
	}
}
else{
if(!isset($_GET['item_type'])){
	$_GET['item_type']=$_SESSION['user_item_type'];
	}	
$cond_item_type=" where id='".$_SESSION['user_item_type']."'";
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
$query_rs_item_type = "select * from item_type ".$cond_item_type;
$rs_item_type = mysql_query($query_rs_item_type, $vmi) or die(mysql_error());
$row_rs_item_type = mysql_fetch_assoc($rs_item_type);
$totalRows_rs_item_type = mysql_num_rows($rs_item_type);

mysql_select_db($database_vmi, $vmi);
$query_rs_item_type2 = "select * from item_type where id='".$_GET['item_type']."'";
$rs_item_type2 = mysql_query($query_rs_item_type2, $vmi) or die(mysql_error());
$row_rs_item_type2 = mysql_fetch_assoc($rs_item_type2);
$totalRows_rs_item_type2 = mysql_num_rows($rs_item_type2);

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
table#t1 thead tr td { border-style:solid;
    border-width:thin; border-color:#CCC }
table#t1{ border-collapse:collapse;}

input {height: 25px;
  padding-left:5px;
  border-radius: 3px;
  border: 1px solid transparent;
  border-top: none;
  border-bottom: 1px solid #DDD;
  box-shadow: inset 0 1px 2px rgba(0,0,0,.39), 0 -1px 1px #FFF, 0 1px 0 #FFF;
  margin-bottom:10px;
}
select {height: 25px;
  padding-left:5px;
  border-radius: 3px;
  border: 1px solid transparent;
  border-top: none;
  border-bottom: 1px solid #DDD;
  box-shadow: inset 0 1px 2px rgba(0,0,0,.39), 0 -1px 1px #FFF, 0 1px 0 #FFF;
  margin-bottom:10px;
}

</style>
<script src="include/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="include/jquery.dataTables.js"></script>
<script>
$(document).ready( function () {
    $('#t1').DataTable({
	"paging":   true,
    "ordering": true,
    "info":     true,
	"order": [[ 0, "asc" ]],
	stateSave: true,
	"scrollX": false,
	"language": {
            "decimal": ",",
            "thousands": "."
        },
	 "lengthMenu": [[10, 20,30, 50,100, -1], [10, 20,30, 50,100, "All"]]
	});


} );
</script>

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
<script src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />

</head>

<body>
<div id="item_list">
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
  <tr>
    <td height="32" class="gray" style="padding-left:10px" colspan="2"><img src="images/add-drug.png" width="32" height="32" align="absmiddle" />&nbsp; <span class="table_head_small_bord">กำหนดรายการ<?php echo $row_rs_item_type2['item_type_name']; ?>ของแต่ละกลุ่มหน่วยงาน</span></td>
  </tr>
  <tr style="height:25px">
    <td align="left" valign="middle" class=" bargreen" style=" text-align:left;padding-left:10px; height:30px" >เลือกกลุ่มหน่วยงาน</td>
  </tr>
  <tr style="height:25px">
    <td  >            <table  width="1000" border="0" cellspacing="5"  style="border-collapse:collapse;" class="table_collapse">
        <?php echo "<tr>"; $intRows = 0;
		while ($row_rs_dept = mysql_fetch_assoc($rs_dept)){
			$intRows++;
			//echo "<td>";
	   ?>

            <td align="center" class="<?php if($row_rs_dept['dept_group_id']==$_GET['action_id']){ echo "red"; }else{ echo "gray"; } ?>" height="30px" width="200px" style="border:0px; border-right:1px solid #CCC; padding:5px; cursor:pointer;"  onclick="changbgcolor(this.id,'gray');page_load('item_list','item_add.php?action_id=<?php echo $row_rs_dept['dept_group_id']; ?>');" id="<?php echo $row_rs_dept['dept_group_id']; ?>"><a href="javascript:valid(0);" class="table_head_small_bord link_red" style="<?php if($row_rs_dept['dept_group_id']==$_GET['action_id']){ echo "color:white;"; }else{ echo "color:black;"; } ?>"><?php echo $row_rs_dept['dept_group_name']; ?></a>
<?php
		echo"</td>";
		if(($intRows)%6==0)
		{
		echo"</tr>";
		}
		else
		{
		echo "<td>";
		}	
	} ?>
          </tr>
    </table>       
  </tr>
</table>
<?php if(isset($_GET['action_id'])&&($_GET['action_id']!="")){ ?>
<form id="form1" name="form1" method="post" action="">
  <div id="item_add"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="table_head_small">
    <tr>
      <td style="padding-left:10px"></td>
    </tr>
    <tr>
      <td style="padding-left:10px"><div >


<ul>
<?php do{ ?>
  <li><a class="active <?php if($row_rs_item_type['id']==$_GET['item_type']){ echo "nav_select"; } ?>" href="javascript:page_load('item_list','item_add.php?item_type=<?php echo $row_rs_item_type['id']; ?>&action_id=<?php echo $row_rs_sel_dep['dept_group_id']; ?>');" ><?php echo $row_rs_item_type['item_type_name']; ?></a></li>
 <?php }while($row_rs_item_type = mysql_fetch_assoc($rs_item_type)); ?>
</ul>

</div>
<div style="margin-top:10px;">
<a href="javascript:alertload1('item_add2.php?dept_group_id=<?php echo $_GET['action_id']; ?>&item_type=<?php echo $_GET['item_type']; ?>','500','500');" id="add" name="add" class=" orange " style="padding:3px; padding-left:10px; position:absolute; text-decoration:none; right:4px; margin-top:-53px; padding-right:10px; height:34.5px; padding-button:5px; "><img src="images/add-drug2.png" width="30" height="30" align="absmiddle" / border="0">&nbsp; เพิ่มรายการยา</a>
<?php if ($totalRows_rs_item_list > 0) { // Show if recordset not empty ?>

  <table width="100%" border="0" cellpadding="3" cellspacing="0" class=" thfont font12 row-border cell-border hover order-column"  id="t1">
  <thead>
    <tr class="table_head_small_bord">
      <td width="38" align="center">ลำดับ</td>
      <td width="400" align="left">รายการยา</td>
      <td width="115" align="center">จำนวนเบิกได้สูงสุด</td>
      <td width="74" align="center">ขนาดบรรจุ</td>
      <td width="84" align="center">หน่วย</td>
      <td width="53" align="center">on demand</td>
      <td width="53" align="center">แก้ไข</td>
    </tr>
    </thead>
    <?php $i=0; do { $i++; ?>
    <tr>
      <td align="center"><?php echo $i; ?></td>
      <td align="left"><span><?php echo $row_rs_item_list['drug_name']; ?></span></td>
      <td align="center"><span><?php print ($row_rs_item_list['max_level']/$row_rs_item_list['pack_ratio']); ?></span></td>
      <td align="center"><span><?php print $row_rs_item_list['pack_ratio']; ?></span></td>
      <td align="center"><span><?php print $row_rs_item_list['sale_unit']; ?></span></td>
      <td align="center"><?php print $row_rs_item_list['on_demand']; ?></td>
      <td align="center"><a href="javascript:alertload1('item_add2.php?dept_group_id=<?php echo $row_rs_item_list['dept_group_id']; ?>&edit_id=<?php echo $row_rs_item_list['id']; ?>&item_type=<?php echo $_GET['item_type']; ?>','500','500')"><img src="images/gtk-edit.png" width="16" height="16" border="0" align="absmiddle" /></a> &nbsp;<a href="javascript:valid(0);" onclick=" if(confirm('ต้องการลบรายการนี้จริงหรือไม่?')==true){ page_load('item_list','item_add.php?action_id=<?php echo $row_rs_item_list['dept_group_id']; ?>&item_id=<?php echo $row_rs_item_list['id']; ?>&action_do=delete&item_type=<?php echo $_GET['item_type']; ?>'); }"><img src="images/delete.png" width="17" height="16" border="0" align="absmiddle" /></a></td>
    </tr>
    <?php } while ($row_rs_item_list = mysql_fetch_assoc($rs_item_list)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
<div id="indicator"  align="center" style="position:fixed; top:500px; left:48%; display:none; z-index:1000;padding:0px;"> <img src="images/indicator.gif" hspace="10" align="absmiddle" />&nbsp;</div>
</td>
      </tr>
    <tr>
      <td style="padding-left:10px"></td>
    </tr>
    <tr>
      <td style="padding-left:10px">

</td>
    </tr>
  </table>
  </div>
</form>
<?php } ?>
</div>
</body>
</html>
<?php
mysql_free_result($rs_dept);

mysql_free_result($rs_item_list);

//mysql_free_result($rs_stock_month);

mysql_free_result($rs_item_type);

mysql_free_result($rs_item_type2);

mysql_free_result($rs_sel_dep);

?>
