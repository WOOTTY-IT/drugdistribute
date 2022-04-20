<?php
if($_GET['sub_page']!=""){
	include($_GET['sub_page'].".php");
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
<script type="text/javascript">
function dept_group_edit(a,b,c){
$('#id').val(a);
$('#do').val(c);
$('#dept').val(b);
}
$(document).ready(function() { 

$("#dept_form").ajaxForm({
		target: '#preview',
		beforSubmit:$('#preview').html('<img src="images/loader.gif" alt="Uploading..."/>'),
		clearForm:true,
		success:function(){$('#id').val("");$('#do').val("");$('#dept').val("");}
		}).submit();
});

function page_load(divid,page){
	$('#indicator').show();
	$("#"+divid).load(page,function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
		$('#indicator').hide();            
        if(statusTxt == "error")
            alert("โหลดข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง");
		$('#indicator').hide();            
    });
	}


</script>

</head>

<body>
</body>
</html>
