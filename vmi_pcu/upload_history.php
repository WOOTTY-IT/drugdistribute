<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="include/jquery.js"></script>

<link href="css/kohrx.css" rel="stylesheet" type="text/css" />

</head>
<?php 
date_default_timezone_set('Asia/bangkok');
?>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr>
        <td class="gray table_head_small_bord" ><img src="images/002.png" width="30" height="30" align="absmiddle" />&nbsp; ประวัติการอัพโหลดข้อมูลของแต่ละ รพ.สต.</td>
</tr><tr>
      <td style="padding-left:30px"><select name="year" id="year">
          <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
          <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
          <?php } ?>
      </select>
      <input type="button" name="search" id="search" class="button blue" onclick="formSubmit('search','form1','upload_list.php','upload_list','indicator')" value="ค้นหา" />
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