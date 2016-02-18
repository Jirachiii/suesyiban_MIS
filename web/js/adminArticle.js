var nowPage_art = 1;
var allPage_art = 1;
var nowPage_art_sel = 1;
var allPage_art_sel= 1;
var nowPage_art_s = 1;
var allPage_art_s= 1;
var show = 0;
function tiggle() {
    if (this.show == 0) {
        document.getElementById("showandhide").style.top = '51px';
        this.show = 1;
    } else {
        document.getElementById("showandhide").style.top = '-100%';
        this.show = 0;
    }
}var show = 0;
function tiggle() {
    if (this.show == 0) {
        document.getElementById("showandhide").style.top = '51px';
        this.show = 1;
    } else {
        document.getElementById("showandhide").style.top = '-100%';
        this.show = 0;
    }
}
/**
 * 显示所有库存
 * @param a
 */
function getRawArticleData(a){
    document.getElementById("searchArticle").value="";
    $("articleMsgShow").empty();
    if(a==4){
        document.getElementById('sel_status').value=a;
    }
    nowPage_art = 1;
    allPage_art = 1;
    nowPage_art_sel = 1;
    allPage_art_sel= 1;
    nowPage_art_s = 1;
    allPage_art_s= 1;
    $("#yema_art").empty()
    $("#yema_art_sel").empty()
    $("#yema_art_s").empty()
    $("#article_prev").attr("onclick","beforePage_article()")
    $("#article_aft").attr("onclick","afterPage_article()")
    $.getJSON('index.php?r=json/getarticledata', function(data, textStatus) {
        if (textStatus == 'success') {
            if(data.success==true){
                allPage_art=data.allPage
                var tableHead = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                var tableBody = '<tbody>';
                for (var i = 0; i < data.articles.length; i++) {
                    switch(data.articles[i].status){
                        case "1":
                            data.articles[i].status='有库存';
                            break;
                        case "2":
                            data.articles[i].status='无库存';
                            break;
                        case "3":
                            data.articles[i].status='已下架';
                            break;
                    }
                    tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                };
                tableBody += '</tbody>';
                document.getElementById('articleMsgShow').innerHTML = tableHead+tableBody;
                //分页的
                var yema="<span id='yema_art' class='yema_art'></span>"
                $("#article_prev").after(yema);
                for(i=1;i<=allPage_art;i++){
                    $("#yema_art").append("<a id='"+i+"' onclick='yema_art("+i+")'>"+i+"</a>")
                }
                danqianye_art()
            }else{
                document.getElementById('articleMsgShow').innerHTML ="没有任何库存"
            }
        } else {
            alert("系统错误" + textStatus);
        }
    });
};


function hideAll() {
    document.getElementById("coverDiv").style.top = '-999px';
    document.getElementById("SetDiv").style.top = '-600px';
    document.getElementById("SetDiv").style.opacity = 0;
}
function hideAll_ch() {
    document.getElementById("coverDiv_ch").style.top = '-999px';
    document.getElementById("SetDiv_ch").style.top = '-600px';
    document.getElementById("SetDiv_ch").style.opacity = 0;
}
function hideAll_ch_2() {
    document.getElementById("coverDiv_ch_2").style.top = '-999px';
    document.getElementById("SetDiv_ch_2").style.top = '-600px';
    document.getElementById("SetDiv_ch_2").style.opacity = 0;
}
function hideAll_ch_3() {
    document.getElementById("coverDiv_ch_3").style.top = '-999px';
    document.getElementById("SetDiv_ch_3").style.top = '-600px';
    document.getElementById("SetDiv_ch_3").style.opacity = 0;
}

/**
 * Created by Administrator on 2016/1/24.
 */

/**
 * 仓库搜索
 */
