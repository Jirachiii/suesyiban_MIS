var show = 0;
var nowPage_anpai = 1;
var allpage_anpai = 1;
function tiggle() {
    if (this.show == 0) {
        document.getElementById("showandhide").style.top = '51px';
        this.show = 1;
    } else {
        document.getElementById("showandhide").style.top = '-100%';
        this.show = 0;
    }
}
function whichweek_manual(){
    var week_sel=document.getElementById("whichweek_manual")
    for(i=1;i<=22;i++){
        week_sel.options.add(new Option('第'+i+'周',i))
    }
}
/**
 * 人工排班的页面出来
 */
function addAnapi() {
    document.getElementById("coverDiv_anpai").style.top = '0px';
    document.getElementById("SetDiv_anpai").style.top = '5%';
    document.getElementById("SetDiv_anpai").style.opacity = 1;
    $("#whichweek_manual").val("");
    $("#weekday_manual").val("");
    $("#zhibantime_manual").val("");
    whichweek_manual()
    getname_manual()
}
function loading() {
    document.getElementById("coverDiv_loading").style.top = '0px';
}

/**
 * 隐藏人工排班的页面
 */
function hideAll_anpai() {
    document.getElementById("coverDiv_anpai").style.top = '-999px';
    document.getElementById("SetDiv_anpai").style.top = '-600px';
    document.getElementById("SetDiv_anpai").style.opacity = 0;
    $("#anpai_result").html("");
}

function daojishi_anpai(){
    $("#anpai_result").html("安排值班成功！1秒后自动关闭此页面");
    setTimeout("hideAll_anpai()",1000)
    clearTimeout();
}
/**
 * 手动排班页面获取部员名字
 */
function getname_manual(){
        $.ajax({
            type:"GET",
            url:"index.php?r=emptyclass/getnamemanual",
            dataType:"json",
            success:function(data){
                if (data.success) {
                    if (data.anpai = '') {
                        $("#anpai_result").html("没有部员");
                    } else {
                        var who_manual=document.getElementById("who_manual")
                        for (var i = 0; i < data.name.length; i++) {
                            who_manual.options.add(new Option(data.name[i].Name+'('+data.name[i].XH_ID+')',data.name[i].XH_ID))
                        };
                    };
                } else {
                    document.getElementById('anpai_result').innerHTML = "出现错误：" + data.msg;
                }
            },
            error:function(data){
                alert("获取失败!")
            }
        })

}
/**
 * 人工排班插入
 */
function insertAnpai(){
    if($("#whichweek_manual").val()==null||$("#weekday_manual").val()==null||$("#zhibantime_manual").val()==null||$("#who_manual").val()==null){
        $("#anpai_result").html("请选择完整");
    }else{
        var str=$("#who_manual").find("option:selected").text();
        var name=str.substr(0,str.indexOf('('))
        $.ajax({
            type: "POST",
            url: "index.php?r=emptyclass/insertmanual",
            data: {
                stname: name,
                xh: $("#who_manual").val(),
                whichweek: $("#whichweek_manual").val(),
                weekday: $("#weekday_manual").val(),
                zhibantime: $("#zhibantime_manual").val(),
            },
            dataType: "json",
            success: function(data){
                if (data.success) {
                    $("#anpai_result").html("安排值班成功！2秒后自动关闭此页面");
                    setTimeout("daojishi_anpai()",1000)
                    if($("#whichweek").val()!=null&&$("#zhibantime").val()!=null&&$("#weekday").val()!=null){
                        searchEmptyclass();
                    }
                    if($("#whichweek_2").val()!=null&&$("#weekday_2").val()!=null){
                        loadanpai()
                    }
                } else {
                    $("#anpai_result").html("出现错误：" + data.msg);
                }
            },
            error: function(jqXHR){
                alert("发生错误：" + jqXHR.status);
            },
        });
    }

}
/**
 * 获取一周安排
 */

