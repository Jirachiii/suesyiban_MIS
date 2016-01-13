<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/main/bootstrap.css">
		<link rel="stylesheet" href="css/main/normalize.css">
		<link rel="stylesheet" href="css/main/login.css">
		<title>Login</title>
	</head>
	<body>
		<div class="main">
			<div class="M_log">
				<h3 class="M_header">Login</h3><br>
				<br><br>
				<div class="M_body">
					<form role="form" action="index.php?r=site/login" method="post">
						<span class="M_sp">用户名：</span><br>
						<input name="username" class="form-control" type="text"><br>
						<span class="M_sp">密码：</span><br>
						<input name="password" class="form-control" type="password" ><br><br>
						<input class="form-control btn-primary" type="submit" value="提交">
					</form>
				</div>
			</div>
		</div>
	</body>
</html>