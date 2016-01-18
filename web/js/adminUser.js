//用户,所有数据都通过ajax请求
function addUser() {
	document.getElementById("coverDiv").style.top = '0px';
	document.getElementById("SetDiv").style.top = '5%';
	document.getElementById("SetDiv").style.opacity = 1;
}
//ajax插入用户
function insertUser(){
	$.ajax({ 
	    type: "POST",
		url: "index.php?r=ajaxuser/admininsertuser",
		data: {
			classmark: $("#insertClassmark").val(), 
			name: $("#insertName").val(), 
			phone: $("#insertPhone").val(), 
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				$("#createResult").html(data.msg);
			} else {
				$("#createResult").html("出现错误：" + data.msg);
			}  
		},
		error: function(jqXHR){
		   alert("发生错误：" + jqXHR.status);  
		},
	});
}
function searchUser() {

}

function forgetPass() {
	document.getElementById("coverDiv").style.top = '0px';
	document.getElementById("SetDiv").style.top = '5%';
	document.getElementById("SetDiv").style.opacity = 1;
}

function searchUser() {

}