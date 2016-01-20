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