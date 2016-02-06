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
	document.getElementById('detailShow').style.top = '50px';
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
					$('#Detail_Doing').append(data.msg1);
					$('#Detail_Done').append(data.msg2);
					var msg = '<div class="detail_add"><p class="Thedetail_p detailadd_p">添加...</p></div>';
					$('#Detail_Doing').append(msg);
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
//ajax实现和数据库的交换，传参，第一个为状态，第二个为事件
function dbStatusChange(itemId,divId) {
	var status = 1;
	if (divId == 'Completed') {
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
				// alert('状态转换成功');
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
//
function detailDet(id) {
	document.getElementById('detail_model').style.left = '50%';
	$.ajax({
	    type: "GET",
		url: "index.php?r=ajaxuser/todowillhandle",
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