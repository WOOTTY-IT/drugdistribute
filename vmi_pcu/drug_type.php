<?
ob_start();
session_start();
?>

<?php require_once('Connections/vmi.php'); ?>
<?php
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
$query_rs_drug_list = "select l.id,d.working_code,d.drug_name,t.drug_type,l.percent_cutpoint from drugs_logic l left outer join drugs d on d.working_code = l.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' left outer join drug_type t on t.id=l.drug_type where d.working_code !='' and d.item_type='".$_GET['item_type']."' and l.hospcode='".$_SESSION['hospcode']."' order by drug_type,d.drug_name ASC";
$rs_drug_list = mysql_query($query_rs_drug_list, $vmi) or die(mysql_error());
$row_rs_drug_list = mysql_fetch_assoc($rs_drug_list);
$totalRows_rs_drug_list = mysql_num_rows($rs_drug_list);

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
	margin-top:0;
    
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

    $('#t2').DataTable({
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
<script src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />



<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body >
<div id="item_type" style="padding:0px">
<div >
<ul>
<?php do{ ?>
  <li><a class="active thfont font12 font_bord <?php if($row_rs_item_type['id']==$_GET['item_type']){ echo "nav_select"; } ?>" href="javascript:page_load('item_type','drug_type.php?item_type=<?php echo $row_rs_item_type['id']; ?>');" ><?php echo $row_rs_item_type['item_type_name']; ?></a></li>
 <?php }while($row_rs_item_type = mysql_fetch_assoc($rs_item_type)); ?>
</ul>

</div>

<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
  <tr>
    <td width="15" height="32" class="gray" style="padding-left:10px"><img src="images/pill-icon.png" width="36" height="36" align="absmiddle" />&nbsp;<span class="table_head_small_bord">กำหนดประเภทของ<?php echo $row_rs_item_type2['item_type_name'];  ?>และ %ในการตัดสินใจตัดเบิก</span></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td><form  method="get" id="form1">
    <a href="javascript:alertload1('drug_type_add.php?item_type=<?php echo $_GET['item_type']; ?>','90%','90%');" id="add" name="add" class="button orange " style="padding:5px; padding-left:10px; padding-right:10px; height:30px; padding-button:5px; "><img src="images/add-drug2.png" width="30" height="30" align="absmiddle" / border="0">&nbsp; เพิ่มรายการยา</a></form></td>
  </tr>
</table>
<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"> <img src="images/indicator.gif" hspace="10" align="absmiddle" />&nbsp;</div><?php if ($totalRows_rs_drug_list > 0) { // Show if recordset not empty ?>
  <div style="padding:10px"><table width="100%" border="0" cellpadding="3" cellspacing="0" class=" thfont font12 row-border cell-border hover order-column"  id="t1">
  <thead>
    <tr class=" table_head thsan-semibold font16" >
      <td width="47" align="center">ลำดับ</td>
      <td width="463" align="center">รายการ</td>
      <td width="98" align="center">ประเภทยา</td>
      <td width="78" align="center">% ตัดจ่าย</td>
      <td width="39" align="center">&nbsp;</td>
    </tr>
    </thead>
    <?php $i=0; do { $i++; 


	?>
    <tr class="dashed table_body grid4">
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $i; ?></td>
      <td align="left" bgcolor="<?php echo $bg; ?>"><?php print $row_rs_drug_list['drug_name']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php print $row_rs_drug_list['drug_type']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php print $row_rs_drug_list['percent_cutpoint']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><img src="images/edit.png" width="28" height="28" style="cursor:pointer" onclick="alertload1('drug_type_add.php?eid=<?php echo $row_rs_drug_list['id']; ?>&working_code=<?php echo $row_rs_drug_list['working_code']; ?>&item_type=<?php echo $_GET['item_type']; ?>','90%','90%');" /></td>
    </tr>      
    <?php } while ($row_rs_drug_list = mysql_fetch_assoc($rs_drug_list)); ?>
    
  </table></div>
  <?php } // Show if recordset not empty ?>

</div>
</body>
</html>
<?php
mysql_free_result($rs_drug_list);

mysql_free_result($rs_item_type);

mysql_free_result($rs_item_type2);

?>