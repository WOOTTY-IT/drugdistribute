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
if(isset($_POST['save'])&&$_POST['save']=="บันทึก"){
mysql_select_db($database_vmi, $vmi);
$query_rs_area = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_area = mysql_query($query_rs_area, $vmi) or die(mysql_error());
$row_rs_area = mysql_fetch_assoc($rs_area);
$totalRows_rs_area = mysql_num_rows($rs_area);

	if($_POST['out_off_stock']==""){
		$out_off_stock="N";
		}
	else {
		$out_off_stock=$_POST['out_off_stock'];		
		}
	if($_POST['dbcenter']==""){
		$dbcenter="N";
		}
	else {
		$dbcenter=$_POST['dbcenter'];		
		}

	$end_date=$_POST['end_date'];

	if($totalRows_rs_area==0){
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into setting (chwpart,amppart) value ('".$_SESSION['chwpart']."','".$_SESSION['amppart']."')";
	$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	}
	
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update setting set  end_date='".$_POST['end_date']."',out_off_stock='".$out_off_stock."',stock_date='".$_POST['end_date2']."',vaccine_end_date='".$_POST['end_date3']."',vaccine_method='".$_POST['vaccine_method']."',dbcenter='".$dbcenter."',token_vaccine='".$_POST['token_vaccine']."' where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
	$rs_update = mysql_query($query_update, $vmi) or die(mysql_error());

mysql_free_result($rs_area);

}

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
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td style="padding-left:10px" class="gray"><img src="images/Settings-icon.png" width="35" height="35" align="absmiddle" /> &nbsp; <span class="table_head_small_bord">ตั้งค่าการทำงาน</span></td>
  </tr>
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="800" border="0" cellpadding="5" cellspacing="0" class=" thsan-light font20">
        <tr>
          <td align="right" class=" font_bord">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="267" align="right" class="font_bord">เบิกจ่ายได้ไม่เกินวันที่</td>
          <td width="513"><select name="end_date" class=" thsan-light font19 inputcss1" id="end_date">
          <?php for($i=1;$i<=30;$i++){?><option value="<?php echo $i; ?>" <?php if (!(strcmp($i, $row_rs_setting['end_date']))) { echo "selected=\"selected\""; } ?>><?php echo $i; ?></option><?php } ?>
    
          </select> 
            ของเดือน</td>
        </tr>
        <tr>
          <td align="right" class="font_bord">งานคลังย้อนหลังได้ไม่เกินวันที่</td>
          <td><select name="end_date2" class="thsan-light font19 inputcss1" id="end_date2">
            <?php for($i=1;$i<=30;$i++){?>
            <option value="<?php echo $i; ?>" <?php if (!(strcmp($i, $row_rs_setting['stock_date']))) { echo "selected=\"selected\""; } ?>><?php echo $i; ?></option>
            <?php } ?>
          </select>
            ของเดือนถัดไป</td>
        </tr>
        <tr>
          <td align="right" class="font_bord">วิธีการเบิกวัคซีน</td>
          <td><select name="vaccine_method" id="vaccine_method" class="thsan-light font19 inputcss1">
            <option value="1" <?php if (!(strcmp(1, $row_rs_setting['vaccine_method']))) {echo "selected=\"selected\"";} ?>>เบิกหลังฉีดวัคซีน</option>
            <option value="2" <?php if (!(strcmp(2, $row_rs_setting['vaccine_method']))) {echo "selected=\"selected\"";} ?>>เบิกก่อนฉีดวัคซีน</option>
          </select></td>
        </tr>
        <tr>
          <td align="right" class="font_bord">วันที่กำหนดฉีดวัคซีน</td>
          <td><select name="end_date3" class="thsan-light font19 inputcss1" id="end_date3">
            <?php for($i=1;$i<=30;$i++){?>
            <option value="<?php echo $i; ?>" <?php if (!(strcmp($i, $row_rs_setting['vaccine_end_date']))) { echo "selected=\"selected\""; } ?>><?php echo $i; ?></option>
            <?php } ?>
          </select>
            ของเดือน</td>
        </tr>
        <tr>
          <td align="right" class="font_bord">เบิกยาโดยใช้ datacenter</td>
          <td><input <?php if (!(strcmp($row_rs_setting['dbcenter'],"Y"))) {echo "checked=\"checked\"";} ?> name="dbcenter" type="checkbox" id="dbcenter" value="Y" />
            <label for="dbcenter"></label></td>
        </tr>
        <tr>
          <td align="right" class="font_bord">ไม่ให้เบิกจ่ายยาที่หมด stock</td>
          <td><input <?php if (!(strcmp($row_rs_setting['out_off_stock'],"Y"))) {echo "checked=\"checked\"";} ?> name="out_off_stock" type="checkbox" class="table_head_small" id="out_off_stock" value="Y" /></td>
        </tr>
        <tr>
          <td align="right" class="table_head_small_bord">Token Line notify</td>
          <td>วัคซีน 
            <label for="token_vaccine"></label>
            <input name="token_vaccine" type="text" id="token_vaccine" value="<?php echo $row_rs_setting['token_vaccine']; ?>" style="width:400px;" /></td>
        </tr>
        <tr>
          <td align="right" class="table_head_small_bord">&nbsp;</td>
          <td><input type="submit" name="save" id="save" value="บันทึก" class="button_black_bar thsan-light font19" style="width:200px" /></td>
        </tr>
        </table>
    </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_setting);

?>
