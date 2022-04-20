
function ajaxLoad(method, URL, data, displayId,indicatorId) {
	var ajax = null;
	if(window.ActiveXObject) {		
		ajax = new ActiveXObject("Microsoft.XMLHTTP");	
	}
	else if(window.XMLHttpRequest) {		
		ajax = new XMLHttpRequest();	
	}
	else {
		alert("Your browser doesn't support Ajax");
		return;
	}
	method = method.toLowerCase();
	URL += "?dummy=" + (new Date()).getTime();
	if(method=="get") {
		URL += "&" + data;
		data = null;
	}

	ajax.open(method, URL);

	if(method=="post") {
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	}
	
	ajax.onreadystatechange = function() {
		if(ajax.readyState==4 && ajax.status==200) {
			var ctype = ajax.getResponseHeader("Content-Type").toLowerCase();
			ajaxCallback(ctype, displayId, ajax.responseText,indicatorId);

			delete ajax;
			ajax = null;
		}
	}

	ajax.send(data);
}

function ajaxCallback(contentType, displayId, responseText,indicatorId) {
	document.getElementById(indicatorId).style.display = 'none'; // ซ่อน indicator	
	if(contentType.match("text/javascript")) {
		eval(responseText);
	}
	else {
		var el = document.getElementById(displayId);
		el.innerHTML = responseText;
	}
}

function getFormData(form_name_or_id) {
	
	var frm = document.forms[form_name_or_id];
	if(frm==null) {
		alert("form not found!");
		return;
	}

	var data = "";
	var num_el = frm.elements.length;
	for(i=0; i<num_el; i++) {
		var el = frm.elements[i];
		if(el.name=="" && el.id=="") {
			continue;
		}
		var param_name = "";
		if(el.name!="") {
			param_name = el.name;
		}
		else if(el.id!="") {
			param_name = el.id;
		}

		var t = frm.elements[i].type;
		var value = "";
		if(t=="text"||t=="password"||t=="hidden"||t=="textarea") {
			value = encodeURI(el.value);
		}
		else if(t=="radio"||t=="checkbox") {
			if(el.checked) {
				value = encodeURI(el.value);
			}
			else {
				continue;
			}
		}
		else if(t=="select-one") {
			value = encodeURI(el.options[el.selectedIndex].value);
		}
		else if(t=="select-multiple") {
			for(j=0; j<el.length; j++) {
				if(el.options[j].selected) {
					if(data!="") {
						data += "&";
					}
					data += param_name + "=";
					data += encodeURI(select.options[j].value);
				}
			}
			
			continue;
		}
		if(data!="") {
			data += "&";
		}
		data += param_name + "=" + value;
	}

	return data;
}


	   var HttPRequest = false;

	   function doCallAjax(Page) {
		  HttPRequest = false;
		  if (window.XMLHttpRequest) { // Mozilla, Safari,...
			 HttPRequest = new XMLHttpRequest();
			 if (HttPRequest.overrideMimeType) {
				HttPRequest.overrideMimeType('text/html');
			 }
		  } else if (window.ActiveXObject) { // IE
			 try {
				HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
			 } catch (e) {
				try {
				   HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			 }
		  } 

		  if (!HttPRequest) {
			 alert('Cannot create XMLHTTP instance');
			 return false;
		  }
	
			var url = 'main_table.php';
			var pmeters = 'myPage='+Page;
			HttPRequest.open('POST',url,true);

			HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HttPRequest.setRequestHeader("Content-length", pmeters.length);
			HttPRequest.setRequestHeader("Connection", "close");
			HttPRequest.send(pmeters);
			
			
			HttPRequest.onreadystatechange = function()
			{

				 if(HttPRequest.readyState == 3)  // Loading Request
				  {
				   document.getElementById("displayTable").innerHTML = "Now is Loading...";
				  }

				 if(HttPRequest.readyState == 4) // Return Request
				  {
				   document.getElementById("displayTable").innerHTML = HttPRequest.responseText;
				  }
				
			}

	   }
