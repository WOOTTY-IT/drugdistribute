<?php require_once('Connections/vmi.php'); ?>

<?php 
ob_start();
session_start();

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
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>
<script>
function datacenter_import(){
	page_load('data_import','datacenter_import_process.php?month_rate='+$('#month').val()+'&year_rate='+$('#year').val()+'&action=import&dept='+$('#dept').val());
}
</script>
</head>

<body>
<div class="gray thfont font12" style="padding:10px;">นำเข้าข้อมูลจากฐานข้อมูลกลาง (datacenter)</div>
<div class="thfont font12" style="padding:10px;" id="data_import">
  <form id="form1" name="form1" method="post" action="">
    นำเข้าเดือน 
    <select name="month" id="month" class="thsan-light  font18 inputcss1">
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
    </select>
    <select name="dept" id="dept" onchange = "ListOrder(this.value)" class="thsan-light  font18 inputcss1">
      <option value="">ทั้งหมด</option>
      
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
    </select>
<input type="button" name="analyze" id="analyze" class=" btn btn-primary thsan-light font19" value="นำเข้า" style="height:30px; padding-top:0px;" onclick="datacenter_import();" />
  </form>
</div>
<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div>
</body>
</html>