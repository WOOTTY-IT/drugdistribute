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
$query_rs_stodk2 = "select d.drug_name,sum(s.remain_qty/s.pack_ratio) as sumqty,s.pack_ratio,d.sale_unit,sum(s.total_cost) as sumcost,ds.working_code,concat(h.hosptype,h.name) as hospname from stock_drugs ds left outer join stock_drugs_c s on s.working_code=ds.working_code and s.hospcode=ds.hospcode left outer join drugs d on d.working_code=s.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' left outer join hospcode h on h.hospcode=ds.hospcode where ds.working_code='".$_GET['working_code']."' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' GROUP BY h.hospcode,s.working_code,s.pack_ratio order by d.drug_name,s.pack_ratio ASC";
$rs_stodk2 = mysql_query($query_rs_stodk2, $vmi) or die(mysql_error());
$row_rs_stodk2 = mysql_fetch_assoc($rs_stodk2);
$totalRows_rs_stodk2 = mysql_num_rows($rs_stodk2);
?>
<?
ob_start();
session_start();
?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">

<table width="99%" border="0" cellspacing="0" cellpadding="5" style="margin:10px 10px;" class="table_collapse thsan-light">
    <tr bgcolor="#EAEAEA">
      <td align="center" style=" border:solid 1px #000000;">ลำดับ</td>
      <td style=" border:solid 1px #000000;">หน่วยงาน</td>
      <td align="center" style=" border:solid 1px #000000;">คงเหลือ</td>
      <td align="center" style=" border:solid 1px #000000;">ขนาดบรรจุ</td>
    </tr>
<?php $i=0; do { $i++; ?> 
    <tr class="grid4">
    <td align="center" style=" border:solid 1px #000000;"><?php echo $i; ?></td>
    <td style=" border:solid 1px #000000;"><?php echo $row_rs_stodk2['hospname']; ?></td>
    <td align="center" style=" border:solid 1px #000000;"><?php echo str_replace('.00','',number_format($row_rs_stodk2['sumqty'],2)); ?></td>
    <td align="center" style=" border:solid 1px #000000;"><?php echo $row_rs_stodk2['pack_ratio']; ?></td>
    </tr>
<?php } while ($row_rs_stodk2 = mysql_fetch_assoc($rs_stodk2)); ?> 
</table> 
<?php
mysql_free_result($rs_stodk2);
?>
