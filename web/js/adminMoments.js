//动态删除
function deleteMom(monentsId) {
	var deleteOrNot = confirm('确定删除此条记录？');
	if(deleteOrNot == true) {
		$.ajax({ 
		    type: "POST",
			url: "index.php?r=ajaxmoments/deleteonemoment",
			data: {
				moment: monentsId,
			},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					alert('删除成功');
					window.location.href('index.php?r=admin/momentsmene');
				} else {
					alert('删除失败');
					window.location.href('index.php?r=admin/momentsmene');
				}
			},
			error: function(jqXHR){
				alert("error:"+jqXHR.status);
			},
		});
	}
}

function beforePage() {
	if (this.nowPage == 1) {
		alert('已经是第一页');
		return;
	}
	this.nowPage = this.nowPage-1;
	$("#userMsgShow").empty();
	$.ajax({
	    type: "POST",
		url: "index.php?r=ajaxmoments/pagechange",
		data: {
			page: this.nowPage,
		},
		dataType: "json",
		success: function(data) {
			if (data.success) {
				var tableHead = '<thead><tr><td>学号</td><td>内容</td><td>点赞数</td><td>姓名</td><td>删除</td></tr></thead>';
				var tableBody = '<tbody>';
				for (var i = 0; i < data.moments.length; i++) {
					tableBody += '<tr><td>'+data.moments[i].XH_ID+'</td><td>'+handleLength(data.moments[i].Content,5)+'</td><td>'+data.moments[i].like_Num+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
				};
				tableBody += '</tbody>';
				document.getElementById('MomentsData').innerHTML = tableHead+tableBody;
			} else {
				alert('错误的页码');
			}
		},
		error: function(jqXHR){
			alert("error:"+jqXHR.status);
		},
	});
}

function afterPage() {
	if (this.nowPage == this.allPage) {
		console.log('last');
		alert('已经是最后一页');
		return;
	}
	this.nowPage = this.nowPage+1;
	$("#userMsgShow").empty();
	$.ajax({
	    type: "POST",
		url: "index.php?r=ajaxmoments/pagechange",
		data: {
			page: this.nowPage,
		},
		dataType: "json",
		success: function(data) {
			if (data.success) {
				var tableHead = '<thead><tr><td>学号</td><td>内容</td><td>点赞数</td><td>姓名</td><td>删除</td></tr></thead>';
				var tableBody = '<tbody>';
				for (var i = 0; i < data.moments.length; i++) {
					tableBody += '<tr><td>'+data.moments[i].XH_ID+'</td><td>'+handleLength(data.moments[i].Content,5)+'</td><td>'+data.moments[i].like_Num+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
				};
				tableBody += '</tbody>';
				document.getElementById('MomentsData').innerHTML = tableHead+tableBody;
			} else {
				alert('错误的页码');
			}
		},
		error: function(jqXHR){
			alert("error:"+jqXHR.status);
		},
	});
}