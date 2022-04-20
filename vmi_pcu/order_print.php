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

if(isset($_GET['dept'])&&($_GET['dept']!="")){
	$dept=$_GET['dept'];
	}
else{
	$dept=$_SESSION['hospcode'];
	}
if(isset($_GET['order_date'])&&($_GET['order_date']!="")){
	$order_date=$_GET['order_date'];
	}
else{
	$order_date=$_POST['year']."-".$_POST['month'];
	}


include('include/function.php');

mysql_select_db($database_vmi, $vmi);
$query_truncate = "truncate table drug_datacenter_order_print";
$truncate = mysql_query($query_truncate, $vmi) or die(mysql_error());

mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode = "select concat(h.hosptype,' ',h.name) as hospname,g.hospmain from hospcode h left outer join dept_list l on l.dept_code=h.hospcode left outer join dept_group g on g.dept_group_id=l.dept_group where h.hospcode='".$dept."'";
$rs_hospcode = mysql_query($query_rs_hospcode, $vmi) or die(mysql_error());
$row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
$totalRows_rs_hospcode = mysql_num_rows($rs_hospcode);

if($row_rs_hospcode['hospmain']!=$_SESSION['hospcode']){
	$hospmain=$row_rs_hospcode['hospmain'];
	}
else{
	$hospmain=$_SESSION['hospcode'];
	}

/*
mysql_select_db($database_vmi, $vmi);
$query_rs_order = " select d2.drug_name,v.didstd,d.working_code,v.hospcode,d2.pack_ratio from month_rate v left outer join drug_stdcode d on d.stdcode=v.didstd and d.hospcode='".$hospmain."'  left outer join drugs d2 on d2.working_code=d.working_code and d.hospcode=d2.hospcode where concat(v.year_rate,'-',v.month_rate)='".$order_date."' and v.hospcode='".$dept."' and d2.drug_name !='' and d2.working_code in 
(select d3.working_code from drug_dept_group d3 left outer join dept_list l on l.dept_group=d3.dept_group_id where l.dept_code='".$dept."' and (d3.on_demand != 'Y' or d3.on_demand is NULL))  order by d2.drug_name ASC";
echo $query_rs_order;
$rs_order = mysql_query($query_rs_order, $vmi) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);

do{
	mysql_select_db($database_vmi, $vmi);
	$query_rs_order2 = "select v.qty,v.pack_ratio from vmi_order v where v.stdcode='".$row_rs_order['didstd']."' and v.hospcode='".$dept."' and order_month='".$order_date."' ";
	$rs_order2 = mysql_query($query_rs_order2, $vmi) or die(mysql_error());
	$row_rs_order2 = mysql_fetch_assoc($rs_order2);
	$totalRows_rs_order2 = mysql_num_rows($rs_order2);	

mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into drug_datacenter_order_print (department,order_date,drug_name,qty,pack_ratio,unit,did) value ('".$row_rs_hospcode['hospname']."','".monthThai(date('Y-m'))."','".$row_rs_order['drug_name']."','".$row_rs_order2['qty']."','".$row_rs_order2['pack_ratio']."','".$row_rs_order['sale_unit']."','".$row_rs_order['didstd']."')";
$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());

mysql_free_result($rs_order2);

	} while($row_rs_order = mysql_fetch_assoc($rs_order));

mysql_free_result($rs_hospcode);

mysql_free_result($rs_order);

exit();
*/

