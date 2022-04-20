<?php require_once('Connections/vmi.php'); ?>
<?
ob_start();
session_start();
?>
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
$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

mysql_select_db($database_vmi, $vmi);
$query_rs_drug_confirm = "select * from drug_take where SUBSTR(drug_take_date,1,7)=SUBSTR(CURDATE(),1,7) and hospcode='".$_SESSION['hospcode']."' limit 1";
$rs_drug_confirm = mysql_query($query_rs_drug_confirm, $vmi) or die(mysql_error());
$row_rs_drug_confirm = mysql_fetch_assoc($rs_drug_confirm);
$totalRows_rs_drug_confirm = mysql_num_rows($rs_drug_confirm);

mysql_select_db($database_vmi, $vmi);
$query_rs_item_type = "select * from item_type";
$rs_item_type = mysql_query($query_rs_item_type, $vmi) or die(mysql_error());
$row_rs_item_type = mysql_fetch_assoc($rs_item_type);
$totalRows_rs_item_type = mysql_num_rows($rs_item_type);

mysql_select_db($database_vmi, $vmi);
$query_rs_item_type2 = "select * from item_type where id='".$_GET['item_type']."'";
$rs_item_type2 = mysql_query($query_rs_item_type2, $vmi) or die(mysql_error());
$row_rs_item_type2 = mysql_fetch_assoc($rs_item_type2);
$totalRows_rs_item_type2 = mysql_num_rows($rs_item_type2);

if(isset($_GET['item_type'])&&($_GET['item_type']!="")){
	$item_type=$_GET['item_type'];
	}
else {
	$item_type=1;
	}

if(isset($_GET['del_id'])&&($_GET['del_id']!="")){
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from drug_take where id='".$_GET['del_id']."'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
}

mysql_select_db($database_vmi, $vmi);
$query_rs_drug_list = "SELECT d.*,dd.drug_name,dd.pack_ratio,dd.buy_unit_cost,dd.sale_unit from drug_take d left outer join drugs dd on dd.working_code=d.working_code and dd.chwpart='".$_SESSION['chwpart']."' and dd.amppart='".$_SESSION['amppart']."'  where d.hospcode='".$_SESSION['hospcode']."' and SUBSTR(d.drug_take_date,1,7)=SUBSTR(CURDATE(),1,7) and item_type='".$item_type."' order by dd.drug_name ASC";
$rs_drug_list = mysql_query($query_rs_drug_list, $vmi) or die(mysql_error());
$row_rs_drug_list = mysql_fetch_assoc($rs_drug_list);
$totalRows_rs_drug_list = mysql_num_rows($rs_drug_list);

?>
<?php include('include/function.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>
function poppage(url,w,h,id,do_it,url2,div,form){
	///
	$.colorbox({width:w,height:h, iframe:true, href:url,onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){dosubmit(id,do_it,url2,div,form);}
	});
	////
	}

</script>
<script src="include/jquery.js"></script>
<script src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" href="styles/font-awesome.min.css">
<script type="text/javascript" src="include/metro/vendor/bootstrap.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />

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
<script>
$(document).ready(function(){
var table=$('#table').DataTable( {
        paging:         false    } );
} );</script>

</head>

<body>
<div id="drug_take">
<ul>
<?php do{ ?>
  <li><a class="active <?php if($row_rs_item_type['id']==$item_type){ echo "nav_select"; } ?>" href="javascript:page_load('drug_take','drug_take2.php?item_type=<?php echo $row_rs_item_type['id']; ?>');" style="text-decoration:none;"  ><?php echo $row_rs_item_type['item_type_name']; ?></a></li>
 <?php }while($row_rs_item_type = mysql_fetch_assoc($rs_item_type)); ?>
</ul>
<form method="post" id="form1" name="form1">
<div style="height:60px; padding:10px; background-color: #CCC;"> เบิก<?php echo $row_rs_item_type2['item_type_name']; ?> on demand ประจำเดือน<?php echo monthThai(date('Y-m')); ?> <span style="padding:10px; padding-left:20px;padding-top:20px">
      <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($row_rs_drug_confirm['confirm']==NULL)){ ?>
      <a href="javascript:poppage('drug_take_add.php?item_type=<?php echo $item_type; ?>','500','500','','show','drug_take2.php?item_type=<?php echo $item_type; ?>','drug_take','');" class="thsan-light btn btn-danger" ><img src="images/add-drug2.png" width="25" height="25" border="0" align="absmiddle" /> เพิ่มรายการยา</a>
      <?php } ?>
    </span></div>
