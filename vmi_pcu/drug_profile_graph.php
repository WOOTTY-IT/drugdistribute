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

 
ob_start();
session_start();

mysql_select_db($database_vmi, $vmi);
$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

//We've included ../Includes/FusionCharts.php and ../Includes/DBConn.php, which contains
//functions to help us easily embed the charts and connect to a database.
include("include/FusionCharts.php");
//include("include/DBConn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<SCRIPT LANGUAGE="Javascript" SRC="include/FusionCharts/FusionCharts.js"></SCRIPT>

<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><p>
      <?php
if($_GET['do']="simple"){
	if($hospcode3!=""){$condition= " and m.hospcode='".$_GET['hospcode3']."'";}
	
		$date11=explode("/",$date1);
		$edate1=($date11[2]-543)."-".$date11[1]."-".$date11[0];
		$esdate1=substr($date11[2],2,4).$date11[1].$date11[0];
		
		$date11=explode("/",$date2);
		$edate2=($date11[2]-543)."-".$date11[1]."-".$date11[0];
		$esdate2=substr($date11[2],2,4).$date11[1].$date11[0];	

	
	$count=12;
	//หาวันที่ เพื่อ limit
	mysql_select_db($database_vmi, $vmi);
	$strlimit = "select concat((year_rate+543),'-',month_rate) as date_serv1,dname from month_rate m left outer join drug_import d on d.didstd=m.didstd and d.hospcode=m.hospcode left outer join hospcode h on h.hospcode=m.hospcode where  concat(year_rate,'-',month_rate) between '".$_GET['date1']."' and '".$_GET['date2']."' and m.didstd='".$_GET['stdcode']."' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' ".$condition." group by concat(year_rate,'-',month_rate)  ";	
$rlimit = mysql_query($strlimit, $vmi) or die(mysql_error());
	$row_rlimit = mysql_fetch_assoc($rlimit);
	$totalRows_rlimit = mysql_num_rows($rlimit);

	//Generate the graph element
	$strXML="<graph caption='อัตราการใช้ยา ".$row_rlimit['dname']."' PYAxisName='Drug count' SYAxisName='Drug count' numberPrefix='' showvalues='0' numDivLines='4' formatNumberScale='0' decimalPrecision='0' anchorSides='10' anchorRadius='3' anchorBorderColor='009900'>";
	// Fetch all factory records

	if($totalRows_rlimit>=12){
	$totalvstdate=$totalRows_rlimit-12;
	}
	else{
	$totalvstdate=0;		
	}
	

mysql_select_db($database_vmi, $vmi);
$query_rs_2d = "select concat(year_rate,'-',month_rate) as date_serv1,sum(qty) as qty from month_rate m left outer join hospcode h on h.hospcode=m.hospcode  where  m.didstd='".$_GET['stdcode']."' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' and concat(year_rate,'-',month_rate) between '".$_GET['date1']."' and '".$_GET['date2']."' ".$condition."  group by concat(year_rate,'-',month_rate)";
echo $query_rs_2d;
$rs_2d = mysql_query($query_rs_2d, $vmi) or die(mysql_error());
$row_rs_2d = mysql_fetch_assoc($rs_2d);
$totalRows_rs_2d = mysql_num_rows($rs_2d);

	mysql_select_db($database_vmi, $vmi);
	$strlimit1 = "select concat(year_rate,'-',month_rate) as date_serv1,sum(qty) as qty from month_rate m left outer join hospcode h on h.hospcode=m.hospcode  where  m.didstd='".$_GET['stdcode']."' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' and concat(year_rate,'-',month_rate) between '".$_GET['date1']."' and '".$_GET['date2']."' ".$condition."  group by concat(year_rate,'-',month_rate)";
	$result1 = mysql_query($strlimit1, $vmi) or die(mysql_error());
	$ors1 = mysql_fetch_assoc($result1);

	mysql_select_db($database_vmi, $vmi);
	$strlimit2 = "select sum(qty)/$totalRows_rlimit as avgqty from month_rate m left outer join hospcode h on h.hospcode=m.hospcode where  didstd='".$_GET['stdcode']."' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' and  concat(year_rate,'-',month_rate) between '".$_GET['date1']."' and '".$_GET['date2']."' ".$condition;
	$result2 = mysql_query($strlimit2, $vmi) or die(mysql_error());
	$ors2 = mysql_fetch_assoc($result2);

	
	//Iterate through each factory
	if ($rlimit) {
		$strXML.="<categories>";
		do {
		$strXML.="<category name='".$row_rlimit['date_serv1']."' />";
		}	while($row_rlimit = mysql_fetch_assoc($rlimit)); 

		$strXML.="</categories>";

	}
	
	$strXML.="<dataset seriesName='ยา' color='1D8BD1' showValues='0' >";
	
	if ($rs_2d) {
		do {
		$strXML .= "<set value='".$row_rs_2d['qty']."' link='drug_profile_graph1.php?do=drilldown&stdcode=".$stdcode."&date_serv=".$row_rs_2d['date_serv1']."&hospcode4=".$hospcode3."'/>";
			//free the resultset
		}		while($row_rs_2d = mysql_fetch_assoc($rs_2d));
	}
	
	$strXML.="</dataset>";
	$strXML.="<dataset seriesName='เส้นแนวโน้ม' color='FF3300' showValues='1' parentYAxis='S'>";
	if ($result1) {
	do{
		$strXML .= "<set value='".$ors1['qty']."' />";
			//free the resultset
		} 		while($ors1 = mysql_fetch_assoc($result1)); 
	}
	$strXML.="</dataset>";
	
	//Finally, close <graph> element
	$strXML .= "</graph>";
	echo $strXML;
	//Create the chart - Pie 3D Chart with data from $strXML
    echo renderChart("include/FusionCharts/FCF_MSColumn2DLineDY.swf", "", $strXML, "FactorySum", 800, 400, false, false);
	}
?>
    </p>
    <p><span class="small_red_bord">อัตราการใช้เฉลี่ยต่อเดือน</span> = <?php echo number_format($ors2['avgqty']); ?></p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_2d);
mysql_free_result($rs_setting);

?>
