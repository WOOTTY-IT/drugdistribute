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
if(isset($_POST['snapshot'])&&($_POST['snapshot']=="Snapshot")){
mysql_select_db($database_vmi, $vmi);
$query_rs_search = "select snapshot_date from stock_remain_snapshot where snapshot_date=CURDATE() limit 1";
$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

if($totalRows_rs_search<>0){
		//ลบอันเดิมออกก่อน
		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_remain_snapshot  where hospcode='".$_SESSION['hospcode']."' and snapshot_date=CURDATE()";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());		
	}
		//บันทึกลงไปใหม่
		mysql_select_db($database_vmi, $vmi);
		$query_insert = "insert into stock_remain_snapshot (snapshot_date,hospcode,working_code,remain_qty,pack_ratio,total_cost) select CURDATE(),'".$_SESSION['hospcode']."',s.working_code,sum(s.remain_qty),s.pack_ratio,sum(s.total_cost) from stock_drugs ds left outer join stock_drugs_c s on s.working_code=ds.working_code and s.hospcode=ds.hospcode left outer join drugs d on d.working_code=s.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' where s.hospcode='".$_SESSION['hospcode']."' GROUP BY s.working_code,s.pack_ratio order by d.drug_name ASC";
		$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	

}
mysql_select_db($database_vmi, $vmi);
$query_rs_hosptype = "select hosptype,hospcode from hospcode where hospcode='".$_SESSION['hospcode']."'";
$rs_hosptype = mysql_query($query_rs_hosptype, $vmi) or die(mysql_error());
$row_rs_hosptype = mysql_fetch_assoc($rs_hosptype);
$totalRows_rs_hosptype = mysql_num_rows($rs_hosptype);

$type_check=array('4');
$type_check2=array('6');
$type_check3=array('1','2','3');
$type_check4=array('5');

// สสจ / รพศ รพท
if (in_array($_SESSION['hospcode_type_id'], $type_check3, true)) {
$condition=" chwpart='".$_SESSION['chwpart']."'";
}
// รพช
if (in_array($_SESSION['hospcode_type_id'], $type_check4, true)) {
$condition=" chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' ";
}

// สสอ
if (in_array($_SESSION['hospcode_type_id'], $type_check, true)) {
$condition=" chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' and u.hospcode_type_id in ('4','6')";
}
// รพ.สต.
if (in_array($_SESSION['hospcode_type_id'], $type_check2, true)) {
$condition="chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' and tmbpart='".$_SESSION['tmbpart']."' and h.hospcode='".$_SESSION['hospcode']."' ";
}

mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode = "select h.*,concat(h.hosptype,' ',h.name) as hospname from dept_list d left outer join hospcode h on h.hospcode=d.dept_code left outer join user u on u.hospcode=d.dept_code where ".$condition." order by h.hosptype,h.name ASC ";
$rs_hospcode = mysql_query($query_rs_hospcode, $vmi) or die(mysql_error());
$row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
$totalRows_rs_hospcode = mysql_num_rows($rs_hospcode);

////////////////////////////////////////
if(isset($_POST['search'])&&($_POST['search']=="ค้นหา")&&($_POST['snapshot_date']!="")){
mysql_select_db($database_vmi, $vmi);
$query_rs_stock = "select d.drug_name,sum(s.remain_qty/s.pack_ratio) as sumqty,s.pack_ratio,d.sale_unit,sum(s.total_cost) as sumcost from stock_drugs ds left outer join stock_remain_snapshot s on s.working_code=ds.working_code and s.hospcode=ds.hospcode left outer join drugs d on d.working_code=s.working_code where s.hospcode='".$_SESSION['hospcode']."' and s.snapshot_date='".$_POST['snapshot_date']."' group by s.working_code,s.pack_ratio order by d.drug_name,s.pack_ratio ASC";

$rs_stock = mysql_query($query_rs_stock, $vmi) or die(mysql_error());
$row_rs_stock = mysql_fetch_assoc($rs_stock);
$totalRows_rs_stock = mysql_num_rows($rs_stock);
}
else if((!isset($_POST['search'])&&($_POST['search']!="ค้นหา"))||(isset($_POST['search'])&&($_POST['search']=="ค้นหา")&&($_POST['snapshot_date']==""))){
	if(isset($_GET['dept_id'])&&($_GET['dept_id']!="")){
	$condition2=" and s.hospcode='".$_GET['dept_id']."'";	
	$condition3=",ds.hospcode ";
	}
	if(!isset($_GET['dept_id'])){
	$condition2=" and s.hospcode='".$_SESSION['hospcode']."'";			
	$condition3=",ds.hospcode ";

	}
	if(isset($_GET['dept_id'])&&($_GET['dept_id']=="")){
	$condition2=" and ".$condition;
	$condition3="";
	}
mysql_select_db($database_vmi, $vmi);
$query_rs_stock = "select d.drug_name,sum(s.remain_qty/s.pack_ratio) as sumqty,s.pack_ratio,d.sale_unit,sum(s.total_cost) as sumcost,ds.working_code".$condition3." from stock_drugs ds left outer join stock_drugs_c s on s.working_code=ds.working_code and s.hospcode=ds.hospcode left outer join drugs d on d.working_code=s.working_code left outer join hospcode h on h.hospcode=ds.hospcode where 1 ".$condition2." GROUP BY s.working_code,s.pack_ratio order by d.drug_name,s.pack_ratio ASC";
$rs_stock = mysql_query($query_rs_stock, $vmi) or die(mysql_error());
$row_rs_stock = mysql_fetch_assoc($rs_stock);
$totalRows_rs_stock = mysql_num_rows($rs_stock);
}

