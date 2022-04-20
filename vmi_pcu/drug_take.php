<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>

<script type="text/javascript">
function buttonchange(){
	$('#save').val("บันทึก");
	$('#save').attr('onclick', 'formSubmit("save","form1","spending_report_list.php","spending_list","indicator");clearForm("sub_po_no","id","do");');
	}
function edit(a,b,c,d,e,f){
	$('#id').val(a);
	$('#sub_po_no').val(b);
	$('#month').val(c);
	$('#year').val(d);
	$('#dept').val(e);
	$('#save').val("แก้ไข");
	$('#save').attr('onclick', 'formSubmit("edit","form1","spending_report_list.php","spending_list","indicator",'+a+');clearForm("sub_po_no","id","do");buttonchange();');
	
	}
</script>
</head>

<body >
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="10" class="thsan-light font18">
    <tr>
      <td class="gray font_bord font20" ><img src="images/Icon-Document03-Blue.png" width="40" height="40" align="absmiddle" />&nbsp;&nbsp;ทะเบียนการเบิกจ่าย</td>
    </tr>
    <tr>
      <td>&nbsp;<span class="table_head_small">ประจำเดือน 
        <select name="month" class="inputcss1" id="month">
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
        <select name="year" class="inputcss1" id="year">
          <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
          <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
          <?php } ?>
        </select>
      </span>&nbsp; <input type="button" name="save" id="save" value="ค้นหา" class="button gray2" onclick="formSubmit('save','form1','drug_take_list.php','spending_list','indicator');" />
<span style="padding-left:30px">
<input type="hidden" name="id" id="id" />
<input type="hidden" name="do" id="do" />
</span></td>
    </tr>
    <tr>
      <td><div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/loader.gif" hspace="10" align="absmiddle" /></div>
  <div id="spending_list"></div></td>
    </tr>
  </table>
</form>
</body>
</html>