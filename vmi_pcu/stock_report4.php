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
$query_rs_hosptype = "select hosptype,hospcode from hospcode where hospcode='".$_SESSION['hospcode']."'";
$rs_hosptype = mysql_query($query_rs_hosptype, $vmi) or die(mysql_error());
$row_rs_hosptype = mysql_fetch_assoc($rs_hosptype);
$totalRows_rs_hosptype = mysql_num_rows($rs_hosptype);

// ใหม่
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

if(isset($_GET['dept_id'])&&($_GET['dept_id']!="")){
	$hoscode=$_GET['dept_id'];
}
if(isset($_POST['dept'])&&($_POST['dept']!="")){
	$hoscode=$_POST['dept'];
}
if(!isset($_GET['dept_id'])&&(!isset($_POST['dept']))){
	$hoscode=$_SESSION['hospcode'];
}

mysql_select_db($database_vmi, $vmi);
$query_rs_pr = "select count(*) as countdrug,concat(DATE_FORMAT(date_withdraw,'%d/%m/'),(DATE_FORMAT(date_withdraw,'%Y')+543)) as withdraw_date,r.withdraw_no from stock_withdraw r left outer join stock_withdraw_c c on c.withdraw_no=r.withdraw_no where r.hospcode='".$hoscode."' group by r.withdraw_no order by date_withdraw DESC";
$rs_pr = mysql_query($query_rs_pr, $vmi) or die(mysql_error());
$row_rs_pr = mysql_fetch_assoc($rs_pr);
$totalRows_rs_pr = mysql_num_rows($rs_pr);
	


////////////////////////////////////////
if(isset($_POST['search'])&&($_POST['search']=="ค้นหา")){


mysql_select_db($database_vmi, $vmi);
$query_rs_report = "select d.drug_name,c.qty_order/c.pack_ratio as qty,c.pack_ratio,c.buy_unit_cost,(c.qty_order/c.pack_ratio)*c.buy_unit_cost as sumcost,concat(h.hosptype,h.name) as hospname from stock_withdraw r left OUTER JOIN stock_withdraw_c c on c.withdraw_no=r.withdraw_no left outer join drugs d on d.working_code=c.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' left outer join hospcode h on h.hospcode=c.hospcode where r.hospcode='".$_POST['dept']."' and r.withdraw_no='".$_POST['ps']."'";
$rs_report = mysql_query($query_rs_report, $vmi) or die(mysql_error());
$row_rs_report = mysql_fetch_assoc($rs_report);
$totalRows_rs_report = mysql_num_rows($rs_report);
}

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
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
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
</head>

<body>
<form action="stock_report4.php" method="post">
  <div class="thsan-semibold" style="font-size:24px">ทะเบียนใบเบิกเวชภัณฑ์ยา</div>
  <table width="600" border="0" cellspacing="0" cellpadding="5" class="thsan-light">
    <tr>
      <td width="148">เลือกหน่วยงาน</td>
      <td width="432"><label for="dept"></label>
        <select name="dept" id="dept" class="thsan-light font16 inputcss1" onChange="window.location.href='stock_report4.php?dept_id='+this.value" >
          <?php
do {  
?>
          <option value="<?php echo $row_rs_hospcode['hospcode']?>"<?php if(!isset($_POST['dept'])&&!isset($_GET['dept_id'])){ if (!(strcmp($row_rs_hospcode['hospcode'], $_SESSION['hospcode']))) {echo "selected=\"selected\"";}} else if(isset($_POST['dept'])) {if (!(strcmp($row_rs_hospcode['hospcode'], $_POST['dept']))) {echo "selected=\"selected\"";}} else if(isset($_GET['dept_id'])) {if (!(strcmp($row_rs_hospcode['hospcode'], $_GET['dept_id']))) {echo "selected=\"selected\"";}} ?>><?php echo $row_rs_hospcode['hospname']?></option>
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
    <tr>
      <td>เลขที่จ่าย</td>
      <td><select name="ps" id="ps" class="thsan-light font16 inputcss1" >
        <?php
do {  
?>
        <option value="<?php echo $row_rs_pr['withdraw_no']?>"<?php if (!(strcmp($row_rs_pr['withdraw_no'], $_POST['ps']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_pr['withdraw_no']." ".$row_rs_pr['withdraw_date'];?></option>
        <?php
} while ($row_rs_pr = mysql_fetch_assoc($rs_pr));
  $rows = mysql_num_rows($rs_pr);
  if($rows > 0) {
      mysql_data_seek($rs_pr, 0);
	  $row_rs_pr = mysql_fetch_assoc($rs_pr);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="search" id="search" value="ค้นหา" class="thsan-semibold font19" style="padding-top:2px; padding-bottom:3px; text-align:center; padding-left:30px;  background-image: url(images/ic_search_48px-128.png); background-repeat:no-repeat; background-size:20px 20px; background-position:5px 3px; padding-right:10px; background-color: #5CA3C9; border:1px solid  #06C; text-decoration:none; color:#FFF; height:30px"></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rs_report > 0) { // Show if recordset not empty ?>
  <div class="thsan-semibold font19">เบิกให้หน่วยงาน :<?php echo $row_rs_report['hospname']; ?></div>
  <table width="100%" border="0" cellspacing="0" cellpadding="3" class="thsan-light">
    <tr class="table_head">
      <td width="9%" align="center">ลำดับ</td>
      <td width="32%" align="left">รายการ</td>
      <td width="14%" align="center">จำนวน</td>
      <td width="18%" align="center">ขนาดบรรจุ</td>
      <td width="13%" align="center">ราคา/หน่วย</td>
      <td width="14%" align="center">ราคารวม</td>
    </tr>
    <?php $sum=0; $i=0; do { $i++; 
   	if($bg == "#F9F9F9") { //ส่วนของการ สลับสี 
$bg = "#FFFFFF";
} else {
$bg = "#F9F9F9";
} 
  $sum+=$row_rs_report['sumcost'];
  ?>
    <tr class="table_body grid4">
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $i; ?></td>
      <td align="left" bgcolor="<?php echo $bg; ?>"><?php print $row_rs_report['drug_name']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php print str_replace('.00','',number_format($row_rs_report['qty'],2)); ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php print $row_rs_report['pack_ratio']; ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php print str_replace('.00','',$row_rs_report['buy_unit_cost']); ?></td>
      <td align="center" bgcolor="<?php echo $bg; ?>"><?php print str_replace('.00','',number_format($row_rs_report['sumcost'],2)); ?></td>
    </tr>
    <?php
	 } while ($row_rs_report = mysql_fetch_assoc($rs_report)); ?>
    <tr class="thsan-semibold font20" style="color:red" >
      <td colspan="4" align="center">&nbsp;</td>
      <td width="13%" align="center">รวม(บาท)</td>
      <td width="14%" align="center"><?php echo str_replace('.00','',number_format($sum,2)); ?>&nbsp;</td>
    </tr>
    
  </table>
  <?php } // Show if recordset not empty ?>
</form>
</body>
</html>
<?php
//echo $query_rs_stock;

mysql_free_result($rs_hosptype);

mysql_free_result($rs_hospcode);

if(isset($_POST['search'])&&($_POST['search']=="ค้นหา")){
mysql_free_result($rs_report);
}

mysql_free_result($rs_pr);
?>