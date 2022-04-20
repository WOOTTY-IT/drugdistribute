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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div style="height:30px; padding:10px" class="thsan-semibold font20 gray">ระบบเบิกยา</div>
<div style="padding:20px;" class=" thsan-light font18"><img src="images/number-1-icon-63209.png" width="30" height="30" border="0" align="absmiddle" /><a href="index.php?sub_page=drug_take2" class="thsan-light" style="color:#000000; text-decoration:none" target="_parent"> เบิก on demand</a>  
  </p>
  <p><a href="index.php?sub_page=iodine_ferrous" class="thsan-light" style="color:#000000; text-decoration:none" target="_parent"><img src="images/number-2.png" width="30" height="30" border="0" align="absmiddle" /> ยาโครงการยาเสริมธาตุเหล็ก</a></p>
  <p onclick="window.open(
  'index.php?sub_page=vaccine_take',
  '_parent' );"  style="cursor:pointer"><img src="images/number-3-icon-63211 (1).png" width="30" height="30" align="absmiddle" />&nbsp;เบิกวัคซีน</p>
</div>
</body>
</html>
