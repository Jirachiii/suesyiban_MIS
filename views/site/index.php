<?php
use yii\grid\GridView;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <link rel="stylesheet" href="css/main/bootstrap.css">
        <script src="js/jquery.min.js"></script>
    </head>
    <body onload="setup()">
        <h1>homepage</h1>
        <form action="index.php?r=site/logout" method="post">
        	<input type="submit" value="退出">
        </form>
        <br>
        <a href="index.php?r=user/item"id="hrefuser">用户界面</a>
        <a href="index.php?r=admin/index" id="hrefadmin">管理员界面</a>
        <script>
            if(<?=	$userstatus ?>!=1){
                $("#hrefadmin").hide()
            }
        </script>

    </body>
</html>
