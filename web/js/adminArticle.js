//仓库管理
/**
 * Created by Administrator on 2016/1/24.
 */
//仓库搜索
function searchArticlehandle() {
    $("#articleMsgShow").empty();
    $.ajax({
        type: "GET",
        url: "index.php?r=ajaxuser/adminsearcharticle&searcharticle="+$("#searchArticle").val(),
        dataType: "json",
        success: function(data){
            if (data.success) {
                if (data.articles == '') {
                    var tableBody = '<h1>没有此物品</h1>';
                } else {
                    var tableBody = '<thead><tr><td>编号</td><td>物品</td><td>数量</td><td>第一次添加时间</td><td>状态</td><td>添加/减少</td></tr></thead>';
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
                        tableBody += '<tr><td>'+(i+1)+'</td><td>'+data.articles[i].Art_Name+'</td><td>'+data.articles[i].Art_Num+'</td><td>'+data.articles[i].Art_Time+'</td><td>'+data.articles[i].status+'</td><td><div class="glyphicon glyphicon-plus" onclick=""></div> | <div class="glyphicon glyphicon-minus" onclick=""></div></td></tr>';
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
function deleteArticle(article_id){
    var deleteOrNot = confirm('确定删除:'+article_id+'？');
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
                    window.location.reload();
                } else {
                    alert('删除失败');
                    window.location.reload();
                }
            },
            error: function(jqXHR){
                alert("error:"+jqXHR.status);
            },
        });
    }
}

function addArticle() {
    document.getElementById("coverDiv").style.top = '0px';
    document.getElementById("SetDiv").style.top = '5%';
    document.getElementById("SetDiv").style.opacity = 1;
}

var daojishinum=2;
function daojishi () {
    $("#createResult").html("保存成功！"+daojishinum+"秒后将自动关闭此页面");
    daojishinum=daojishinum-1;
    setTimeout("daojishi()",1000);

}
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
                setTimeout("hideAll()",3500);
                $("#createResult").html("保存成功！3秒后将自动关闭此页面");
                setTimeout("daojishi()",1000);
                setTimeout("window.location.reload();",3800);


            } else {
                $("#createResult").html("出现错误：" + data.msg);
            }
        },
        error: function(jqXHR){
            alert("发生错误：" + jqXHR.status);
        },
    });
}