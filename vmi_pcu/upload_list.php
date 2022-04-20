<?
ob_start();
session_start();
date_default_timezone_set('Asia/bangkok');
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


if($do=="search"){
mysql_select_db($database_vmi, $vmi);
$query_rs_search = "SELECT m.dept_code,h.hosptype,h.name,m.dept_code FROM dept_list m left outer join hospcode h on h.hospcode=m.dept_code WHERE  h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' GROUP BY m.dept_code";
$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

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
<?php if ($totalRows_rs_search > 0) { // Show if recordset not empty ?>
  <img src="images/right_icon2.png" width="17" height="13" />1 = Drug_opd&nbsp;&nbsp;<img src="images/right_icon3.png" width="18" height="14" />2 = On demand&nbsp;&nbsp;<img src="images/right_icon4.png" width="17" height="13" />3 = Ferrous&nbsp;&nbsp;<img src="images/right_icon5.png" width="17" height="13" />4 = Vaccine
  <table width="100%" border="0" cellpadding="3" cellspacing="0" class=" thsan-light font16 table_collapse">
    <tr>
      <td width="3%" rowspan="2" align="center" bgcolor="#E7E7E7" class=" thsan-light font16 table_border"><span style="border-bottom:dashed 1px #000000">ลำดับ</span></td>
      <td width="7%" rowspan="2" align="center" bgcolor="#E7E7E7" class="thsan-light font16 table_border"><span style="border-bottom:dashed 1px #000000">หน่วยงาน<span></td>
      <?php for($m=1;$m<=12;$m++){
	switch($m){
		case 1:
		$mn="มกราคม";
		$mmm="01";
		break;
		case 2:
		$mn="กุมภาพันธ์";
		$mmm="02";
		break;
		case 3:
		$mn="มีนาคม";
		$mmm="03";
		break;
		case 4:
		$mn="เมษายน";
		$mmm="04";
		break;
		case 5:
		$mn="พฤษภาคม";
		$mmm="05";
		break;
		case 6:
		$mn="มิถุนายน";
		$mmm="06";
		break;
		case 7:
		$mn="กรกฎาคม";
		$mmm="07";
		break;
		case 8:
		$mn="สิงหาคม";
		$mmm="08";
		break;
		case 9:
		$mn="กันยายน";
		$mmm="09";
		break;
		case 10:
		$mn="ตุลาคม";
		$mmm="10";
		break;
		case 11:
		$mn="พฤษศจิกายน";
		$mmm="11";
		break;
		case 12:
		$mn="ธันวาคม";
		$mmm="12";
		break;

		}	  
	 ?>
      <td width="7.5%" colspan="4" align="center" bgcolor="#E7E7E7" class="thsan-light font16 table_border"><?php echo $mn; ?></td>
      <?php } ?>
    </tr>

        <tr class="dashed grid4">
              <?php for($m=1;$m<=12;$m++){ ?>

      <td height="30" align="center" class="thsan-light font16 table_border" style="cursor:pointer;" >1</td>
      <td align="center" class="thsan-light font16 table_border"  style="cursor:pointer; font-size:12px" >2</td>
      <td align="center" class="thsan-light font16 table_border" >3</td>
      <td align="center" class="thsan-light font16 table_border" >4</td>
    <?php } ?>
    </tr>

    <?php $i=0; do { $i++; 
	?>
    <tr class="dashed grid4">
      <td align="center" class="thsan-light font16 table_border" height="30"><?php echo $i; ?></td>
      <td align="left" class="thsan-light font16 table_border"><?php echo $row_rs_search['hosptype'].$row_rs_search['name']; ?></td>
      <?php for($m=1;$m<=12;$m++){
	$my=$_POST['year']."-".sprintf('%02d',$m);	  
	switch($m){
		case 1:
		$mn="มกราคม";
		$mmm="01";
		break;
		case 2:
		$mn="กุมภาพันธ์";
		$mmm="02";
		break;
		case 3:
		$mn="มีนาคม";
		$mmm="03";
		break;
		case 4:
		$mn="เมษายน";
		$mmm="04";
		break;
		case 5:
		$mn="พฤษภาคม";
		$mmm="05";
		break;
		case 6:
		$mn="มิถุนายน";
		$mmm="06";
		break;
		case 7:
		$mn="กรกฎาคม";
		$mmm="07";
		break;
		case 8:
		$mn="สิงหาคม";
		$mmm="08";
		break;
		case 9:
		$mn="กันยายน";
		$mmm="09";
		break;
		case 10:
		$mn="ตุลาคม";
		$mmm="10";
		break;
		case 11:
		$mn="พฤษศจิกายน";
		$mmm="11";
		break;
		case 12:
		$mn="ธันวาคม";
		$mmm="12";
		break;

		}	  
 
mysql_select_db($database_vmi, $vmi);
$query_rs_order2 = "select count(*) as count_upload  from month_rate where year_rate='".$_POST['year']."' and month_rate='".$mmm."'  and hospcode='".$row_rs_search['dept_code']."'";
$rs_order2 = mysql_query($query_rs_order2, $vmi) or die(mysql_error());
$row_rs_order2 = mysql_fetch_assoc($rs_order2);
$totalRows_rs_order2 = mysql_num_rows($rs_order2);
	  
mysql_select_db($database_vmi, $vmi);
$query_rs_order = "select count(*) as count_order  from vmi_order where order_month='".$year."-".$mmm."' and hospcode='".$row_rs_search['dept_code']."'";
$rs_order = mysql_query($query_rs_order, $vmi) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);

mysql_select_db($database_vmi, $vmi);
$query_rs_order3 = "select count(*) as count_order  from drug_take where substr(drug_take_date,1,7)='".$year."-".$mmm."' and hospcode='".$row_rs_search['dept_code']."'";
$rs_order3 = mysql_query($query_rs_order3, $vmi) or die(mysql_error());
$row_rs_order3 = mysql_fetch_assoc($rs_order3);
$totalRows_rs_order3 = mysql_num_rows($rs_order3);

mysql_select_db($database_vmi, $vmi);
$query_rs_order4 = "select count(*) as count_order  from iodine_ferrous where substr(order_date,1,7)='".$year."-".$mmm."' and hospcode='".$row_rs_search['dept_code']."'";
$rs_order4 = mysql_query($query_rs_order4, $vmi) or die(mysql_error());
$row_rs_order4 = mysql_fetch_assoc($rs_order4);
$totalRows_rs_order4 = mysql_num_rows($rs_order4);

mysql_select_db($database_vmi, $vmi);
$query_rs_order5 = "select count(*) as count_order  from vaccine_take where substr(vaccine_take_date,1,7)='".$year."-".$mmm."' and hospcode='".$row_rs_search['dept_code']."'";
$rs_order5 = mysql_query($query_rs_order5, $vmi) or die(mysql_error());
$row_rs_order5 = mysql_fetch_assoc($rs_order5);
$totalRows_rs_order5 = mysql_num_rows($rs_order5);

	  ?> 
      <td align="center" class="thsan-light font16 table_border" <?php if($row_rs_order['count_order']>0){ ?> style="cursor:pointer" onclick="window.open('order_print.php?order_date=<?php echo $my; ?>&dept=<?php echo $row_rs_search['dept_code']; ?>','_blank');"<?php  } ?>><?php
 if($row_rs_order2['count_upload']>0){ ?>
        <img src="images/right_icon2.png" width="14" height="11" /><?php if($row_rs_order['count_order']>0){print $row_rs_order['count_order'];} } ?>
      </td>
      <td align="center" class="thsan-light font16 table_border" ><?php if($row_rs_order3['count_order']>0){ ?><img src="images/right_icon3.png" width="14" height="11" /><?php echo "&nbsp;".$row_rs_order3['count_order']; } ?></td>
      <td align="center" class="thsan-light font16 table_border" style="cursor:pointer" >
        <?php if($row_rs_order4['count_order']>0){ ?>
      <img src="images/right_icon4.png" alt="" width="14" height="11" /><?php echo "&nbsp;".$row_rs_order4['count_order']; } ?></td>
      <td align="center" class="thsan-light font16 table_border" style="cursor:pointer" >
        <?php if($row_rs_order5['count_order']>0){ ?>
      <img src="images/right_icon5.png" alt="" width="14" height="11" /><?php echo "&nbsp;".$row_rs_order5['count_order']; } ?></td>
      
      <?php
	    mysql_free_result($rs_order);
	    mysql_free_result($rs_order2);
	    mysql_free_result($rs_order3);
	    mysql_free_result($rs_order4);
	    mysql_free_result($rs_order5);

	   } ?>
    </tr>   
     <?php

   } while ($row_rs_search = mysql_fetch_assoc($rs_search)); ?>

    <tr>
      <td height="30" colspan="2" align="center" bgcolor="#666666" class="thsan-light font16 table_border"  style="color:#FFFFFF">รายการยาที่ดำเนินการแล้ว</td>
      <?php for($m=1;$m<=12;$m++){ $my=$_POST['year']."-".sprintf('%02d',$m);
	  mysql_select_db($database_vmi, $vmi);
	$query_rs_order3 = "select count(*) as count_order  from vmi_order where order_month='".$my."'";
	$rs_order3 = mysql_query($query_rs_order3, $vmi) or die(mysql_error());
	$row_rs_order3 = mysql_fetch_assoc($rs_order3);
	$totalRows_rs_order3 = mysql_num_rows($rs_order3);

	   ?><td colspan="4" align="center" class="thsan-light font16 table_border" <?php if($row_rs_order3['count_order']>0){ echo "style=\"cursor:pointer\""; }?>  ><?php if($row_rs_order3['count_order']>0){ ?><img src="images/Printer and Fax.png" onclick="alertload('drug_take_roll_indiv2.php','90%','90%','month','<?php echo $my; ?>');" class="link1"  width="30" height="30" /><?php } ?></td><? 
	   mysql_free_result($rs_order3);
	   } ?>
    </tr>      
    
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rs_search);

?>
