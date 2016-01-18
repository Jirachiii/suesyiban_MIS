<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/main/bootstrap.css">
		<link rel="stylesheet" href="css/main/normalize.css">
		<link rel="stylesheet" href="css/admin/adminMain.css">
		<link rel="stylesheet" href="css/admin/adminUser.css">
		<title>管理员</title>
	</head>
	<body onload="getMomentData()">
		<header class="Ad_head">
			<button class="btn btn-primary hd_downbtn" onclick="tiggle()">下拉</button>
		</header>
		<div class="col-xs-2 Ad_Lef" id="showandhide">
			<br>
			<input type="text" class="form-control Ad_Lef_sea" placeholder="Search"><br>
			<div class="Ad_Lef_hr"></div><br>
			<div class="Ad_Lef_btn" onclick="UserMeneShow()">
				<span class="col-xs-2 Ad_Lef_btnPic"><a class="glyphicon glyphicon-home Ad_Lef_btnA"></a></span>
				<div class="col-xs-10 Ad_Lef_btnWord"><p class="Ad_Lef_btnP">用户管理</p></div>
			</div>
			<div class="Ad_Lef_btn" onclick="emptyClassShow()">
				<span class="col-xs-2 Ad_Lef_btnPic"><a class="glyphicon glyphicon-education Ad_Lef_btnA"></a></span>
				<div class="col-xs-10 Ad_Lef_btnWord"><p class="Ad_Lef_btnP">空课表查看</p></div>
			</div>
			<div class="Ad_Lef_btn" onclick="itemShow()">
				<span class="col-xs-2 Ad_Lef_btnPic"><a class="glyphicon glyphicon-briefcase Ad_Lef_btnA"></a></span>
				<div class="col-xs-10 Ad_Lef_btnWord"><p class="Ad_Lef_btnP">项目查看</p></div>
			</div>
			<div class="Ad_Lef_btn" onclick="itemCreate()">
				<span class="col-xs-2 Ad_Lef_btnPic"><a class="glyphicon glyphicon-folder-open Ad_Lef_btnA"></a></span>
				<div class="col-xs-10 Ad_Lef_btnWord"><p class="Ad_Lef_btnP">项目发布</p></div>
			</div>
			<div class="Ad_Lef_btn" onclick="item()">
				<span class="col-xs-2 Ad_Lef_btnPic"><a class="glyphicon glyphicon-calendar Ad_Lef_btnA"></a></span>
				<div class="col-xs-10 Ad_Lef_btnWord"><p class="Ad_Lef_btnP">项目</p></div>
			</div>
			<div class="Ad_Lef_btn" onclick="SignIn()">
				<span class="col-xs-2 Ad_Lef_btnPic"><a class="glyphicon glyphicon-th-list Ad_Lef_btnA"></a></span>
				<div class="col-xs-10 Ad_Lef_btnWord"><p class="Ad_Lef_btnP">签到情况</p></div>
			</div>
			<div class="Ad_Lef_btn_checked" onclick="MomentsMene()">
				<span class="col-xs-2 Ad_Lef_btnPic"><a class="glyphicon glyphicon-comment Ad_Lef_btnA Ad_Lef_btn_colorchange"></a></span>
				<div class="col-xs-10 Ad_Lef_btnWord"><p class="Ad_Lef_btnP Ad_Lef_btn_colorchange">动态管理</p></div>
			</div>
			<br>
		</div>
		<div class="col-xs-10 Ad_Rig">
			<h2>动态管理</h2><br>
			<div class="Ad_RShow"><br>
				<span class="Ad_btns">
						<a class="nav_btn" onclick="getRawData()">所有动态</a>
						<a class="nav_btn" onclick="addUser()">动态置顶</a>
						<a class="nav_btn" onclick="?">动态搜索</a>
				</span><hr><br>
				<div class="Ad_User_Main">
					<table id="MomentsData" class="table table-condensed table-hover">
					</table>
				</div>
			</div>
		</div>
        <script src="js/adminHref.js"></script>
        <script src="js/adminMoments.js"></script>
        <script src="http://apps.bdimg.com/libs/jquery/1.11.1/jquery.js"></script>
		<script>
			var show = 0;
			function tiggle() {
				if (this.show == 0) {
					document.getElementById("showandhide").style.top = '51px';
					this.show = 1;
				} else {
					document.getElementById("showandhide").style.top = '-100%';
					this.show = 0;
				}
			}
			function getMomentData() {
				$.getJSON('index.php?r=json/getmomentdata', function(data, textStatus) {
					if (textStatus == 'success') {
						var tableHead = '<thead><tr><td>编号</td><td>学号</td><td>内容</td><td>点赞数</td><td>姓名</td><td>删除</td></tr></thead>';
						var tableBody = '<tbody>';
						for (var i = 0; i < data.moments.length; i++) {
							tableBody += '<tr><td>'+(i+1)+'</td><td>'+data.moments[i].XH_ID+'</td><td>'+data.moments[i].Content+'</td><td>'+data.moments[i].like_Num+'</td><td>'+data.moments[i].username+'</td><td><div class="Set_dele glyphicon glyphicon-remove" onclick="deleteMom('+data.moments[i].id+')"></div></td></tr>';
						};
						tableBody += '</tbody>';
						document.getElementById('MomentsData').innerHTML = tableHead+tableBody;
					} else {
						alert("系统错误" + textStatus);
					}
				});
			}
		</script>
	</body>
</html>