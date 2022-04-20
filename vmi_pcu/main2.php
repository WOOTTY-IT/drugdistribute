<?php
if($sub_page!=""){
	include($sub_page.".php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #ededed;
}
</style>
<?php include('java_function.php'); ?>
<script>
function test(){
	alert('asdf');
	}
</script>
<script type="text/javascript">
$(document).ready(function() { 
$("#dept_form").ajaxForm({target: '#preview',
		beforSubmit:$('#preview').html('<img src="images/indicator.gif" alt="Uploading..."/>'),
		clearForm:true}).submit();
});
 		</script>

</head>

<body>
</body>
</html>
