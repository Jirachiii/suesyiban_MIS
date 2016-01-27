//动态显示
function getMomentData_dongtai() {
	document.getElementById("searchMoment").value="";
	$.getJSON('index.php?r=json/getmomentdata', function(data, textStatus) {
		if (textStatus == 'success') {
			this.allpage = data.allPage;
			var tableHead = '<thead><tr><td>学号</td><td>内容</td><td>时间</td><td>姓名</td><td>修改/置顶/删除</td></tr></thead>';
			var tableBody = '<tbody>';
			for (var i = 0; i < data.moments.length; i++) {
				tableBody += '<tr><td>'+data.moments[i].XH_ID+'</td><td>'+data.moments[i].Content+'</td><td>'+data.moments[i].Mdate+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-pencil" onclick="changeMoment(&quot;'+data.moments[i].id+'&quot;,&quot;'+data.moments[i].XH_ID+'&quot;,&quot;'+data.moments[i].username+'&quot;,&quot;'+data.moments[i].Mdate+'&quot;,&quot;'+data.moments[i].Content+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-sort" onclick="changeMomentTop('+data.moments[i].id+')"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
			};
			tableBody += '</tbody>';
			document.getElementById('MomentsData').innerHTML = tableHead+tableBody;
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
					alert('删除成功');
					getMomentData_dongtai();
				} else {
					alert('删除失败');
					getMomentData_dongtai();
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
					tableBody += '<tr><td>'+data.moments[i].XH_ID+'</td><td>'+data.moments[i].Content+'</td><td>'+data.moments[i].Mdate+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-pencil" onclick="changeMoment(&quot;'+data.moments[i].id+'&quot;,&quot;'+data.moments[i].XH_ID+'&quot;,&quot;'+data.moments[i].username+'&quot;,&quot;'+data.moments[i].Mdate+'&quot;,&quot;'+data.moments[i].Content+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-sort" onclick="changeMomentTop('+data.moments[i].id+')"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
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
					tableBody += '<tr><td>'+data.moments[i].XH_ID+'</td><td>'+data.moments[i].Content+'</td><td>'+data.moments[i].Mdate+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-pencil" onclick="changeMoment(&quot;'+data.moments[i].id+'&quot;,&quot;'+data.moments[i].XH_ID+'&quot;,&quot;'+data.moments[i].username+'&quot;,&quot;'+data.moments[i].Mdate+'&quot;,&quot;'+data.moments[i].Content+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-sort" onclick="changeMomentTop('+data.moments[i].id+')"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
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
						tableBody += '<tr><td>'+data.moments[i].XH_ID+'</td><td>'+data.moments[i].Content+'</td><td>'+data.moments[i].Mdate+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-pencil" onclick="changeMoment(&quot;'+data.moments[i].id+'&quot;,&quot;'+data.moments[i].XH_ID+'&quot;,&quot;'+data.moments[i].username+'&quot;,&quot;'+data.moments[i].Mdate+'&quot;,&quot;'+data.moments[i].Content+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-sort" onclick="changeMomentTop('+data.moments[i].id+')"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';


					};
					tableBody += '</tbody>';
				};
				document.getElementById('MomentsData').innerHTML = tableBody;
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
//更改动态页面
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


}
//动态内容提交方法
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
				setTimeout("daojishi_mom()",1000);
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
//置顶动态
function changeMomentTop(){

}


//倒计时
var daojishinum_mom=1;
function daojishi_mom () {
	$("#changeResult_mom").html("修改成功！"+daojishinum_mom+"秒后将自动关闭此页面");
	daojishinum_mom=daojishinum_mom-1;
	if(daojishinum_mom<0){
		clearTimeout(i);
		daojishinum_mom=1;
		//document.getElementById("changeResult_mom").value="";
		$("#changeResult_mom").html(" ");
	}else{
		var i= setTimeout("daojishi_mom()",1000);
	}
}


