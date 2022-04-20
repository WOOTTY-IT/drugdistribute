<?php 
ob_start();
session_start();
?>
<?php require_once('Connections/vmi.php'); ?>
<?php require_once('Connections/datacenter.php'); ?>

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


if($_GET['do']=="search"){
	$edate1=$_GET['year']."-".$_GET['month'];	
	$edate2=$_GET['year2']."-".$_GET['month2'];	

if(isset($_GET['dept'])&&($_GET['dept']!="")){
	$condition="where i.hospcode='".$_GET['dept']."' and ";
	$hospcode2=$_GET['dept'];
}
else if(isset($_GET['dept'])&&($_GET['dept']=="")){
	$condition="where ";
}

/*else {if(isset($hospcode1)&&$hospcode1!=""){ $hospcode2=$hospcode1; $condition="where i.hospcode='$hospcode2' and";
} 
else if(!isset($_SESSION['hospcode'])){$hospcode2=$_SESSION['hospcode']; $condition="where i.hospcode='".$_SESSION['hospcode']."' and";
}
else if(isset($hospcode1)&&($hospcode1=="")){$hospcode2=""; $condition="where ";

}
}
*/
if($row_rs_setting['dbcenter']=="N"){
mysql_select_db($database_vmi, $vmi);
$query_rs_drug_list = "SELECT dname,didstd FROM drug_import i left outer join hospcode h on h.hospcode=i.hospcode ".$condition." h.chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' and substr(date_serv,1,7) between '".$edate1."' and '".$edate2."' and dname !='' GROUP BY didstd ORDER BY dname";
$rs_drug_list = mysql_query($query_rs_drug_list, $vmi) or die(mysql_error());
$row_rs_drug_list = mysql_fetch_assoc($rs_drug_list);
$totalRows_rs_drug_list = mysql_num_rows($rs_drug_list);
}

else{
	if($edate1==$edate2){
	$dom=$number = cal_days_in_month(CAL_GREGORIAN, $_GET['month'], $_GET['year']);
	$datediff=$dom;
	}

mysql_select_db($database_vmi, $vmi);
$query_rs_monthdiff = "SELECT TIMESTAMPDIFF(MONTH, '".$edate1."-01', '".$edate2."-01') as monthdiff";
$rs_monthdiff = mysql_query($query_rs_monthdiff, $vmi) or die(mysql_error());
$row_rs_monthdiff = mysql_fetch_assoc($rs_monthdiff);

 $month_rate=$row_rs_monthdiff['monthdiff']+1;
mysql_select_db($database_datacenter, $datacenter);
$query_rs_drug_list = "SELECT drug_name as dname,did as didstd,sum(qty) as sumqty FROM datacenter_drug_opd i left outer join hospcode h on h.hospcode=i.hospcode ".$condition." h.chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' and substr(vstdate,1,7) between '".$edate1."' and '".$edate2."' and drug_name !='' GROUP BY did ORDER BY drug_name";
$rs_drug_list = mysql_query($query_rs_drug_list, $datacenter) or die(mysql_error());
$row_rs_drug_list = mysql_fetch_assoc($rs_drug_list);
$totalRows_rs_drug_list = mysql_num_rows($rs_drug_list);

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
<?php if ($totalRows_rs_drug_list > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellpadding="2" cellspacing="0" class="head_small_gray">
    <tr class="table_head_small_bord" >
      <td width="57" height="39" align="center" valign="bottom" style="border-bottom:solid 1px #CCCCCC">ลำดับ</td>
      <td width="366" align="center" valign="bottom" style="border-bottom:solid 1px #CCCCCC">รายการยา</td>
      <td width="226" align="center" valign="bottom" style="border-bottom:solid 1px #CCCCCC">รหัส 24 หลัก</td>
      <td width="106" align="center" valign="bottom" style="border-bottom:solid 1px #CCCCCC">จำนวนการใช้</td>
      <td width="25" align="center" valign="bottom" style="border-bottom:solid 1px #CCCCCC">rate</td>
    </tr>
    <?php $i=0; do { $i++;
 //ค้นหาเดือนและปี
if($row_rs_setting['dbcenter']=="N"){
 mysql_select_db($database_vmi, $vmi);
$query_rs_drug_list2 = "select didstd from month_rate i ".$condition." concat(year_rate,'-',month_rate) BETWEEN concat('$year','-','$month') and concat('$year2','-','$month2') and didstd='".$row_rs_drug_list['didstd']."' group by concat(year_rate,'-',month_rate)";
$rs_drug_list2 = mysql_query($query_rs_drug_list2, $vmi) or die(mysql_error());
$row_rs_drug_list2 = mysql_fetch_assoc($rs_drug_list2);
$totalRows_rs_drug_list2 = mysql_num_rows($rs_drug_list2);
//จำนวนรวม
 mysql_select_db($database_vmi, $vmi);
$query_rs_drug_list3 = "select sum(qty) as sumqty from month_rate i ".$condition." concat(year_rate,'-',month_rate) BETWEEN '".$edate1."' and '".$edate2."' and didstd='".$row_rs_drug_list['didstd']."' ";
$rs_drug_list3 = mysql_query($query_rs_drug_list3, $vmi) or die(mysql_error());
$row_rs_drug_list3 = mysql_fetch_assoc($rs_drug_list3);
$totalRows_rs_drug_list3 = mysql_num_rows($rs_drug_list3);

$qty_rate=str_replace('.00','',number_format(($row_rs_drug_list3['sumqty']/$totalRows_rs_drug_list2),2));
}
else{
$qty_rate=str_replace('.00','',number_format(($row_rs_drug_list['sumqty']/$month_rate),2));	
}
  	     if($bgcolor=="") { $bgcolor="#E1E1E1"; $font="#FFFFFF"; } else { $bgcolor=""; $font="#999999"; } 

  ?>
    <tr class="grid" onclick="alertload1('drug_profile_graph.php?do=simple&date1=<?php echo $edate1; ?>&date2=<?php echo $edate2; ?>&stdcode=<?php echo $row_rs_drug_list['didstd']; ?>&hospcode3=<?php echo $hospcode2; ?>','90%','90%');">
      <td height="29" align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $i; ?></td>
      <td align="left" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_drug_list['dname']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_drug_list['didstd']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php if($row_rs_setting['dbcenter']=="N"){ echo number_format($row_rs_drug_list3['sumqty']); } else { echo number_format($row_rs_drug_list['sumqty']); } ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php print $qty_rate; ?></td>
    </tr>
    <?php
	if($row_rs_setting['dbcenter']=="N"){
	mysql_free_result($rs_drug_list2);
	mysql_free_result($rs_drug_list3);
	}

  } while ($row_rs_drug_list = mysql_fetch_assoc($rs_drug_list)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
if($_POST['do']=="search"){
mysql_free_result($rs_drug_list);
}
mysql_free_result($rs_setting);

?>