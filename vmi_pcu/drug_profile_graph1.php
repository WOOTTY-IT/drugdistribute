<?php
ob_start();
session_start();
?>
<?php require_once('Connections/vmi.php'); ?>
<?php require_once('Connections/datacenter.php'); ?>

<?php 

mysql_select_db($database_vmi, $vmi);
$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

	if($_GET['hospcode4']!=""){
	$condition="m.hospcode='".$_GET['hospcode4']."' ";
	}
	else {
	$condition=" h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."'";	
	}
	
$eyear=substr($_GET['date_serv'],0,4);
$emonth=substr($_GET['date_serv'],5,2);
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
</head>

<body>
<p>
  <?php
	$datecount=cal_days_in_month(CAL_GREGORIAN, $emonth, $eyear);
	
	if($row_rs_setting['dbcenter']=="N"){
	mysql_select_db($database_vmi, $vmi);
	$strlimit1 = "select date_serv,dname,sum(amount) as qty from drug_import m left outer join hospcode h on h.hospcode=m.hospcode where ".$condition." and  substring(date_serv,1,7)='".$_GET['date_serv']."' and didstd='".$_GET['stdcode']."' group by dname,date_serv";
	$result = mysql_query($strlimit1, $vmi) or die(mysql_error());
	$ors = mysql_fetch_assoc($result);
	}
	else{
	mysql_select_db($database_datacenter, $datacenter);
	$strlimit1 = "select vstdate as date_serv,drug_name as dname,sum(qty) as qty from datacenter_drug_opd m left outer join hospcode h on h.hospcode=m.hospcode where ".$condition." and  substring(vstdate,1,7)='".$_GET['date_serv']."' and did='".$_GET['stdcode']."' group by drug_name,vstdate";
	$result = mysql_query($strlimit1, $datacenter) or die(mysql_error());
	$ors = mysql_fetch_assoc($result);
		
	}

	//Generate the graph element
	$strXML="<graph caption='ปริมาณการใช้ ".$ors['dname']." ในเดือน ".$emonth."/".($eyear+543)."' PYAxisName='amount of use'  formatNumberScale='0'  >";
	// Fetch all factory records
	//หาวันที่ เพื่อ limit
	
	
	
	if ($result) {
		do {
		$strXML .= "<set name='".substr($ors['date_serv'],8,2)."' value='".$ors['qty']."' link='drug_profile_detail.php?stdcode=".$_GET['stdcode']."&date_serv=".$ors['date_serv']."&hospcode4=".$_GET['hospcode4']."' />";
			//free the resultset
		}while($ors = mysql_fetch_array($result));
	}
		//Finally, close <graph> element
	$strXML .= "</graph>";
	echo $strXML;
	//Create the chart - Pie 3D Chart with data from $strXML
    echo renderChart("include/FusionCharts/FCF_Column2D.swf", "", $strXML, "FactorySum", 800, 400, false, false);
?>
</p>
<p align="center">
  <input type="button" name="back" id="back" value="ย้อนกลับ" onclick="window.history.back();" class="button blue" />
</p>
</body>
</html>
<?php
mysql_free_result($rs_setting);
?>