mysql_select_db($database_vmi, $vmi);
$query_rs_snap = "select * from stock_remain_snapshot where hospcode='".$_SESSION['hospcode']."' group by snapshot_date";
$rs_snap = mysql_query($query_rs_snap, $vmi) or die(mysql_error());
$row_rs_snap = mysql_fetch_assoc($rs_snap);
$totalRows_rs_snap = mysql_num_rows($rs_snap);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
div.divhead{
	
	position:fixed;
	top:0;
	width:100%;
	font-size:23px;
	padding:10px;
	border-bottom:solid 1px #CCCCCC;
	background-color:#ededed;
	}
div.divcontent{
	margin-top:60px;
	padding:10px;
	width:98%;	
	}
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
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

<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('.tbdiv').hide();
});
function divhide(a){
	$('.tbdiv').hide();
	$('#'+a+'1').show();
	$('#'+a+'1').load("stock_remain_indiv.php?working_code="+a);
	}
</script>

</head>

<body>
<div class="thsan-light divhead "><img src="images/icon-3-deals.png" width="39" height="39" align="absmiddle"> &nbsp;&nbsp;คงคลัง ( Stock Remain)</div>
<div class="divcontent thsan-light font18" >
  <form name="form1" method="post" action=""><table width="600" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="148">เลือกหน่วยงาน</td>
    <td width="432"><label for="dept"></label>
      <select name="dept" id="dept" class="thsan-light font16 inputcss1" onChange="window.location.href='stock_remain.php?dept_id='+this.value">
		<?php if($totalRows_rs_hospcode>1){?>
		<option value=""<?php if(!isset($_GET['dept_id'])){ if (!(strcmp("", $_POST['dept']))) {echo "selected=\"selected\"";}} else {if (!(strcmp("", $_GET['dept_id']))) {echo "selected=\"selected\"";}} ?>>=== ทั้งหมด ===</option>
        <?php
		}
do {  
?>
        <option value="<?php echo $row_rs_hospcode['hospcode']?>"<?php if(!isset($_GET['dept_id'])){ if (!(strcmp($row_rs_hospcode['hospcode'], $_SESSION['hospcode']))) {echo "selected=\"selected\"";}} else {if (!(strcmp($row_rs_hospcode['hospcode'], $_GET['dept_id']))) {echo "selected=\"selected\"";}} ?>><?php echo $row_rs_hospcode['hospname']?></option>
        <?php
} while ($row_rs_hospcode = mysql_fetch_assoc($rs_hospcode));
  $rows = mysql_num_rows($rs_hospcode);
  if($rows > 0) {
      mysql_data_seek($rs_hospcode, 0);
	  $row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
  }
