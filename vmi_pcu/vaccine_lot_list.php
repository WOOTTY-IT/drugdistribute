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
$query_rs_vaccine1 = "select v.vaccine_name,v.id from vaccine_lot l left outer join vaccines v on v.id=l.vaccine_id where date_take='".$_GET['date_current']."' and chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' group by v.id order by v.vaccine_name ASC";
$rs_vaccine1 = mysql_query($query_rs_vaccine1, $vmi) or die(mysql_error());
$row_rs_vaccine1 = mysql_fetch_assoc($rs_vaccine1);
$totalRows_rs_vaccine1 = mysql_num_rows($rs_vaccine1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($totalRows_rs_vaccine1 > 0) { // Show if recordset not empty ?>
  <div class=" thfont font14" style="padding-left:20px">
    รายการจ่ายวัคซีนตาม lot และวันหมดอายุ
  </div>
  <div>
    
  </div>
  <div class="thfont font12" style="padding:20px">
    <table width="500" border="0" cellspacing="0" cellpadding="3">
      <?php do {
		

	mysql_select_db($database_vmi, $vmi);
	$query_rs_vaccine3 = "select vaccine_lot,vaccine_exp,vaccine_id,date_take from vaccine_lot where date_take='".$_GET['date_current']."' and vaccine_id='".$row_rs_vaccine1['id']."' and chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' group by vaccine_lot";
	$rs_vaccine3 = mysql_query($query_rs_vaccine3, $vmi) or die(mysql_error());
	$row_rs_vaccine3 = mysql_fetch_assoc($rs_vaccine3);
	$totalRows_rs_vaccine3 = mysql_num_rows($rs_vaccine3);		

	?>
      <tr>
        <td bgcolor="#CCCCCC"><?php echo $row_rs_vaccine1['vaccine_name']; ?></td>
      </tr>
      <?php do { 
 	mysql_select_db($database_vmi, $vmi);
	$query_rs_vaccine2 = "select l.*,h.name from vaccine_lot l left outer join hospcode h on h.hospcode=l.hospcode where l.date_take='".$_GET['date_current']."' and l.vaccine_id='".$row_rs_vaccine1['id']."' and l.vaccine_lot='".$row_rs_vaccine3['vaccine_lot']."' and l.chwpart='".$_SESSION['chwpart']."' and l.amppart='".$_SESSION['amppart']."'";
	$rs_vaccine2 = mysql_query($query_rs_vaccine2, $vmi) or die(mysql_error());
	$row_rs_vaccine2 = mysql_fetch_assoc($rs_vaccine2);
	$totalRows_rs_vaccine2 = mysql_num_rows($rs_vaccine2);
 ?>
      <tr>
        <td style="padding-left:10px; border-bottom:dashed 1px #000000">lot: <?php echo $row_rs_vaccine3['vaccine_lot']; ?>&nbsp;&nbsp;วันหมดอายุ&nbsp;:&nbsp;<?php echo $row_rs_vaccine3['vaccine_exp']; ?>&nbsp;&nbsp;<img src="images/gtk-edit.png" width="16" align="absmiddle" height="16" style="cursor:pointer" onclick="alertload1('vaccine_lot_add.php?date1=<?php echo $row_rs_vaccine3['date_take']; ?>&vaccine_id=<?php echo $row_rs_vaccine3['vaccine_id']; ?>&vaccine_lot=<?php echo $row_rs_vaccine3['vaccine_lot']; ?>&action=edit&date_current=<?php echo $_GET['date_current']; ?>','800','500');" /></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <?php $intRows = 0; 
	  do{
		  $intRows++;
		  


		  
	  ?>
            <td style="padding-left:10px"><table width="100%" border="0" cellspacing="0" >
              <tr>
                <td ><?php echo $row_rs_vaccine2['name']; ?></td>
                </tr>
              </table></td>
            <?
		if(($intRows)%4==0)
		{
		echo"</tr>";
		}
		else
		{
		echo "<td>";
		}	
	}while ($row_rs_vaccine2 = mysql_fetch_assoc($rs_vaccine2)); ?>
            </tr>
        </table></td>
      </tr>
      <?php
  mysql_free_result($rs_vaccine2);
 }  while ($row_rs_vaccine3 = mysql_fetch_assoc($rs_vaccine3));
  mysql_free_result($rs_vaccine3);

  } while ($row_rs_vaccine1 = mysql_fetch_assoc($rs_vaccine1)); ?>
      
    </table>
    
  </div>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rs_vaccine1);

?>
