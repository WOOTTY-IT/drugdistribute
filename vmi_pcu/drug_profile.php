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
 $condition=" l.dept_code != '".$_SESSION['hospcode']."' ";
 }
 else{
 $condition=" l.dept_code = '".$_SESSION['hospcode']."'";	 
	}
	
mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode = "select h.hospcode,d.dept_group_name from dept_group d left outer join dept_list l on l.dept_group=d.dept_group_id left outer join hospcode h on h.hospcode=l.dept_code where ".$condition." and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' group by l.dept_code";
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
<script>
$(document).ready(function(){
    $('#search').click(function(){
        $('#indicator').show();
                $("#displayDiv").load('drug_profile_list.php?do=search&dept='+$('#dept').val()+'&month='+$('#month').val()+'&year='+$('#year').val()+'&month2='+$('#month2').val()+'&year2='+$('#year2').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
    });
});
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
<?php include('java_css_online.php'); ?>

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
        html,body{overflow-y: hidden}
	</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />

</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-secondary">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#"><img src="images/contact-center-reporting-icon.png" width="31" height="33" align="absmiddle" />&nbsp; อัตราการใช้ยาของสถานบริการ&ensp;<button class="btn btn-primary" onClick="window.open('antibiotic_report.php','_new');">ข้อมูลการใช้ยาปฏิชีวนะ</button></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">

    </div>
</nav>
<div class="p-3">
<div class="card">
<div class="card-body">
<div class="row">
    <label class="col-form-label col-form-label-sm col-sm-1">หน่วยงาน</label>
    <div class="col-sm-2">
            <input type="hidden" name="do" id="do" />
            <input name="hospcode1" type="hidden" id="hospcode1" value="<?php echo $_SESSION['hospcode']; ?>" />
              <select name="dept" id="dept"  class="form-control"><?php if (in_array($_SESSION['hospcode_type_id'], $type_check, true)) {
?><option value="">=== ทั้งหมด ===</option><?php } ?>
                
                <?php
do {  
?>
  <option value="<?php echo $row_rs_hospcode['hospcode']?>"><?php echo $row_rs_hospcode['dept_group_name']; ?></option>
                <?php
} while ($row_rs_hospcode = mysql_fetch_assoc($rs_hospcode));
  $rows = mysql_num_rows($rs_hospcode);
  if($rows > 0) {
      mysql_data_seek($rs_hospcode, 0);
	  $row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
  }
?>
              </select>

    </div>
    <label class="col-form-label col-form-label-sm col-sm-auto">เลือกช่วงเดือน</label>
    <div class="col-sm-1">
             <select name="month" id="month" class="form-control">
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
    </div>
        <div class="col-sm-auto">
            <select name="year" id="year" class="form-control" >
              <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
              <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
              <?php } ?>
            </select>
    </div>
    <label class="col-form-label col-form-label-sm col-sm-auto">ถึง</label>
    
        <div class="col-sm-auto">              
              <select name="month2" id="month2" class="form-control">
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
    </div>
        <div class="col-sm-auto">
              <select name="year2" id="year2"  class="form-control">
                <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
                <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
                <?php } ?>
              </select>
    </div>
        <div class="col-sm-auto">
    <input type="button" name="search" id="search" value="ค้นหา" class="button gray2" style="margin-top:5px"/>
            <input type="hidden" name="id" id="id" />
        </div>
    </div>
</div>
</div> <!-- card-body -->
</div> <!-- card -->
</div>


      <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div>
      <div id="displayDiv" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:85vh;"></div>

</body>
</html>
<?php
mysql_free_result($rs_hospcode);
?>
