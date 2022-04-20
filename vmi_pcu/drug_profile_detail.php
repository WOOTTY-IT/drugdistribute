<?php
ob_start();
session_start();
?>
<?php require_once('Connections/vmi.php'); ?>
<?php require_once('Connections/datacenter.php'); ?>
<?
mysql_select_db($database_vmi, $vmi);
$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

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



	if($_GET['hospcode4']!=""){
	$condition="m.hospcode='".$_GET['hospcode4']."' ";
	}
	else {
	$condition=" h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."'";	
	}

if($row_rs_setting['dbcenter']=="N"){
mysql_select_db($database_vmi, $vmi);
$query_rs_drug = "select *,concat(date_format(date_serv,'%d/%m/'),(date_format(date_serv,'%Y')+543)) as date_serv from drug_import m left outer join hospcode h on h.hospcode=m.hospcode where ".$condition." and didstd='".$_GET['stdcode']."' and date_serv='".$_GET['date_serv']."'";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
}
else{
mysql_select_db($database_datacenter, $datacenter);
$query_rs_drug = "select *,concat(date_format(vstdate,'%d/%m/'),(date_format(vstdate,'%Y')+543)) as date_serv from datacenter_drug_opd m left outer join hospcode h on h.hospcode=m.hospcode where ".$condition." and did='".$_GET['stdcode']."' and vstdate='".$_GET['date_serv']."'";
$rs_drug = mysql_query($query_rs_drug, $datacenter) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
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
<span class="big_red16"><?php echo $row_rs_drug['drug_name']; ?></span>&nbsp;<br />
วันที่ให้บริการ
<?php echo $row_rs_drug['date_serv']; ?><br />
<br />
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
  <tr class="big_black16">
    <td width="6%" height="34" align="center" style="border-bottom:solid 1px #CCCCCC">ลำดับ</td>
    <td width="19%" align="center" style="border-bottom:solid 1px #CCCCCC">HN</td>
    <td width="20%" align="center" style="border-bottom:solid 1px #CCCCCC">จำนวน</td>
    <td width="17%" align="center" style="border-bottom:solid 1px #CCCCCC">ราคาขาย(บาท)</td>
    <td width="17%" align="center" style="border-bottom:solid 1px #CCCCCC">ราคายา(บาท)</td>
    <td width="21%" align="center" style="border-bottom:solid 1px #CCCCCC">รวม(บาท)</td>
  </tr>
    <?php $i=0; do {$i++; ?>
  <tr class="grid">
      <td height="27" align="center" style="border-bottom:dashed 1px #CCCCCC"><?php echo $i; ?></td>
      <td align="center" style="border-bottom:dashed 1px #CCCCCC"><?php echo $row_rs_drug['hn']; ?></td>
      <td align="center" style="border-bottom:dashed 1px #CCCCCC"><?php echo $row_rs_drug['qty']; ?></td>
      <td align="center" style="border-bottom:dashed 1px #CCCCCC"><?php echo $row_rs_drug['drug_price']; ?></td>
      <td align="center" style="border-bottom:dashed 1px #CCCCCC"><?php echo $row_rs_drug['drug_cost']; ?></td>
      <td align="center" style="border-bottom:dashed 1px #CCCCCC"><?php echo $row_rs_drug['qty']*$row_rs_drug['drug_cost']; ?></td>
  </tr>      
  <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>

</table>

<p align="center">
  <input type="button" name="back" id="back" value="ย้อนกลับ" onclick="window.history.back();" class="button blue" />
</p>
</body>
</html>
<?php
mysql_free_result($rs_drug);
mysql_free_result($rs_setting);

?>
