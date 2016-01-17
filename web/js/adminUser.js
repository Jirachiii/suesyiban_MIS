//用户,所有数据都通过ajax请求
function allUser(){
	$.ajax({
		type: "GET",
		url: "index.php?r=json/getuserdata",
		dataType: "json",
		success: function(data) {
			if (data.success) {

			} else {
				
			}
		},
		error: function(jqXHR){
			alert("error:"+jqXHR.status);
		},
	});
}

function addUser() {
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/search&classmark=" + $("#keyword").val(),
		dataType: "json",
		success: function(data) {
			if (data.success) {
			} else {
			}
		},
		error: function(jqXHR){
			alert("error:"+jqXHR.status);
		},
	});
}

function IDontKnowHowToNameIt() {
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/search&classmark=" + $("#keyword").val(),
		dataType: "json",
		success: function(data) {
			if (data.success) {
			} else {
			}
		},
		error: function(jqXHR){
			alert("error:"+jqXHR.status);
		},
	});
}

function userAuthority() {
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/search&classmark=" + $("#keyword").val(),
		dataType: "json",
		success: function(data) {
			if (data.success) {
			} else {
			}
		},
		error: function(jqXHR){
			alert("error:"+jqXHR.status);
		},
	});
}

function forgetPass() {
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/search&classmark=" + $("#keyword").val(),
		dataType: "json",
		success: function(data) {
			if (data.success) {
			} else {
			}
		},
		error: function(jqXHR){
			alert("error:"+jqXHR.status);
		},
	});
}