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


////////////////////////////////////////
if(isset($_POST['search'])&&($_POST['search']=="ค้นหา")){
	$date11=explode("/",$_POST['date1']);
	$edate1=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

	$date11=explode("/",$_POST['date2']);
	$edate2=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

	if($_POST['dept']==""){
	$condition2=" and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."'";
	}
	if($_POST['dept']!=""){
	$condition2.=" and r.hospcode='".$_POST['dept']."'";	
	}	
	if($_POST['dept2']!=""){
	$condition2.=" and r.dept_id='".$_POST['dept2']."'";	
	}

mysql_select_db($database_vmi, $vmi);
$query_rs_report = "select sum((c.qty/c.pack_ratio)*c.buy_unit_cost) as sumcost,d.supply_type from stock_receive r left outer join stock_receive_c c on c.receive_no=r.receive_no  left outer join drugs d on d.working_code=c.working_code  and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' left outer join hospcode h on h.hospcode=r.hospcode where 1 ".$condition2." and r.receive_date between '".$edate1."' and '".$edate2."' group by d.supply_type";
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
<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
</head>

<body>
<form action="stock_report1.php" method="post">
  <div class="thsan-semibold" style="font-size:24px">รายงานสรุปยอดรับเข้าคลัง</div>
  <table width="600" border="0" cellspacing="0" cellpadding="5" class="thsan-light">
    <tr>
      <td width="148">เลือกหน่วยงาน</td>
      <td width="432"><label for="dept"></label>
        <select name="dept" id="dept" class="thsan-light font16 inputcss1" >
          <?php if($totalRows_rs_hospcode>1){?>
          <option value=""<?php if(!isset($_POST['dept'])){ if (!(strcmp("", $_POST['dept']))) {echo "selected=\"selected\"";}} else {if (!(strcmp("", ""))) {echo "selected=\"selected\"";}} ?>>=== ทั้งหมด ===</option>
          <?php
		}
do {  
?>
          <option value="<?php echo $row_rs_hospcode['hospcode']?>"<?php if(!isset($_POST['dept'])){ if (!(strcmp($row_rs_hospcode['hospcode'], $_SESSION['hospcode']))) {echo "selected=\"selected\"";}} else {if (!(strcmp($row_rs_hospcode['hospcode'], $_POST['dept']))) {echo "selected=\"selected\"";}} ?>><?php echo $row_rs_hospcode['hospname']?></option>
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
      <td>รับเข้าจากหน่วยงาน</td>
      <td><select name="dept2" id="dept2" class="thsan-light font16 inputcss1" >
        <?php if($totalRows_rs_hospcode>1){?>
        <option value=""<?php  if (!(strcmp("", $_POST['dept2']))) {echo "selected=\"selected\"";} ?>>=== ทั้งหมด ===</option>
        <?php
		}
do {  
?>
        <option value="<?php echo $row_rs_hospcode['hospcode']?>"<?php if (!(strcmp($row_rs_hospcode['hospcode'], $_POST['dept2']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_hospcode['hospname']?></option>
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
      <td>ช่วงวันที่</td>
      <td><label for="snapshot_date"><span class="input-group" style="width:200px">
        <input type="text" id="date1" name="date1"  class=" inputcss1 thsan-light font18" style="width:100px" value="<?php if(isset($_POST['date1'])){ echo $date1; } else { echo date('d/m/').(date('Y')+543); } ?>"  />
      </span>ถึง <span class="input-group" style="width:200px">
      <input type="text" id="date2" name="date2"  class=" inputcss1 thsan-light font18" style="width:100px" value="<?php if(isset($_POST['date2'])){ echo $_POST['date2']; } else { echo date('d/m/').(date('Y')+543); } ?>"  />
      </span></label></td>
    </tr>
    <?php }  ?>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="search" id="search" value="ค้นหา" class="thsan-semibold font19" style="padding-top:2px; padding-bottom:3px; text-align:center; padding-left:30px;  background-image: url(images/ic_search_48px-128.png); background-repeat:no-repeat; background-size:20px 20px; background-position:5px 3px; padding-right:10px; background-color: #5CA3C9; border:1px solid  #06C; text-decoration:none; color:#FFF; height:30px"></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rs_report > 0) { // Show if recordset not empty ?>
  <table width="300" border="0" cellspacing="0" cellpadding="5" class="thsan-light table_collapse" >
    <tr class="font_border font20">
      <td width="123" align="center" bgcolor="#E7E7E7" style=" border-right:1px solid #000; border-bottom:solid 1px #000000">ประเภท</td>
      <td width="357" align="center" bgcolor="#E7E7E7" style=" border-left:1px solid #000; border-bottom:solid 1px #000000">มูลค่ารับเข้า</td>
      </tr>
    <tr>
      <td align="center" style=" border-right:1px solid #000; border-top:solid 1px #000000"><?php if($row_rs_report['supply_type']=="1"){ echo "ED"; } else if($row_rs_report['supply_type']=="2"){ echo "NED"; } ?></td>
      <td align="center" style=" border-left:1px solid #000; border-top:solid 1px #000000"><?php echo str_replace('.00','',number_format($row_rs_report['sumcost'],2)); ?></td>
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


?>