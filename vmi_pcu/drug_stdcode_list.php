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
$query_rs_stdcode = "select d.working_code,d.drug_name,d.pack_ratio,d.sale_unit,s.stdcode from drugs d left outer join drug_stdcode s on s.working_code=d.working_code and d.hospcode=s.hospcode where d.hospcode='".$_SESSION['hospcode']."' order by d.drug_name";
$rs_stdcode = mysql_query($query_rs_stdcode, $vmi) or die(mysql_error());
$row_rs_stdcode = mysql_fetch_assoc($rs_stdcode);
$totalRows_rs_stdcode = mysql_num_rows($rs_stdcode);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="900" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
  <tr>
    <td width="34" align="center" class="table_head_small_bord">ลำดับ</td>
    <td width="97" align="center" class="table_head_small_bord">working_code</td>
    <td width="260" align="center" class="table_head_small_bord">รายการยา</td>
    <td width="77" align="center" class="table_head_small_bord">ขนาดบรรจุ</td>
    <td width="56" align="center" class="table_head_small_bord">หน่วย</td>
    <td width="308" align="center" class="table_head_small_bord">รหัสมาตรฐาน</td>
    <td width="26" align="center">&nbsp;</td>
  </tr>
    <?php $i=0; do { $i++; ?>
  <tr class="grid">
    <td align="center"><?php echo $i; ?></td>
      <td align="center"><?php print $row_rs_stdcode['working_code']; ?></td>
      <td align="left" style="padding-left:10px"><?php echo $row_rs_stdcode['drug_name']; ?></td>
      <td align="center"><?php print $row_rs_stdcode['pack_ratio']; ?></td>
      <td align="center"><?php print $row_rs_stdcode['sale_unit']; ?></td>
      <td align="center"><?php echo $row_rs_stdcode['stdcode']; ?></td>
      <td align="center"><a href="javascript:alertload1('drug_stdcode_edit.php?working_code=<?php echo $row_rs_stdcode['working_code']; ?>','500','400');"><img src="images/config.png" width="24" height="24" border="0" /></a></td>
  </tr>      <?php } while ($row_rs_stdcode = mysql_fetch_assoc($rs_stdcode)); ?>

</table>
</body>
</html>
<?php
mysql_free_result($rs_stdcode);
?>
