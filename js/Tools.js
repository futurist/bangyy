
$(function () {
    //搜索框   开始
    $(".u-in").unbind().bind("click", searchKeyWord);
    //搜索框   
});
function searchKeyWord() {
    var url = "";
    var type = "product";
    //var kwd = $(this).children(".m-ipt").children("#se_key").val();//$("#se_key").val();
    var kwd = $(this).parent().find("#se_key").val();
    if (kwd != "请输入关键词") {
        url = "/Mobile/MProduct/ProSearch.aspx?objtype=" + type + "&kwd=" + kwd;
        window.location.href = url;
    }
}
/* 新闻详细页评论  */
function postComment(src, _oid, _mark) {
    var _content = $("#txtComment").val();
    $("#validate").removeAttr("class").addClass("red");
    if (_content.length == 0 || _content == "还可以输入140个字") {
        $("#validate").empty();
        $("#validate").append("请输入评论内容！");
        return;
    }
    if (_content.length > 140) {
        $("#validate").empty();
        $("#validate").append("输入内容过多，不能超过140字！");
        return;
    }
    $.post("/Mobile/MAjax.ashx?action=PostComment&t=" + Math.random(), {
        content: _content,
        oid: _oid,
        mark: _mark
    }, function (msg) {
        $("#validate").empty();
        var sta = gav(msg, "state");
        var sMsg = gav(msg, "msg");
        if (sta == "1") {
            $("#validate").removeClass("red").addClass("green");
        }
        $("#validate").append(sMsg);
        $("#txtComment").val("");
    });
}
/* 新闻详细页评论  */
/* 提交意向订单  */
//提交订单信息，UserName，为用户名文本框的ID，Phone为手机文本框的ID，OrderInfo为订单描述文本框的ID，_oid为当前产品的ID
function submitOrder(UserName, Phone, OrderInfo, _oid) {
    var zz = /^1[3|4|5|8][0-9]\d{4,8}$/;
    $("#ErrorInfo").removeAttr("class").addClass("red pl10");
    if ($("#" + UserName + "").val() == "" || $("#" + UserName + "").val() == "请输入姓名") {
        $("#ErrorInfo").empty();
        $("#ErrorInfo").append("请输入用户名");
        return;
    } else if ($("#" + Phone + "").val() == "" || $("#" + Phone + "").val() == "请输入手机号") {
        $("#ErrorInfo").empty();
        $("#ErrorInfo").append("请输入手机号码");
        return;
    } else if (!zz.test($("#" + Phone + "").val())) {
        $("#ErrorInfo").empty();
        $("#ErrorInfo").append("手机号码格式不正确");
        return;
    } else if ($("#" + OrderInfo + "").val() == "" || $("#" + OrderInfo + "").val() == "请输入留言") {
        $("#ErrorInfo").empty();
        $("#ErrorInfo").append("请输入留言信息");
        return;
    } else if ($("#" + OrderInfo + "").val().length < 10) {
        $("#ErrorInfo").empty();
        $("#ErrorInfo").append("留言信息必须10个字以上");
        return;
    } else if (_oid <= 0) {
        $("#ErrorInfo").empty();
        $("#ErrorInfo").append("提交数据有误，请重试");
    } else {
        $("#ErrorInfo").empty();
        $.post("/Mobile/MAjax.ashx?action=submitorder&t=" + Math.random(), {
            oid: _oid,
            UserName: $("#" + UserName + "").val(),
            Phone: $("#" + Phone + "").val(),
            OrderInfo: $("#" + OrderInfo + "").val()
        }, function (msg) {
            var sta = gav(msg, "state");
            var sMsg = gav(msg, "msg");
            $("#ErrorInfo").append(sMsg);
            if (sta == "1") {
                $("#ErrorInfo").removeAttr("class").addClass("green pl10");
                $("#ErrorInfo").show(1000, function () {
                    $("#" + UserName + "").val("");
                    $("#" + Phone + "").val("");
                    $("#" + OrderInfo + "").val("");
                    $("#" + UserName + "").val("请输入姓名");
                    $("#" + Phone + "").val("请输入手机号");
                    $("#" + OrderInfo + "").val("请输入留言");
                });
            } else {
            }
        });
    }
}
/* 提交意向订单  */
///根据ID（产品和资讯ID读取评论列表和回复列表）
//AppendTo  此参数表示要拼接的的元素的ID，比如将读取的评论列表拼接到ID为CommentList的Div里面
//subId 根据ID读取相应的评论，此ID可以为产品ID和资讯ID
//_mark 评论类型，product为产品，news为资讯
function GetCommentById(AppendTo, Top, subId, type) {
    $.post("/Mobile/MAjax.ashx?action=GetCommentById&t=" + Math.random(), {
        oid: subId,
        type: type,
        top: Top
    }, function (msg) {
        var iCount = $(msg).find("count").text();
        var cou = "评论(" + iCount + "条)";
        var commtns = $(msg).find("comment");
        var sHtml = "";
        for (var i = 0; i < commtns.length; i++) {
            var jCmt = $(commtns[i]);
            var sUsername = jCmt.find("username").text();
            var sContent = jCmt.find("content").text();
            var sTime = jCmt.find("inputTime").text().toString();
            var sfeedback = jCmt.find("feedback").text();
            //sHtml += "<dl>"
            //  + "<dt class=\"relay_time\"><span>" + sUsername + "</span> 于<em>" + sTime + "</em>说道：</dt>"
            //      + "  <dd>" +
            //           +" <p class=\"re_fir\">" + sContent + "</p>"
            //           + " <p class=\"re_sec\"><span class=\"tit\">管理员回复：</span>" + sfeedback + "</p>"
            //        + "</dd>"
            //    + "</dl>";
            //sHtml = sHtml.replace("NaN", "");
            var list = [];
            list.push(["<dl>", "<dt class=\"relay_time\"><span>", sUsername, "</span> 于<em>", sTime, "</em>说道：</dt>"].join(''));
            list.push(["  <dd>", " <p class=\"re_fir\">", sContent.toString(), "</p>", " <p class=\"re_sec\"><span class=\"tit\">管理员回复：</span>"].join(''));
            list.push([sfeedback, "</p>", "</dd>", "</dl>"].join(''));
            list = list.join('');
            sHtml += list;
        }
        var _type = type == "product" ? 1 : 2;    //product为产品，news为资讯    
        sHtml += "<p class=\"show_more\"><a href=\"javascript:void(0)\" onclick=\"javascript:window.location='/Mobile/Tools/Comment.aspx?oid=" + subId + "&type=" + _type + "';\" id=\"spCommentCount\" class=\"blue fs12\">" + cou + "</a><input class='u-co-bt f-fr' type='button' name='' value='全部评论' onclick=\"javascript:window.location='/Mobile/Tools/Comment.aspx?oid=" + subId + "&type=" + _type + "';\"></p>";
        if (iCount > 0) {
            $("#" + AppendTo + "").html(sHtml);
        } else {
            $("#" + AppendTo + "").html("暂无评论");
        }
    });
}
///根据ID（产品和资讯ID读取评论列表和回复列表）
//AppendTo  此参数表示要拼接的的元素的ID，比如将读取的评论列表拼接到ID为CommentList的Div里面
//subId 根据ID读取相应的评论，此ID可以为产品ID和资讯ID
//_mark 评论类型，product为产品，news为资讯
function GetCommentById2(AppendTo, Top, subId, type) {
    $.post("/Mobile/MAjax.ashx?action=GetCommentById&t=" + Math.random(), {
        oid: subId,
        type: type,
        top: Top
    }, function (msg) {
        var iCount = $(msg).find("count").text();
        var commtns = $(msg).find("comment");
        $("#span-num").text(iCount);
        var sHead = "<h2 class=\"tj\">用户评论(<i class=\"red\">" + iCount + "</i>)</h2><div class=\"u-com\">";
        var sHtml = "";
        if (iCount > 0) {
            for (var i = 0; i < commtns.length; i++) {
                var jCmt = $(commtns[i]);
                var sUsername = jCmt.find("username").text();
                var sContent = jCmt.find("content").text();
                var sTime = jCmt.find("inputTime").text().toString();
                var sfeedback = jCmt.find("feedback").text();
                sHtml = sHtml + "<dl> <dt>" + sContent + "</dt>  <dd><i class=\"f-fl\">来自：" + sUsername + "</i> <i class=\"f-fr\">" + sTime + "</i>   </dd></dl>";
            }
        } else {
            sHtml = sHtml + "<span class='u-noComment'>暂无评论</span>";
        }
        var _type = type == "product" ? 1 : 2;    //product为产品，news为资讯    
        sHtml = sHead + sHtml + "<a href=\"javascript:void(0)\" onclick=\"javascript:window.location='/Mobile/Tools/Comment1.aspx?oid=" + subId + "&type=" + _type + "'\"><p class=\"u-btn1\">我要评论</p></a>";
        sHtml = sHtml + "<a href=\"javascript:void(0)\" onclick=\"javascript:window.location='/Mobile/Tools/Comment.aspx?oid=" + subId + "&type=" + _type + "'\"><p class=\"u-btn1\">查看更多</p></a><p class=\"clear\"></p></div>";
        $("#" + AppendTo + "").html(sHtml);
    });
}
/********************
* 修改个人资料
********************/
function UpdateUserData() {
    var _userName = $("#txtUserName").val();   //用户名
    var _phone = $("#txtPhone").val();         //手机号码
    var _email = $("#txtEmail").val();         //邮箱
    var _address = $("#txtAddress").val();     //地址
    var _userName1 = $("#txtUserName1").val();   //用户名
    var _phone1 = $("#txtPhone1").val();         //手机号码
    var _email1 = $("#txtEmail1").val();         //邮箱
    var _address1 = $("#txtAddress1").val();     //地址
    $("#validate1").text("");
    $("#validate2").text("");
    $("#validate3").text("");
    $("#validate3").removeAttr("class").addClass("red");
    if (_userName == _userName1 && _phone == _phone1 && _email == _email1 && _address == _address1) {
        $("#validate3").text("您没有修改或新增任何资料");
        return;
    }
    var _reg = /^1[3|4|5|8][0-9]\d{4,8}$/;
    if (!_reg.test(_phone)) {
        $("#validate1").text("手机号不合法！");
        return;
    }
    _reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    if (!_reg.test(_email)) {
        $("#validate2").text("邮箱格式不正确！");
        return;
    }
    $.post("/Mobile/MAjax.ashx?action=UpdateUserData&t=" + Math.random(), {
        UserName: _userName,
        Phone: _phone,
        Email: _email,
        Address: _address
    }, function (msg) {
        var sta = gav(msg, "state");
        var sMsg = gav(msg, "msg");
        $("#validate3").removeClass("red").addClass("green");
        if (sta == "1") {
            $("#txtUserName1").val(_userName);   //用户名
            $("#txtPhone1").val(_phone);         //手机号码
            $("#txtEmail1").val(_email);         //邮箱
            $("#txtAddress1").val(_address);     //地址
            $("#validate3").text(sMsg);
        } else if (sta == "-1") {
            $("#validate3").text(sMsg);
        } else {
            window.location = '../User/Login.aspx.htm'/*tpa=http://www.bmxz99.com/mobile/User/Login.aspx*/;
        }
    });
}
/********************
* 修改个人资料
********************/
/********************
* 修改密码
********************/
function UpdateUserPwd() {
    var _pwd = $("#txtPwd").val();               //原始密码
    var _newPwd = $("#txtNewPwd").val();         //新密码
    var _newPwdTwo = $("#txtNewPwdTwo").val();   //确认新密码
    $("#validate1").text("");
    $("#validate2").text("");
    $("#validate3").text("");
    if (_pwd.length == 0) {
        $("#validate1").text("原始密码不能为空！");
        $("#txtPwd").focus();
    } else if (_pwd.length < 6) {
        $("#validate1").text("原始密码长度小于6");
        $("#txtPwd").focus();
    } else if (_newPwd.length == 0) {
        $("#validate2").text("新密码不能为空！");
        $("#txtNewPwd").focus();
    } else if (_newPwd.length < 6) {
        $("#validate2").text("新密码长度小于6");
        $("#txtNewPwd").focus();
    } else if (_newPwdTwo.length == 0) {
        $("#validate3").text("确认密码不能为空！");
        $("#txtNewPwdTwo").focus();
    } else if (_newPwd != _newPwdTwo) {
        $("#validate3").text("两次输入密码不一致，请检查");
        $("#txtNewPwd").focus();
    } else {
        $.post("/Mobile/MAjax.ashx?action=UpdateUserPwd&t=" + Math.random(), {
            Pwd: _pwd,
            NewPwd: _newPwd
        }, function (msg) {
            var sta = gav(msg, "state");
            var sMsg = gav(msg, "msg");
            $("#validate1").text("");
            $("#validate2").text("");
            $("#validate3").text(sMsg);
            $("#txtPwd").val("");
            $("#txtNewPwd").val("");
            $("#txtNewPwdTwo").val("");
            if (sta == "2") {
                window.location = '../User/Login.aspx.htm'/*tpa=http://www.bmxz99.com/mobile/User/Login.aspx*/;
            } else if (sta == "1") {
                $("#validate3").removeClass("red").addClass("green");
            }
        });
    }
}
/********************
* 修改密码
********************/
/********************
* 首页 在线申请加盟
********************/
function SaveAgentApply() {
    var _content = $("#txtContent").val() == "请输入留言" ? "" : $("#txtContent").val();
    var _phone = $("#txtPhone").val() == "请输入手机号" ? "" : $("#txtPhone").val();
    var _name = $("#txtName").val() == "请输入姓名" ? "游客" : $("#txtName").val();
    $("#MsgInfo").removeAttr("class").addClass("red pl10");
    var _reg = /^1[3|4|5|8][0-9]\d{4,8}$/;
    if (_phone == "") {
        $("#MsgInfo").text("手机不能为空");
        $("#txtPhone").focus();
    } else if (!_reg.test(_phone)) {
        $("#MsgInfo").text("手机号码格式不对");
        $("#txtPhone").focus();
    } else if (_content == "") {
        $("#MsgInfo").text("提交的内容不能为空");
        $("#txtContent").focus();
    } else {
        $.post("/Mobile/MAjax.ashx?action=CheckAgentApply&t=" + Math.random(), {
            Name: _name,
            Phone: _phone,
            Content: _content
        }, function (msg) {
            var sta = gav(msg, "state");
            var sMsg = gav(msg, "msg");
            if (sta == "1") {
                $("#MsgInfo").removeClass("red pl10").addClass("green pl10");
                $("#MsgInfo").text(sMsg);
                $("#txtContent").val("");
                $("#txtPhone").val("");
                $("#txtName").val("");
            }
            else {
                $("#MsgInfo").text(sMsg);
            }
        });
    }
}
/********************
* 首页 在线申请加盟
********************/
/********************
* 显示验证码
********************/
function showVerifyCode(elmId, msgElmId, imgId, chgLnkId) {
    if (elmId == null) {
        elmId = "spVerCode";
    }
    if (msgElmId == null) {
        msgElmId = "spVerCodeMsg";
    }
    if (imgId == null) {
        imgId = "imgVerCode";
    }
    if (chgLnkId == null) {
        chgLnkId = "spChgVerCode";
    }
    var jImg = $("#" + elmId + "");
    var jMsg = $("#" + msgElmId + "");
    var jChgLnk = $("#" + chgLnkId + "");
    if (jImg.html() == "") {
        jMsg.html("正在加载验证码...");
        jMsg.show();
        jImg.html("<img src='../../Tools/ValidCode.aspx.gif'/*tpa=http://www.bmxz99.com/Tools/ValidCode.aspx*/  style='width:50px;height:25px;display:none;' id='" + imgId + "' alt='验证码' />");
    }
    var jVerCode = $("#" + imgId + "");
    jVerCode.bind("load", function () {
        jMsg.hide();
        jVerCode.show();
        jChgLnk.show();
        $("#changeCode").show();   //显示换一张链接 
    });
}
/********************
* 在线咨询/反馈/加盟申请
********************/
function SaveFaq(postType,columnId) {
    var _content = $("#txtConent").val() == "还可以输入500个字" ? "" : $("#txtConent").val();
    var _phone = $("#txtPhone").val() == "请输入手机号" ? "" : $("#txtPhone").val();
    var _reg = /^1[3|4|5|8][0-9]\d{4,8}$/;
    if (_content == "") {
        $("#Msg").html("<p class='red'>提交的内容不能为空</p>");
        document.getElementById("txtConent").focus();
    }
    else if (_content.length >= 500) {
        $("#Msg").html("<p class='red'>输入内容过多，不能超过500字！</p>");
        document.getElementById("txtConent").focus();
    }
    else if (_phone != "" && !_reg.test(_phone)) {
        $("#Msg").html("<p class='red'>手机号码格式不对</p>");
        document.getElementById("txtConent").focus();
    } else {
        if (columnId == false) {
            columnId = 0;
        }
        $.post("/Mobile/MAjax.ashx?action=CheckFaq&t=" + Math.random(), {
            PostType: postType,
            Phone: _phone,
            Content: _content,
            ColumnId:columnId
        }, function (msg) {
            $("#txtConent").val("");
            $("#txtPhone").val("");
            $("#Msg").html("<p class='green'>提交成功，感谢您的参与。</p>");
            $(".right").hide();
            $(".wrong").hide();
            $(".contapic_bg").hide();
            var returnValue = parseInt(gav(msg, "state"), 10);
            if (typeof (returnValue) == "undefined") {
                $(".wrong").show();
                $(".contapic_bg").show();
            } else if (parseInt(returnValue) >= 0) {
                $(".right").show();
                $(".contapic_bg").show();
            } else {
                $(".wrong").show();
                $(".contapic_bg").show();
            }
        });
    }
}
/********************
* 在线咨询/反馈/加盟申请
********************/
/********************
* 得到在线咨询/反馈/加盟申请
********************/
//AppendTo  此参数表示要拼接的的元素的ID，比如将读取的评论列表拼接到ID为CommentList的Div里面
//Top 显示加盟申请的条数
//subId 根据ID读取相应的评论，此ID可以为招商加盟ID
function GetFaqById(AppendTo, Top, subId) {
    $.post("/Mobile/MAjax.ashx?action=GetFaqById&t=" + Math.random(), {
        oid: subId,
        top: Top
    }, function (msg) {
        var iCount = $(msg).find("count").text();
        var cou = "评论(" + iCount + "条)";
        var commtns = $(msg).find("comment");
        var sHtml = "";
        for (var i = 0; i < commtns.length; i++) {
            var jCmt = $(commtns[i]);
            var sUsername = jCmt.find("username").text();
            var sContent = jCmt.find("content").text();
            var sTime = jCmt.find("inputTime").text().toString();
            var sfeedback = jCmt.find("feedback").text();
            var list = [];
            list.push(["<dl>", "<dt class=\"relay_time\"><span>", sUsername, "</span> 于<em>", sTime, "</em>说道：</dt>"].join(''));
            list.push(["  <dd>", " <p class=\"re_fir\">", sContent.toString(), "</p>", " <p class=\"re_sec\"><span class=\"tit\">管理员回复：</span>"].join(''));
            list.push([sfeedback, "</p>", "</dd>", "</dl>"].join(''));
            list = list.join('');
            sHtml += list;
        }
        var _type = "agent";
        sHtml += "<p class=\"show_more\"><a href=\"javascript:void(0)\" onclick=\"javascript:window.location='/Mobile/Tools/Comment.aspx?oid=" + subId + "&type=" + _type + "';\" id=\"spCommentCount\" class=\"blue fs12\">" + cou + "</a><input class='u-co-bt f-fr' type='button' name='' value='全部评论' onclick=\"javascript:window.location='/Mobile/Tools/Comment.aspx?oid=" + subId + "&type=" + _type + "';\"></p>";
        if (iCount > 0) {
            $("#" + AppendTo + "").html(sHtml);
        } else {
            $("#" + AppendTo + "").html("暂无评论");
        }
    });
}
/********************
* 得到在线咨询/反馈/加盟申请
********************/
/********************
* 得到方案的评论数
********************/
function getProjectNum(subId) {
    $.post("/Mobile/MAjax.ashx?action=GetProjectNum&t=" + Math.random(), {
        ID: subId
    }, function (msg) {
        var returnValue = parseInt(gav(msg, "count"),0);
        $("#projectNum").text(returnValue);
    });
}
/********************
* 得到方案的评论数
********************/
/********************
* 更换验证码
********************/
function changeVerCode(elmId, msgElmId) {
    if (elmId == null) {
        elmId = "imgVerCode";
    }
    if (msgElmId == null) {
        msgElmId = "spVerCodeMsg";
    }
    var jImg = $("#" + elmId + "");
    var jMsg = $("#" + msgElmId + "");
    jMsg.html("正在刷新验证码...").show();
    jImg.attr({ src: "/Tools/ValidCode.aspx?x=" + Math.random(), alt: "验证码" });
    jImg.hide();
    jImg.bind("load", function () {
        jMsg.hide();
        jImg.show();
    });
}
/********************
* 加入收藏代码  
*研发部张新华
********************/
function addfavor(url, title) {
    if (confirm("网站名称：" + title + "\n网址：" + url + "\n确定添加收藏?")) {
        if (document.all) {
            window.external.addToFavoritesBar("" + url + "", '' + title + '');
        } else if (window.sidebar) {
            window.sidebar.addPanel('' + title + '', "" + url + "", "");
        } else {
            alert('关闭本提示后，请使用Ctrl+D添加到收藏夹');
        }
    }
}
/********************
* 根据key获取 ajax对象节点值getAjaxVal
* xMsg : xml对象
* key : 节点的属性key
********************/
function gav(xMsg, key) {
    var jMsg = $(xMsg);
    var s = $(jMsg.find("node[key=" + key + "]")).text();
    return s;
}
/**************************************************************************************
*详细页分页功能公用函数
*参数说明：
*fit-----点击的方向，up,down,qw三个值，up表示上一页，down表示下一页，qw表示余下全文
*pCount-----表示总页数
*currUrl----表示当前页的链接  如果：xxxxx_page2.html  程序会自动截取_page后面的2来分页
*OId:当前文章或者产品的ID
*思路：
*当pCount小于1的时候，内容不分页 ，分页按钮不显示
*当pCount大于1，用户可以点击上一页，当当前页等于1的时候 上一页按钮禁用
*当当前页小于pCount的时候，用户可以点击下一页，否则禁用下一页按钮
*当pCount大于3的时候显示余下全文按钮，否则都不显示
*余下全文按钮的的效果：点击余下全文，将从当前页开始到最后一页的内容全部加载出来
***************************************************************************************/
function SetPageCount(getType, OId, pCount, currUrl) {
    //1-设置分页按钮是否显示隐藏，根据pCount判断
    if (parseInt(pCount) > 1) {
        $("#setPage").show(); //显示分页层
        //如果pCount>3才显示余下全文按钮
        if (pCount > 3) {
            $(".page-qw").show();
        } else {
            $(".page-qw").hide();
        }
        //设置总页数数
        var curr = currUrl.substring(currUrl.indexOf("_page") + 5, currUrl.indexOf(".html"));
        if (parseInt(curr) == 1) {
            $("#up").addClass("no_click");
        } else if (parseInt(curr) == pCount) {
            $("#next").addClass("no_click");
            $("#qw").hide();
        }
        $("#pCount").empty();
        //$("#pCount").append("<span class=\"pageno\">" + curr + "</span>/" + pCount + "");
        document.getElementById("pCount").innerHTML = "<span class=\"pageno\">" + curr + "</span>/" + pCount + "";
        $("#up").unbind("click").bind("click", function () {
            ToUp(getType, OId, "up", pCount, curr, currUrl);
        });
        $("#qw").unbind("click").bind("click", function () {
            ToUp(getType, OId, "qw", pCount, curr, currUrl);
        });
        $("#next").unbind("click").bind("click", function () {
            ToUp(getType, OId, "down", pCount, curr, currUrl);
        });
    } else {
        $("#setPage").hide(); //隐藏分页层
    }
}
/*********************************************
*根据用户的动作决定是显示上一页还是下一页还是余下全文
*参数说明：
*fit:用户动作，up上一页，down下一页，qw余下全文
*pCount：总页数
*curr:当前页
*currUrl：当前页面的路径
*********************************************/
function ToUp(getType, OId, fit, pCount, curr, currUrl) {
    var strUrl = window.location.href.toString();
    strUrl = strUrl.substring(0, strUrl.indexOf("_page"));
    curr = parseInt(curr);
    switch (fit) {
        case "up":
            if (curr > 1) {
                curr = curr - 1;
                strUrl += "_page" + curr + ".html";
            } else {
                //已经是第一页了
                $("#up").addClass("no_click");
                $("#up").unbind("click");
                return;
            }
            break;
        case "down":
            if (curr < pCount) {
                curr = curr + 1;
                strUrl += "_page" + curr + ".html";
            } else {
                //已经是最后一页了
                $("#next").addClass("no_click");
                $("#next").unbind("click");
                $("#qw").addClass("no_click");
                return;
            }
            break;
        case "qw":
            if (pCount - curr <= 8) {
                $.post("/Mobile/MAjax.ashx?action=GetContentQW&t=" + Math.random(),
                    {
                        curr: curr,
                        OId: OId,
                        getType: getType
                    }
                    , function (msg) {
                        if (gav(msg, "state") == "1") {
                            $("#content").empty();
                            $("#content").append(gav(msg, "Content") + "<div class=\"clear\"></div>");
                            $("#setPage").hide(); //隐藏分页层
                        } else {
                            alert(gav(msg, "Content"));
                        }
                    });
            } else {
                alert("抱歉，余下全文的内容太长，请翻页查看");
            }
            return;
        default:
            if (curr > 1) {
                curr = curr - 1;
                strUrl += "_page" + curr + ".html";
            } else {
                //已经是第一页了
                $("#up").addClass("no_click");
                $("#up").unbind("click");
                return;
            }
            break;
    }
    window.location = strUrl;
}
///清空表单中的文本数据
///formName参数：表单的ID
function emptyText(formName) {
    document.getElementById(formName).onreset();
}
/*  设置列表页分页  */
function SetListPage(action,index) {
    var path = decodeURI(window.location.href.toLowerCase());
    path = path.replace("#logo", "").replace("#", "");
    var p = path.substring(path.lastIndexOf('/') + 1).toLowerCase();
    var ext = ".shtml";   //后缀
    var regex = /^(\w)*-(\d{4}(,\d{4})*).shtml$/;               //例news-0001,0003,0030.shtml的格式
    var regexPage = /^(\w)*-(\d{4}(,\d{4})*)-(\d+).shtml$/;     //例news-0001,0003,0030-1.shtml的格式
    var pageIndex = $("#lblPageIndex").text();      //得到当前页
    var totalCount = $("#lblTotalCount").text();    //得到总页数
    if (action == "pageNext") {      //下一页
        pageIndex = parseInt(pageIndex, 10) + 1;
    } else if (action == "pageUp")   //上一页
    {
        pageIndex = parseInt(pageIndex, 10) - 1;
    }
    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    else if (pageIndex >= totalCount) {
        pageIndex = totalCount;
    }
    if (index != "") {
        pageIndex = index;
    }
    path = path.toLowerCase();
    if (path.indexOf("search") >= 0 || path.lastIndexOf("index.aspx.htm"/*tpa=http://www.bmxz99.com/Mobile/js/index.aspx*/) >= 0 || path.lastIndexOf("index" + ext) >= 0 || path.lastIndexOf("list.aspx.htm"/*tpa=http://www.bmxz99.com/Mobile/js/list.aspx*/) >= 0 || path.lastIndexOf("list" + ext) >= 0 || path.indexOf("ajax.ashx.htm"/*tpa=http://www.bmxz99.com/Mobile/js/ajax.ashx*/) >= 0 || p.indexOf("commend.aspx.htm"/*tpa=http://www.bmxz99.com/Mobile/js/commend.aspx*/)>=0 || p == "" || p.indexOf("1=1&page=") >= 0)//首页、正常列表页
    {
        path = getUrl(path) + "&page=" + pageIndex;
        window.location.href = path;
    } else if (regex.test(p)) {           //例news-0001,0003,0030.shtml的格式
        path = path.substring(0, path.lastIndexOf('.')) + "-" + pageIndex + ext;
        window.location.href = path;
    } else if (regexPage.test(p)) {      //例news-0001,0003,0030-1.shtml的格式  
        path = path.substring(0, path.lastIndexOf('-') + 1) + pageIndex + ext;
        window.location.href = path;
    }
    else {          //扩展URL的伪静态
        var regexPage = /^(\w+)-(\d+).shtml$/;  //有分页
        var regex2 = /^(\w+).shtml$/;           //无分页
        if (regexPage.test(p)) {
            path = path.substring(0, path.lastIndexOf('-') + 1) + pageIndex + ext;
            window.location.href = path;
        } else if (regex2.test(p)) {
            path = path.substring(0, path.lastIndexOf(".")) + "-" + pageIndex + ext;
            window.location.href = path;
        }
    }
    return false;
}
//设置分页下拉框的值
$(function () {
    var _size = $("#lblTotalCount").text();
    var _index = $("#lblPageIndex").text();
    for (var i = 1; i <= _size; i++) {
        var op = new Option(i, i);
        $("#drpPageIndex").append(op);
    }
    $("#drpPageIndex").val(_index);
});
function getUrl(path) {
    var para;
    var i = path.indexOf("?");
    if (path.indexOf("?") != -1) {
        para = path.substring(i + 1);
        var index = para.lastIndexOf("&page");
        if (index != -1) {
            para = para.substring(0, index);
            path = path.substring(0, i + 1) + para;
        }
    }
    else {
        para = "?1=1";
        path = path + para;
    }
    return path;
}
/*  列表页分页  */
/*产品列表外宽宽度  */
$(function () {
    var _width = parseInt($(".wrap_width img").attr("width"), 10);
    if (_width > 130) {
        _width = _width + 2;
        $(".wrap_width").css("width", _width + "px");
    }
});
//ProductDetail.aspx页面使用的方法----------------------------------------------------------------------------------------------------------------------------
function GetContentByProductId(proId) {
    if (proId == 0) {
        alert("抱歉，参数错误");
        history.go(-1);
        return;
    }
    getContent(proId, 1);
}
function getContent(proId, currPage) {
    //ID正确，则开始请求数据
    $.post("/Mobile/MAjax.ashx?action=GetContentByProductId&t=" + Math.random(), { ProId: proId, CurrPage: currPage }, function (rsp) {
        if ($(rsp).find("Error").length > 0) {
            alert($(rsp).find("Error").text());
        } else {
            $("#AppendContent").empty();
            $("#AppendContent").append($(rsp).find("Content").html());
            var count = $(rsp).find("PageCount").text();
            var curr = $(rsp).find("CurrPage").text();
            $("#ToUp").attr("curr", curr);
            $("#Count").attr("Count", count);
            $("#Count").empty();
            $("#Count").append(curr + "/" + count);
        }
    });
}
function ToUpDown(proId, action) {
    if (proId == 0) {
        alert("抱歉，参数错误");
        history.go(-1);
        return;
    }
    var curr = parseInt($("#ToUp").attr("curr"));
    var count = parseInt($("#Count").attr("Count"));
    switch (action) {
        case "Up":
            if (curr > 1) {
                curr -= 1;
            } else {
                $("#ToUp").addClass("no_click");
                $("#ToUp").unbind("click");
                return;
            }
            break;
        case "Down":
            if (curr < count) {
                curr += 1;
            } else {
                $("#ToUp").addClass("no_click");
                $("#ToDown").unbind("click");
                return;
            }
            break;
    }
    getContent(proId, curr);
}
///根据产品ID获取产品的评论总数
//参数说明：
//proId:产品ID
var commentCount = 1;
function GetCommentCountByProId(proId, appendDiv) {
    if (proId == 0) {
        alert("抱歉，参数错误");
        history.go(-1);
        return;
    }
    //ID正确，则开始请求数据
    $.post("/Mobile/MAjax.ashx?action=GetCommentCountByProId&t=" + Math.random(), { ProId: proId }, function (rsp) {
        if ($(rsp).find("Error").length > 0) {
            alert($(rsp).find("Error").text());
        } else {
            $("#" + appendDiv + "").empty();
            $("#" + appendDiv + "").append($(rsp).find("Count").text());
            commentCount = parseInt($(rsp).find("Count").text());
            //获取产品评论列表
        }
    });
}
///忘记密码后的修改密码操作
function UpdatePwd(code) {
    if ($("#txtNewPin").val() == "") {
        $(validate).text("请输入新密码");
    } else if ($("#txtConfirmPin").val() == "") {
        $(validate).text("请输入确认新密码");
    } else if ($("#txtNewPin").val() != $("#txtConfirmPin").val()) {
        $(validate).text("新密码和确认新密码不一致，请重新输入");
    } else {
        $.post("/Mobile/MAjax.ashx?action=UpdatePwd&t=" + Math.random(), { Code: code, Pwd: $("#txtNewPin").val() }, function (msg) {
            var sta = gav(msg, "state");
            var sMsg = gav(msg, "msg");
            if (sta == "0") {
                $("#validate").text(sMsg);
            } else {
                $("#contapic_bg").show();
                $("#right").show();
                window.setTimeout(function () { location.href = "../User/login-1.aspx.htm"/*tpa=http://www.bmxz99.com/Mobile/User/login.aspx*/; }, 3000);
            }
        });
    }
}