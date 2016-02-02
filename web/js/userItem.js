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
	
}
//未完成任务
function maskhaveToBeDone() {
	
}