var allpage_item=1
var nowpage_item=1
var allpage_item_s=1
var nowpage_item_s=1
var allpage_item_sel=1
var nowpage_item_sel=1
function selectAllItem() {
	$('#userMsgShow').empty();
	nowpage_item=1
	allpage_item=1
	nowpage_item_sel=1
	allpage_item_sel=1
	nowpage_item_s=1
	allpage_item_s=1
	$("#yema_item").empty()
	$("#item_prev").attr("onclick","beforePage_item()")
	$("#item_aft").attr("onclick","afterPage_item()")
	$.getJSON('index.php?r=json/admingetallitems', {nowpage:nowpage_item,allpage:allpage_item},function(data, textStatus) {
		if (textStatus == 'success') {
			allpage_item=data.allpage
			$("#head a").first().attr("id",data.userIdNow).html(data.userName+'<span class="caret"></span>')
			document.getElementById('userMsgShow').innerHTML = data.msg
			//分页的
			var yema="<span id='yema_item' class='yema_item'></span>"
			$("#item_prev").after(yema);
			for(i=1;i<=allpage_item;i++){
				$("#yema_item").append("<a id='"+i+"' onclick='yema_item("+i+")'>"+i+"</a>")
			}
			danqianye_item()
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
		nowpage_item=1
		allpage_item=1
		nowpage_item_sel=1
		allpage_item_sel=1
		nowpage_item_s=1
		allpage_item_s=1
		$("#yema_item").empty()
		$('#userMsgShow').empty();
		$("#item_prev").attr("onclick","beforePage_item_sel()")
		$("#item_aft").attr("onclick","afterPage_item_sel()")
		$.ajax({
		    type: "GET",
			url: "index.php?r=ajaxuser/adminstatusgetitems&status="+$("#sel_status").val(),
			data: {
				page:nowpage_item_sel
			},
			dataType: "json",
			success: function(data){
				if (data.success) {
					allpage_item_sel=data.allpage
					document.getElementById('userMsgShow').innerHTML = data.msg;
					//分页的
					var yema="<span id='yema_item' class='yema_item'></span>"
					$("#item_prev").after(yema);
					for(i=1;i<=allpage_item_sel;i++){
						$("#yema_item").append("<a id='"+i+"' onclick='yema_item("+i+")'>"+i+"</a>")
					}
					danqianye_item_sel()
				}	
			},
			error: function(jqXHR){
			   alert("发生错误：" + jqXHR.status);  
			},
		});		
	}

}
//搜索项目
function searchitem(){
	$('#userMsgShow').empty();
	nowpage_item=1
	allpage_item=1
	nowpage_item_sel=1
	allpage_item_sel=1
	nowpage_item_s=1
	allpage_item_s=1
	$("#yema_item").empty()
	$('#userMsgShow').empty();
	$("#item_prev").attr("onclick","beforePage_item_s()")
	$("#item_aft").attr("onclick","afterPage_item_s()")
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/searchitem",
		data: {
			page:nowpage_item_s,
			content: $("#searchitem").val()
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				allpage_item_s=data.allpage
				document.getElementById('userMsgShow').innerHTML = data.msg;
				//分页的
				var yema="<span id='yema_item' class='yema_item'></span>"
				$("#item_prev").after(yema);
				for(i=1;i<=allpage_item_s;i++){
					$("#yema_item").append("<a id='"+i+"' onclick='yema_item("+i+")'>"+i+"</a>")
				}
				danqianye_item_s()
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
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
//分页
function afterPage_item(){
	if (nowpage_item == allpage_item) {
		console.log('last');
		alert('已经是最后一页');
		return;
	}
	nowpage_item = nowpage_item+1;
	$.getJSON('index.php?r=json/admingetallitems', {nowpage:nowpage_item,allpage:allpage_item},function(data, textStatus) {
		if (textStatus == 'success') {
			allpage_item=data.allpage
			$("#head a").first().attr("id",data.userIdNow).html(data.userName+'<span class="caret"></span>')
			document.getElementById('userMsgShow').innerHTML = data.msg;
			danqianye_item()
		} else {
			alert("系统错误" + textStatus);
		}
	});

}
function beforePage_item(){
	if (nowpage_item == 1) {
		alert('已经是第一页');
		return;
	}
	nowpage_item = nowpage_item-1;
	$.getJSON('index.php?r=json/admingetallitems', {nowpage:nowpage_item,allpage:allpage_item},function(data, textStatus) {
		if (textStatus == 'success') {
			allpage_item=data.allpage
			$("#head a").first().attr("id",data.userIdNow).html(data.userName+'<span class="caret"></span>')
			document.getElementById('userMsgShow').innerHTML = data.msg;
			danqianye_item()
		} else {
			alert("系统错误" + textStatus);
		}
	});

}
function yema_item(page){
	nowpage_item=page
	$.getJSON('index.php?r=json/admingetallitems', {nowpage:nowpage_item,allpage:allpage_item},function(data, textStatus) {
		if (textStatus == 'success') {
			allpage_item=data.allpage
			$("#head a").first().attr("id",data.userIdNow).html(data.userName+'<span class="caret"></span>')
			document.getElementById('userMsgShow').innerHTML = data.msg;
			danqianye_item()
		} else {
			alert("系统错误" + textStatus);
		}
	});
}
//分页（select）
function afterPage_item_sel(){
	if (nowpage_item_sel == allpage_item_sel) {
		console.log('last');
		alert('已经是最后一页');
		return;
	}
	nowpage_item_sel = nowpage_item_sel+1;
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/adminstatusgetitems&status="+$("#sel_status").val(),
		data: {
			page:nowpage_item_sel
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				allpage_item_sel=data.allpage
				document.getElementById('userMsgShow').innerHTML = data.msg;
				danqianye_item_sel()
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}

function beforePage_item_sel(){
	if (nowpage_item_sel == 1) {
		alert('已经是第一页');
		return;
	}
	nowpage_item_sel = nowpage_item_sel-1;
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/adminstatusgetitems&status="+$("#sel_status").val(),
		data: {
			page:nowpage_item_sel
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				allpage_item_sel=data.allpage
				document.getElementById('userMsgShow').innerHTML = data.msg;
				danqianye_item_sel()
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}
//分页（search）
function afterPage_item_s(){
	if (nowpage_item_s == allpage_item_s) {
		console.log('last');
		alert('已经是最后一页');
		return;
	}
	nowpage_item_s = nowpage_item_s+1;
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/searchitem",
		data: {
			page:nowpage_item_s,
			content: $("#searchitem").val()
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				allpage_item_s=data.allpage
				document.getElementById('userMsgShow').innerHTML = data.msg;
				danqianye_item_s()
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}
function beforePage_item_s(){
	if (nowpage_item_s == 1) {
		alert('已经是第一页');
		return;
	}
	nowpage_item_s = nowpage_item_s-1;
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxuser/searchitem",
		data: {
			page:nowpage_item_s,
			content: $("#searchitem").val()
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				allpage_item_s=data.allpage
				document.getElementById('userMsgShow').innerHTML = data.msg;
				danqianye_item_s()
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
function danqianye_item(){
	$("#yema_item a").filter(function(inx){
		if(inx+1==nowpage_item){
			return true
		}
	}).addClass("yema_choosed")
	$("#yema_item a").filter(function(inx){
		if(inx+1!=nowpage_item){
			return true
		}
	}).removeClass("yema_choosed")
}
function danqianye_item_sel(){
	$("#yema_item a").filter(function(inx){
		if(inx+1==nowpage_item_sel){
			return true
		}
	}).addClass("yema_choosed")
	$("#yema_item a").filter(function(inx){
		if(inx+1!=nowpage_item_sel){
			return true
		}
	}).removeClass("yema_choosed")
}
function danqianye_item_s(){
	$("#yema_item a").filter(function(inx){
		if(inx+1==nowpage_item_s){
			return true
		}
	}).addClass("yema_choosed")
	$("#yema_item a").filter(function(inx){
		if(inx+1!=nowpage_item_s){
			return true
		}
	}).removeClass("yema_choosed")
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