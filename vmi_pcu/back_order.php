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
$query_rs_hospcode = "select h.hosptype,h.name,v.hospcode from vmi_order v left outer join hospcode h on h.hospcode=v.hospcode group by v.hospcode";
$rs_hospcode = mysql_query($query_rs_hospcode, $vmi) or die(mysql_error());
$row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
$totalRows_rs_hospcode = mysql_num_rows($rs_hospcode);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>

<script language = "JavaScript">

		//**** List Province (Start) ***//
		function ListOrder(SelectValue)
		{
			form1.order_month.length = 0
			
			//*** Insert null Default Value ***//
			var myOption = new Option('','')  
			form1.order_month.options[form1.order_month.length]= myOption
			
			<?
			mysql_select_db($database_vmi, $vmi);		
			$intRows = 0;
			$strSQL = "SELECT * FROM vmi_order group by order_month ORDER BY order_month DESC ";
			$rs_strSQL = mysql_query($strSQL, $vmi) or die(mysql_error());
			$intRows = 0;
			while($objResult = mysql_fetch_array($rs_strSQL))
			{
			$intRows++;
			?>			
				x = <?=$intRows;?>;
				mySubList = new Array();
				
				strValue = "<?=$objResult["hospcode"];?>";
				strItem = "<?=$objResult["order_month"];?>";
				mySubList[x,0] = strItem;
				mySubList[x,1] = strValue;
				if (mySubList[x,1] == SelectValue){
					var myOption = new Option(mySubList[x,0], mySubList[x,0])  
					form1.order_month.options[form1.order_month.length]= myOption					
				}
			<?
			}
			?>																	
		}

</script>

</head>

<body>
<p>เรียกดูใบเบิกย้อนหลัง</p>
<form id="form1" name="form1" method="post" action="requisition.php" target="_blank">
  <table width="500" border="0" cellpadding="3" cellspacing="0" class="head_small_gray">
    <tr>
      <td width="28%">1. เลือกหน่วยงาน</td>
      <td width="72%"><select name="hospcode1" id="hospcode1" onChange = "ListOrder(this.value)">
        <option value="">เลือก</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_hospcode['hospcode']?>"><?php echo $row_rs_hospcode['hosptype']." ".$row_rs_hospcode['name']?></option>
        <?php
} while ($row_rs_hospcode = mysql_fetch_assoc($rs_hospcode));
  $rows = mysql_num_rows($rs_hospcode);
  if($rows > 0) {
      mysql_data_seek($rs_hospcode, 0);
	  $row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td>2. เลือกใบเบิก(ปี-เดือน)</td>
      <td><select id="order_month" name="order_month"  ></select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="ดาว์นโหลด" /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_hospcode);
?>
