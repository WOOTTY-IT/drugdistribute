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

$date11=explode("/",$date1);
$edate1=$_POST['year']."-".$_POST['month'];

switch($_POST['month']){
	case "01" :
	$monththai="มกราคม";
	break;
	case "02" :
	$monththai="กุมภาพันธ์";
	break;
	case "03" :
	$monththai="มีนาคม";
	break;
	case "04" :
	$monththai="เมษายน";
	break;
	case "05" :
	$monththai="พฤษภาคม";
	break;
	case "06" :
	$monththai="มิถุนายน";
	break;
	case "07" :
	$monththai="กรกฎาคม";
	break;
	case "08" :
	$monththai="สิงหาคม";
	break;
	case "09" :
	$monththai="กันยายน";
	break;
	case "10" :
	$monththai="ตุลาคม";
	break;
	case "11" :
	$monththai="พฤศจิกายน";
	break;
	case "12" :
	$monththai="ธันวาคม";
	break;

	}
$my=$_POST['year']."-".$_POST['month'];

if(isset($_POST['do'])&&($_POST['do']=="process")){

for($i=0;$i<count($order_bring);$i++){
mysql_select_db($database_vmi, $vmi);
$query_rs_month = "select * from drug_remain where stdcode='".$_POST['stdcode'][$i]."' and vmi_date<'".$_POST['year']."-".$_POST['month']."' and hospcode='".$_POST['dept']."' order by vmi_date DESC limit 1";
$rs_month = mysql_query($query_rs_month, $vmi) or die(mysql_error());
$row_rs_month = mysql_fetch_assoc($rs_month);
$totalRows_rs_month = mysql_num_rows($rs_month);

if($order_bring[$i]<>0){
//update data_flag ใน month_rate
mysql_select_db($database_vmi, $vmi);
$query_update = "update month_rate set data_flag='Y' where didstd='".$_POST['stdcode'][$i]."' and concat(year_rate,'-',month_rate)='".$_POST['year']."-".$_POST['month']."' and hospcode='".$_POST['dept']."'";
$update = mysql_query($query_update, $vmi) or die(mysql_error());

if($_POST['remain'][$i]<>0){
//บันทึกข้อมูลยาเหลือในเดือนล่าสุด
	$query_insert = "insert into drug_remain (hospcode,stdcode,qty,date_remain,vmi_date) value ('".$_POST['dept']."','".$_POST['stdcode'][$i]."','".$_POST['remain'][$i]."',NOW(),'".$_POST['year']."-".$_POST['month']."')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	
	}
}
else {
	if($_POST['remain'][$i]<>0){

//บันทึกข้อมูลยาเหลือในเดือนล่าสุด
	$query_insert = "insert into drug_remain (hospcode,stdcode,qty,date_remain,vmi_date) value ('".$_POST['dept']."','".$_POST['stdcode'][$i]."','".$_POST['remain'][$i]."',NOW(),'".$_POST['year']."-".$_POST['month']."')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());		
	}
	mysql_select_db($database_vmi, $vmi);
$query_update = "update drug_remain set data_flag=null where stdcode='".$_POST['stdcode'][$i]."' and vmi_date='".$_POST['year']."-".$_POST['month']."' and hospcode='".$_POST['dept']."' ";
$update = mysql_query($query_update, $vmi) or die(mysql_error());

	}
// ค้นหาข้อมูลในฐานข้อมูลว่าเคยมีการบันทึกยาตัวนั้นในเดือนนั้นไว้หรือไม่
mysql_select_db($database_vmi, $vmi);
$query_rs_vmi = "select * from vmi_order where stdcode='".$_POST['stdcode'][$i]."' and order_month='".$_POST['year']."-".$_POST['month']."' and hospcode='".$_POST['dept']."'";
$rs_vmi = mysql_query($query_rs_vmi, $vmi) or die(mysql_error());
$row_rs_vmi = mysql_fetch_assoc($rs_vmi);
$totalRows_rs_vmi = mysql_num_rows($rs_vmi);

if($totalRows_rs_vmi==0 and $order_bring[$i]!=0){
$query_insert = "insert into vmi_order (hospcode,stdcode,qty,order_month,pack_ratio) value ('".$_POST['dept']."','".$_POST['stdcode'][$i]."','".$_POST['order_bring'][$i]."','".$edate1."','".$_POST['pack_ratio'][$i]."')";
$insert = mysql_query($query_insert, $vmi) or die(mysql_error());

mysql_select_db($database_vmi, $vmi);
$query_update = "update drug_remain set data_flag='Y',order_date='".$_POST['year']."-".$_POST['month']."' where stdcode='".$_POST['stdcode'][$i]."' and vmi_date<='".$row_rs_month['vmi_date']."' and order_date is NULL and hospcode='".$_POST['dept']."' ";
$update = mysql_query($query_update, $vmi) or die(mysql_error());	
}

if($totalRows_rs_vmi!=0 and $order_bring[$i]!=0){
$query_insert = "update vmi_order set qty='".$_POST['order_bring'][$i]."',pack_ratio='".$_POST['pack_ratio'][$i]."' where hospcode='".$_POST['dept']."' and stdcode='".$_POST['stdcode'][$i]."' and order_month='".$_POST['year']."-".$_POST['month']."'";
$insert = mysql_query($query_insert, $vmi) or die(mysql_error());

mysql_select_db($database_vmi, $vmi);
$query_update = "update drug_remain set data_flag='Y',order_date='".$_POST['year']."-".$_POST['month']."' where stdcode='".$_POST['stdcode'][$i]."' and vmi_date<='".$row_rs_month['vmi_date']."' and order_date  is NULL and hospcode='".$_POST['dept']."' ";
$update = mysql_query($query_update, $vmi) or die(mysql_error());
}

if($totalRows_rs_vmi!=0 and $order_bring[$i]==0){
$query_insert = "delete from vmi_order where hospcode='".$_POST['dept']."' and stdcode='".$_POST['stdcode'][$i]."' and order_month='".$_POST['year']."-".$_POST['month']."'";
$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	
}


//ค้นหายาที่เหลือ
/*$query_remain_search = "select * from drug_remain where stdcode='$stdcode[$i]' and hospcode='$dept'";
$remain_search = mysql_query($query_remain_search, $vmi) or die(mysql_error());
$row_remain_search = mysql_fetch_assoc($remain_search);
$totalRows_remain_search = mysql_num_rows($remain_search);
	
	if($totalRows_remain_search==0 and $remain[$i]!=0){
	$query_insert = "insert into drug_remain (hospcode,stdcode,qty,date_remain,vmi_date) value ('$dept','$stdcode[$i]','$remain[$i]',NOW(),'$edate1')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	
	}

	
	if($totalRows_remain_search<>0 and $remain[$i]!=0){
	$query_update = "update drug_remain set  qty=qty+'$remain[$i]',date_remain=NOW(),vmi_date='$edate1' where hospcode='$dept' and stdcode='$stdcode[$i]' and vmi_date!='$edate1'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());	
	
	}
	if($totalRows_remain_search<>0 and $remain[$i]==0){
	$query_update = "delete from drug_remain where hospcode='$dept' and stdcode='$stdcode[$i]' and vmi_date!='$edate1'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());	
	
	}
*/
	mysql_free_result($rs_month);

	}

//ลบข้อมูลที่เป็น 0
	$query_update = "delete from drug_remain where hospcode='".$_POST['dept']."' and qty=0";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());	

