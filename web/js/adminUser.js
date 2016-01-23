//用户,所有数据都通过ajax请求
function addUser() {
	document.getElementById("coverDiv").style.top = '0px';
	document.getElementById("SetDiv").style.top = '5%';
	document.getElementById("SetDiv").style.opacity = 1;
}
//ajax插入用户
var daojishinum=2;
function daojishi () {
	$("#createResult").html("保存成功！"+daojishinum+"秒后将自动关闭此页面");
	daojishinum=daojishinum-1;
	setTimeout("daojishi()",1000);				
	
}
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
				setTimeout("hideAll()",3500);
				$("#createResult").html("保存成功！3秒后将自动关闭此页面");
				setTimeout("daojishi()",1000);				
				setTimeout("window.location.reload();",3800);


			} else {
				$("#createResult").html("出现错误：" + data.msg);
			}  
		},
		error: function(jqXHR){
		   alert("发生错误：" + jqXHR.status);  
		},
	});
}
//搜索并且渲染页面
function searchUserhandle() {
	$("#userMsgShow").empty();
	$.ajax({
	    type: "GET",
		url: "index.php?r=ajaxuser/adminsearchuser&searchuser="+$("#searchUser").val(),
		dataType: "json",
		success: function(data){
			if (data.success) {
				if (data.users == '') {
					var tableBody = '<h1>没有此人</h1>';
				} else {
					var tableBody = '<thead><tr><td>编号</td><td>学号</td><td>姓名</td><td>权限</td><td>电话</td><td>重置密码</td></tr></thead><tbody>';
					for (var i = 0; i < data.users.length; i++) {
						tableBody += '<tr><td>'+(i+1)+'</td><td>'+data.users[i].XH_ID+'</td><td>'+data.users[i].Name+'</td><td>'+data.users[i].Authority+'</td><td>'+data.users[i].phone+'</td><td><div class="Set_dele glyphicon glyphicon-wrench" onclick="resetPass(&quot;'+data.users[i].XH_ID+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteUser(&quot;'+data.users[i].XH_ID+'&quot;)"></div></td></tr>';
					};
					tableBody += '</tbody>';
				};
				document.getElementById('userMsgShow').innerHTML = tableBody;
			} else {
				document.getElementById('userMsgShow').innerHTML = "出现错误：" + data.msg;
			}
		},
		error: function(jqXHR){
		   alert("发生错误：" + jqXHR.status);  
		},
	});
}
//重置密码
function resetPass(XH_ID) {
	var deleteOrNot = confirm('确定重置:'+XH_ID+' 的密码？');
	if(deleteOrNot == true) {
		$.ajax({
		    type: "POST",
			url: "index.php?r=ajaxuser/resetpass",
			data: {
				XH_ID: XH_ID,
			},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					alert('重置成功');
					window.location.href('index.php?r=admin/index');
				} else {
					alert('重置失败');
					window.location.href('index.php?r=admin/index');
				}
			},
			error: function(jqXHR){
				alert("error:"+jqXHR.status);
			},
		});
	}
}
//删除用户(有问题？)
function deleteUser(XH_ID) {
	var deleteOrNot = confirm('确定删除:'+XH_ID+'？');
	if(deleteOrNot == true) {
		$.ajax({ 
		    type: "POST",
			url: "index.php?r=ajaxuser/deleteone",
			data: {
				XH_ID: XH_ID,
			},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					alert('删除成功');
					window.location.reload();
				} else {
					alert('删除失败');
					window.location.reload();
				}
			},
			error: function(jqXHR){
				alert("error:"+jqXHR.status);
			},
		});
	}
}

function searchUser() {

}