//动态显示
function getMomentData_dongtai() {
	document.getElementById("searchMoment").value="";
	$.getJSON('index.php?r=json/getmomentdata', function(data, textStatus) {
		if (textStatus == 'success') {
			this.allpage = data.allPage;
			var tableHead = '<thead><tr><td>学号</td><td>内容</td><td>时间</td><td>姓名</td><td>修改/置顶/删除</td></tr></thead>';
			var tableBody = '<tbody>';
			for (var i = 0; i < data.moments.length; i++) {
				tableBody += '<tr><td ><span style="display: none">'+data.moments[i].status+'</span>&nbsp;'+data.moments[i].XH_ID+'</td><td>'+data.moments[i].Content+'</td><td>'+data.moments[i].Mdate+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-pencil" onclick="changeMoment(&quot;'+data.moments[i].id+'&quot;,&quot;'+data.moments[i].XH_ID+'&quot;,&quot;'+data.moments[i].username+'&quot;,&quot;'+data.moments[i].Mdate+'&quot;,&quot;'+data.moments[i].Content+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-sort" onclick="changeMomentTop('+data.moments[i].id+',&quot;'+data.moments[i].username+'&quot;,&quot;'+data.moments[i].Mdate+'&quot;,&quot;'+data.moments[i].status+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
			};
			tableBody += '</tbody>';
			document.getElementById('MomentsData').innerHTML = tableHead+tableBody;
			$("td span:contains(1)").addClass("glyphicon glyphicon-fire myred").show().empty();
			$("td span[class='glyphicon glyphicon-fire myred']").parent().parent().insertBefore($("tbody tr:eq(0)"))
		} else {
			alert("系统错误" + textStatus);
		}
	});
}
//获取总页数
//function getAllyema(){
//
//
//}
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
					if($("#searchMoment").val()!=""){
						searchMoment();
					}else {
						getMomentData_dongtai();
					}
					alert('删除成功');
				} else {
					if($("#searchMoment").val()!=""){
						searchMoment();
					}else {
						getMomentData_dongtai();
					}
					alert('删除失败');
				}
			},
			error: function(jqXHR){
				alert("error:"+jqXHR.status);
			},
		});
	}
}
var show = 0;
var nowPage = 1;
var allpage = 1;
function tiggle() {
	if (this.show == 0) {
		document.getElementById("showandhide").style.top = '51px';
		this.show = 1;
	} else {
		document.getElementById("showandhide").style.top = '-100%';
		this.show = 0;
	}
}
//上一页
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
				var tableHead = '<thead><tr><td>学号</td><td>内容</td><td>时间</td><td>姓名</td><td>修改/置顶/删除</td></tr></thead>';
				var tableBody = '<tbody>';
				for (var i = 0; i < data.moments.length; i++) {
<<<<<<< HEAD
					tableBody += '<tr><td>'+data.moments[i].XH_ID+'</td><td>'+handleLength(data.moments[i].Content,5)+'</td><td>'+data.moments[i].like_Num+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
=======
					tableBody += '<tr><td>'+data.moments[i].XH_ID+'</td><td>'+data.moments[i].Content+'</td><td>'+data.moments[i].Mdate+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-pencil" onclick="changeMoment(&quot;'+data.moments[i].id+'&quot;,&quot;'+data.moments[i].XH_ID+'&quot;,&quot;'+data.moments[i].username+'&quot;,&quot;'+data.moments[i].Mdate+'&quot;,&quot;'+data.moments[i].Content+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-sort" onclick="changeMomentTop('+data.moments[i].id+')"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
>>>>>>> origin/master
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
//下一页
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
				var tableHead = '<thead><tr><td>学号</td><td>内容</td><td>时间</td><td>姓名</td><td>修改/置顶/删除</td></tr></thead>';
				var tableBody = '<tbody>';
				for (var i = 0; i < data.moments.length; i++) {
					tableBody += '<tr><td>'+data.moments[i].XH_ID+'</td><td>'+data.moments[i].Content+'</td><td>'+data.moments[i].Mdate+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-pencil" onclick="changeMoment()"></div> | <div class="Set_dele glyphicon glyphicon-sort" onclick="changeMomentTop()"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
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
//动态搜索
function searchMoment(){
	$("#MomentsData").empty();
	$.ajax({
		type: "GET",
		url: "index.php?r=ajaxmoments/adminsearchmoment&searchmoment="+$("#searchMoment").val(),
		dataType: "json",
		success: function(data){
			if (data.success) {
				if (data.moments == '') {
					var tableBody = '<h1>没有此动态</h1>';
				} else {
					var tableBody = '<thead><tr><td>学号</td><td>内容</td><td>时间</td><td>姓名</td><td>修改/置顶/删除</td></tr></thead>';
					for (var i = 0; i < data.moments.length; i++) {
						tableBody += '<tr><td ><span style="display: none">'+data.moments[i].status+'</span>&nbsp;'+data.moments[i].XH_ID+'</td><td>'+data.moments[i].Content+'</td><td>'+data.moments[i].Mdate+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-pencil" onclick="changeMoment(&quot;'+data.moments[i].id+'&quot;,&quot;'+data.moments[i].XH_ID+'&quot;,&quot;'+data.moments[i].username+'&quot;,&quot;'+data.moments[i].Mdate+'&quot;,&quot;'+data.moments[i].Content+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-sort" onclick="changeMomentTop('+data.moments[i].id+',&quot;'+data.moments[i].username+'&quot;,&quot;'+data.moments[i].Mdate+'&quot;,&quot;'+data.moments[i].status+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
					};
					tableBody += '</tbody>';
					document.getElementById('MomentsData').innerHTML = tableBody;
					$("td span:contains(1)").addClass("glyphicon glyphicon-fire myred").show().empty();
					$("td span[class='glyphicon glyphicon-fire myred']").parent().parent().insertBefore($("tbody tr:eq(0)"))
				};
			} else {
				document.getElementById('MomentsData').innerHTML = "出现错误：" + data.msg;
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}
//隐藏更改页
function hideAll_mom() {
	document.getElementById("coverDiv_mom").style.top = '-999px';
	document.getElementById("SetDiv_mom").style.top = '-600px';
	document.getElementById("SetDiv_mom").style.opacity = 0;
}
function hideAll_mom_1() {
	document.getElementById("coverDiv_mom_1").style.top = '-999px';
	document.getElementById("SetDiv_mom_1").style.top = '-600px';
	document.getElementById("SetDiv_mom_1").style.opacity = 0;
}
隐藏置顶页面
function hideAll_mom_2() {
	document.getElementById("coverDiv_mom_2").style.top = '-999px';
	document.getElementById("SetDiv_mom_2").style.top = '-600px';
	document.getElementById("SetDiv_mom_2").style.opacity = 0;
	$("#changeResult_mom_2").html("")
}
//显示更改页
function changeMoment(obj1,obj2,obj3,obj4,obj5){
	document.getElementById("coverDiv_mom").style.top = '0px';
	document.getElementById("SetDiv_mom").style.top = '5%';
	document.getElementById("SetDiv_mom").style.opacity = 1;
	document.getElementById("momentid").innerHTML=obj1;
	document.getElementById("userid_mom").innerHTML=obj2;
	document.getElementById("username_mom").innerHTML=obj3;
	document.getElementById("date_mom").innerHTML=obj4;
	document.getElementById("moment_content").value=obj5;
	//document.getElementById("changeResult_mom").value=null;
	$("#changeResult_mom").html(" ");


}
//显示添加页
function addMoment(){
	document.getElementById("coverDiv_mom_1").style.top = '0px';
	document.getElementById("SetDiv_mom_1").style.top = '5%';
	document.getElementById("SetDiv_mom_1").style.opacity = 1;
	$("#moment_content_1").val("");
	$("#changeResult_mom_1").html("");
}
//显示置顶页面
function changeMomentTop(obj1,obj2,obj3,obj4){
	//1:momentid,2:username,3:date,4:is top
	document.getElementById("coverDiv_mom_2").style.top = '0px';
	document.getElementById("SetDiv_mom_2").style.top = '40%';
	document.getElementById("SetDiv_mom_2").style.left = '70%';
	document.getElementById("SetDiv_mom_2").style.opacity = 1;
	document.getElementById("momentid_2").innerHTML=obj1;
	//document.getElementById("userid_mom").innerHTML=obj2;
	document.getElementById("username_mom_2").innerHTML=obj2;
	document.getElementById("date_mom_2").innerHTML=obj3;
	if(obj4==1){
		$("#momenttop_2").html("取消置顶").val(2)
	}else{
		$("#momenttop_2").html("置顶").val(1)
	}
}

//动态内容修改提交方法
function updateMoment(){
	$.ajax({
		type: "POST",
		url: "index.php?r=ajaxmoments/adminupdatemoment",
		data: {
			momentid: $("#momentid").text(),
			moment_content: $("#moment_content").val(),
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				setTimeout("hideAll_mom()",2000);
				$("#changeResult_mom").html("修改成功！2秒后将自动关闭此页面");
				setTimeout(function daojishi_(){$("#changeResult_mom").html("修改成功！1秒后将自动关闭此页面")},1000);
				if($("#searchMoment").val()!=""){
					searchMoment();
				}else {
					getMomentData_dongtai();
				}
			} else {
				$("#changeResult_mom").html("出现错误：" + data.msg);
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}

//添加新动态方法
function updateMoment_1(){
	$.ajax({
		type: "POST",
		url: "index.php?r=ajaxmoments/adminupdatemoment_1",
		data: {
			moment_content: $("#moment_content_1").val(),
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				setTimeout("hideAll_mom_1()",2000);
				$("#changeResult_mom_1").html("添加成功！2秒后将自动关闭此页面");
				setTimeout(function daojishi_1(){$("#changeResult_mom_1").html("添加成功！1秒后将自动关闭此页面")},1000);
				getMomentData_dongtai();
			} else {
				$("#changeResult_mom_1").html("出现错误：" + data.msg);
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}
//动态置顶提交方法
function updateMoment_2(){
	$.ajax({
		type: "POST",
		url: "index.php?r=ajaxmoments/adminupdatemoment_2",
		data: {
			momentid: $("#momentid_2").text(),
			status: $("#momenttop_2").val(),
		},
		dataType: "json",
		success: function(data){
			if (data.success) {
				setTimeout("hideAll_mom_2()",1300);
				$("#changeResult_mom_2").html("修改成功！1秒后将自动关闭此页面");
				if($("#searchMoment").val()!=""){
					searchMoment();
				}else {
					getMomentData_dongtai();
				}
			} else {
				$("#changeResult_mom_2").html("出现错误：" + data.msg);
			}
		},
		error: function(jqXHR){
			alert("发生错误：" + jqXHR.status);
		},
	});
}


