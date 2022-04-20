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



mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "select * from dept_group where hospmain='".$_SESSION['hospcode']."' order by dept_group_name ASC";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);

mysql_select_db($database_vmi, $vmi);
$query_rs_vaccine = "select * from vaccines order by vaccine_name ASC";
$rs_vaccine = mysql_query($query_rs_vaccine, $vmi) or die(mysql_error());
$row_rs_vaccine = mysql_fetch_assoc($rs_vaccine);
$totalRows_rs_vaccine = mysql_num_rows($rs_vaccine);

mysql_select_db($database_vmi, $vmi);
$query_rs_project = "select * from vaccine_project";
$rs_project = mysql_query($query_rs_project, $vmi) or die(mysql_error());
$row_rs_project = mysql_fetch_assoc($rs_project);
$totalRows_rs_project = mysql_num_rows($rs_project);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>

<script type="text/javascript">
$(document).ready(function(e) {
    $('.item_add').hide();
	$('#vaccine').change(function(){
	if($('#vaccine').val()!=""){
    $('.item_add').show();
		}
	else{
    $('.item_add').hide();		
	}
	});

});

function clearform(){
	$('#vaccine').val("");
	$('#project').val("");
	$('.item_add').hide();
	}
	
function vaccine_sel(id)
	{
		if(id!=""){
		switch(id)
		{
			<?
			mysql_select_db($database_vmi, $vmi);
			$strSQL = "SELECT * from vaccines";
			$objQuery = mysql_query($strSQL);
			while($objResult = mysql_fetch_array($objQuery))
			{
			?>
				case "<?=$objResult["id"];?>":
		$('#doses').text("<?php print $objResult["doses"]." dose/ขวด";?>");
		
		$('#factor').text("<?php print $objResult["vaccine_factor"];?>");
		break;
			<?
			}
			?>
		}
		}
	}

</script>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body onload="formSubmit('load','form1','vaccine_list.php','vaccine_list','indicator','');">

<table width="100%" border="0" cellpadding="3" cellspacing="0" class=" thsan-bold font20">
  <tr>
    <td height="32" class="gray" style="padding-left:10px" colspan="2"><img src="images/icon_42151.png" width="30" height="30" align="absmiddle" />&nbsp; <span class=" thsan-semibold font18">กำหนดรายการวัคซีน</span></td>
  </tr>
</table>
<form id="form1" name="form1" method="post" action="vaccine_add.php" style="margin-top:10px">
  <table width="500" border="0" cellspacing="0" cellpadding="5" class="thsan-light font16">
  <tr>
    <td width="68" align="right" class="font_bord">เลือกวัคซีน</td>
    <td width="412"><label for="vaccine"></label>
      <select name="vaccine" id="vaccine" onchange="vaccine_sel(this.value);" class="thsan-light font16 inputcss1">
        <option value="">=== เลือกวัคซีน ===</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_vaccine['id']?>"><?php echo $row_rs_vaccine['vaccine_name']?></option>
        <?php
} while ($row_rs_vaccine = mysql_fetch_assoc($rs_vaccine));
  $rows = mysql_num_rows($rs_vaccine);
  if($rows > 0) {
      mysql_data_seek($rs_vaccine, 0);
	  $row_rs_vaccine = mysql_fetch_assoc($rs_vaccine);
  }
?>
      </select>
      <input type="hidden" name="id" id="id" />
      <input type="hidden" name="do" id="do" /></td>
  </tr>
  <tr class="item_add">
    <td align="right" class="font_bord">บรรจุ</td>
    <td><div id="doses" style="color:#F00" class="font_bord font18"></div></td>
  </tr>
  <tr class="item_add">
    <td align="right" class="font_bord">factor</td>
    <td><label for="factor"></label>
      <input type="text" name="factor" id="factor" /></td>
  </tr>
  <tr class="item_add">
    <td align="right" class="font_bord">โครงการ</td>
    <td><label for="project"></label>
      <select name="project" id="project" class="thsan-light font16 inputcss1">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_project['id']?>"><?php echo $row_rs_project['vaccine_project']?></option>
        <?php
} while ($row_rs_project = mysql_fetch_assoc($rs_project));
  $rows = mysql_num_rows($rs_project);
  if($rows > 0) {
      mysql_data_seek($rs_project, 0);
	  $row_rs_project = mysql_fetch_assoc($rs_project);
  }
?>
      </select></td>
  </tr>
  <tr class="item_add">
    <td align="right">&nbsp;</td>
    <td><input type="button" name="save" id="save" value="บันทึก" onclick="formSubmit('save','form1','vaccine_list.php','vaccine_list','indicator','');clearform();" /></td>
  </tr>
  </table>
  <hr class="style-two" width="80%" align="left" />
<div id="vaccine_list" ></div>
      <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div> 

</form>
</body>
</html>
<?php
mysql_free_result($rs_dept);

mysql_free_result($rs_vaccine);

mysql_free_result($rs_project);
?>
