<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>

<script>
function page_load(divid,page){
	$('#indicator').show();
	$("#"+divid).load(page,function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
		$('#indicator').hide();            
        if(statusTxt == "error")
            alert("โหลดข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง");
		$('#indicator').hide();            

    });
	}

$(document).ready(function() {
    $('#save').click(function(){
	alertload1('vaccine_lot_add.php?date1='+$('#year').val()+'-'+$('#month').val(),'800','500');
	});
    $('#save2').click(function(){
   page_load('vaccine','vaccine_lot_list.php?date_current='+$('#year').val()+'-'+$('#month').val());
	});

   page_load('vaccine','vaccine_lot_list.php?date_current='+$('#year').val()+'-'+$('#month').val());

});

function vaccine_list(){
	page_load('vaccine','vaccine_lot_list.php?date_current='+$('#year').val()+'-'+$('#month').val());
	}
</script>
</head>

<body>
  <form id="form1" name="form1" method="post" action="" >

<div style="height:50px; padding-top:20px; padding-left:20px" class="gray thsan-semibold font18" ><img src="images/edit_property.png" width="32" height="32" align="absmiddle" />&nbsp;&nbsp;บันทึก lot และวันหมดอายุ การจ่ายวัคซีน</div>
<div style="padding:20px" class="thsan-light font16">
  ประจำเดือน <span class="table_head_small">
  <select name="month" class="inputcss1 thsan-light font18" id="month">
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
  <select name="year" class="inputcss1 thsan-light font18" id="year">
    <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
    <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
    <?php } ?>
  </select>
  </span>
  <input type="button" name="save" id="save" value="เพิ่ม" class="button gray2"  />
  <input type="button" name="save2" id="save2" value="แสดง" class="button gray2"  />
  </div> 
<div id="vaccine">&nbsp;</div>
<div id="indicator"  align="center" style="position:relative; top:500px; display:none; z-index:1000;padding:0px;"> <img src="images/indicator.gif" hspace="10" align="absmiddle" />&nbsp;</div>
 </form>
</body>
</html>