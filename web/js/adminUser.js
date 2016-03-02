var nowPage_user=1
var allPage_user=1
var nowPage_user_s=1
var allPage_user_s=1
/**
 * 加载用户表
 */
function getRawData() {
	$("#userMsgShow").empty();
	$("#searchUser").val("")
	nowPage_user=1
	allPage_user=1
	nowPage_user_s=1
	allPage_user_s=1
	$("#user_prev").attr("onclick","beforePage_user()");
	$("#user_aft").attr("onclick","afterPage_user()");
	$("#yema_user_s").empty()
	$.getJSON('index.php?r=json/getuserdata', function(data, textStatus) {
		if (textStatus == 'success') {
			if(data.success){
				allPage_user=data.allPage
				var tableHead = '<thead><tr><td>学号</td><td>姓名</td><td>权限</td><td>电话</td><td>更改权限/重置密码/删除</td></tr></thead>';
				var tableBody = '<tbody>';
				for (var i = 0; i < data.users.length; i++) {
					tableBody += '<tr><td>'+data.users[i].XH_ID+'</td><td>'+data.users[i].Name+'</td><td>'+userOrAdmin(data.users[i].status)+'</td><td>'+data.users[i].phone+'</td><td><div class="Set_dele glyphicon glyphicon-wrench" onclick="changesuserstatus(\''+data.users[i].XH_ID+'\','+data.users[i].status+')"></div> | <div class="Set_dele glyphicon glyphicon-off" onclick="resetPass(&quot;'+data.users[i].XH_ID+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteUser(&quot;'+data.users[i].XH_ID+'&quot;)"></div></td></tr>';
				};
				tableBody += '</tbody>';
				document.getElementById('userMsgShow').innerHTML = tableHead+tableBody;
				$("#head a").first().attr("id",data.userIdNow).html(data.userName)
				//分页的
				$("#yema_user").empty();
				var yema="<span id='yema_user' class='yema_user'></span>"
				$("#user_prev").after(yema);
				for(i=1;i<=allPage_user;i++){
					$("#yema_user").append("<a id='"+i+"' onclick='yema_user("+i+")'>"+i+"</a>")
				}
				danqianye_user()
			}else{
				document.getElementById('userMsgShow').innerHTML =data.msg
			}
		} else {
			alert("系统错误" + textStatus);
		}
	});
}
function userOrAdmin(status) {
	if (status == 1) {
		return '管理员'
	} else {
		return '用户'
	}
}
//隐藏状态栏
function hideAll() {
	document.getElementById("coverDiv").style.top = '-999px';
	document.getElementById("SetDiv").style.top = '-600px';
	document.getElementById("SetDiv").style.opacity = 0;
}
//下拉手风琴
var show = 0;
function tiggle() {
	if (this.show == 0) {
		document.getElementById("showandhide").style.top = '51px';
		this.show = 1;
	} else {
		document.getElementById("showandhide").style.top = '-100%';
		this.show = 0;
	}
}
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
				setTimeout("getRawData();",3800);


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
	nowPage_user=1
	allPage_user=1
	nowPage_user_s=1
	allPage_user_s=1
	$("#yema_user").empty()
	$("#userMsgShow").empty();
	$("#user_prev").attr("onclick","beforePage_user_s()");
	$("#user_aft").attr("onclick","afterPage_user_s()");
	$.ajax({
	    type: "GET",
		url: "index.php?r=ajaxuser/adminsearchuser&searchuser="+$("#searchUser").val(),
		dataType: "json",
		data: {
			page: nowPage_user_s
		},
		success: function(data){
			allPage_user_s=data.allPage
			if (data.success) {
				if (data.users == '') {
					var tableBody = '<h1>没有此人</h1>';
				} else {
					var tableBody = '<thead><tr><td>学号</td><td>姓名</td><td>权限</td><td>电话</td><td>更改权限/重置密码/删除</td></tr></thead><tbody>';
					for (var i = 0; i < data.users.length; i++) {
						tableBody += '<tr><td>'+data.users[i].XH_ID+'</td><td>'+data.users[i].Name+'</td><td>'+userOrAdmin(data.users[i].status)+'</td><td>'+data.users[i].phone+'</td><td><div class="Set_dele glyphicon glyphicon-wrench" onclick="changesuserstatus(\''+data.users[i].XH_ID+'\','+data.users[i].status+')"></div> | <div class="Set_dele glyphicon glyphicon-off" onclick="resetPass(&quot;'+data.users[i].XH_ID+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteUser(&quot;'+data.users[i].XH_ID+'&quot;)"></div></td></tr>';
					};
					tableBody += '</tbody>';
				};
				document.getElementById('userMsgShow').innerHTML = tableBody;
				//分页的
				$("#yema_user_s").empty();
				var yema="<span id='yema_user_s' class='yema_user'></span>"
				$("#user_prev").after(yema);
				for(i=1;i<=allPage_user_s;i++){
					$("#yema_user_s").append("<a id='"+i+"' onclick='yema_user_s("+i+")'>"+i+"</a>")
				}
				danqianye_user_s()
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
					if($("#searchUser").val()!=""){
						searchUserhandle();
					}else{
						getRawData()
					}
				} else {
					alert('重置失败');
					if($("#searchUser").val()!=""){
						searchUserhandle();
					}else{
						getRawData()
					}
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
					if($("#searchUser").val()!=""){
						searchUserhandle();
					}else{
						getRawData()
					}
				} else {
					alert('删除失败');
					if($("#searchUser").val()!=""){
						searchUserhandle();
					}else{
						getRawData()
					}
				}
			},
			error: function(jqXHR){
				alert("error:"+jqXHR.status);
			},
		});
	}
}
//更改权限页面
function changesuserstatus(obj,obj2) {
	//obj:xh_id,obj2:status
	document.getElementById("coverDiv_user_2").style.top = '0px';
	document.getElementById("SetDiv_user_2").style.top = '10%';
	document.getElementById("SetDiv_user_2").style.opacity = 1;
	if(obj2==1){
		document.getElementById("showuserstatus_2").innerHTML="管理员";
	}else{
		document.getElementById("showuserstatus_2").innerHTML="用户";
	}
	document.getElementById("thisuserid_2").innerHTML=obj;
	$("#changeResult_user_2").html("");

}
function hideAll_user_2() {
	document.getElementById("coverDiv_user_2").style.top = '-999px';
	document.getElementById("SetDiv_user_2").style.top = '-600px';
	document.getElementById("SetDiv_user_2").style.opacity = 0;
}
function updateuser_2(){
	$.ajax({
		type: "POST",
		url: "index.php?r=ajaxuser/changeuserstatus",
		data: {
			status: $("#changestatus_user_2").val(),
			id:$("#thisuserid_2").text(),
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				$("#changeResult_user_2").html("修改成功！1秒后将自动关闭此页面");
				setTimeout(function(){
					hideAll_user_2()
					$("#changeResult_user_2").html("");
				},1500);
				if($("#searchUser").val()!=""){
					searchUserhandle();
				}else{
					getRawData()
				}

			} else {
				$("#changeResult_user_2").html("出现错误：" + data.msg);
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}
/**
 * 分页
 */
function afterPage_user() {
	if (nowPage_user == allPage_user) {
		console.log('last');
		alert('已经是最后一页');
		return;
	}
	nowPage_user= nowPage_user+1;
	//$("#userMsgShow").empty();
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/userpagechange",
		dataType: "json",
		data :{
			page : nowPage_user
		},
		success: function(data){
			if (data.success) {
				if (data.users == '') {
					var tableBody = '<h1>没有此人</h1>';
				} else {
					allPage_user=data.allPage
					var tableBody = '<thead><tr><td>学号</td><td>姓名</td><td>权限</td><td>电话</td><td>更改权限/重置密码/删除</td></tr></thead><tbody>';
					for (var i = 0; i < data.users.length; i++) {
						tableBody += '<tr><td>'+data.users[i].XH_ID+'</td><td>'+data.users[i].Name+'</td><td>'+userOrAdmin(data.users[i].status)+'</td><td>'+data.users[i].phone+'</td><td><div class="Set_dele glyphicon glyphicon-wrench" onclick="changesuserstatus(\''+data.users[i].XH_ID+'\','+data.users[i].status+')"></div> | <div class="Set_dele glyphicon glyphicon-off" onclick="resetPass(&quot;'+data.users[i].XH_ID+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteUser(&quot;'+data.users[i].XH_ID+'&quot;)"></div></td></tr>';
					};
					tableBody += '</tbody>';
				};
				document.getElementById('userMsgShow').innerHTML = tableBody;
				danqianye_user()
			} else {
				document.getElementById('userMsgShow').innerHTML = "出现错误：" + data.msg;
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}
function beforePage_user() {
	if (nowPage_user == 1) {
		console.log('first');
		alert('已经第一页');
		return;
	}
	nowPage_user= nowPage_user-1;
	//$("#userMsgShow").empty();
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/userpagechange",
		dataType: "json",
		data :{
			page : nowPage_user
		},
		success: function(data){
			if (data.success) {
				if (data.users == '') {
					var tableBody = '<h1>没有此人</h1>';
				} else {
					allPage_user=data.allPage
					var tableBody = '<thead><tr><td>学号</td><td>姓名</td><td>权限</td><td>电话</td><td>更改权限/重置密码/删除</td></tr></thead><tbody>';
					for (var i = 0; i < data.users.length; i++) {
						tableBody += '<tr><td>'+data.users[i].XH_ID+'</td><td>'+data.users[i].Name+'</td><td>'+userOrAdmin(data.users[i].status)+'</td><td>'+data.users[i].phone+'</td><td><div class="Set_dele glyphicon glyphicon-wrench" onclick="changesuserstatus(\''+data.users[i].XH_ID+'\','+data.users[i].status+')"></div> | <div class="Set_dele glyphicon glyphicon-off" onclick="resetPass(&quot;'+data.users[i].XH_ID+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteUser(&quot;'+data.users[i].XH_ID+'&quot;)"></div></td></tr>';
					};
					tableBody += '</tbody>';
				};
				document.getElementById('userMsgShow').innerHTML = tableBody;
				danqianye_user()
			} else {
				document.getElementById('userMsgShow').innerHTML = "出现错误：" + data.msg;
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}
/**
 * 分页（搜索）
 */
function afterPage_user_s(){
	if (nowPage_user_s == allPage_user_s) {
		console.log('last');
		alert('已经是最后一页');
		return;
	}
	nowPage_user_s= nowPage_user_s+1;
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/adminsearchuser&searchuser="+$("#searchUser").val(),
		dataType: "json",
		data: {
			page: nowPage_user_s
		},
		success: function(data){
			allPage_user_s=data.allPage
			if (data.success) {
				if (data.users == '') {
					var tableBody = '<h1>没有此人</h1>';
				} else {
					var tableBody = '<thead><tr><td>学号</td><td>姓名</td><td>权限</td><td>电话</td><td>更改权限/重置密码/删除</td></tr></thead><tbody>';
					for (var i = 0; i < data.users.length; i++) {
						tableBody += '<tr><td>'+data.users[i].XH_ID+'</td><td>'+data.users[i].Name+'</td><td>'+userOrAdmin(data.users[i].status)+'</td><td>'+data.users[i].phone+'</td><td><div class="Set_dele glyphicon glyphicon-wrench" onclick="changesuserstatus(\''+data.users[i].XH_ID+'\','+data.users[i].status+')"></div> | <div class="Set_dele glyphicon glyphicon-off" onclick="resetPass(&quot;'+data.users[i].XH_ID+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteUser(&quot;'+data.users[i].XH_ID+'&quot;)"></div></td></tr>';
					};
					tableBody += '</tbody>';
				};
				document.getElementById('userMsgShow').innerHTML = tableBody;
				danqianye_user_s()
			} else {
				document.getElementById('userMsgShow').innerHTML = "出现错误：" + data.msg;
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}
function beforePage_user_s(){
	if (nowPage_user_s == 1) {
		console.log('last');
		alert('已经是第一页');
		return;
	}
	nowPage_user_s= nowPage_user_s-1;
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/adminsearchuser&searchuser="+$("#searchUser").val(),
		dataType: "json",
		data: {
			page: nowPage_user_s
		},
		success: function(data){
			allPage_user_s=data.allPage
			if (data.success) {
				if (data.users == '') {
					var tableBody = '<h1>没有此人</h1>';
				} else {
					var tableBody = '<thead><tr><td>学号</td><td>姓名</td><td>权限</td><td>电话</td><td>更改权限/重置密码/删除</td></tr></thead><tbody>';
					for (var i = 0; i < data.users.length; i++) {
						tableBody += '<tr><td>'+data.users[i].XH_ID+'</td><td>'+data.users[i].Name+'</td><td>'+userOrAdmin(data.users[i].status)+'</td><td>'+data.users[i].phone+'</td><td><div class="Set_dele glyphicon glyphicon-wrench" onclick="changesuserstatus(\''+data.users[i].XH_ID+'\','+data.users[i].status+')"></div> | <div class="Set_dele glyphicon glyphicon-off" onclick="resetPass(&quot;'+data.users[i].XH_ID+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteUser(&quot;'+data.users[i].XH_ID+'&quot;)"></div></td></tr>';
					};
					tableBody += '</tbody>';
				};
				document.getElementById('userMsgShow').innerHTML = tableBody;
				danqianye_user_s()
			} else {
				document.getElementById('userMsgShow').innerHTML = "出现错误：" + data.msg;
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}
/**
 * 页码跳转
 * @param page
 */
function yema_user(page){
	nowPage_user=page
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/userpagechange",
		dataType: "json",
		data :{
			page : nowPage_user
		},
		success: function(data){
			if (data.success) {
				if (data.users == '') {
					var tableBody = '<h1>没有此人</h1>';
				} else {
					allPage_user=data.allPage
					var tableBody = '<thead><tr><td>学号</td><td>姓名</td><td>权限</td><td>电话</td><td>更改权限/重置密码/删除</td></tr></thead><tbody>';
					for (var i = 0; i < data.users.length; i++) {
						tableBody += '<tr><td>'+data.users[i].XH_ID+'</td><td>'+data.users[i].Name+'</td><td>'+userOrAdmin(data.users[i].status)+'</td><td>'+data.users[i].phone+'</td><td><div class="Set_dele glyphicon glyphicon-wrench" onclick="changesuserstatus(\''+data.users[i].XH_ID+'\','+data.users[i].status+')"></div> | <div class="Set_dele glyphicon glyphicon-off" onclick="resetPass(&quot;'+data.users[i].XH_ID+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteUser(&quot;'+data.users[i].XH_ID+'&quot;)"></div></td></tr>';
					};
					tableBody += '</tbody>';
				};
				document.getElementById('userMsgShow').innerHTML = tableBody;
				danqianye_user()
			} else {
				document.getElementById('userMsgShow').innerHTML = "出现错误：" + data.msg;
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}

function yema_user_s(page){
	nowPage_user_s= page;
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/adminsearchuser&searchuser="+$("#searchUser").val(),
		dataType: "json",
		data: {
			page: nowPage_user_s
		},
		success: function(data){
			allPage_user_s=data.allPage
			if (data.success) {
				if (data.users == '') {
					var tableBody = '<h1>没有此人</h1>';
				} else {
					var tableBody = '<thead><tr><td>学号</td><td>姓名</td><td>权限</td><td>电话</td><td>更改权限/重置密码/删除</td></tr></thead><tbody>';
					for (var i = 0; i < data.users.length; i++) {
						tableBody += '<tr><td>'+data.users[i].XH_ID+'</td><td>'+data.users[i].Name+'</td><td>'+userOrAdmin(data.users[i].status)+'</td><td>'+data.users[i].phone+'</td><td><div class="Set_dele glyphicon glyphicon-wrench" onclick="changesuserstatus(\''+data.users[i].XH_ID+'\','+data.users[i].status+')"></div> | <div class="Set_dele glyphicon glyphicon-off" onclick="resetPass(&quot;'+data.users[i].XH_ID+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteUser(&quot;'+data.users[i].XH_ID+'&quot;)"></div></td></tr>';
					};
					tableBody += '</tbody>';
				};
				document.getElementById('userMsgShow').innerHTML = tableBody;
				danqianye_user_s()
			} else {
				document.getElementById('userMsgShow').innerHTML = "出现错误：" + data.msg;
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}

/**
 * 当前页高亮
 */
function danqianye_user(){
	$("#yema_user a").filter(function(inx){
		if(inx+1==nowPage_user){
			return true
		}
	}).addClass("yema_choosed")
	$("#yema_user a").filter(function(inx){
		if(inx+1!=nowPage_user){
			return true
		}
	}).removeClass("yema_choosed")
}
function danqianye_user_s(){
	$("#yema_user_s a").filter(function(inx){
		if(inx+1==nowPage_user_s){
			return true
		}
	}).addClass("yema_choosed")
	$("#yema_user_s a").filter(function(inx){
		if(inx+1!=nowPage_user_s){
			return true
		}
	}).removeClass("yema_choosed")
}