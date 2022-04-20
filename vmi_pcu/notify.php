<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="include/jquery.js"></script>
<script>
	function bodyOnload()
{
	$( "#vaccine" ).load( "vaccine_checker.php?chwpart=<?php echo $_GET['chwpart']; ?>&amppart=<?php echo $_GET['amppart']; ?>" );
	setTimeout("doLoop();",3600000);
}

function doLoop()
{
	bodyOnload();
}
</script>

</head>

<body onLoad="doLoop()">
<div style="font-size:20px; padding:10px;">
ระบบแจ้งเตือน Line Notify
</div>
<div id="vaccine" style="border: solid 1px #000000"></div>
</body>
</html>