<table  border="0" width="100%" cellspacing="0" cellpadding="0" >
  <tr>
    <td style="padding:10px; padding-left:20px;padding-top:20px"><?php if ($totalRows_rs_drug_list > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellpadding="3" cellspacing="0" id="table" class="table table-striped table-bordered">
	<thead>
    <tr>
      <th width="5%" align="center">ลำดับ</th>
      <th width="42%" align="left" style="padding-left:10px">รายการยา</th>
      <th width="9%" align="center">จำนวนเบิก</th>
      <th width="9%" align="center">ขนาดบรรจุ</th>
      <th width="10%" align="center">หน่วย</th>
      <th width="10%" align="center">ราคา/หน่วย</th>
      <th width="8%" align="center">ราคารวม</th>
     <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($row_rs_drug_list['confirm']==NULL)){ echo "<td width=\"7%\" align=\"center\">&nbsp;</td>"; } ?>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; do { $i++; 
	$sumprice+=($row_rs_drug_list['buy_unit_cost']*($row_rs_drug_list['among']/$row_rs_drug_list['pack_ratio']));
	?>
      <tr class="dashed">
        <td align="center"><?php echo $i; ?></td>
        <td align="left" style="padding-left:10px"><span><?php print $row_rs_drug_list['drug_name']; ?></span></td>
        <td align="center"><span style="padding-left:10px"><?php print ($row_rs_drug_list['among']/$row_rs_drug_list['pack_ratio']); ?></span></td>
        <td align="center"><span style="padding-left:10px"><?php print $row_rs_drug_list['pack_ratio']; ?></span></td>
        <td align="center"><span style="padding-left:10px"><?php print $row_rs_drug_list['sale_unit']; ?></span></td>
        <td align="center"><span style="padding-left:10px"><?php print $row_rs_drug_list['buy_unit_cost']; ?></span></td>
        <td align="center"><span style="padding-left:10px"><?php print ($row_rs_drug_list['buy_unit_cost']*($row_rs_drug_list['among']/$row_rs_drug_list['pack_ratio'])); ?></span></td>
        <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($row_rs_drug_list['confirm']==NULL)){ ?><td align="center"><i class="fas fa-edit" onclick="poppage('drug_take_add.php?id=<?php echo $row_rs_drug_list['id']; ?>&do=edit','500','500','','show','drug_take2.php?item_type=<?php echo $item_type; ?>','drug_take','');" style="cursor:pointer;"></i>&nbsp;<i onclick="javascript:if(confirm('ต้องการลบรายการนี้หรือไม่')==true){page_load('drug_take','drug_take2.php?item_type=<?php echo $item_type; ?>&del_id=<?php echo $row_rs_drug_list['id']; ?>');}" style="cursor:pointer;" class="fas fa-trash-alt"></i></td><?php } ?>
      </tr>
      <?php } while ($row_rs_drug_list = mysql_fetch_assoc($rs_drug_list)); ?>
          
    </tbody>
      <tfoot>
      <tr>
        <td colspan="7" align="center" ><span class="big_black16">ราคารวม</span> <span class=" big_red16"><?php echo number_format($sumprice,2); ?></span><span class="big_black16">บาท&nbsp;<a href="javascript:window.open('drug_take_roll_print.php?deptcode=<?php echo $_SESSION['hospcode']; ?>&month=<?php echo date('Y-m'); ?>&item_type=<?php if(!isset($_GET['item_type'])||($_GET['item_type']=="")){ echo 1; } else{ echo $_GET['item_type']; } ?>','_new');" class="btn btn-primary"><i class="fas fa-print" ></i>&nbsp;พิมพ์ใบเบิก</a></span></td>
         <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($row_rs_drug_list['confirm']==NULL)){ ?><td align="center">&nbsp;</td><?php } ?>
      </tr>  
      </tfoot>
  </table>
  <?php } // Show if recordset not empty ?>
</td>
  </tr>
  <tr>
    <td style="padding-left:20px"><div id="drug_take_list"></div></td>
  </tr>
</table>
</form>
</div>

</body>
</html>
<?php
mysql_free_result($rs_drug_list);

mysql_free_result($rs_item_type);

mysql_free_result($rs_item_type2);

mysql_free_result($rs_setting);

mysql_free_result($rs_drug_confirm);
?>
