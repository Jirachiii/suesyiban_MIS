<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="height=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" href="css/main/bootstrap.css">
	<link rel="stylesheet" href="css/login/login.css">
	<link rel="stylesheet" type="text/css" href="css/bootcss.css">
	<link rel="stylesheet" href="css/login.css">
	<title>document</title>
	<style>
		html,body {
			width: 100%;
			height:100%;
			background-image: url(images/sues1.png);
			background-size: percentage;
			background-position: 50% 50%;
			overflow: hidden;
		}

	</style>
</head>
<body onload="loginGetIn()">
<section class="loginhandle" id="Login">
	<h3 class="login_h3" id="log_h">易班工作站登陆</h3><br>
<?php $form = ActiveForm::begin([
		'id'            => 'login-form',
		'options'       => ['class'       => 'form-horizontal'],
		'fieldConfig'   => [
			'template'     => "{label}\n<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
			'labelOptions' => ['class' => 'col-lg-12 control-label'],
		],
	]);?>
<?=$form->field($model, 'username')->input('text', ['class' => 'ipt', 'id' => 'log_ipt1', 'placeholder' => '学号'])?>

<?=$form->field($model, 'password')->passwordInput(['class' => 'ipt', 'id' => 'log_ipt2', 'placeholder' => '密码'])?>
<!-- <?=$form->field($model, 'rememberMe')->checkbox([
		'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
	])?>-->

<?=Html::submitButton('登录', ['class' => 'ipt ipt_btn', 'name' => 'login-button', 'id' => 'log_ipt3', 'placeholder' => '密码'])?>


<?php ActiveForm::end();?>
</section>
<p class="ipt_p">©2015-2018 工程大易班软件开发部, All Rights Reserved.　　本站发布的所有内容，未经许可，不得转载，详见《知识产权声明》。</p>
<script src="js/jquery.min.js"></script>
<script>
	function loginGetIn() {
		$('.control-label').remove();
		document.getElementById("Login").style.left = '50%';
		document.getElementById("log_ipt1").style.left = '50%';
		document.getElementById("log_ipt2").style.left = '50%';
		document.getElementById("log_ipt3").style.left = '50%';
	}
</script>
</body>
</html>