?>
      </select></td>
  </tr>
  <?php if(!isset($_GET['dept_id'])||isset($_GET['dept_id'])&&$_SESSION['hospcode']==$_GET['dept_id']){?>
  <tr>
    <td>Snapshot data</td>
    <td><label for="snapshot_date"></label>
      <select name="snapshot_date" id="snapshot_date" class="thsan-light font16 inputcss1">
        <option value="" <?php if (!(strcmp("", $_POST['snapshot_date']))) {echo "selected=\"selected\"";} ?>>ปัจจุบัน</option>
        <?php
do {  
	if($totalRows_rs_snap<>0){
?>
        <option value="<?php echo $row_rs_snap['snapshot_date']?>"<?php if (!(strcmp($row_rs_snap['snapshot_date'], $_POST['snapshot_date']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_snap['snapshot_date']?></option>
        <?php
	}
} while ($row_rs_snap = mysql_fetch_assoc($rs_snap));
  $rows = mysql_num_rows($rs_snap);
  if($rows > 0) {
      mysql_data_seek($rs_snap, 0);
	  $row_rs_snap = mysql_fetch_assoc($rs_snap);
  }
?>
      </select></td>
  </tr>
<?php } ?>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="search" id="search" value="ค้นหา" class="thsan-semibold font19" style="padding-top:2px; padding-bottom:3px; text-align:center; padding-left:30px;  background-image: url(images/ic_search_48px-128.png); background-repeat:no-repeat; background-size:20px 20px; background-position:5px 3px; padding-right:10px; background-color: #5CA3C9; border:1px solid  #06C; text-decoration:none; color:#FFF; height:30px">  <?php if(!isset($_GET['dept_id'])||isset($_GET['dept_id'])&&$_SESSION['hospcode']==$_GET['dept_id']){?><input type="submit" name="snapshot" id="snapshot" value="Snapshot" class="thsan-semibold font19" style="padding-top:2px; padding-bottom:3px; margin-left:5px; text-align:center; padding-left:30px; padding-right:10px; background-color: #999; background-image: url(images/screenshot1235.png); background-repeat:no-repeat; background-size:20px 20px; background-position:5px 3px; border:1px solid #666; text-decoration:none; color:#FFF; height:30px"><?php } ?></td>
  </tr>
  </table>


  </form>
  <?php if ($totalRows_rs_stock > 0) { // Show if recordset not empty ?>
<div style="margin-top:20px">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr class="table_head thsan-bold font20">
    <td width="6%" align="center">ลำดับ</td>
    <td width="40%" align="left">รายการ</td>
    <td width="15%" align="center">จำนวน</td>
    <td width="14%" align="center">ขนาดบรรจุ</td>
    <td width="11%" align="center">หน่วยบรรจุ</td>
    <td width="14%" align="center">มูลค่า</td>
  </tr>
<?php 
$sumprice=0;
$i=0; do { $i++; 
 	if($bg == "#F9F9F9") { //ส่วนของการ สลับสี 
$bg = "#ededed";
} else {
$bg = "#F9F9F9";
} 
  ?>
  <tr class="table_body table_body1 thsan-light font18 grid" <?php if(isset($_GET['dept_id'])&&($_GET['dept_id']=="")&&$row_rs_stock['sumqty']!=0){?>onClick="OpenPopup('stock_remain_indiv.php','800','600','&working_code=<?php echo $row_rs_stock['working_code']; ?>');"<?php } ?><?php if($row_rs_stock['hospcode']!=""&&$row_rs_stock['sumqty']!=0){?>onClick="OpenPopup('stock_remain_detail.php','800','600','&working_code=<?php echo $row_rs_stock['working_code']; ?>&hospcode1=<?php echo $row_rs_stock['hospcode']; ?>');"<?php } ?>>
    <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $i; ?></td>
    <td align="left" bgcolor="<?php echo $bg; ?>"><?php print $row_rs_stock['drug_name']; ?></td>
    <td align="center" bgcolor="<?php echo $bg; ?>"><?php print number_format($row_rs_stock['sumqty']); ?></td>
    <td align="center" bgcolor="<?php echo $bg; ?>"><?php print $row_rs_stock['pack_ratio']; ?></td>
    <td align="center" bgcolor="<?php echo $bg; ?>"><?php print $row_rs_stock['sale_unit']; ?></td>
    <td align="center" bgcolor="<?php echo $bg; ?>"><?php print str_replace('.00','',$row_rs_stock['sumcost']); ?></td>
  </tr>
  <tr id="<?php echo $row_rs_stock['working_code'].$row_rs_stock['pack_ratio']."1"; ?>" class="tbdiv">
    <td colspan="6" align="left" ><div id="<?php echo $row_rs_stock['working_code'].$row_rs_stock['pack_ratio']; ?>"></div></td>
  </tr>
  <?php $sumprice+=$row_rs_stock['sumcost']; } while ($row_rs_stock = mysql_fetch_assoc($rs_stock)); ?>
  <tr><td colspan="4" style="border-top:double 2px #000000"></td>
  <td align="center" class="font20 font_border" style="border-top:double 2px #000000">รวม</td>
  <td align="center" class="font20 font_border" style="color:#F00;border-top:double 2px #000000""><?php echo str_replace('.00','',number_format($sumprice,2)); ?></td>
  </tr>
</table>

</div> 
 <?php } 
 // Show if recordset not empty ?>

</div>
</body>
</html>
<?php
//echo $query_rs_stock;

mysql_free_result($rs_hosptype);

mysql_free_result($rs_snap);

mysql_free_result($rs_stock);

mysql_free_result($rs_hospcode);



?>
