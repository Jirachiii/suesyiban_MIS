//插入项目
function InsertItem() {
	var itemName = $("#insertItemName").val();
	var itemIntro = $("#insertiIntro").val();
	if (itemName == '' || itemIntro == '') {
		alert('不能为空');
		window.location.reload();
	} else {
		$.ajax({
		    type: "POST",
			url: "index.php?r=ajaxuser/insertitem",
			data: {
				ItemName: itemName,
				ItemIntro: itemIntro,
			},
			dataType: "json",
			success: function(data){
				if (data.success) {
					alert('申请成功，等待审核');
					window.location.reload();
				} else {
					alert('申请失败');
					window.location.reload();
				}  
			},
			error: function(jqXHR){
				alert("发生错误：" + jqXHR.status);
				window.location.reload();
			},
		});
	}
}
//ajax显示
function detailShow(id){
	itemIdOnAllShow = id;
	$.ajax({
		    type: "GET",
			url: "index.php?r=ajaxuser/detailshow",
			data: {
				id: id,
			},
			dataType: "json",
			success: function(data){
				if (data.success) {
					$('#Detail_Doing').empty();
					$('#Detail_Done').empty();
					var msg = '<div id="item_detail_add" class="detail_add" onclick="AddDetail('+id+')"><p class="Thedetail_p detailadd_p" >添加...</p></div>';
					$('#Detail_Doing').append(msg);
					$('#Detail_Doing').append(data.msg1);
					$('#Detail_Done').append(data.msg2);
				} else {
					alert('获取失败');
					window.location.reload();
				}
				if(data.authority!=1){
					$("#item_option_1").hide();
					$("#item_option_3").hide();
					$("#item_detail_add").hide();
				}
				document.getElementById('detailShow').style.top = '50px';

			},

			error: function(jqXHR){
				alert("发生错误：" + jqXHR.status);
				window.location.reload();
			},
	});
}
//ajax实现和数据库的交换，传参，第一个为状态，第二个为事件
function dbStatusChange(urgentLev,ev) {
	var itemId = ev.getAttribute("data-id");
	var status = 1;
	if (urgentLev == 'Detail_Done') {
		status = 2;
	}
	$.ajax({
	    type: "POST",
		url: "index.php?r=ajaxuser/changestatus",
		data: {
			id: itemId,
			status: status,
		},
		dataType: "json",
		success: function(data){
			if (data.success) {

			} else {
				alert('出现错误');
				window.location.reload();
			}  
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//改变事件状态(非常紧急、紧急、正常)
function Urgenthandle(ev,urgentLev) {
	$.ajax({
	    type: "POST",
		url: "index.php?r=ajaxuser/changetodostatus",
		data: {
			Num: ev.getAttribute("data-Num"),
			CreateDate: ev.getAttribute("data-createDate"),
			urgentLev: urgentLev,
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				getRawData();
			} else {
				alert('不能改变此状态');
				window.location.reload();
			}  
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//删除任务
function deletehandle(ev) {
	$.ajax({
	    type: "POST",
		url: "index.php?r=ajaxuser/deleteownertodo",
		data: {
			Num: ev.getAttribute("data-Num"),
			CreateDate: ev.getAttribute("data-createDate"),
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				getRawData();
			} else {
				alert('操作失败');
				window.location.reload();
			}  
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//今日已完成
function Completed() {
	$.ajax({
	    type: "GET",
		url: "index.php?r=ajaxuser/getdonemask",
		dataType: "json",
		success: function(data){
			if (data.success) {
				$("#today_Show").empty();
				$("#deleteTodo").empty();
				document.getElementById('today_Show').innerHTML = '<br><p class="TS_head">今天已完成任务</p><hr><div class="today_Show_miss" id="today_Show_miss">';
				document.getElementById('today_Show_miss').innerHTML = data.msg;
			} else {
				alert('错误的数据');
				window.location.reload();
			}  
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//近一个月
function pastOneMonth() {
	$.ajax({
	    type: "GET",
		url: "index.php?r=ajaxuser/todopastoneweek",
		dataType: "json",
		success: function(data){
			if (data.success) {
				$("#today_Show").empty();
				$("#deleteTodo").empty();
				document.getElementById('today_Show').innerHTML = '<br><p class="TS_head">近一周</p><hr><div class="today_Show_miss" id="today_Show_miss">';
				document.getElementById('today_Show_miss').innerHTML = data.msg;
			} else {
				alert('没有数据');
			}  
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//未完成任务
function maskhaveToBeDone() {
	$.ajax({
	    type: "GET",
		url: "index.php?r=ajaxuser/todowillhandle",
		dataType: "json",
		success: function(data){
			if (data.success) {
				$("#today_Show").empty();
				$("#deleteTodo").empty();
				document.getElementById('today_Show').innerHTML = '<br><p class="TS_head">未完成</p><hr><div class="today_Show_miss" id="today_Show_miss">';
				document.getElementById('today_Show_miss').innerHTML = data.msg;
				document.getElementById('deleteTodo').innerHTML = '<span class="glyphicon glyphicon-check trash_style" id="deleteIcon" aria-hidden="true"></span>';
			} else {
				alert('错误的数据');
				window.location.reload();
			}  
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//团队任务的单个细节
function detailDet(id) {
	document.getElementById('detail_model').style.left = '50%';
	$.ajax({
	    type: "GET",
		url: "index.php?r=ajaxuser/onedetailshow",
		data: {
			id: id,
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				$("#detail_model").empty();
				$("#detail_model").append(data.msg);
			} else {
				alert('错误的数据');
				window.location.reload();
			}  
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//团队任务添加进个人任务
function addInTodo() {
	var discribe = $("#detail_text").html();
	if (discribe) {
		$.ajax({
		    type: "POST",
			url: "index.php?r=ajaxuser/inserttodo",
			data: {
				content: discribe,
			},
			dataType: "json",
			success: function(data){
				if (data.success) {
					alert('插入成功');
					window.location.href='index.php?r=user/homepage';
				} else {
					alert('错误的数据');
					window.location.reload();
				}  
			},
			error: function(jqXHR){
				alert("发生错误：" + jqXHR.status);
				window.location.reload();
			},
		});
	} else {
		alert('不能为空');
	}
}
//修改团队任务描述
function changeDetail(ItemDetail_Id) {
	var discribe = $("#detail_text").val();
	if (discribe) {
		$.ajax({
			type: "POST",
			url: "index.php?r=ajaxuser/changediscribe",
			data: {
				ItemDetail_Id: ItemDetail_Id,
				discribe: discribe,
			},
			dataType: "json",
			success: function(data){
				if (data.success) {
					alert('修改成功');
					window.location.reload();
				} else {
					alert('错误的数据');
					window.location.reload();
				}  
			},
			error: function(jqXHR){
				alert("发生错误：" + jqXHR.status);
				window.location.reload();
			},
		});
	} else {
		alert('描述不能为空!');
	}
}
//添加项目细节
function AddDetail(itemId) {
	document.getElementById('detail_model').style.left = '50%';
	$("#detail_model").empty();
	var msg = '<span onclick="closeModel()" class="glyphicon glyphicon-remove delete_span"></span><div id="detailmodel_Main"><textarea id="detail_text" cols="30" rows="3" class="detail_Maintext"></textarea><button onclick="addInto('+itemId+')" class="detailmodel_btn">插入</button></div>';
	$("#detail_model").append(msg);
}
//插入项目细节
function addInto(itemId) {
	var discribe = $("#detail_text").val();
	if (discribe) {
		$.ajax({
			type: "POST",
			url: "index.php?r=ajaxuser/insertdetail",
			data: {
				item_id: itemId,
				discribe: discribe,
			},
			dataType: "json",
			success: function(data){
				if (data.success) {
					alert('任务添加成功');
					window.location.reload();
				} else {
					alert('添加失败');
					window.location.reload();
				}  
			},
			error: function(jqXHR){
				alert("发生错误：" + jqXHR.status);
				window.location.reload();
			},
		});
	} else {
		alert('内容为空');
	}
}
//渲染待审核项目
function ItemWillStart() {
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/getitembystatus",
		dataType: "json",
		data: {
			status: 1,
		},
		success: function(data){
			if (data.success) {
				$("#item_showbox").empty();
				$("#item_showbox").append(data.msg);
			} else {
				alert('获取失败');
				window.location.reload();
			}  
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//渲染归档项目
function ItemCompleted() {
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/getitembystatus",
		dataType: "json",
		data: {
			status: 3,
		},
		success: function(data){
			if (data.success) {
				$("#item_showbox").empty();
				$("#item_showbox").append(data.msg);
			} else {
				alert('获取失败');
				window.location.reload();
			}  
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//使归档
function getDoneItem() {
	var handleOrNot = confirm('确定归档？');
	if(deleteOrNot == true) {
		$.ajax({
			type: "POST",
			url: "index.php?r=ajaxuser/changeitemstatus",
			dataType: "json",
			data: {
				Item_Id: itemIdOnAllShow,
				status: 3,
			},
			success: function(data){
				if (data.success) {
					$("#item_showbox").empty();
					$("#item_showbox").append(data.msg);
				} else {
					alert('获取失败');
					window.location.reload();
				}  
			},
			error: function(jqXHR){
				alert("发生错误：" + jqXHR.status);
				window.location.reload();
			},
		});
	}
}
//删除项目
function deleteItem() {
	var handleOrNot = confirm('确定删除？');
	if(deleteOrNot == true) {
		$.ajax({
			type: "POST",
			url: "index.php?r=ajaxuser/deleteItem",
			dataType: "json",
			data: {
				Item_Id: itemIdOnAllShow,
			},
			success: function(data){
				if (data.success) {
					alert('删除成功');
					window.location.reload();
				} else {
					alert('删除失败');
					window.location.reload();
				}
			},
			error: function(jqXHR){
				alert("发生错误：" + jqXHR.status);
				window.location.reload();
			},
		});
	}
}
//添加用户
function insertUser() {
	var person = $("#insertUser").val();
	if (person == '') {
		alert('不能为空')
		return
	}
	$.ajax({
		type: "POST",
		url: "index.php?r=ajaxuser/insertitemperson",
		dataType: "json",
		data: {
			person: person,
			Item_Id: itemIdOnAllShow,
		},
		success: function(data){
			if (data.success) {
				alert('添加成功');
			} else {
				alert('添加失败');
				window.location.reload();
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//更新自己的密码
function updatePassword() {
	var password = $("#Up_password").val();
	if (password == '') {
		alert('不能为空');
		return;
	}
	$.ajax({
		type: "POST",
		url: "index.php?r=ajaxuser/updateuserpassword",
		dataType: "json",
		data: {
			password: password,
		},
		success: function(data){
			if (data.success) {
				alert('修改成功');
			} else {
				alert('修改失败');
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}
//跳转管理员
function hrefadmin(){
	$.ajax({
		type: "POST",
		url: "index.php?r=ajaxuser/hrefadmin",
		dataType: "json",

		success: function(data){
			if(data!=1){
				alert("您没有权限")
			}else{
				location.href="?r=admin/index"
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
			window.location.reload();
		},
	});
}