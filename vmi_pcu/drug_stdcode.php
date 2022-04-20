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

mysql_select_db($database_vmi, $vmi);
$query_rs_stdcode = "select d.working_code,d.drug_name,d.pack_ratio,d.sale_unit,s.stdcode from drugs d left outer join drug_stdcode s on s.working_code=d.working_code and d.hospcode=s.hospcode where d.hospcode='".$_SESSION['hospcode']."' and d.item_type='".$_GET['item_type']."' order by d.drug_name";
$rs_stdcode = mysql_query($query_rs_stdcode, $vmi) or die(mysql_error());
$row_rs_stdcode = mysql_fetch_assoc($rs_stdcode);
$totalRows_rs_stdcode = mysql_num_rows($rs_stdcode);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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

<style type="text/css">
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

<script src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />

<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body >
<div id="stdcode_list">
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small table_collapse">
  <tr>
    <td class="gray"><img src="images/add-drug.png" width="32" height="32" align="absmiddle" />&nbsp;<span class="table_head_small_bord"> กำหนดรหัสมาตรฐาน<?php echo $row_rs_item_type2['item_type_name']; ?></span></td>
  </tr>
</table>
<div >
<ul>
<?php do{ ?>
  <li><a class="active thfont font12 font_bord <?php if($row_rs_item_type['id']==$_GET['item_type']){ echo "nav_select"; } ?>" href="javascript:valid(0);" onclick="page_load('stdcode_list','drug_stdcode.php?item_type=<?php echo $row_rs_item_type['id']; ?>');" ><?php echo $row_rs_item_type['item_type_name']; ?></a></li>
 <?php }while($row_rs_item_type = mysql_fetch_assoc($rs_item_type)); ?>
</ul>

</div>
<form id="form1" name="form1" method="post" action="">
  <input type="hidden" name="id" id="id" />
  <input type="hidden" name="do" id="do" />
</form>
<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div>
<div style="padding:10px;">
  <?php if ($totalRows_rs_stdcode > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellpadding="3" cellspacing="0" class=" thfont font12 row-border cell-border hover order-column"  id="t1">
  <thead>
    <tr>
      <td width="34" align="center" class="table_head_small_bord">ลำดับ</td>
      <td width="97" align="center" class="table_head_small_bord">working_code</td>
      <td width="260" align="center" class="table_head_small_bord">รายการยา</td>
      <td width="77" align="center" class="table_head_small_bord">ขนาดบรรจุ</td>
      <td width="56" align="center" class="table_head_small_bord">หน่วย</td>
      <td width="308" align="center" class="table_head_small_bord">รหัสมาตรฐาน</td>
      <td width="26" align="center">&nbsp;</td>
    </tr>
    </thead>
    <?php $i=0; do { $i++; ?>
    <tr class="grid">
      <td align="center"><?php echo $i; ?></td>
      <td align="center"><?php print $row_rs_stdcode['working_code']; ?></td>
      <td align="left" style="padding-left:10px"><?php echo $row_rs_stdcode['drug_name']; ?></td>
      <td align="center"><?php print $row_rs_stdcode['pack_ratio']; ?></td>
      <td align="center"><?php print $row_rs_stdcode['sale_unit']; ?></td>
      <td align="center"><?php echo $row_rs_stdcode['stdcode']; ?></td>
      <td align="center"><a href="javascript:alertload1('drug_stdcode_edit.php?working_code=<?php echo $row_rs_stdcode['working_code']; ?>&item_type=<?php echo $_GET['item_type']; ?>','500','400');"><img src="images/config.png" width="24" height="24" border="0" /></a></td>
    </tr>      <?php } while ($row_rs_stdcode = mysql_fetch_assoc($rs_stdcode)); ?>
    
  </table>
  <?php } // Show if recordset not empty ?>
</div></div>
</body>
</html>
<?php
mysql_free_result($rs_stdcode);
mysql_free_result($rs_item_type);
mysql_free_result($rs_item_type2);

?>
