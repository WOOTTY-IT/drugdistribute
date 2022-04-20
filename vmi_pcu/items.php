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

if(!isset($_GET['item_type'])){
	$_GET['item_type']=1;
	}
mysql_select_db($database_vmi, $vmi);
$query_rs_tiem = "select * from drugs where item_type='".$_GET['item_type']."' and hospcode='".$_SESSION['hospcode']."' order by drug_name ASC";
$rs_tiem = mysql_query($query_rs_tiem, $vmi) or die(mysql_error());
$row_rs_tiem = mysql_fetch_assoc($rs_tiem);
$totalRows_rs_tiem = mysql_num_rows($rs_tiem);

ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
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

body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
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

</style>
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
<script src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />

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

function alertload1(url,w,h,str,queue){
	 $.colorbox({width:w,height:h, iframe:true, href:url,	onOpen : function () {$('body, html').css('overflowY','hidden');},onCleanup :function(){
$('body, html').css('overflowY','auto');}
});

	}			

</script>

</head>

<body >
<div id="itemdiv" >
<div>
<ul>
<?php do{ ?>
  <li><a class="active thfont font12 font_bord <?php if($row_rs_item_type['id']==$_GET['item_type']){ echo "nav_select"; } ?>" href="javascript:valid(0);" onclick="page_load('itemdiv','items.php?item_type=<?php echo $row_rs_item_type['id']; ?>');" ><?php echo $row_rs_item_type['item_type_name']; ?></a></li>
 <?php }while($row_rs_item_type = mysql_fetch_assoc($rs_item_type)); ?>
</ul>

</div>
<div style="height:30px; padding:10px" class="thsan-semibold font20 gray">ทะเบียนรายการ<?php echo $row_rs_item_type2['item_type_name']; ?></div>


<form id="form1" name="form1" method="post" action="" class="thfont font12" style="margin-top:10px; padding-left:10px; margin-bottom:10px;">
  <a href="javascript:alertload1('items_add.php?action=add','800','600');" id="add" name="add" class="button orange " style="padding:5px; padding-left:10px; padding-right:10px; height:30px; padding-button:5px; "><img src="images/add-drug2.png" width="30" height="30" align="absmiddle" / border="0">&nbsp; เพิ่มรายการยา</a>
</form>
<div id="indicator"  align="center" style="position:fixed; top:500px; left:48%; display:none; z-index:1000;padding:0px;"> <img src="images/indicator.gif" hspace="10" align="absmiddle" />&nbsp;</div>
<div style="margin:10px;">
  <?php if ($totalRows_rs_tiem > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="3" class=" thfont font12 row-border cell-border hover order-column"  id="t1">
  <thead>
    <tr >
      <td align="center">#</td>
      <td align="center">working_code</td>
      <td align="center">รายการ</td>
      <td align="center">ขนาดบรรจุ</td>
      <td align="center">หน่วย</td>
      <td align="center">สถานะ</td>
      <td align="center">ราคา/หน่วย</td>
    </tr>
    </thead>
    <?php $i=1; ?>
    <?php do { ?>
      <tr class="table_body grid" style="cursor:pointer" onclick="alertload1('items_add.php?action=edit&working_code=<?php echo $row_rs_tiem['working_code'] ?>','800','600')">
        <td align="center"><?php echo $i; ?></td>
        <td align="center"><?php echo $row_rs_tiem['working_code']; ?></td>
        <td align="left"><?php echo $row_rs_tiem['drug_name']; ?></td>
        <td align="center"><?php echo $row_rs_tiem['pack_ratio']; ?></td>
        <td align="center"><?php echo $row_rs_tiem['sale_unit']; ?></td>
        <td align="center"><?php echo $row_rs_tiem['istatus']; ?></td>
        <td align="center"><?php echo $row_rs_tiem['buy_unit_cost']; ?></td>
      </tr>
      <?php $i++; ?>
      <?php } while ($row_rs_tiem = mysql_fetch_assoc($rs_tiem)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_item_type);

mysql_free_result($rs_item_type2);

mysql_free_result($rs_tiem);
?>
