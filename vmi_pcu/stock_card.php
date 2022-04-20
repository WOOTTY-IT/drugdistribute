<?
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

mysql_select_db($database_vmi, $vmi);
$query_rs_item = "select d.working_code,d.drug_name from stock_card s left outer join drugs d on d.working_code=s.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' where s.hospcode='".$_SESSION['hospcode']."' group by d.working_code order by drug_name ASC  ";
$rs_item = mysql_query($query_rs_item, $vmi) or die(mysql_error());
$row_rs_item = mysql_fetch_assoc($rs_item);
$totalRows_rs_item = mysql_num_rows($rs_item);

mysql_select_db($database_vmi, $vmi);
$query_rs_date = "select concat(substr(DATE_ADD(CURDATE(),interval -30 DAY),9,2),'/',substr(DATE_ADD(CURDATE(),interval -30 DAY),6,2),'/',(substr(DATE_ADD(CURDATE(),interval -30 DAY),1,4)+543)) as last_date";
$rs_date = mysql_query($query_rs_date, $vmi) or die(mysql_error());
$row_rs_date = mysql_fetch_assoc($rs_date);
$totalRows_rs_date = mysql_num_rows($rs_date);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
div.divhead{
	
	position:fixed;
	top:0;
	width:100%;
	font-size:23px;
	padding:10px;
	border-bottom:solid 1px #CCCCCC;
	background-color:#ededed;
	}
div.divcontent{
	margin-top:60px;
	padding:10px;
	width:98%;	
	}
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>

<script>
   function btn_print(){
		OpenPopup('stock_card_print.php','100%','100%','&date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&item='+$('#item').val());
   }
</script>
</head>

<body>
<div class="thsan-light divhead "><img src="images/stock_card.PNG" width="37" height="40" align="absmiddle"> &nbsp;&nbsp;สต๊อกการ์ด ( Stock Card)</div>
<div class="divcontent thsan-light font18" >
  <form name="form1" method="post" action="stock_card.php"><table width="500" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td width="79">รายการยา</td>
    <td width="293"><label for="item"></label>
      <select name="item" id="item" class="thsan-light font18 inputcss1">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_item['working_code']?>"<?php if (!(strcmp($row_rs_item['working_code'], $_GET['did']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_item['drug_name']?></option>
        <?php
} while ($row_rs_item = mysql_fetch_assoc($rs_item));
  $rows = mysql_num_rows($rs_item);
  if($rows > 0) {
      mysql_data_seek($rs_item, 0);
	  $row_rs_item = mysql_fetch_assoc($rs_item);
  }
?>
      </select></td>
    <td width="110"><input type="hidden" name="do" id="do">
      <input type="hidden" name="id" id="id"></td>
  </tr>
  <tr>
    <td>ช่วงวันที่</td>
    <td><span class="input-group" style="width:200px">
      <input type="text" id="date1" name="date1"  class=" inputcss1 thsan-light font18" style="width:100px" value="<?php if(isset($_POST['date1'])){ echo $date1; } else { echo $row_rs_date['last_date']; } ?>"  />
    ถึง <span class="input-group" style="width:200px">
    <input type="text" id="date2" name="date2"  class=" inputcss1 thsan-light font18" style="width:100px" value="<?php if(isset($_POST['date1'])){ echo $date1; } else { echo date('d/m/').(date('Y')+543); } ?>"  />
    </span></span></td>
    <td><input type="button" name="search" id="search" value="แสดง" style="height:35px ; width:80px; text-align:center; padding:10px; padding-top:4px; cursor:pointer" class="thsan-light font18" onClick="formSubmit('search','form1','stock_card_list.php','divload','indicator');"></td>
  </tr>
</table>

    <div  id="divload" style="margin-left:0px; margin-top:10px;"></div>
      <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div> 
      </form>


</div>

</body>
</html>
<?php
mysql_free_result($rs_item);

mysql_free_result($rs_date);


?>
