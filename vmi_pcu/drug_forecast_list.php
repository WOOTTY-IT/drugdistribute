<?php
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
if($data_type==1){ $condition="m.didstd"; $condition2="month_rate='".$_POST['month']."' and year_rate='".$_POST['year']."'";$table="month_rate";$condition3="(sum(qty)/d2.pack_ratio) as sum_qty";}
else{$condition="m.stdcode";$condition2="order_month='".$year."-".$month."'";$table="vmi_order";$condition3="(sum(qty)) as sum_qty";}
mysql_select_db($database_vmi, $vmi);
$query_rs_search = "select ".$condition3.",".$condition." as didstd,d2.drug_name,d2.pack_ratio,(d2.remain/d2.pack_ratio) as remain,d2.sale_unit from ".$table." m left outer join hospcode h on h.hospcode=m.hospcode left outer join drug_stdcode d on d.stdcode=".$condition." left outer join drugs d2 on d2.working_code=d.working_code and d2.hospcode='".$_SESSION['hospcode']."' where ".$condition2." and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' and d2.drug_name !='' group by ".$condition." order by d2.drug_name ASC";
$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="big_red16"><a href="drug_forecast_sheet.php?order_date=<?php echo $year.'-',$month; ?>&data_type=<?php echo $data_type; ?>" target="_new" class="big_red16">:: ดาว์นโหลดรายงานจัดยา ::</a></p>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
  <tr>
    <td width="42" align="center" class="table_head_small_bord" style="border-bottom:solid 1px #CCCCCC">ลำดับ</td>
    <td width="299" align="left" class="table_head_small_bord" style="border-bottom:solid 1px #CCCCCC">รายการยา</td>
    <td width="134" align="center" class="table_head_small_bord" style="border-bottom:solid 1px #CCCCCC"><?php if($data_type==1){echo "จำนวนใช้"; }else {echo "จำนวนเบิก";} ?></td>
    <td width="106" align="center" class="table_head_small_bord" style="border-bottom:solid 1px #CCCCCC">คงเหลือ</td>
    <td width="121" align="center" class="table_head_small_bord" style="border-bottom:solid 1px #CCCCCC">ขนาดบรรจุ</td>
    <td width="62" align="center" class="table_head_small_bord" style="border-bottom:solid 1px #CCCCCC">หน่วย</td>
    <td width="62" align="center" class="table_head_small_bord" style="border-bottom:solid 1px #CCCCCC">&nbsp;</td>
    <td width="62" align="center" class="table_head_small_bord" style="border-bottom:solid 1px #CCCCCC">&nbsp;</td>
  </tr>
    <?php $i=0; do { $i++; ?>
  <tr class="grid" onclick="divhide('<?php echo $row_rs_search['didstd']; ?>');dosubmit('<?php echo $row_rs_search['didstd']; ?>&amp;month=<?php echo $month; ?>&amp;year=<?php echo $year; ?>&amp;data_type=<?php echo $data_type; ?>','show','drug_take_roll_indiv_detail.php','<?php echo $row_rs_search['didstd']; ?>','');">
      <td height="31" align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $i; ?></td>
      <td align="left" style="border-bottom:solid 1px #CCCCCC" ><?php print $row_rs_search['drug_name']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php print number_format($row_rs_search['sum_qty'],2); ?></td>
      <td align="center" <?php if($row_rs_search['remain']<$row_rs_search['sum_qty']){echo "class=\"small_red_bord\""; } ?> style="border-bottom:solid 1px #CCCCCC"><?php print number_format($row_rs_search['remain'],2); ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php print $row_rs_search['pack_ratio']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php print $row_rs_search['sale_unit']; ?></td>
      <td align="center" style="border-bottom:solid 1px #CCCCCC">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr> 
    <tr id="<?php echo $row_rs_search['didstd']; ?>1" class="tbdiv">
    <td colspan="8" align="left" ><div id="<?php echo $row_rs_search['didstd']; ?>"></div></td>
  </tr>
     
  <?php } while ($row_rs_search = mysql_fetch_assoc($rs_search)); ?>

</table>
</body>
</html>
<?php
mysql_free_result($rs_search);
?>
