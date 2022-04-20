<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('.tbdiv').hide();
});

function divhide(a){
	$('.tbdiv').hide();
	$('#'+a+'1').show();
	$('#'+a+'1').css({"border-top":"dashed 1px #000000"});
	}

</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr>
        <td class="gray table_head_small_bord" ><img src="images/operational-analytics-icon.png" width="38" height="38" align="absmiddle" />&nbsp; ประมาณการ การเบิกยาของ รพ.สต. ในแต่ละเดือน</td>
</tr><tr>
      <td style="padding-left:30px"><select name="month" id="month">
        <option value="01" <?php if (!(strcmp("01", date('m')))) {echo "selected=\"selected\"";} ?>>มกราคม</option>
        <option value="02" <?php if (!(strcmp("02", date('m')))) {echo "selected=\"selected\"";} ?>>กุมภาพันธ์</option>
        <option value="03" <?php if (!(strcmp("03", date('m')))) {echo "selected=\"selected\"";} ?>>มีนาคม</option>
        <option value="04" <?php if (!(strcmp("04", date('m')))) {echo "selected=\"selected\"";} ?>>เมษายน</option>
        <option value="05" <?php if (!(strcmp("05", date('m')))) {echo "selected=\"selected\"";} ?>>พฤษภาคม</option>
        <option value="06" <?php if (!(strcmp("06", date('m')))) {echo "selected=\"selected\"";} ?>>มิถุนายน</option>
        <option value="07" <?php if (!(strcmp("07", date('m')))) {echo "selected=\"selected\"";} ?>>กรกฎาคม</option>
        <option value="08" <?php if (!(strcmp("08", date('m')))) {echo "selected=\"selected\"";} ?>>สิงหาคม</option>
        <option value="09" <?php if (!(strcmp("09", date('m')))) {echo "selected=\"selected\"";} ?>>กันยายน</option>
        <option value="10" <?php if (!(strcmp("10", date('m')))) {echo "selected=\"selected\"";} ?>>ตุลาคม</option>
        <option value="11" <?php if (!(strcmp("11", date('m')))) {echo "selected=\"selected\"";} ?>>พฤศจิกายน</option>
        <option value="12" <?php if (!(strcmp("12", date('m')))) {echo "selected=\"selected\"";} ?>>ธันวาคม</option>
      </select>
        <select name="year" id="year">
          <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
          <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
          <?php } ?>
      </select>
        <label for="data_type"></label>
        <select name="data_type" id="data_type">
          <option value="1">ข้อมูลใช้จริง</option>
          <option value="2">ข้อมูลดำเนินการ</option>
        </select>
<input type="button" name="search" id="search" class="button blue" onclick="formSubmit('search','form1','drug_forecast_list.php','upload_list','indicator')" value="ค้นหา" />
      <input type="hidden" name="id" id="id" />
      <input type="hidden" name="do" id="do" /></td>
    </tr>
<tr>
  <td style="padding-left:30px">  <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/loader.gif" hspace="10" align="absmiddle" /></div>
  <div id="upload_list"></div>
</td>
</tr>
  </table>
</form>
</body>
</html>