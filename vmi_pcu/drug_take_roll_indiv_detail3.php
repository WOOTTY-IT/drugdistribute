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
$query_rs_drug_indiv = "SELECT d.working_code,d.drug_name,t.qty,t.pack_ratio,concat(h.hosptype,h.`name`) as hospname  FROM vmi_order t left OUTER JOIN hospcode h on h.hospcode=t.hospcode left outer join drug_stdcode s on s.stdcode=t.stdcode and s.chwpart='".$_SESSION['chwpart']."' and s.amppart='".$_SESSION['amppart']."' left outer join drugs d on d.working_code=s.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' WHERE t.order_month='".$_GET['month']."' and d.working_code='".$_GET['action_id']."' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' group by t.hospcode ORDER BY t.qty DESC";
$rs_drug_indiv = mysql_query($query_rs_drug_indiv, $vmi) or die(mysql_error());
$row_rs_drug_indiv = mysql_fetch_assoc($rs_drug_indiv);
$totalRows_rs_drug_indiv = mysql_num_rows($rs_drug_indiv);
?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">

<table width="800" border="0" cellpadding="3" cellspacing="0" class="table_head_small table_collapse" >
    <tr>
      <td width="20%" rowspan="<?php echo $totalRows_rs_drug_indiv+2; ?>" align="right" valign="top" ><img src="images/line_detail.gif" width="93" height="30" /></td>
      <td bgcolor="#B1E2FA"  style="padding-left: 10px; " class="rounded_top_left">หน่วยงาน</td>
      <td align="center" bgcolor="#B1E2FA" class="rounded_top_right"  >จำนวนเบิก</td>
    </tr>
  <?php do { 
  				if($row_rs_drug_indiv['qty']!=0){$i++;}
  if($bgcolor=="#FFFFFF") { $bgcolor="#F4FAFB"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

  ?>
    <tr>
    <td width="57%" bgcolor="<?php echo $bgcolor; ?>" style="padding-left: 10px;border-left: 1px #BDD9FD solid;border-bottom: 1px #BDD9FD solid" ><?php echo $row_rs_drug_indiv['hospname']; ?>&nbsp;</td>
      <td width="23%" align="center" bgcolor="<?php echo $bgcolor; ?>"  style="border-left: 1px #BDD9FD solid; border-right: 1px #BDD9FD solid;border-bottom: 1px #BDD9FD solid" ><?php echo ($row_rs_drug_indiv['qty']); ?></td>
       
</tr>
    <?php } while ($row_rs_drug_indiv = mysql_fetch_assoc($rs_drug_indiv)); ?>
    <tr>
      <td  >&nbsp;</td>
      <td align="center" >&nbsp;</td>
    </tr> 
</table>
<?php
mysql_free_result($rs_drug_indiv);
?>
