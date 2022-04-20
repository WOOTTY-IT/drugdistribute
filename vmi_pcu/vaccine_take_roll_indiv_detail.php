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
if($_GET['deptcode']!=""){ $condition=" and t.hospcode='".$_GET['deptcode']."'"; }

mysql_select_db($database_vmi, $vmi);
$query_rs_drug_indiv = "SELECT d.vaccine_name,d.vaccine_factor,d.doses,t.*,concat(h.hosptype,h.name) as hospname,ceiling((target*vaccine_factor)/doses) as request FROM vaccine_take t left outer join vaccines d on d.id=t.vaccine_id left outer join hospcode h on h.hospcode=t.hospcode WHERE concat(t.year,'-',t.month)='".$_GET['month']."' ".$condition." and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."'  and t.vaccine_id='".$_GET['action_id']."' and h.name !=''  ORDER BY d.vaccine_name";

$rs_drug_indiv = mysql_query($query_rs_drug_indiv, $vmi) or die(mysql_error());
$row_rs_drug_indiv = mysql_fetch_assoc($rs_drug_indiv);
$totalRows_rs_drug_indiv = mysql_num_rows($rs_drug_indiv);
?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">

<table width="800" border="0" cellpadding="3" cellspacing="0" class="table_head_small table_collapse" >
    <tr>
      <td width="40%" rowspan="<?php echo $totalRows_rs_drug_indiv+2; ?>" align="right" valign="top" ><img src="images/line_detail.gif" width="93" height="30" /></td>
      <td bgcolor="#B1E2FA"  style="padding-left: 10px; " class="rounded_top_left">หน่วยงาน</td>
      <td align="center" bgcolor="#B1E2FA" width="20%">เป้าหมาย</td>
      <td align="center" bgcolor="#B1E2FA" width="20%">คงเหลือ</td>
      <td align="center" bgcolor="#B1E2FA" class="rounded_top_right" width="20%">จำนวนเบิก</td>
    </tr>
  <?php 
    $sum=0;

  do { 
  if($row_s_drug['qty']!=0){$i++;}
  if($bgcolor=="#FFFFFF") { $bgcolor="#F4FAFB"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

  ?>
  <?php  if($row_rs_drug_indiv['remain']>=$row_rs_drug_indiv['request']){ $request= 0;} else  { $request= $row_rs_drug_indiv['request']-$row_rs_drug_indiv['remain'];}?>
    <?php if($request!=0){ ?><tr class="grid4">
    <td  bgcolor="<?php echo $bgcolor; ?>" width="40%" style="padding-left: 10px;border-left: 1px #BDD9FD solid;border-bottom: 1px #BDD9FD solid" ><?php echo $row_rs_drug_indiv['hospname']; ?>&nbsp;</td>
      <td align="center"  class="rounded_top_right" bgcolor="<?php echo $bgcolor; ?>" style="border-left: 1px #BDD9FD solid; border-right: 1px #BDD9FD solid;border-bottom: 1px #BDD9FD solid"><?php echo $row_rs_drug_indiv['target']; ?></td>
      <td align="center"  class="rounded_top_right" style="border-left: 1px #BDD9FD solid; border-right: 1px #BDD9FD solid;border-bottom: 1px #BDD9FD solid" bgcolor="<?php echo $bgcolor; ?>"  ><?php echo $row_rs_drug_indiv['remain']; ?></td>

      <td  align="center" bgcolor="<?php echo $bgcolor; ?>"  style="border-left: 1px #BDD9FD solid; border-right: 1px #BDD9FD solid;border-bottom: 1px #BDD9FD solid" ><?php echo $request; ?></td>

       
</tr>
    <?php 
	}
	$sum+=$request;
	 } while ($row_rs_drug_indiv = mysql_fetch_assoc($rs_drug_indiv)); ?>
    <tr>
      <td colspan="3">รวม</td>
      <td align="center" ><?php echo $sum; ?></td>
    </tr> 
</table><?php
mysql_free_result($rs_drug_indiv);
?>
