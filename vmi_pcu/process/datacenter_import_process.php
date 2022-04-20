<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
<?php require_once('../Connections/vmi.php'); ?>
<?php require_once('../Connections/datacenter.php'); ?>

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
$query_rs_month_rate = "select count(*) as count_rate from month_rate where year_rate='".$_GET['year_rate']."' and month_rate='".$_GET['month_rate']."' and data_flag ='Y'";
$rs_month_rate = mysql_query($query_rs_month_rate, $vmi) or die(mysql_error());
$row_rs_month_rate = mysql_fetch_assoc($rs_month_rate);
$totalRows_rs_month_rate = mysql_num_rows($rs_month_rate);

if($row_rs_month_rate['count_rate']>0){
	echo "ข้อมูลมีการดำเนินการเบิกแล้วบางส่วนกรุณายกเลิกข้อมูลส่วนนั้นก่อนนำเข้าข้อมูลจากฐานข้อมูลกลาง&nbsp;<a href=\"index.php?sub_page=datacenter_import\" target=\"_parent\" class=\"btn btn-primary thsan-light font19\" style=\"height:30px; padding-top:0px;\">ย้อนกลับ</a>";
	}
else {
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from month_rate where month_rate='".$_GET['month_rate']."' and year_rate='".$_GET['year_rate']."'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());

mysql_select_db($database_datacenter, $datacenter);
$query_rs_dbcenter = "select hospcode,did,sum(qty) as sumqty from datacenter_drug_opd where substr(vstdate,1,7)='".$_GET['year_rate']."-".$_GET['month_rate']."' GROUP BY hospcode,did";
$rs_dbcenter = mysql_query($query_rs_dbcenter, $datacenter) or die(mysql_error());
$row_rs_dbcenter = mysql_fetch_assoc($rs_dbcenter);
$totalRows_rs_dbcenter = mysql_num_rows($rs_dbcenter);
	
	do{
	$success=0;
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into month_rate (month_rate,year_rate,hospcode,didstd,qty) value ('".$_GET['month_rate']."','".$_GET['year_rate']."','".$row_rs_dbcenter['hospcode']."','".$row_rs_dbcenter['did']."','".$row_rs_dbcenter['sumqty']."')";
	$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	$success=1;
	}while($row_rs_dbcenter = mysql_fetch_assoc($rs_dbcenter));
	
	if($success==1){
	echo "นำเข้าเสร็จแล้ว";
	}
mysql_free_result($rs_dbcenter);

	}
	
mysql_free_result($rs_month_rate);

?>
