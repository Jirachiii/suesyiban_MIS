function selectAllItem() {
	$('#userMsgShow').empty();
	$.getJSON('index.php?r=json/admingetallitems', function(data, textStatus) {
		if (textStatus == 'success') {
			$("#head a").first().attr("id",data.userIdNow).html(data.userName+'<span class="caret"></span>')
			document.getElementById('userMsgShow').innerHTML = data.msg;
		} else {
			alert("系统错误" + textStatus);
		}
	});
}

function selectItem() {
	if ($("#sel_status").val() == 0) {
		selectAllItem();
	} else {
		$('#userMsgShow').empty();
		$.ajax({
		    type: "GET",
			url: "index.php?r=ajaxuser/adminstatusgetitems&status="+$("#sel_status").val(),
			dataType: "json",
			success: function(data){
				if (data.success) {
					document.getElementById('userMsgShow').innerHTML = data.msg;
				}	
			},
			error: function(jqXHR){
			   alert("发生错误：" + jqXHR.status);  
			},
		});		
	}

}
//审核通过
function ItemPass(itemId) {
	var confirm1 = confirm('确定通过该项目？');
	if(confirm1==true){
		$.ajax({
			type: "POST",
			url: "index.php?r=ajaxuser/changeitemstatus",
			dataType: "json",
			data: {
				Item_Id: itemId,
				status: 2,
			},
			success: function(data){
				if (data.success) {
					window.location.reload();
				} else {
					alert('修改失败');
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
//审核未通过
function ItemFail(itemId) {
	var confirm2 = confirm('确定否定该项目？');
	if(confirm2==true){
		$.ajax({
			type: "POST",
			url: "index.php?r=ajaxuser/changeitemstatus",
			dataType: "json",
			data: {
				Item_Id: itemId,
				status: 4,
			},
			success: function(data){
				if (data.success) {
					window.location.reload();
				} else {
					alert('修改失败');
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
//查看项目详细
function ItemDescribe(itemId) {
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/adminshowitem",
		dataType: "json",
		data: {
			Item_Id: itemId,
		},
		success: function(data){
			if (data.success) {
				itemDetailshow()
				$("#item_name_1").html(data.msg[0].Item_Name)
				$("#item_date_1").html("发布日期"+data.msg[0].Date)
				$("#item_content_1").html(data.msg[0].Item_Intro)
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
//显示项目详情
function itemDetailshow(){
	document.getElementById("coverDiv_item_1").style.top = '0px';
	document.getElementById("SetDiv_item_1").style.top = '5%';
	document.getElementById("SetDiv_item_1").style.opacity = 1;
	$("#item_name_1").val("");
	$("#item_content_1").html("");
}
function hideAll_item_1 (){
	document.getElementById("coverDiv_item_1").style.top = '-999px';
	document.getElementById("SetDiv_item_1").style.top = '-600px';
	document.getElementById("SetDiv_item_1").style.opacity = 0;

}