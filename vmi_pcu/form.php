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
<script src="include/jquery.js"></script>

<style type="text/css">
body {
	background-color: #ededed;
}
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="10" style=" border:solid 1px #76C2CB;box-shadow: 5px 5px 20px -8px #888, -5px 0px 10px -7px #888;" class="thsan-light font20">
  <tr>
    <td bgcolor="#BCD8E7"><p><span class=" thsan-bold" style="font-size:30px">เลือกไฟล์ที่ทำการอัพโหลด </span><br />
    </p><?php if(date('j')<=$row_rs_setting['end_date']){?>
      <form action="upload.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
        ข้อมูลเดือน 
        <select name="month" id="month" class="thsan-light  font18 inputcss1">
          <option value="01" <?php if (!(strcmp("1", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>มกราคม</option>
          <option value="02" <?php if (!(strcmp("2",(ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>กุมภาพันธ์</option>
          <option value="03" <?php if (!(strcmp("3", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>มีนาคม</option>
          <option value="04" <?php if (!(strcmp("4", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>เมษายน</option>
          <option value="05" <?php if (!(strcmp("5", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>พฤษภาคม</option>
          <option value="06" <?php if (!(strcmp("6", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>มิถุนายน</option>
          <option value="07" <?php if (!(strcmp("7", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>กรกฎาคม</option>
          <option value="08" <?php if (!(strcmp("8", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>สิงหาคม</option>
          <option value="09" <?php if (!(strcmp("9", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>กันยายน</option>
          <option value="10" <?php if (!(strcmp("10", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>ตุลาคม</option>
          <option value="11" <?php if (!(strcmp("11", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>พฤศจิกายน</option>
          <option value="12" <?php if (!(strcmp("12", (ltrim(date('m'),'0')-1)))) {echo "selected=\"selected\"";} ?>>ธันวาคม</option>
        </select>
        <select name="year" id="year" class="thsan-light  font18 inputcss1">
          <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
          <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
          <?php } ?>
        </select>
<input type="file" name="fileCVS" id="fileCVS" />
        <input type="submit" name="button" id="button" value="Submit" />
    </form><?php } else { echo "เกินกำหนดวันที่อนุณาตให้อัพโหลดช้อมูลแล้วครับ"; } ?></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_setting);
?>
