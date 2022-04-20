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


?>
<?php
 $type_check=array('1','2','3','4','5');
if (in_array($_SESSION['hospcode_type_id'], $type_check, true)) {
 $condition=" v.hospcode != '".$_SESSION['hospcode']."' ";
 }
 else{
 $condition=" v.hospcode = '".$_SESSION['hospcode']."'";	 
	}
	
mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode = "select v.hospcode from drug_import v left outer join hospcode h on h.hospcode=v.hospcode  where ".$condition." and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' group by v.hospcode";
$rs_hospcode = mysql_query($query_rs_hospcode, $vmi) or die(mysql_error());
$row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
$totalRows_rs_hospcode = mysql_num_rows($rs_hospcode);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
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
function formSubmit(sID,displayDiv,indicator,eID) {
	if(sID!=''){ $('#do').val(sID);}
	if(eID!=''){ $('#id').val(eID);}
	 var URL = "drug_profile_list.php"; 
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}
</script>
<!--//////////////////////////////////////// -->
<!-- fancybox -->
	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="include/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="include/jquery.fancybox.js?v=2.1.3"></script>
	<link rel="stylesheet" type="text/css" href="include/jquery.fancybox.css?v=2.1.2" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="include/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="include/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="include/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="include/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="include/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

	<script type="text/javascript">
$(document).ready(function() {
	$('#fancybox').fancybox({
		//'title'	: 'Drug Interacion',
		'type' : 'iframe',
		'autoSize': true,
		'autoScale': false,
		maxWidth : 1200,
		minHeight   : 500,
		arrows : false,
		beforeClose :function(){formSubmit('Q','displayIndiv','indicator');}
	});
	$('#fancybox2').fancybox({
		//'title'	: 'Drug Interacion',
		'type' : 'iframe',
		'autoSize': true,
		'autoScale': false,
		'width' : '95%',
		'height'   : '95%',
		arrows : false,
	});
	$('#fancybox3').fancybox({
		//'title'	: 'Drug Interacion',
		'type' : 'iframe',
		'autoSize': true,
		'autoScale': false,
		maxWidth : 1200,
		minHeight   : 500,
		arrows : false,
		beforeClose :function(){formSubmit('AN','displayIndiv','indicator');}
	});
	
		$('#fancybox4').fancybox({
		//'title'	: 'Drug Interacion',
		'type' : 'iframe',
		'autoSize': true,
		'autoScale': false,
		maxWidth : 1200,
		minHeight   : 500,
		arrows : false,
	});


});	</script>
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
	</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
      <td class="gray table_head_small_bord" ><img src="images/contact-center-reporting-icon.png" width="31" height="33" align="absmiddle" />&nbsp; <span class="big_black16">อัตราการใช้ยาของสถานบริการ</span></td>
  </tr>
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
        <table width="639" border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td height="58" valign="top" class="table_head_small"><input type="hidden" name="id" id="id" />
              <input type="hidden" name="do" id="do" />
              <input name="hospcode1" type="hidden" id="hospcode1" value="<?php echo $_SESSION['hospcode']; ?>" />

              <select name="dept" id="dept"  class="thsan-light  font18 inputcss1">             <?php if (in_array($_SESSION['hospcode_type_id'], $type_check, true)) {
?><option value="">=== ทั้งหมด ===</option><?php } ?>
                
                <?php
do {  
mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode1 = "select hosptype,name,hospcode from hospcode where hospcode = '".$row_rs_hospcode['hospcode']."' and name !=''";
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
            <br />
            <select name="month" id="month" class="inputcss1 thsan-light font18">
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
              <select name="year" id="year" class="inputcss1 thsan-light font18">
                <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
                <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
                <?php } ?>
              </select>
              -
              <select name="month2" id="month2" class="inputcss1 thsan-light font18">
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
              <select name="year2" id="year2" class="inputcss1 thsan-light font18">
                <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
                <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
                <?php } ?>
              </select>
            <input type="button" name="button12" id="button12" value="ค้นหา"  onclick="formSubmit('search','form1','drug_profile_list.php','displayDiv','indicator')" class="button gray2" style="margin-top:5px"/></td>
          </tr>
        </table>
      </form>
      <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div>
      <div id="displayDiv">&nbsp;</div>
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_hospcode);
?>
