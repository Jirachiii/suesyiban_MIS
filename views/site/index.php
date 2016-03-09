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
                overflow: hidden;

            }
            .indeximg2{
                width: 50%;
                height: 100%;
                background-color:#5D66D0;
                float: right;
                overflow: hidden;
                cursor: pointer;
            }
            .textarea{
                margin-left: 35%;
                margin-top: 35%;
                color:white;
                font-size: 5em;
                font-family: Microsoft YaHei;
            }
            .textarea_hidden{
                opacity:0.3!important;
            }

        </style>
    </head>
    <body>
        <div class="indeximg1" id="left" onclick="leftin()">
            <p class="textarea">部 员</p>
        </div> 

        <div class="indeximg2" id="right" onclick="rightin()">  
            <p class="textarea">管 理 员</p>
        </div>
    </body>
         <script>
            function leftin(){
                location.href="index.php?r=user/item"
            }
            function rightin(){
                location.href="index.php?r=admin/index"
            
            }
            $(function(){
                if(<?=  $userstatus ?>!=1){
                    $("#right").removeAttr('onclick').addClass("textarea_hidden")
                }
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