function searchArticlehandle() {
    //$("#articleMsgShow").empty();
    $("#article_prev").attr("onclick","beforePage_article_s()")
    $("#article_aft").attr("onclick","afterPage_article_s()")
    nowPage_art_s = 1;
    allPage_art_s= 1;
    $("#yema_art").empty()
    $("#yema_art_sel").empty()
    $("#yema_art_s").empty()
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/adminsearcharticle&searcharticle="+$("#searchArticle").val(),
        dataType: "json",
        data: {
            page:nowPage_art_s
        },
        success: function(data){
            if (data.success) {
                if (data.articles == '') {
                    var tableBody = '<h1>没有此物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                allPage_art_s=data.allPage
                document.getElementById('articleMsgShow').innerHTML = tableBody
                //分页的
                var yema="<span id='yema_art_s' class='yema_art'></span>"
                $("#article_prev").after(yema);
                for(i=1;i<=allPage_art_s;i++){
                    $("#yema_art_s").append("<a id='"+i+"' onclick='yema_art_s("+i+")'>"+i+"</a>")
                }
                danqianye_art_s()
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
/**
 * 状态筛选
 */
function selectArticle() {
    nowPage_art_sel = 1;
    allPage_art_sel= 1;
    $("#yema_art").empty()
    $("#yema_art_sel").empty()
    $("#yema_art_s").empty()
    //$("#articleMsgShow").empty();
    $("#article_prev").attr("onclick","beforePage_article_sel()")
    $("#article_aft").attr("onclick","afterPage_article_sel()")
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/adminselectarticle&searcharticle="+$("#sel_status").val(),
        dataType: "json",
        data:{
            page: nowPage_art_sel
        },
        success: function(data){
            if (data.success) {
                if (data.articles == '') {
                    var tableBody = '<h1>此分类为空</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                document.getElementById('articleMsgShow').innerHTML = tableBody;
                allPage_art_sel=data.allPage
                //分页的
                var yema="<span id='yema_art_sel' class='yema_art'></span>"
                $("#article_prev").after(yema);
                for(i=1;i<=allPage_art_sel;i++){
                    $("#yema_art_sel").append("<a id='"+i+"' onclick='yema_art_sel("+i+")'>"+i+"</a>")
                }
                danqianye_art_sel()
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
//删除库存
function deleteArticle(article_id,article_name){
    var deleteOrNot = confirm('确定删除:'+article_name+'？');
    if(deleteOrNot == true) {
        $.ajax({
            type: "POST",
            url: "index.php?r=ajaxuser/deletearticle",
            data: {
                art_id: article_id,
            },
            dataType: "json",
            success: function(data) {
                if (data.success) {
                    alert('删除成功');
                    if($("#searchArticle").val()!=""){
                        searchArticlehandle();//回到模糊搜索界面
                    }else{
                        selectArticle();//回到筛选界面
                    }
                    // window.location.reload();
                } else {
                    alert('删除失败');
                    if($("#searchArticle").val()!=""){
                        searchArticlehandle();//回到模糊搜索界面
                    }else{
                        selectArticle();//回到筛选界面
                    }
                    // window.location.reload();
                }
            },
            error: function(jqXHR){
                alert("error:"+jqXHR.status);
            },
        });
    }
}


//插入库存
function insertArticle(){
    $.ajax({
        type: "POST",
        url: "index.php?r=ajaxuser/admininsertarticle",
        data: {
            itemname: $("#insertItemname").val(),
            number: $("#insertNumber").val(),
        },
        dataType: "json",
        success: function(data){
            if (data.success) {
                setTimeout("hideAll()",2000);
                $("#createResult").html("保存成功！2秒后将自动关闭此页面");
                setTimeout("daojishi()",1000);
                getRawArticleData();
                //setTimeout("window.location.reload();",3000);
            } else {
                $("#createResult").html("出现错误：" + data.msg);
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
//用于添加库存时两个框点击后重置另一个
function clearvalue(obj){
    $(obj).attr('id')=="changeart_sel"?$("#changeart_inp").val(""):$("#changeart_sel option:first").prop("selected", 'selected');
}
function clearvalue_2(obj){
    $(obj).attr('id')=="changeart_sel_2"?$("#changeart_inp_2").val(""):$("#changeart_sel_2 option:first").prop("selected", 'selected');
}
//添加库存数量
function updateArticle(){
    $.ajax({
        type: "POST",
        url: "index.php?r=ajaxuser/adminupdatearticle",
        data: {
            articleid: $("#articleid").text(),
            changeart_sel: $("#changeart_sel").val(),
            changeart_inp: $("#changeart_inp").val(),
            total:         Number($("#showarticlenumber").text())+Number($("#changeart_sel").val())+Number($("#changeart_inp").val()),
            //total: "10000",
        },
        dataType: "json",
        success: function(data){
            if (data.success) {
                setTimeout("hideAll_ch()",2000);
                $("#changeResult").html("修改成功！2秒后将自动关闭此页面");
                setTimeout("daojishi2()",1000);
                if($("#searchArticle").val()!=""){
                    searchArticlehandle();//回到模糊搜索界面
                }else{
                    selectArticle();//回到筛选界面
                }

            } else {
                $("#changeResult").html("出现错误：" + data.msg);
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
//减少库存数量
function updateArticle_2(){
    $.ajax({
        type: "POST",
        url: "index.php?r=ajaxuser/adminupdatearticle2",
        data: {
            articleid: $("#articleid_2").text(),
            changeart_sel: $("#changeart_sel_2").val(),
            changeart_inp: $("#changeart_inp_2").val(),
            total:         Number($("#showarticlenumber_2").text())-Number($("#changeart_sel_2").val())-Number($("#changeart_inp_2").val()),
            //total: "10000",
        },
        dataType: "json",
        success: function(data){
            if (data.success) {
                setTimeout("hideAll_ch_2()",2000);
                $("#changeResult_2").html("修改成功！2秒后将自动关闭此页面");
                setTimeout("daojishi3()",1000);
                if($("#searchArticle").val()!=""){
                    searchArticlehandle();//回到模糊搜索界面
                }else{
                    selectArticle();//回到筛选界面
                }


            } else {
                $("#changeResult_2").html("出现错误：" + data.msg);
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
//更改上架状态
function updateArticle_3(){
    $.ajax({
        type: "POST",
        url: "index.php?r=ajaxuser/adminupdatearticle3",
        data: {
            changeart_sel: $("#changeart_sel_3").val(),
            //status: $("#showarticlestatus_3").text(),
            id:$("#articleid_3").text(),
        },
        dataType: "json",
        success: function(data){
            if (data.success) {
                setTimeout(function(){
                    hideAll_ch_3()
                    $("#changeResult_3").html("");
                },1500);
                $("#changeResult_3").html("修改成功！1秒后将自动关闭此页面");
                if($("#searchArticle").val()!=""){
                    searchArticlehandle();//回到模糊搜索界面
                }else{
                    selectArticle();//回到筛选界面
                }


            } else {
                $("#changeResult_3").html("出现错误：" + data.msg);
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
function addArticle() {
    document.getElementById("coverDiv").style.top = '0px';
    document.getElementById("SetDiv").style.top = '5%';
    document.getElementById("SetDiv").style.opacity = 1;
}
//加的页面出来
function changeArticle(obj,obj2,obj3) {
    //obj:name,obj2:number;obj3:id
    document.getElementById("coverDiv_ch").style.top = '0px';
    document.getElementById("SetDiv_ch").style.top = '5%';
    document.getElementById("SetDiv_ch").style.opacity = 1;
    document.getElementById("showarticlename").innerHTML=obj;
    document.getElementById("showarticlenumber").innerHTML=obj2;
    document.getElementById("articleid").innerHTML=obj3;
    $("#changeart_sel option:first").prop("selected", 'selected');
    $("#changeResult").html("");

}
//减的页面出来
function changeArticle_2(obj,obj2,obj3) {
    //obj:name,obj2:number;obj3:id
    document.getElementById("coverDiv_ch_2").style.top = '0px';
    document.getElementById("SetDiv_ch_2").style.top = '5%';
    document.getElementById("SetDiv_ch_2").style.opacity = 1;
    document.getElementById("showarticlename_2").innerHTML=obj;
    document.getElementById("showarticlenumber_2").innerHTML=obj2;
    document.getElementById("articleid_2").innerHTML=obj3;
    $("#changeart_sel_2 option:first").prop("selected", 'selected');
    $("#changeResult_2").html("");
    $("#changeart_inp_2").val("");
}
function changeArticle_3(obj,obj3) {
    //obj:status,obj2:;obj3:id
    document.getElementById("coverDiv_ch_3").style.top = '0px';
    document.getElementById("SetDiv_ch_3").style.top = '10%';
    document.getElementById("SetDiv_ch_3").style.opacity = 1;
    if(obj='有库存'){
        document.getElementById("showarticlestatus_3").innerHTML="上架";
    }

    //document.getElementById("showarticlenumber_3").innerHTML=obj2;
    document.getElementById("articleid_3").innerHTML=obj3;
}
//插入倒计时
var daojishinum=1;
function daojishi () {
    $("#createResult").html("保存成功！"+daojishinum+"秒后将自动关闭此页面");
    daojishinum=daojishinum-1;
    if(daojishinum<0){
        clearTimeout(i);
        daojishinum=1;
        $("#insertItemname").val("");
        $("#insertNumber").val("");
        $("#createResult").html("");
    }else{
        var i=setTimeout("daojishi()",1000);

    }

}
//增加倒计时
var daojishinum2=1;
function daojishi2 () {
    $("#changeResult").html("修改成功！"+daojishinum2+"秒后将自动关闭此页面");
    daojishinum2=daojishinum2-1;
    if(daojishinum2<0){
        clearTimeout(i);
        daojishinum2=1;
        $("#changeart_sel option:first").prop("selected", 'selected');
        $("#changeResult").html("");
    }else{
        var i=setTimeout("daojishi2()",1000);

    }
}
//减少倒计时
var daojishinum3=1;
function daojishi3 () {
    $("#changeResult_2").html("修改成功！"+daojishinum3+"秒后将自动关闭此页面");
    daojishinum3=daojishinum3-1;
    if(daojishinum3<0){
        clearTimeout(i);
        daojishinum3=1;
    }else{
        var i= setTimeout("daojishi3()",1000);

    }
}
/**
 * 下一页
 */
function afterPage_article(){
    if (nowPage_art == allPage_art) {
        console.log('last');
        alert('已经是最后一页');
        return;
    }
    nowPage_art = nowPage_art+1;
    //$("#articleMsgShow").empty();
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/articlepagchange",
        dataType: "json",
        data: {
            page: nowPage_art,
        },
        success: function(data){
            if (data.success) {
                allPage_art=data.allPage
                if (data.articles == '') {
                    var tableBody = '<h1>没有物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                document.getElementById('articleMsgShow').innerHTML = tableBody;
                danqianye_art()
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
/**
 * 上一页
 */
function beforePage_article(){
    if (nowPage_art == 1) {
        console.log('first');
        alert('已经是第一页');
        return;
    }
    nowPage_art = nowPage_art-1;
    //$("#articleMsgShow").empty();
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/articlepagchange",
        dataType: "json",
        data: {
            page: nowPage_art,
        },
        success: function(data){
            if (data.success) {
                allPage_art=data.allPage
                if (data.articles == '') {
                    var tableBody = '<h1>没有物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                document.getElementById('articleMsgShow').innerHTML = tableBody;
                danqianye_art()
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
/**
 * 下一页(筛选)
 */
function afterPage_article_sel(){
    if (nowPage_art_sel == allPage_art_sel) {
        console.log('last');
        alert('已经是最后一页');
        return;
    }
    nowPage_art_sel = nowPage_art_sel+1;
    //$("#articleMsgShow").empty();
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/articlepagchangesel",
        dataType: "json",
        data: {
            page: nowPage_art_sel,
            status: $("#sel_status").val()
        },
        success: function(data){
            if (data.success) {
                allPage_art_sel=data.allPage
                if (data.articles == '') {
                    var tableBody = '<h1>没有物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                document.getElementById('articleMsgShow').innerHTML = tableBody;
                danqianye_art_sel()
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
/**
 * 上一页（筛选）
 */

function beforePage_article_sel(){
    if (nowPage_art_sel == 1) {
        console.log('first');
        alert('已经是第一页');
        return;
    }
    nowPage_art_sel = nowPage_art_sel-1;
    //$("#articleMsgShow").empty();
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/articlepagchangesel",
        dataType: "json",
        data: {
            page: nowPage_art_sel,
            status: $("#sel_status").val()
        },
        success: function(data){
            if (data.success) {
                allPage_art_sel=data.allPage
                if (data.articles == '') {
                    var tableBody = '<h1>没有物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                document.getElementById('articleMsgShow').innerHTML = tableBody;
                danqianye_art_sel()
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
/**
 * 下一页(搜素)
 */
function afterPage_article_s(){
    if (nowPage_art_s == allPage_art_s) {
        console.log('last');
        alert('已经是最后一页');
        return;
    }
    nowPage_art_s = nowPage_art_s+1;
    //$("#articleMsgShow").empty();
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/adminsearcharticlefenye",
        dataType: "json",
        data: {
            page: nowPage_art_s,
            article: $("#searchArticle").val()
        },
        success: function(data){
            if (data.success) {
                allPage_art_s=data.allPage
                if (data.articles == '') {
                    var tableBody = '<h1>没有物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                document.getElementById('articleMsgShow').innerHTML = tableBody;
                danqianye_art_s()
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
/**
 * 上一页（搜索）
 */

function beforePage_article_s(){
    if (nowPage_art_s == 1) {
        console.log('first');
        alert('已经是第一页');
        return;
    }
    nowPage_art_s = nowPage_art_s-1;
    //$("#articleMsgShow").empty();
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/adminsearcharticlefenye",
        dataType: "json",
        data: {
            page: nowPage_art_s,
            article: $("#searchArticle").val()
        },
        success: function(data){
            if (data.success) {
                allPage_art_s=data.allPage
                if (data.articles == '') {
                    var tableBody = '<h1>没有物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                document.getElementById('articleMsgShow').innerHTML = tableBody;
                danqianye_art_s()
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
/**
 * 页码
 **/
function yema_art(page){
    nowPage_art=page
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/articlepagchange",
        dataType: "json",
        data: {
            page: nowPage_art,
        },
        success: function(data){
            if (data.success) {
                allPage_art=data.allPage
                if (data.articles == '') {
                    var tableBody = '<h1>没有物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                document.getElementById('articleMsgShow').innerHTML = tableBody;
                danqianye_art()
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}
function yema_art_s(page){
    nowPage_art_s=page
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/articlepagchangesel",
        dataType: "json",
        data: {
            page: nowPage_art_sel,
            status: $("#sel_status").val()
        },
        success: function(data){
            if (data.success) {
                allPage_art_sel=data.allPage
                if (data.articles == '') {
                    var tableBody = '<h1>没有物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                document.getElementById('articleMsgShow').innerHTML = tableBody;
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}

function yema_art_sel(page){
    nowPage_art_sel=page
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/articlepagchangesel",
        dataType: "json",
        data: {
            page: nowPage_art_sel,
            status: $("#sel_status").val()
        },
        success: function(data){
            if (data.success) {
                allPage_art_sel=data.allPage
                if (data.articles == '') {
                    var tableBody = '<h1>没有物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少/更改状态/删除</td></tr></thead>';
                    for (var i = 0; i < data.articles.length; i++) {
                        switch(data.articles[i].status){
                            case "1":
                                data.articles[i].status='有库存';
                                break;
                            case "2":
                                data.articles[i].status='无库存';
                                break;
                            case "3":
                                data.articles[i].status='已下架';
                                break;
                        }
                        tableBody += '<tr><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="Set_dele glyphicon glyphicon-plus" onclick="changeArticle(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-minus" onclick="changeArticle_2(&quot;'+data.articles[i].Art_Name+'&quot;,&quot;'+data.articles[i].Art_Num+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-wrench" onclick="changeArticle_3(&quot;'+data.articles[i].status+'&quot;,&quot;'+data.articles[i].Art_Id+'&quot;)"></div> | <div class="Set_dele glyphicon glyphicon-remove" onclick="deleteArticle(&quot;'+data.articles[i].Art_Id+'&quot;,&quot;'+data.articles[i].Art_Name+'&quot;)"></div> </td></tr>';

                    };
                    tableBody += '</tbody>';
                };
                document.getElementById('articleMsgShow').innerHTML = tableBody;
                danqianye_art_sel()
            } else {
                document.getElementById('articleMsgShow').innerHTML = "出现错误：" + data.msg;
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}

/**
* 当前页高亮
*/
function danqianye_art(){
    $("#yema_art a").filter(function(inx){
        if(inx+1==nowPage_art){
            return true
        }
    }).addClass("yema_choosed")
    $("#yema_art a").filter(function(inx){
        if(inx+1!=nowPage_art){
            return true
        }
    }).removeClass("yema_choosed")
}

function danqianye_art_s(){
    $("#yema_art_s a").filter(function(inx){
        if(inx+1==nowPage_art_s){
            return true
        }
    }).addClass("yema_choosed")
    $("#yema_art_s a").filter(function(inx){
        if(inx+1!=nowPage_art_s){
            return true
        }
    }).removeClass("yema_choosed")
}
function danqianye_art_sel(){
    $("#yema_art_sel a").filter(function(inx){
        if(inx+1==nowPage_art_sel){
            return true
        }
    }).addClass("yema_choosed")
    $("#yema_art_sel a").filter(function(inx){
        if(inx+1!=nowPage_art_sel){
            return true
        }
    }).removeClass("yema_choosed")
}