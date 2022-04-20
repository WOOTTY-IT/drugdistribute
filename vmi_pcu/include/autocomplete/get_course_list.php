<?php require_once('../../Connections/hos.php'); ?>
<?php
require_once "config.php";
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "select shortlist,drugusage,name1,name2,name3,code from drugusage where shortlist LIKE '$q%' and status='Y'";
$rsd = mysql_query($sql);

while($rs = mysql_fetch_array($rsd)) {
	$cname = $rs['shortlist'];
	$ccode=  $rs['drugusage'];
	$name1=  $rs['name1'];
	$name2=  $rs['name2'];
	$name3=  $rs['name3'];
	$code2=	$rs['code'];

	echo "$cname|$ccode|$name1|$name2|$name3|$code2\n";
}
?>