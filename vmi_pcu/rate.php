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

mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode = "select v.hospcode from drug_import v left outer join hospcode h on h.hospcode=v.hospcode  where h.chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' and v.hospcode != '".$_SESSION['hospcode']."' group by v.hospcode";
$rs_hospcode = mysql_query($query_rs_hospcode, $vmi) or die(mysql_error());
$row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
$totalRows_rs_hospcode = mysql_num_rows($rs_hospcode);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.10.custom.css" rel="stylesheet" />	
<?php include('java_function.php'); ?>
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
	</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="10" >
  <tr>
    <td class="gray"><img src="images/chart_up.png" width="33" height="33" align="absmiddle" />&nbsp;อัตราการใช้ยาของแต่ละหน่วยงาน</td>
  </tr>
  <tr>
    <td style="padding-left:0px"><form id="form1" name="form1" method="post" action="">
      <table width="800" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
        <tr>
          <td width="3%">&nbsp;</td>
          <td width="14%">เลือกหน่วยงาน</td>
          <td width="83%"><select name="hospcode1" id="hospcode1" onchange = "ListOrder(this.value)">
            <option value="">ทั้งหมด</option>
            <?php
do {  
mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode1 = "select hosptype,name from hospcode where hospcode = '$row_rs_hospcode[hospcode]'";
$rs_hospcode1 = mysql_query($query_rs_hospcode1, $vmi) or die(mysql_error());
$row_rs_hospcode1 = mysql_fetch_assoc($rs_hospcode1);
$totalRows_rs_hospcode1 = mysql_num_rows($rs_hospcode1);
?>
            <option value="<?php echo $row_rs_hospcode['hospcode']?>"><?php echo $row_rs_hospcode1['hosptype']." ".$row_rs_hospcode1['name']?></option>
            <?php
} while ($row_rs_hospcode = mysql_fetch_assoc($rs_hospcode));
  $rows = mysql_num_rows($rs_hospcode);
  if($rows > 0) {
      mysql_data_seek($rs_hospcode, 0);
	  $row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
  }
?>
          </select>
            <input type="hidden" name="id" id="id" />
            <input type="hidden" name="do" id="do" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>เลือกช่วงวันที่</td>
          <td><select name="month" id="month">
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
            <select name="year" id="year">
              <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
              <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
              <?php } ?>
            </select>
            -
            <select name="month2" id="month2">
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
            <select name="year2" id="year2">
              <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
              <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
              <?php } ?>
            </select>
            <input type="button" name="button12" id="button12" value="ค้นหา"  onclick="formSubmit('search','form1','drug_profile_list.php','displayDiv','indicator')" class="button gray2"/></td>
        </tr>
      </table>
      <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div>
      <div id="displayDiv">&nbsp;</div>
    </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_hospcode);
?>
