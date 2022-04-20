<?php
date_default_timezone_set('Asia/bangkok');
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

</head>

<body>
<form id="form1" name="form1" method="post" action="order_print.php" target="_new">
  <table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr>
      <td class="gray table_head_small_bord" ><img src="images/order2.png" width="39" height="39" align="absmiddle" />&nbsp;พิมพ์ใบเบิกยาจากระบบ datacenter</td>
    </tr>
    <tr>
      <td style="padding-left:30px"><input name="dept" type="hidden" id="dept" value="<?php echo $hospcode; ?>" />
        <select name="month" id="month" class="thfont inputcss1">
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
        <select name="year" id="year" class="thfont inputcss1">
          <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
          <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
          <?php } ?>
        </select>
        <input type="submit" name="search" id="search" class="button gray" value="พิมพ์" />
        <input type="hidden" name="id" id="id" />
      <input type="hidden" name="do" id="do" />
      <input name="disable_button" type="hidden" id="disable_button" value="Y" />
      <input type="hidden" name="hospcode2" id="hospcode2" /></td>
    </tr>
    <tr>
      <td style="padding-left:30px"><table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div>
        <div id="displayDiv">&nbsp;</div></td>
  </tr>
</table>
</td>
    </tr>
  </table>
</form>
</body>
</html>