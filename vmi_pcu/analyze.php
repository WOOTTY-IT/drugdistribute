<?php require_once('Connections/vmi.php'); ?>
<?php 
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
?>
<?php
mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode = "select v.hospcode from month_rate v left outer join hospcode h on h.hospcode=v.hospcode  where  h.chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'  group by v.hospcode";
$rs_hospcode = mysql_query($query_rs_hospcode, $vmi) or die(mysql_error());
$row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
$totalRows_rs_hospcode = mysql_num_rows($rs_hospcode);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.10.custom.css" rel="stylesheet" />	
<script src="include/jquery.js" type="text/javascript"></script>
<script  src="include/ajax_framework.js"></script>
<script src="include/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.10.offset.datepicker.min.js"></script>
<script type="text/javascript" src="include/ui.datepicker-th.js"></script>

<script>
jQuery(function($){ 
  $("#date1").mask("99/99/9999"); 
  $("#time1").mask("99:99");
  $("#time2").mask("99:99");
  $("#time3").mask("99:99");
  });
</script>
<script type="text/javascript">
function formSubmit(sID,URL,form,displayDiv,indicator,eID) {
	if(sID!=''){ $('#do').val(sID);}
	if(eID!=''){ $('#id').val(eID);}
	var data = getFormData(form);
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}
	
function bring(){
var inps = document.getElementsByName('order_bring[]');
var sumus= document.getElementsByName('sum_use[]');
var packs= document.getElementsByName('pack_ratio[]');
var remains= document.getElementsByName('remain[]');

for (var i = 0; i <inps.length; i++) {
var inp=inps[i];sumu=sumus[i];pack=packs[i];remain=remains[i];
	remain.value=sumu.value-(inp.value*pack.value);	
	if(remain.value<=0){
		remain.value=0;
		}
    //alert("order_bring["+i+"].value="+inp.value);
	}
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />

<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="10" class="thsan-light font18">
    <tr>
      <td class="gray font_bord font20" ><img src="images/agile_development_logo2.png" width="41" height="41" align="absmiddle" style="padding:5px" />&nbsp;&nbsp;<span class=" thsan-semibold">เตรียมการวิเคราะห์เพื่อดำเนินการเบิกจ่าย </span></td>
    </tr>
    <tr>
      <td>      <form id="form1" name="form1" method="post" action="process_vmi.php" target="_self">
<table width="100%" border="0" cellspacing="0" class="thsan-light font19">
  <tr>
    <td style="padding:20px">
        <table width="600" border="0" cellpadding="5" cellspacing="0" class="pad5">
          <tr>
            <td width="86">เลือกช่วงวัน</td>
            <td width="565"><select name="month" id="month" class="thsan-light  font18 inputcss1">
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
              </select></td>
          </tr>
          <tr>
            <td>หน่วยงาน</td>
            <td><select name="dept" id="dept" onchange = "ListOrder(this.value)" class="thsan-light  font18 inputcss1">
              <?php
do {  
mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode1 = "select hosptype,name from hospcode where hospcode = '".$row_rs_hospcode['hospcode']."' and name !=''";
$rs_hospcode1 = mysql_query($query_rs_hospcode1, $vmi) or die(mysql_error());
$row_rs_hospcode1 = mysql_fetch_assoc($rs_hospcode1);
$totalRows_rs_hospcode1 = mysql_num_rows($rs_hospcode1);
?>
<option value="<?php echo $row_rs_hospcode['hospcode']?>"><?php echo $row_rs_hospcode1['hosptype']." ".$row_rs_hospcode1['name']?></option>
              <?php
      mysql_free_result($rs_hospcode1);
    
} while ($row_rs_hospcode = mysql_fetch_assoc($rs_hospcode));
  $rows = mysql_num_rows($rs_hospcode);
  if($rows > 0) {
      mysql_data_seek($rs_hospcode, 0);
	  $row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label for="process_type"></label>
              <select name="process_type" id="process_type" class="thsan-light  font18 inputcss1">
                <option value="1">ยังไม่ดำเนินการ</option>
                <option value="2">ดำเนินการแล้ว</option>
              </select></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="button" name="analyze" id="analyze" class=" btn btn-primary thsan-light font19" value="วิเคราะห์"  onclick="formSubmit('search','form1','analyzing.php','displayDiv','indicator')"/>
              <input type="hidden" name="id" id="id" />
              <input type="hidden" name="do" id="do" /></td>
          </tr>
        </table>
    </td>
  </tr>
</table><div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div>
<div id="displayDiv">&nbsp;</div></form>
</td>
    </tr>
  </table>
</body>
</html>
<?php
mysql_free_result($rs_hospcode);
?>