function loadanpai(){
    if($("#whichweek_2").val()==null||$("#weekday_2").val()==null){
        alert("请完成筛选条件")
    }else{
        $.ajax({
            type:"GET",
            url:"index.php?r=emptyclass/searchanpai",
            data:{
                whichweek_2:$("#whichweek_2").val(),
                weekday_2:$("#weekday_2").val(),
            },
            dataType:"json",
            success:function(data){
                if (data.success) {
                    if (data.anpai == '') {
                        $("#first_arr").html("暂无安排");
                        $("#second_arr").html("暂无安排");
                        $("#third_arr").html("暂无安排");
                    } else {
                        $("#first_arr").html("");
                        $("#second_arr").html("");
                        $("#third_arr").html("");
                        for (var i = 0; i < data.anpai.length; i++) {
                            switch (data.anpai[i].date_turn){
                                case "1":
                                    $("#first_arr").append(data.anpai[i].stname+" "+data.anpai[i].stid+'  '+'<span class="Set_dele glyphicon glyphicon-remove myblue " onclick="delanpai(\''+data.anpai[i].id+'\')"></span>');
                                    if(data.anpai[i].conflict_class!=null){
                                        $("#first_arr").append('<span>(有课程冲突)</span>'+'<br>')
                                    }else{
                                        $("#first_arr").append('<br>')
                                    }
                                    break;
                                case "2":
                                    $("#second_arr").append(data.anpai[i].stname+" "+data.anpai[i].stid+'  '+'<span class="Set_dele glyphicon glyphicon-remove myblue " onclick="delanpai(\''+data.anpai[i].id+'\')"></span>'+'<br>');
                                    break;
                                case "3":
                                    $("#third_arr").append(data.anpai[i].stname+" "+data.anpai[i].stid+'  '+'<span class="Set_dele glyphicon glyphicon-remove myblue " onclick="delanpai(\''+data.anpai[i].id+'\')"></span>'+'<br>');
                                    break;
                            }
                        };
                    };
                } else {
                    document.getElementById('zhibanshow').innerHTML = "出现错误：" + data.msg;
                }
            },
            error:function(data){
                alert("获取失败!")
            }
        })
    }
}

/**
 * 更新课表，把部员的课表存到一个新表中
 */
function updateMemberkb(){
    var update = confirm('当课表发生变化时使用此功能。更新需要十秒左右');
    if(update == true){
        var update2=confirm('确认更新?')
        loading()
        if(update2==true){
            $.getJSON('index.php?r=emptyclass/updatememberkb', function(data, textStatus) {
                if (textStatus == 'success') {
                    if(data.ifsuccess==true){
                        alert(data.msg)
                        window.location.reload()
                    }else{
                        alert(data.msg)
                        window.location.reload()
                    }
                } else {
                    alert("更新失败" + textStatus);
                }
            })
        }
    }
}
/**
 * 搜索这班没课的人
 */
function searchEmptyclass(){
    $("#yema_anpai").empty();
    if($("#whichweek").val()==null||$("#zhibantime").val()==null||$("#weekday").val()==null){
        alert("请完成筛选条件")
    }else{
        nowPage_anpai = 1;
        allpage_anpai = 1;
        $.ajax({
            type:"GET",
            url:"index.php?r=emptyclass/searchemptyclass",
            data:{
                page: nowPage_anpai,
                whichweek:$("#whichweek").val(),
                zhibantime:$("#zhibantime").val(),
                weekday:$("#weekday").val(),
            },
            dataType:"json",
            success:function(data){
                if (data.success) {
                    if (data.kongkebiao == '') {
                        var tableBody = '<h1>没有空闲</h1>';
                    } else {
                        var tableBody = '<thead><tr><td>姓名</td><td>学号</td><td>排班</td><td>取消</td></tr></thead><tbody>';
                        for (var i = 0; i < data.kongkebiao.length; i++) {
                            tableBody += '<tr><td>'+data.kongkebiao[i].Name+'</td><td>'+data.kongkebiao[i].XH_ID+'</td><td><div class="Set_dele glyphicon glyphicon-hand-up" onclick="managest(\''+data.kongkebiao[i].XH_ID+'\',\''+data.kongkebiao[i].Name+'\')"></div></td><td><span style="display: none" onclick="delanpai('+data.kongkebiao[i].anpai_id+')">'+data.kongkebiao[i].status+'</span></td></tr>';
                        };
                        tableBody += '</tbody>';
                    };
                    $("#msg918").empty()
                    document.getElementById('emptyclassdata').innerHTML = tableBody;
                    $("td span:contains(1)").addClass("Set_dele glyphicon glyphicon-remove myblue").show().empty();
                    $("td span[class='Set_dele glyphicon glyphicon-remove myblue']").parent().parent().insertBefore($("tbody tr:eq(0)"))
                    allpage_anpai=data.allPage
                    //分页的
                    $("#anpai_prev").show();
                    $("#anpai_aft").show();
                    var yema="<span id='yema_anpai' class='yema_anpai'></span>"
                    $("#anpai_prev").after(yema);
                    for(i=1;i<=allpage_anpai;i++){
                        $("#yema_anpai").append("<a id='"+i+"' onclick='yema_anpai("+i+")'>"+i+"</a>")
                    }
                    danqianye_anpai()
                } else {
                    document.getElementById('emptyclassdata').innerHTML = "出现错误：" + data.msg;
                }
            },
            error:function(data){
                alert("获取失败!")
            }
        })
    }
}
/**
 * 排班按钮
 */
