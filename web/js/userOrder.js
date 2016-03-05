function searchOrder(){
    if($("#whichweek_1").val()==null||$("#weekday_1").val()==null){
        alert("请完成筛选条件")
    }else{
        $.ajax({
            type:"GET",
            url:"index.php?r=emptyclass/searchorder",
            data:{
                whichweek_user:$("#whichweek_1").val(),
                weekday_user:$("#weekday_1").val(),
            },
            dataType:"json",
            success:function(data){
                if (data.success) {
                    if (data.anpai == '') {
                        $("#getname").empty();
                        $("#getname").append(data.name);
                        $("#first_arr").html("暂无安排");
                        $("#second_arr").html("暂无安排");
                        $("#third_arr").html("暂无安排");
                    } else {
                        $("#getname").empty();
                        $("#getname").append(data.name);
                        $("#first_arr").html("");
                        $("#second_arr").html("");
                        $("#third_arr").html("");
                        for (var i = 0; i < data.anpai.length; i++) {
                            switch (data.anpai[i].date_turn){
                                case "1":
                                    $("#first_arr").append(data.anpai[i].stname+" "+data.anpai[i].stid);
                                    if(data.anpai[i].conflict_class!=null){
                                        $("#first_arr").append('<span>(有课程冲突)</span>'+'<br>')
                                    }else{
                                        $("#first_arr").append('<br>')
                                    }
                                    break;
                                case "2":
                                    $("#second_arr").append(data.anpai[i].stname+" "+data.anpai[i].stid);
                                    if(data.anpai[i].conflict_class!=null){
                                        $("#second_arr").append('<span>(有课程冲突)</span>'+'<br>')
                                    }else{
                                        $("#second_arr").append('<br>')
                                    }
                                    break;
                                case "3":
                                    $("#third_arr").append(data.anpai[i].stname+" "+data.anpai[i].stid);
                                    if(data.anpai[i].conflict_class!=null){
                                        $("#third_arr").append('<span>(有课程冲突)</span>'+'<br>')
                                    }else{
                                        $("#third_arr").append('<br>')
                                    }
                                    break;
                            }
                        };
                    };
                } else {
                    document.getElementById('zhibanshow').innerHTML = "出现错误：" + data.msg;
                    $("#getname").empty();
                    $("#getname").append(data.name);
                }
            },
            error:function(data){
                alert("获取失败!")
            }
        })
    }
}