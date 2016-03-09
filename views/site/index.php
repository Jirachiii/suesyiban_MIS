<?php
use yii\grid\GridView;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <link rel="stylesheet" href="css/main/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/site.css">
        <script src="js/jquery.min.js"></script>
        <style type="text/css">
            body{
                margin: 0;
                padding: 0;
            }
            .indeximg1{
                width: 49.98%;
                height: 100%;
                background-color: #74C4D7;
                float: left;
                cursor: pointer;
            }
            .indeximg2{
                width: 50%;
                height: 100%;
                background-color:#5D66D0;
                float: right;
                cursor: pointer;
            }
            .textarea{
               /* margin-left: 50%;
                margin-top: 30%;*/
                position: relative;
                top: 40%;
                left: 40%;

               /* width: 50%;
                margin: 0 auto;*/
                color:white;
                font-size: 5em;
            }

        </style>
    </head>
    <body>
    <!--     <a href="index.php?r=user/item"id="hrefuser">用户界面</a>
        <a href="index.php?r=admin/index" id="hrefadmin">管理员界面</a>
         -->
        <div class="indeximg1" id="left" onclick="leftin()">
            <p class="textarea">部员</p>
        </div> 

        <div class="indeximg2" id="right" onclick="rightin()">  
            <p class="textarea">管理员</p>
        </div>
    </body>
     // <script>
     //        if(<?=  $userstatus ?>!=1){
     //            $("#hrefadmin").hide()
     //        }
            function leftin(){
                location.href="index.php?r=user/item"
            }
            function rightin(){
                location.href="index.php?r=admin/index"
            
            }
            $(function(){
                 $("div").fadeTo(300,0.4)
                 $("div").each(function (index) {
                    $(this).hover(
                        function(){
                            $(this).fadeTo(300,1);
                        },
                        function(){
                            $(this).fadeTo(300,0.4);
                        }
                        )  
                        })
            })
        </script>
</html>
