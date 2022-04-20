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


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ThaiCreate.Com PHP & Text To MySQL</title>
<style type="text/css">
body {
	background-color: #ededed;
}
</style>
</head>
<body>
<?php
mysql_select_db($database_vmi, $vmi);
$query_rs_order = "select * from vmi_order where order_month='".$_POST['year']."-".$_POST['month']."' and hospcode='".$_SESSION['hospcode']."'";
$rs_order = mysql_query($query_rs_order, $vmi) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);

if($totalRows_rs_order<>0){
	echo "ไม่สามารถอัพโหลดได้เนื่องจากมีการรับใบเบิกแล้ว  กรุณาติดต่อเจ้าหน้าที่คลังยา";
}
else {

	if(move_uploaded_file($_FILES["fileCVS"]["tmp_name"],"upload/DRUG_OPD_".$_SESSION['hospcode']."_".date('Y-m-d').".txt")){
$strFileName = "DRUG_OPD_".$_SESSION['hospcode']."_".date('Y-m-d').".txt";
$objFopen = fopen("upload/".$strFileName, 'r')or die("Unable to open file!");;

mysql_select_db($database_vmi, $vmi);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'vmi2' 
AND TABLE_NAME = 'drug_import_".$_SESSION['hospcode']."' ";
$check = mysql_query($query_check, $vmi) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);
		if($row_check['ccolumn']!=0){
		mysql_select_db($database_vmi, $vmi);
		$query_rs_drop = "
		DROP TABLE `drug_import_".$_SESSION['hospcode']."`";
		$rs_drop = mysql_query($query_rs_drop, $vmi) or die(mysql_error());	
		}
		else{
		mysql_select_db($database_vmi, $vmi);
		$query_rs_create = "
		CREATE TABLE `drug_import_".$_SESSION['hospcode']."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `hospcode` char(5) DEFAULT NULL,
		  `pid` char(9) DEFAULT NULL,
		  `seq` char(9) DEFAULT NULL,
		  `date_serv` date DEFAULT NULL,
		  `clinic` char(5) DEFAULT NULL,
		  `didstd` char(24) DEFAULT NULL,
		  `dname` varchar(255) DEFAULT NULL,
		  `amount` int(10) DEFAULT NULL,
		  `unit` char(5) DEFAULT NULL,
		  `unit_packing` char(10) DEFAULT NULL,
		  `drugprice` double(5,2) DEFAULT NULL,
		  `drugcost` double(5,2) DEFAULT NULL,
		  `provider` char(10) DEFAULT NULL,
		  `d_update` datetime DEFAULT NULL,
		  `data_flag` char(1) DEFAULT NULL,
		  `date_upload` date DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=280155 DEFAULT CHARSET=utf8";
		$rs_create = mysql_query($query_rs_create, $vmi) or die(mysql_error());	
			
		}
		
mysql_select_db($database_vmi, $vmi);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'vmi2' 
AND TABLE_NAME = 'drug_import_".$_SESSION['hospcode']."_2' ";
$check = mysql_query($query_check, $vmi) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

if($row_check['ccolumn']!=0){
	mysql_select_db($database_vmi, $vmi);
	$query_rs_drop = "
	DROP TABLE `drug_import_".$_SESSION['hospcode']."_2`";
	$rs_drop = mysql_query($query_rs_drop, $vmi) or die(mysql_error());	
}
else{
mysql_select_db($database_vmi, $vmi);
$query_rs_create = "
CREATE TABLE `drug_import_".$_SESSION['hospcode']."_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hospcode` char(5) DEFAULT NULL,
  `pid` char(9) DEFAULT NULL,
  `seq` char(9) DEFAULT NULL,
  `date_serv` date DEFAULT NULL,
  `clinic` char(5) DEFAULT NULL,
  `didstd` char(24) DEFAULT NULL,
  `dname` varchar(255) DEFAULT NULL,
  `amount` int(10) DEFAULT NULL,
  `unit` char(5) DEFAULT NULL,
  `unit_packing` char(10) DEFAULT NULL,
  `drugprice` double(5,2) DEFAULT NULL,
  `drugcost` double(5,2) DEFAULT NULL,
  `provider` char(10) DEFAULT NULL,
  `d_update` datetime DEFAULT NULL,
  `data_flag` char(1) DEFAULT NULL,
  `date_upload` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=280155 DEFAULT CHARSET=utf8";
$rs_create = mysql_query($query_rs_create, $vmi) or die(mysql_error());	
}

while(!feof($objFopen)) {
        $file = fgets($objFopen, 4096);
		$aa=explode("|",$file);
		if($aa[0]!=""){ $hospcode_drug=$aa[0]; }

		if($aa[3]!=""){
		$date_serv=	substr($aa[3],0,4)."-".substr($aa[3],4,2)."-".substr($aa[3],6,2);
		}else {
		$date_serv="0000-00-00";	
		}
if(substr($date_serv,0,7)==$_POST['year']."-".$_POST['month']){
//insert ลงในตาราง drug_import_hospcode
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into drug_import_".$_SESSION['hospcode']." (hospcode,pid,seq,date_serv,clinic,didstd,dname,amount,unit,unit_packing,drugprice,drugcost,provider,d_update,date_upload) value ('".$aa[0]."','".$aa[1]."','".$aa[2]."','".$date_serv."','".$aa[4]."','".$aa[5]."','".$aa[6]."','".$aa[7]."','".$aa[8]."','".$aa[9]."','".$aa[10]."','".$aa[11]."','".$aa[12]."','".substr($aa[13],0,4)."-".substr($aa[13],4,2)."-".substr($aa[13],6,2)." ".substr($aa[13],8,2).":".substr($aa[13],10,2).":".substr($aa[13],12 ,2)."',NOW())";
		$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	}
}

//ลบข้อมูล 0
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from drug_import_".$_SESSION['hospcode']." where amount=0  or substr(date_serv,1,7)!='".$_POST['year']."-".$_POST['month']."'";
$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

//insert ข้อม๔ลจาก drug_import
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into drug_import_".$_SESSION['hospcode']." (hospcode,pid,seq,date_serv,clinic,didstd,dname,amount,unit,unit_packing,drugprice,drugcost,provider,d_update,date_upload) select hospcode,pid,seq,date_serv,clinic,didstd,dname,amount,unit,unit_packing,drugprice,drugcost,provider,d_update,date_upload from drug_import where hospcode='".$hospcode_drug."' and substr(date_serv,1,7)= '".$_POST['year']."-".$_POST['month']."'";
$insert = mysql_query($query_insert, $vmi) or die(mysql_error());

//ตัดตัวที่ซ้ำออก
?>
<?php
mysql_select_db($database_vmi, $vmi);
$query_rs_dup = "select pid,seq,date_serv,didstd from drug_import_".$_SESSION['hospcode']." group by pid,seq,date_serv,didstd ";
$rs_dup = mysql_query($query_rs_dup, $vmi) or die(mysql_error());
$row_rs_dup = mysql_fetch_assoc($rs_dup);
$totalRows_rs_dup = mysql_num_rows($rs_dup);
?>
<?

do{
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into drug_import_".$_SESSION['hospcode']."_2 (hospcode,pid,seq,date_serv,clinic,didstd,dname,amount,unit,unit_packing,drugprice,drugcost,provider,d_update,date_upload) select hospcode,pid,seq,date_serv,clinic,didstd,dname,amount,unit,unit_packing,drugprice,drugcost,provider,d_update,date_upload from drug_import_".$_SESSION['hospcode']." where pid='".$row_rs_dup['pid']."' and seq='".$row_rs_dup['seq']."' and date_serv='".$row_rs_dup['date_serv']."' and didstd='".$row_rs_dup['didstd']."' order by d_update DESC limit 1";
$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
}while($row_rs_dup = mysql_fetch_assoc($rs_dup));

//ลบข้อมูล drug_import
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from drug_import where hospcode=".$hospcode_drug." and substr(date_serv,1,7)='".$_POST['year']."-".$_POST['month']."'";
$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());

//insert ข้อมูลใหม่ลงไป
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into drug_import (hospcode,pid,seq,date_serv,clinic,didstd,dname,amount,unit,unit_packing,drugprice,drugcost,provider,d_update,date_upload) select hospcode,pid,seq,date_serv,clinic,didstd,dname,amount,unit,unit_packing,drugprice,drugcost,provider,d_update,date_upload from drug_import_".$_SESSION['hospcode']."_2 ";
$insert = mysql_query($query_insert, $vmi) or die(mysql_error());

//ลบข้อมูล month_rate
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from month_rate where hospcode=".$hospcode_drug." and year_rate='".$_POST['year']."' and month_rate='".$_POST['month']."'";
$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());

//บันทึก month rate ใหม่
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into month_rate (year_rate,month_rate,hospcode,didstd,qty) select '".$_POST['year']."','".$_POST['month']."',hospcode,didstd,sum(amount) from drug_import_".$_SESSION['hospcode']."_2 group by didstd";
$insert = mysql_query($query_insert, $vmi) or die(mysql_error());

	if($query_insert){
	echo "<p>อัพโหลดข้อมูลเสร็จเรียบร้อยแล้ว </p>
";	
	}

fclose($objFopen);

	}
mysql_free_result($rs_dup);

}
?>

</table>
</body>
</html>
<?php

mysql_free_result($rs_order);



?>
