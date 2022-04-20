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
$query_rs_order = "select drug_name,qty,std_ratio from vmi_order v  left outer join inv_md i on i.standard_code=substring(v.stdcode,1,19) where hospcode='$hospcode1' and order_month='$order_month' order by drug_name ASC";
$rs_order = mysql_query($query_rs_order, $vmi) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);
 
header('Content-type: application/ms-word'); //การผลเป็นไฟล์ word 
header('Content-Disposition: attachment; filename="requisition.doc"');

$datethai=explode('-',$order_month);
switch($datethai[1]){
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
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
  <tr>
    <td colspan="4" align="center" bgcolor="#000000" style="border:solid 1px #000000"><font color="#FFFFFF">ใบเบิกยาประจำเดือนจากหน่วยงานภายนอก&nbsp; &nbsp;โรงพยาบาลมหาชนะชัย</font></td>
  </tr>
  <tr>
    <td colspan="4" align="left" bgcolor="#FFFFFF" ><font size="+2" color="#FF0000"><strong>ใบเบิกประจำเดือน <?php echo $monththai." ".($datethai[0]+543); ?> <br />
    หน่วยเบิก </strong></font></td>
  </tr>
  <tr>
    <td width="45" align="center" bgcolor="#000000" style="border:solid 1px #000000; font-size: 14px ; color:#FFFFFF">ลำดับ</td>
    <td width="404" align="center" bgcolor="#000000" style="border:solid 1px #000000;font-size: 14px ; color:#FFFFFF">รายการยา</td>
    <td width="133" align="center" bgcolor="#000000" style="border:solid 1px #000000;font-size: 14px ; color:#FFFFFF">จำนวนเบิก</td>
    <td width="133" align="center" bgcolor="#000000" style="border:solid 1px #000000;font-size: 14px ; color:#FFFFFF">จ่ายจริง</td>
  </tr>
    <?php $i=0; do { $i++;
	    if($bgcolor=="#FFFFFF") { $bgcolor="#CCCCCC"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999"; } 

	 ?>
  <tr>

    <td align="center" bgcolor="<?php echo $bgcolor; ?>" style="border:solid 1px #000000;font-size: 14px"><?php echo $i; ?></td>
      <td align="left" bgcolor="<?php echo $bgcolor; ?>" style="border:solid 1px #000000;font-size: 14px"><?php echo $row_rs_order['drug_name']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>" style="border:solid 1px #000000;font-size: 14px"><?php echo $row_rs_order['qty']; ?>x<?php echo $row_rs_order['std_ratio']; ?></td>
    <td align="center" bgcolor="<?php echo $bgcolor; ?>" style="border:solid 1px #000000;font-size: 14px">&nbsp;</td>
  </tr>
        <?php } while ($row_rs_order = mysql_fetch_assoc($rs_order)); ?>

</table>
</body>
</html>
<?php
mysql_free_result($rs_order);
?>
