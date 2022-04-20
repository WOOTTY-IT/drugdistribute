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

if(isset($_POST['do'])&&$_POST['do']=="delete"){
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from drug_return where id='$id'";
	$rs_delete = mysql_query(		$query_delete, $vmi) or die(mysql_error());

}

if(isset($_POST['do'])&&$_POST['do']=="insert"){
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into drug_return (hospcode,stdcode,qty,date_return) value ('$dept','$drug','$qty','".date('Y-m')."')";
	$rs_insert = mysql_query(		$query_insert, $vmi) or die(mysql_error());
	}

mysql_select_db($database_vmi, $vmi);
$query_rs_drug_list = "SELECT r.id,drug_name,r.qty,r.date_return,i.std_ratio from drug_return r left join inv_md i on i.standard_code=substring(r.stdcode,1,19) where r.hospcode='$dept' and r.data_flag is null order by drug_name ASC";
$rs_drug_list = mysql_query($query_rs_drug_list, $vmi) or die(mysql_error());
$row_rs_drug_list = mysql_fetch_assoc($rs_drug_list);
$totalRows_rs_drug_list = mysql_num_rows($rs_drug_list);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body >
<?php if ($totalRows_rs_drug_list > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr class="gray2">
      <td width="5%" align="center">no.</td>
      <td width="61%" align="center">รายการยา</td>
      <td width="18%" align="center">จำนวน</td>
      <td width="16%" align="center">เดือน-ปี</td>
    </tr>
    <?php $i=0; do { $i++; ?>
    <tr>
      
      <td align="center"><?php echo $i; ?></td>
      <td align="left"><?php echo $row_rs_drug_list['drug_name']; ?></td>
      <td align="center"><?php echo "$row_rs_drug_list[qty]"; ?> x <?php echo "$row_rs_drug_list[std_ratio]"; ?></td>
      <td align="center"><?php echo "$row_rs_drug_list[date_return]"; ?> <a href="javascript:formSubmit('delete','displayDiv','indicator','<?php echo $row_rs_drug_list['id']; ?>')" onclick="return confirm('ยืนยันการลบข้อมูล?')"><img src="images/bin.png" width="16" height="16" border="0" align="absmiddle" /></a></td>
    </tr>
    <?php } while ($row_rs_drug_list = mysql_fetch_assoc($rs_drug_list)); ?>
    <tr>
      <td align="center">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center"><input type="submit" name="button" id="button" value="บันทึก" /></td>
      
    </tr>
    
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rs_drug_list);
?>
