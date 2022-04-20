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
if(isset($_POST['action_do'])&&($_POST['action_do']=="delete")){
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from  drug_take where id='".$_POST['action_id']."'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());

	}

mysql_select_db($database_vmi, $vmi);
$query_rs_drug_list = "SELECT d.*,dd.drug_name,dd.pack_ratio,dd.buy_unit_cost,dd.sale_unit from drug_take d left outer join drugs dd on dd.working_code=d.working_code and dd.chwpart='".$_SESSION['chwpart']."' and dd.amppart='".$_SESSION['amppart']."'  where d.hospcode='".$_SESSION['hospcode']."' and SUBSTR(d.drug_take_date,1,7)=SUBSTR(CURDATE(),1,7) and item_type='".$_GET['item_type']."' order by dd.drug_name ASC";
$rs_drug_list = mysql_query($query_rs_drug_list, $vmi) or die(mysql_error());
$row_rs_drug_list = mysql_fetch_assoc($rs_drug_list);
$totalRows_rs_drug_list = mysql_num_rows($rs_drug_list);

mysql_select_db($database_vmi, $vmi);
$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);
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
  <table width="800" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
    <tr class="table_head_small_bord">
      <td width="5%" align="center">ลำดับ</td>
      <td width="42%" align="left" style="padding-left:10px">รายการยา</td>
      <td width="9%" align="center">จำนวนเบิก</td>
      <td width="9%" align="center">ขนาดบรรจุ</td>
      <td width="10%" align="center">หน่วย</td>
      <td width="10%" align="center">ราคา/หน่วย</td>
      <td width="8%" align="center">ราคารวม</td>
     <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($row_rs_drug_list['confirm']==NULL)){ echo "<td width=\"7%\" align=\"center\">&nbsp;</td>"; } ?>
    </tr>
    <?php $i=0; do { $i++; 
	$sumprice+=($row_rs_drug_list['buy_unit_cost']*($row_rs_drug_list['among']/$row_rs_drug_list['pack_ratio']));
	?>
      <tr class="dashed">
        <td align="center"><?php echo $i; ?></td>
        <td align="left" style="padding-left:10px"><span><?php print $row_rs_drug_list['drug_name']; ?></span></td>
        <td align="center"><span style="padding-left:10px"><?php print ($row_rs_drug_list['among']/$row_rs_drug_list['pack_ratio']); ?></span></td>
        <td align="center"><span style="padding-left:10px"><?php print $row_rs_drug_list['pack_ratio']; ?></span></td>
        <td align="center"><span style="padding-left:10px"><?php print $row_rs_drug_list['sale_unit']; ?></span></td>
        <td align="center"><span style="padding-left:10px"><?php print $row_rs_drug_list['buy_unit_cost']; ?></span></td>
        <td align="center"><span style="padding-left:10px"><?php print ($row_rs_drug_list['buy_unit_cost']*($row_rs_drug_list['among']/$row_rs_drug_list['pack_ratio'])); ?></span></td>
        <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($row_rs_drug_list['confirm']==NULL)){ ?><td align="center"><a href="javascript:poppage('drug_take_add.php?id=<?php echo $row_rs_drug_list['id']; ?>&do=edit','500','500','','show','drug_take_list.php','drug_take_list','');"><img src="images/Pencil-icon.png" width="20" height="20" border="0" /></a><a href="javascript:dosubmit('<?php echo $row_rs_drug_list['id']; ?>','delete','drug_take_list2.php','drug_take_list','');"><img src="images/delete.png" width="20" height="18" border="0" /></a></td><?php } ?>
      </tr>
      <?php } while ($row_rs_drug_list = mysql_fetch_assoc($rs_drug_list)); ?>
      <tr>
        <td colspan="7" align="center" style="border-top: dotted 1px #000000; border-bottom: dotted 1px #000000"><span class="big_black16">ราคารวม</span> <span class=" big_red16"><?php echo number_format($sumprice,2); ?></span> <span class="big_black16">บาท</span></td>
         <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($row_rs_drug_list['confirm']==NULL)){ ?><td align="center">&nbsp;</td><?php } ?>
      </tr>      
    
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rs_drug_list);

mysql_free_result($rs_setting);
?>