mysql_select_db($database_vmi, $vmi);
$query_rs_order = " select d2.drug_name,v.didstd,d.working_code,v.hospcode,d2.sale_unit,d2.pack_ratio from month_rate v left outer join drug_stdcode d on d.stdcode=v.didstd and d.hospcode='".$hospmain."'  left outer join drugs d2 on d2.working_code=d.working_code and d.hospcode=d2.hospcode where concat(v.year_rate,'-',v.month_rate)='".$order_date."' and v.hospcode='".$dept."' and d2.drug_name !='' and d2.working_code in 
(select d3.working_code from drug_dept_group d3 left outer join dept_list l on l.dept_group=d3.dept_group_id where l.dept_code='".$dept."' and (d3.on_demand != 'Y' or d3.on_demand is NULL))  order by d2.drug_name ASC";
echo $query_rs_order;
$rs_order = mysql_query($query_rs_order, $vmi) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);
switch(substr($order_date,5,2)){
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style>
.thborder td{
	border:solid 1px #000000;
	}
tr:nth-child(even) {background: #F4F4F4}
tr:nth-child(odd) {background: #FFF}

</style>
</head>

<body onload="window.print()">
<div align="center" ><span class=" thfont font_bord" style="font-size:30px">ใบเบิกเวชภัณฑ์ยาจากระบบ datacenter</span><br />
<span class=" thfont font_bord" style="font-size:18px">หน่วยงาน :</span><span class=" thfont" style="font-size:18px">  <?php echo $row_rs_hospcode['hospname']; ?></span><br /><span class=" thfont" style="font-size:16px">
ประจำเดือน : </span><span class="thsan-light " style="font-size:24px"><?php echo $monththai."  ".(substr($order_date,0,4)+543); ?></span></div>
<div align="center" class="thfont">เลขที่ใบเบิก : DBC-<?php echo $dept."-".str_replace('-','',$order_date); ?></div>
<table width="100%" border="0" cellspacing="0" cellpadding="3" class="thborder table_collapse" >
	<thead>
  <tr class=" thfont font_bord font14">
    <th width="5%" align="center">no.</th>
    <th width="30%" align="center">รายการยา</th>
    <th width="15%" align="center">จำนวนเบิก</th>
    <th width="20%" align="center">ขนาดบรรจุ</th>
    <th width="10%" align="center">หน่วย</th>
    <th width="20%" align="center">หมายเหตุ</th>
  </tr>
	</thead>
	<tbody>
  <?php $i=0; do { $i++; 
  	//ยอดเบิก
	mysql_select_db($database_vmi, $vmi);
	$query_rs_order2 = "select v.qty,v.pack_ratio from vmi_order v where v.stdcode='".$row_rs_order['didstd']."' and v.hospcode='".$dept."' and order_month='".$order_date."'";
	$rs_order2 = mysql_query($query_rs_order2, $vmi) or die(mysql_error());
	$row_rs_order2 = mysql_fetch_assoc($rs_order2);
	$totalRows_rs_order2 = mysql_num_rows($rs_order2);
	
	//คงคลัง
  	mysql_select_db($database_vmi, $vmi);
	$query_rs_order3 = "select remain_qty,pack_ratio from stock_drugs  where working_code='".$row_rs_order['working_code']."' and hospcode='".$row_rs_order['hospcode']."'";
	$rs_order3 = mysql_query($query_rs_order3, $vmi) or die(mysql_error());
	$row_rs_order3 = mysql_fetch_assoc($rs_order3);
	$totalRows_rs_order3 = mysql_num_rows($rs_order3);

	//max
  	mysql_select_db($database_vmi, $vmi);
	$query_rs_order4 = "select g.max_level from drug_dept_group g left outer join dept_list l on l.dept_group =g.dept_group_id  where g.working_code='".$row_rs_order['working_code']."' and l.dept_code='".$row_rs_order['hospcode']."'";
	$rs_order4 = mysql_query($query_rs_order4, $vmi) or die(mysql_error());
	$row_rs_order4 = mysql_fetch_assoc($rs_order4);
	$totalRows_rs_order4 = mysql_num_rows($rs_order4);

  ?>
  <tr class=" thfont font12">
    <td align="center"><?php echo $i; ?></td>
    <td align="left" style="padding-left:5px" class="font14"><?php echo $row_rs_order['drug_name']; ?></td>
    <td align="center"><?php if($row_rs_order2['qty']!=""){ print $row_rs_order2['qty'];} else { print 0;} ?></td>
    <td align="center"><?php echo number_format($row_rs_order2['pack_ratio']); ?></td>
    <td align="center"><?php echo $row_rs_order['sale_unit']; ?> </td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php
  mysql_free_result($rs_order2);
  mysql_free_result($rs_order3);
  mysql_free_result($rs_order4);

   } while ($row_rs_order = mysql_fetch_assoc($rs_order)); ?>
	</tbody>
</table>
<table width="500" border="0" align="center" cellpadding="5" cellspacing="0" style=" margin-top:10px;" class="thsan-light font16">
</table>

<div class="thfont" align="center" style="padding:10px;">ลงชื่อ _________________ ผู้เบิก</div>
<div class="thfont" align="center" style="padding:10px;">(............................................)</div>
<div class="thfont" align="center" >วันที่......../............/.............</div>    
<div class="thfont" align="center" >ตำแหน่ง...........................................</div>    
<div class="thfont" align="center" >ผู้อำนวยการ <?php echo $row_rs_hospcode['hospname']; ?></div>
</body>
</html>
<?php
mysql_free_result($rs_hospcode);

mysql_free_result($rs_order);


?>
