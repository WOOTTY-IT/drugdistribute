<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_online.php'); ?>
<script>
$(document).ready(function() {
	$('#indicator').hide();
	$('#keyword').focus();
 	$('#keyword').bind('keyup', function(e) {
            if(e.which == '13'){ //enter
        
				$('#indicator').show();
				$("#search_result").load('datacenter_patient_search_result.php?keyword='+encodeURIComponent($('#keyword').val()), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
				$('#indicator').hide();
				  //alert("External content loaded successfully!");
				if(statusTxt == "error")
				  alert("Error: " + xhr.status + ": " + xhr.statusText);
				});
			}
  });
  
  //datatable
  $('#t1').DataTable( {
        "paging":   false,
        "info":     false
    } );
  
  //option chang
   
});


</script>
	
</head>

<body>
<div style="padding:5px; background-color: #EFEFEF">
    <div class="row" >
		<label for="keyword" class="col-form-label col-sm-4">&ensp;คำค้น&nbsp;(hn,cid,ชื่อ นามสกุล)&nbsp;</label>
		<div class="col-sm-8">
			<input type="text" id="keyword" name="keyword" class="form-control" />
		</div>	
	</div>
</div>
<div id="indicator" class="p-3" style="margin-left: 40%">
<button class="btn btn-light">
  <span class="spinner-border spinner-border-sm"></span>
  Loading..
</button>
</div>
<div id="search_result" >
</body>
</html>