function managest(xh,name){
    $("#yema_anpai").empty();
    $.ajax({
        type: "POST",
        url: "index.php?r=emptyclass/managest",
        data: {
            stname: name,
            xh: xh,
            whichweek:$("#whichweek").val(),
            weekday:$("#weekday").val(),
            zhibantime:$("#zhibantime").val(),
        },
        dataType: "json",
        success: function(data){
            if (data.success) {
                alert(data.msg)
                if($("#whichweek_2").val()!=""&&$("#weekday_2").val()!=""){
                    loadanpai()
                }
                searchEmptyclass();
            } else {
                $("#anpai_prev").hide();
                $("#anpai_aft").hide();
                $("#emptyclassdata").html("出现错误：" + data.msg);
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
/**
 * 删除排班
 */
function delanpai(anpai_id){
    var delornot=confirm("确定删除该排班吗？")
    if(delornot==true){
        $.ajax({
            type:"POST",
            url: "index.php?r=emptyclass/delanpai",
            data:{
                anpai_id:anpai_id,
            },
            dataType: "json",
            success:function(data){
                if(data.success){
                    alert("删除成功！");
                    if($("#whichweek").val()!=null&&$("#zhibantime").val()!=null&&$("#weekday").val()!=null){
                        searchEmptyclass();
                    }
                    if($("#whichweek_2").val()!=null&&$("#weekday_2").val()!=null){
                        loadanpai()
                    }
                }else{
                    alert("出现错误：" + data.msg);
                }

            },
            error:function(jqXHR){
                alert("error:"+jqXHR.status);
            },

        })
    }
}
//下一页
function afterPage_anpai(){
    if (nowPage_anpai == allpage_anpai) {
        console.log('last');
        alert('已经是最后一页');
        return;
    }
    nowPage_anpai= nowPage_anpai+1;
    //$("#emptyclassdata").empty();
    $.ajax({
        type:"GET",
        url:"index.php?r=emptyclass/searchemptyclass",
        data:{
            page: nowPage_anpai,
            whichweek:$("#whichweek").val(),
            zhibantime:$("#zhibantime").val(),
            weekday:$("#weekday").val(),
        },
        dataType:"json",
        success:function(data){
            if (data.success) {
                if (data.kongkebiao == '') {
                    var tableBody = '<h1>没有空闲</h1>';
                } else {
                    var tableBody = '<thead><tr><td>姓名</td><td>学号</td><td>排班</td><td>取消</td></tr></thead><tbody>';
                    for (var i = 0; i < data.kongkebiao.length; i++) {
                        tableBody += '<tr><td>'+data.kongkebiao[i].Name+'</td><td>'+data.kongkebiao[i].XH_ID+'</td><td><div class="Set_dele glyphicon glyphicon-hand-up" onclick="managest(\''+data.kongkebiao[i].XH_ID+'\',\''+data.kongkebiao[i].Name+'\')"></div></td><td><span style="display: none" onclick="delanpai('+data.kongkebiao[i].anpai_id+')">'+data.kongkebiao[i].status+'</span></td></tr>';
                    };
                    tableBody += '</tbody>';
                };
                $("#msg918").empty()
                document.getElementById('emptyclassdata').innerHTML = tableBody;
                $("td span:contains(1)").addClass("Set_dele glyphicon glyphicon-remove myblue").show().empty();
                $("td span[class='Set_dele glyphicon glyphicon-remove myblue']").parent().parent().insertBefore($("tbody tr:eq(0)"))
                $("#anpai_prev").show();
                $("#anpai_aft").show();
                allpage_anpai=data.allPage
                danqianye_anpai()
            } else {
                document.getElementById('emptyclassdata').innerHTML = "出现错误：" + data.msg;
            }
        },
        error:function(data){
            alert("获取失败!")
        }
    })
}
//上一页
function beforePage_anpai(){
    if (nowPage_anpai==1) {
        console.log('last');
        alert('已经是第一页');
        return;
    }
    nowPage_anpai= nowPage_anpai-1;
    $("#emptyclassdata").empty();
    $.ajax({
        type:"GET",
        url:"index.php?r=emptyclass/searchemptyclass",
        data:{
            page: nowPage_anpai,
            whichweek:$("#whichweek").val(),
            zhibantime:$("#zhibantime").val(),
            weekday:$("#weekday").val(),
        },
        dataType:"json",
        success:function(data){
            if (data.success) {
                if (data.kongkebiao == '') {
                    var tableBody = '<h1>没有空闲</h1>';
                } else {
                    var tableBody = '<thead><tr><td>姓名</td><td>学号</td><td>排班</td><td>取消</td></tr></thead><tbody>';
                    for (var i = 0; i < data.kongkebiao.length; i++) {
                        tableBody += '<tr><td>'+data.kongkebiao[i].Name+'</td><td>'+data.kongkebiao[i].XH_ID+'</td><td><div class="Set_dele glyphicon glyphicon-hand-up" onclick="managest(\''+data.kongkebiao[i].XH_ID+'\',\''+data.kongkebiao[i].Name+'\')"></div></td><td><span style="display: none" onclick="delanpai('+data.kongkebiao[i].anpai_id+')">'+data.kongkebiao[i].status+'</span></td></tr>';
                    };
                    tableBody += '</tbody>';
                };
                $("#msg918").empty()
                document.getElementById('emptyclassdata').innerHTML = tableBody;
                $("td span:contains(1)").addClass("Set_dele glyphicon glyphicon-remove myblue").show().empty();
                $("td span[class='Set_dele glyphicon glyphicon-remove myblue']").parent().parent().insertBefore($("tbody tr:eq(0)"))
                $("#anpai_prev").show();
                $("#anpai_aft").show();
                allpage_anpai=data.allPage
                danqianye_anpai()
            } else {
                document.getElementById('emptyclassdata').innerHTML = "出现错误：" + data.msg;
            }
        },
        error:function(data){
            alert("获取失败!")
        }
    })
}
/**
 * 页码跳转
 */
function yema_anpai(page){
    nowPage_anpai= page
    $("#emptyclassdata").empty();
    $.ajax({
        type:"GET",
        url:"index.php?r=emptyclass/searchemptyclass",
        data:{
            page: nowPage_anpai,
            whichweek:$("#whichweek").val(),
            zhibantime:$("#zhibantime").val(),
            weekday:$("#weekday").val(),
        },
        dataType:"json",
        success:function(data){
            if (data.success) {
                if (data.kongkebiao == '') {
                    var tableBody = '<h1>没有空闲</h1>';
                } else {
                    var tableBody = '<thead><tr><td>姓名</td><td>学号</td><td>排班</td><td>取消</td></tr></thead><tbody>';
                    for (var i = 0; i < data.kongkebiao.length; i++) {
                        tableBody += '<tr><td>'+data.kongkebiao[i].Name+'</td><td>'+data.kongkebiao[i].XH_ID+'</td><td><div class="Set_dele glyphicon glyphicon-hand-up" onclick="managest(\''+data.kongkebiao[i].XH_ID+'\',\''+data.kongkebiao[i].Name+'\')"></div></td><td><span style="display: none" onclick="delanpai('+data.kongkebiao[i].anpai_id+')">'+data.kongkebiao[i].status+'</span></td></tr>';
                    };
                    tableBody += '</tbody>';
                };
                $("#msg918").empty()
                document.getElementById('emptyclassdata').innerHTML = tableBody;
                $("td span:contains(1)").addClass("Set_dele glyphicon glyphicon-remove myblue").show().empty();
                $("td span[class='Set_dele glyphicon glyphicon-remove myblue']").parent().parent().insertBefore($("tbody tr:eq(0)"))
                $("#anpai_prev").show();
                $("#anpai_aft").show();
                allpage_anpai=data.allPage
                danqianye_anpai()

            } else {
                document.getElementById('emptyclassdata').innerHTML = "出现错误：" + data.msg;
            }
        },
        error:function(data){
            alert("获取失败!")
        }
    })
}
function danqianye_anpai(){
    $("#yema_anpai a").filter(function(inx){
        if(inx+1==nowPage_anpai){
            return true
        }
    }).addClass("yema_choosed")
    $("#yema_anpai a").filter(function(inx){
        if(inx+1!=nowPage_anpai){
            return true
        }
    }).removeClass("yema_choosed")
}
