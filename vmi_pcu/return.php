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
$query_rs_drug = "SELECT  drug_name,std_ratio,standard_code,working_code FROM inv_md WHERE drug_name not like '*%' order by drug_name ASC";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.10.custom.css" rel="stylesheet" />	


<script>	
function resutName(icode)
	{
		switch(icode)
		{
			<?
			mysql_select_db($database_vmi, $vmi);
			$strSQL = "SELECT * FROM inv_md ";
			$objQuery = mysql_query($strSQL,$vmi) or die(mysql_error());
			while($objResult = mysql_fetch_array($objQuery))
			{
			?>
				case "<?=$objResult["standard_code"];?>":
				form1.pack.value = "<?=$objResult["std_ratio"];?>";
				form1.sunit.value = "<?=$objResult["sale_unit"];?>";
							
				break;
			<?
			}
			?>
			default:
			 form1.pack.value = "";
			  form1.sunit.value = "";
		}
	}

</script>

</head>

<body onload="formSubmit('','displayDiv','indicator');">
<p class="big_red16">บันทึกค้างจ่ายยา</p>
<form id="form1" name="form1" method="post" action="return_process.php">
  <table width="800" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td width="121">เลือกหน่วยงาน</td>
      <td width="671"><select name="dept" id="dept">
        <option value="03886">หัวเมือง</option>
        <option value="03888">คุ้ม</option>
        <option value="03890">หัวดอน</option>
        <option value="03893">โพธิ์ศรี</option>
        <option value="03896">ชัยชนะ</option>
      </select>
      <input type="hidden" name="do" id="do" />
      <input type="hidden" name="id" id="id" /></td>
    </tr>
    <tr>
      <td>เลือกยา</td>
      <td><select name="drug" id="drug" onchange="resutName(this.value)">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_drug['standard_code']?>"><?php echo $row_rs_drug['drug_name']?></option>
        <?php
} while ($row_rs_drug = mysql_fetch_assoc($rs_drug));
  $rows = mysql_num_rows($rs_drug);
  if($rows > 0) {
      mysql_data_seek($rs_drug, 0);
	  $row_rs_drug = mysql_fetch_assoc($rs_drug);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td>จำนวน</td>
      <td><input name="qty" type="text" id="qty" size="5" />
      <input name="pack" type="text" id="pack" size="10" disabled="disabled" />
      <input name="sunit" type="text" id="sunit" size="5" disabled="disabled" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="button" name="button" id="button" value="เพิ่ม" onclick="formSubmit('insert','form1','return_list.php','displayDiv','indicator')" /></td>
    </tr>
    <tr>
      <td colspan="2"> <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="displayDiv">&nbsp;</div></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_drug);
?>
