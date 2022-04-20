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
$query_rs_drug_indiv = "SELECT concat(h.hosptype,h.`name`) as hospname,d.pack_ratio,sum(ceil(t.target/g.factor)) as among FROM iodine_ferrous t left outer join group_age g on g.group_age=t.group_age left outer join drugs d on d.working_code=g.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' left OUTER JOIN hospcode h on h.hospcode=t.hospcode WHERE SUBSTR(order_date,1,7) ='".$_GET['month']."' and g.working_code='".$_GET['action_id']."' ".$condition." group by t.hospcode,g.working_code order by among DESC";
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
  				if($row_s_drug['qty']!=0){$i++;}
  if($bgcolor=="#FFFFFF") { $bgcolor="#F4FAFB"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

  ?>
    <tr>
    <td width="57%" bgcolor="<?php echo $bgcolor; ?>" style="padding-left: 10px;border-left: 1px #BDD9FD solid;border-bottom: 1px #BDD9FD solid" ><?php echo $row_rs_drug_indiv['hospname']; ?>&nbsp;</td>
      <td width="23%" align="center" bgcolor="<?php echo $bgcolor; ?>"  style="border-left: 1px #BDD9FD solid; border-right: 1px #BDD9FD solid;border-bottom: 1px #BDD9FD solid" ><?php echo ($row_rs_drug_indiv['among']); ?></td>
       
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
