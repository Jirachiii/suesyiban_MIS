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