//ทำใบเบิก
mysql_select_db($database_vmi, $vmi);
$query_rs_order = "select d.drug_name,v.qty,d.pack_ratio from vmi_order v  left outer join drug_stdcode s on s.stdcode=v.stdcode and s.hospcode='".$_SESSION['hospcode']."' left outer join drugs d on d.working_code=s.working_code and d.hospcode=s.hospcode where v.hospcode='".$_POST['dept']."' and v.order_month='".$edate1."' order by d.drug_name ASC";
$rs_order = mysql_query($query_rs_order, $vmi) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);
 
 
echo "<div style=\"padding-left:100px\"><span style=\"color:red\">ผล :</span> ดำเนินการเรียบร้อยแล้วจำนวน ".$totalRows_rs_order." รายการ <a href=\"javascript:valid(0);\" onclick=\"window.open('order_print.php?order_date=".$my."&dept=".$dept."','_blank');\" class=\" btn btn-default thsan-light font19\"><img src=\"images/Printer and Fax.png\" width=\"24\" height=\"24\" align=\"absmiddle\" /> พิมพ์ใบเบิก</a></div>";
	
	}
if(isset($_POST['do'])&&($_POST['do']=="delete")){
//ค้นหาเดือนที่มากกว่า
mysql_select_db($database_vmi, $vmi);
$query_rs_month_late = "select order_month from vmi_order where order_month>'".$edate1."' and hospcode='".$_POST['dept']."' order by order_month DESC limit 1";
$rs_month_late = mysql_query($query_rs_month_late, $vmi) or die(mysql_error());
$row_rs_month_late = mysql_fetch_assoc($rs_month_late);
$totalRows_rs_month_late = mysql_num_rows($rs_month_late);

if($totalRows_rs_month_late<>0){
	echo "ไม่สามารถลบข้อมูลใบเบิกได้เนื่องจากมีการเบิกจ่ายในเดือนถัดไปแล้ว";
	exit();
	}
//ค้นหาเดือนที่น้อยกว่า
	mysql_select_db($database_vmi, $vmi);
$query_rs_month_late1 = "select order_month from vmi_order where order_month<='".$edate1."'   and hospcode='".$_POST['dept']."' order by order_month DESC limit 1";
$rs_month_late1 = mysql_query($query_rs_month_late1, $vmi) or die(mysql_error());
$row_rs_month_late1 = mysql_fetch_assoc($rs_month_late1);
$totalRows_rs_month_late1 = mysql_num_rows($rs_month_late1);

if($totalRows_rs_month_late1<>0){
//ลบข้อมูลเดือนที่เลือก
$query_update = "update month_rate set data_flag = null where hospcode='".$_POST['dept']."' and concat(year_rate,'-',month_rate)='".$edate1."'";
$update = mysql_query($query_update, $vmi) or die(mysql_error());	

//ลบข้อมูลเดือนที่เลือก
$query_update = "delete from drug_remain where hospcode='".$_POST['dept']."' and vmi_date='".$edate1."'";
$update = mysql_query($query_update, $vmi) or die(mysql_error());	
//ลบข้อมูล data flag
$query_update = "update drug_remain set data_flag = null,order_date=null where hospcode='".$_POST['dept']."' and order_date='".$_POST['year']."-".$_POST['month']."'";
$update = mysql_query($query_update, $vmi) or die(mysql_error());	

//ลบข้อมูลใบเบิก
$query_update = "delete from vmi_order where hospcode='".$_POST['dept']."' and order_month='".$edate1."'";
$update = mysql_query($query_update, $vmi) or die(mysql_error());	

echo "<div style=\"padding-left:100px\"><span style=\"color:red\">ผล :</span> ลบใบเบิก  เดือน".$monththai." ".($_POST['year']+543)."  เรียบร้อยแล้ว</div>";
exit();
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
</body>
</html>
<?php
if(isset($_POST['button2'])&&($_POST['button2']=="ดำเนินการ")){

mysql_free_result($rs_vmi);
mysql_free_result($rs_order);
}
if(isset($_POST['button4'])&&($_POST['button4']=="ลบใบเบิก")){
mysql_free_result($rs_month_late);
mysql_free_result($rs_month_late1);

}
?>
