
function getUrl (item) {
  var svalue = location.search.match(new RegExp("[\?\&]" + item + "=([^\&]*)(\&?)","i"));
  svalue = svalue ? svalue[1] : svalue;
  if(/openid/i.test(item)){
	  var s = Zepto.cookie("openid");
	  if(s){
		svalue=s;
	  }else{
		svalue=+new Date()+Math.round(Math.random()*100);
		Zepto.cookie("openid", svalue,{path:"/"}); 
	  }
  }
	return svalue;
}

try{
	var desc = document.querySelector("meta[name=description]").content;	
}catch(e){
	var desc = document.title;
}


var urlstr1=window.location.pathname;
var urlstrtmp=urlstr1.split("/");
var urlstr="http://"+window.location.host;
for(var i=0;i<urlstrtmp.length-1;i++){
    urlstr+=urlstrtmp[i]+"/";
}



(function (window) {

    "use strict";

    /**
     * 定义WeixinApi
     */
    var WeixinApi = {
        version: 4.3
    };

    // 将WeixinApi暴露到window下：全局可使用，对旧版本向下兼容
    window.WeixinApi = WeixinApi;

    /////////////////////////// CommonJS /////////////////////////////////
    if (typeof define === 'function' && (define.amd || define.cmd)) {
        if (define.amd) {
            // AMD 规范，for：requirejs
            define(function () {
                return WeixinApi;
            });
        } else if (define.cmd) {
            // CMD 规范，for：seajs
            define(function (require, exports, module) {
                module.exports = WeixinApi;
            });
        }
    }

    /**
     * 对象简单继承，后面的覆盖前面的，继承深度：deep=1
     * @private
     */
    var _extend = function () {
        var result = {}, obj, k;
        for (var i = 0, len = arguments.length; i < len; i++) {
            obj = arguments[i];
            if (typeof obj === 'object') {
                for (k in obj) {
                    obj[k] && (result[k] = obj[k]);
                }
            }
        }
        return result;
    };

    /**
     * 内部私有方法，分享用
     * @private
     */
    var _share = function (cmd, data, callbacks) {
        callbacks = callbacks || {};

        // 分享过程中的一些回调
        var progress = function (resp) {
            switch (true) {
                // 用户取消
                case /\:cancel$/i.test(resp.err_msg) :
                    callbacks.cancel && callbacks.cancel(resp);
                    break;
                // 发送成功
                case /\:(confirm|ok)$/i.test(resp.err_msg):
                    callbacks.confirm && callbacks.confirm(resp);
                    break;
                // fail　发送失败
                case /\:fail$/i.test(resp.err_msg) :
                default:
                    callbacks.fail && callbacks.fail(resp);
                    break;
            }
            // 无论成功失败都会执行的回调
            callbacks.all && callbacks.all(resp);
        };

        // 执行分享，并处理结果
        var handler = function (theData, argv) {

            // 加工一下数据
            if (cmd.menu == 'menu:share:timeline' ||
                (cmd.menu == 'general:share' && argv.shareTo == 'timeline')) {

                var title = theData.title;
                theData.title = theData.desc || title;
                theData.desc = title || theData.desc;
            }

            // 如果是收藏操作，并且在wxCallbacks中配置了favorite为false，则不执行回调
            if (argv && (argv.shareTo == 'favorite' || argv.scene == 'favorite')) {
                if (callbacks.favorite === false) {
                    WeixinJSBridge.invoke('sendAppMessage', theData, new Function());
                } else {
                    WeixinJSBridge.invoke(cmd.action, theData, progress);
                }
            } else {
                // 新的分享接口，单独处理
                if (cmd.menu === 'general:share') {
                    if (argv.shareTo === 'timeline') {
                        WeixinJSBridge.invoke('shareTimeline', theData, progress);
                    } else if (argv.shareTo === 'friend') {
                        WeixinJSBridge.invoke('sendAppMessage', theData, progress);
                    } else if (argv.shareTo === 'QQ') {
                        WeixinJSBridge.invoke('shareQQ', theData, progress);
                    } else if (argv.shareTo === 'weibo') {
                        WeixinJSBridge.invoke('shareWeibo', theData, progress);
                    }
                } else {
                    WeixinJSBridge.invoke(cmd.action, theData, progress);
                }
            }
        };

        // 监听分享操作
        WeixinJSBridge.on(cmd.menu, function (argv) {
            callbacks.dataLoaded = callbacks.dataLoaded || new Function();
            if (callbacks.async && callbacks.ready) {
                WeixinApi["_wx_loadedCb_"] = callbacks.dataLoaded;
                if (WeixinApi["_wx_loadedCb_"].toString().indexOf("_wx_loadedCb_") > 0) {
                    WeixinApi["_wx_loadedCb_"] = new Function();
                }
                callbacks.dataLoaded = function (newData) {
                    callbacks.__cbkCalled = true;
                    var theData = _extend(data, newData);
                    theData.img_url = theData.imgUrl || theData.img_url;
                    delete theData.imgUrl;
                    WeixinApi["_wx_loadedCb_"](theData);
                    handler(theData, argv);
                };
                // 然后就绪
                if (!(argv && (argv.shareTo == 'favorite' || argv.scene == 'favorite') && callbacks.favorite === false)) {
                    callbacks.ready && callbacks.ready(argv, data);
                    // 如果设置了async为true，但是在ready方法中并没有手动调用dataLoaded方法，则自动触发一次
                    if (!callbacks.__cbkCalled) {
                        callbacks.dataLoaded({});
                        callbacks.__cbkCalled = false;
                    }
                }
            } else {
                // 就绪状态
                var theData = _extend(data);
                if (!(argv && (argv.shareTo == 'favorite' || argv.scene == 'favorite') && callbacks.favorite === false)) {
                    callbacks.ready && callbacks.ready(argv, theData);
                }
                handler(theData, argv);
            }
        });
    };

    /**
     * 分享到微信朋友圈
     * @param       {Object}    data       待分享的信息
     * @p-config    {String}    appId      公众平台的appId（服务号可用）
     * @p-config    {String}    imgUrl     图片地址
     * @p-config    {String}    link       链接地址
     * @p-config    {String}    desc       描述
     * @p-config    {String}    title      分享的标题
     *
     * @param       {Object}    callbacks  相关回调方法
     * @p-config    {Boolean}   async                   ready方法是否需要异步执行，默认false
     * @p-config    {Function}  ready(argv, data)       就绪状态
     * @p-config    {Function}  dataLoaded(data)        数据加载完成后调用，async为true时有用，也可以为空
     * @p-config    {Function}  cancel(resp)    取消
     * @p-config    {Function}  fail(resp)      失败
     * @p-config    {Function}  confirm(resp)   成功
     * @p-config    {Function}  all(resp)       无论成功失败都会执行的回调
     */
    WeixinApi.shareToTimeline = function (data, callbacks) {
        _share({
            menu: 'menu:share:timeline',
            action: 'shareTimeline'
        }, {
            "appid": data.appId ? data.appId : '',
            "img_url": data.imgUrl,
            "link": data.link,
            "desc": data.desc,
            "title": data.title,
            "img_width": "640",
            "img_height": "640"
        }, callbacks);
    };

    /**
     * 发送给微信上的好友
     * @param       {Object}    data       待分享的信息
     * @p-config    {String}    appId      公众平台的appId（服务号可用）
     * @p-config    {String}    imgUrl     图片地址
     * @p-config    {String}    link       链接地址
     * @p-config    {String}    desc       描述
     * @p-config    {String}    title      分享的标题
     *
     * @param       {Object}    callbacks  相关回调方法
     * @p-config    {Boolean}   async                   ready方法是否需要异步执行，默认false
     * @p-config    {Function}  ready(argv, data)       就绪状态
     * @p-config    {Function}  dataLoaded(data)        数据加载完成后调用，async为true时有用，也可以为空
     * @p-config    {Function}  cancel(resp)    取消
     * @p-config    {Function}  fail(resp)      失败
     * @p-config    {Function}  confirm(resp)   成功
     * @p-config    {Function}  all(resp)       无论成功失败都会执行的回调
     */
    WeixinApi.shareToFriend = function (data, callbacks) {
        _share({
            menu: 'menu:share:appmessage',
            action: 'sendAppMessage'
        }, {
            "appid": data.appId ? data.appId : '',
            "img_url": data.imgUrl,
            "link": data.link,
            "desc": data.desc,
            "title": data.title,
            "img_width": "640",
            "img_height": "640"
        }, callbacks);
    };

    /**
     * 分享到腾讯微博
     * @param       {Object}    data       待分享的信息
     * @p-config    {String}    link       链接地址
     * @p-config    {String}    desc       描述
     *
     * @param       {Object}    callbacks  相关回调方法
     * @p-config    {Boolean}   async                   ready方法是否需要异步执行，默认false
     * @p-config    {Function}  ready(argv, data)       就绪状态
     * @p-config    {Function}  dataLoaded(data)        数据加载完成后调用，async为true时有用，也可以为空
     * @p-config    {Function}  cancel(resp)    取消
     * @p-config    {Function}  fail(resp)      失败
     * @p-config    {Function}  confirm(resp)   成功
     * @p-config    {Function}  all(resp)       无论成功失败都会执行的回调
     */
    WeixinApi.shareToWeibo = function (data, callbacks) {
        _share({
            menu: 'menu:share:weibo',
            action: 'shareWeibo'
        }, {
            "content": data.desc,
            "url": data.link
        }, callbacks);
    };

    /**
     * 新的分享接口
     * @param       {Object}    data       待分享的信息
     * @p-config    {String}    appId      公众平台的appId（服务号可用）
     * @p-config    {String}    imgUrl     图片地址
     * @p-config    {String}    link       链接地址
     * @p-config    {String}    desc       描述
     * @p-config    {String}    title      分享的标题
     *
     * @param       {Object}    callbacks  相关回调方法
     * @p-config    {Boolean}   async                   ready方法是否需要异步执行，默认false
     * @p-config    {Function}  ready(argv, data)       就绪状态
     * @p-config    {Function}  dataLoaded(data)        数据加载完成后调用，async为true时有用，也可以为空
     * @p-config    {Function}  cancel(resp)    取消
     * @p-config    {Function}  fail(resp)      失败
     * @p-config    {Function}  confirm(resp)   成功
     * @p-config    {Function}  all(resp)       无论成功失败都会执行的回调
     */
    WeixinApi.generalShare = function (data, callbacks) {
        _share({
            menu: 'general:share'
        }, {
            "appid": data.appId ? data.appId : '',
            "img_url": data.imgUrl,
            "link": data.link,
            "desc": data.desc,
            "title": data.title,
            "img_width": "640",
            "img_height": "640"
        }, callbacks);
    };

    /**
     * 设置页面禁止分享：包括朋友圈、好友、腾讯微博、qq
     * @param callback
     */
    WeixinApi.disabledShare = function (callback) {
        callback = callback || function () {
            alert('当前页面禁止分享！');
        };
        ['menu:share:timeline', 'menu:share:appmessage', 'menu:share:qq',
            'menu:share:weibo', 'general:share'].forEach(function (menu) {
                WeixinJSBridge.on(menu, function () {
                    callback();
                    return false;
                });
            });
    };

    /**
     * 调起微信Native的图片播放组件。
     * 这里必须对参数进行强检测，如果参数不合法，直接会导致微信客户端crash
     *
     * @param {String} curSrc 当前播放的图片地址
     * @param {Array} srcList 图片地址列表
     */
    WeixinApi.imagePreview = function (curSrc, srcList) {
        if (!curSrc || !srcList || srcList.length == 0) {
            return;
        }
        WeixinJSBridge.invoke('imagePreview', {
            'current': curSrc,
            'urls': srcList
        });
    };

    /**
     * 显示网页右上角的按钮
     */
    WeixinApi.showOptionMenu = function () {
        WeixinJSBridge.call('showOptionMenu');
    };


    /**
     * 隐藏网页右上角的按钮
     */
    WeixinApi.hideOptionMenu = function () {
        WeixinJSBridge.call('hideOptionMenu');
    };

    /**
     * 显示底部工具栏
     */
    WeixinApi.showToolbar = function () {
        WeixinJSBridge.call('showToolbar');
    };

    /**
     * 隐藏底部工具栏
     */
    WeixinApi.hideToolbar = function () {
        WeixinJSBridge.call('hideToolbar');
    };

    /**
     * 返回如下几种类型：
     *
     * network_type:wifi     wifi网络
     * network_type:edge     非wifi,包含3G/2G
     * network_type:fail     网络断开连接
     * network_type:wwan     2g或者3g
     *
     * 使用方法：
     * WeixinApi.getNetworkType(function(networkType){
     *
     * });
     *
     * @param callback
     */
    WeixinApi.getNetworkType = function (callback) {
        if (callback && typeof callback == 'function') {
            WeixinJSBridge.invoke('getNetworkType', {}, function (e) {
                // 在这里拿到e.err_msg，这里面就包含了所有的网络类型
                callback(e.err_msg);
            });
        }
    };

    /**
     * 关闭当前微信公众平台页面
     * @param       {Object}    callbacks       回调方法
     * @p-config    {Function}  fail(resp)      失败
     * @p-config    {Function}  success(resp)   成功
     */
    WeixinApi.closeWindow = function (callbacks) {
        callbacks = callbacks || {};
        WeixinJSBridge.invoke("closeWindow", {}, function (resp) {
            switch (resp.err_msg) {
                // 关闭成功
                case 'close_window:ok':
                    callbacks.success && callbacks.success(resp);
                    break;

                // 关闭失败
                default :
                    callbacks.fail && callbacks.fail(resp);
                    break;
            }
        });
    };

    /**
     * 当页面加载完毕后执行，使用方法：
     * WeixinApi.ready(function(Api){
     *     // 从这里只用Api即是WeixinApi
     * });
     * @param readyCallback
     */
    WeixinApi.ready = function (readyCallback) {

        /**
         * 加一个钩子，同时解决Android和iOS下的分享问题
         * @private
         */
        var _hook = function () {
            var _WeixinJSBridge = {};
            Object.keys(WeixinJSBridge).forEach(function (key) {
                _WeixinJSBridge[key] = WeixinJSBridge[key];
            });
            Object.keys(WeixinJSBridge).forEach(function (key) {
                if (typeof WeixinJSBridge[key] === 'function') {
                    WeixinJSBridge[key] = function () {
                        try {
                            var args = arguments.length > 0 ? arguments[0] : {},
                                runOn3rd_apis = args.__params ? args.__params.__runOn3rd_apis || [] : [];
                            ['menu:share:timeline', 'menu:share:appmessage', 'menu:share:weibo',
                                'menu:share:qq', 'general:share'].forEach(function (menu) {
                                    runOn3rd_apis.indexOf(menu) === -1 && runOn3rd_apis.push(menu);
                                });
                        } catch (e) {
                        }
                        return _WeixinJSBridge[key].apply(WeixinJSBridge, arguments);
                    };
                }
            });
        };

        if (readyCallback && typeof readyCallback == 'function') {
            var Api = this;
            var wxReadyFunc = function () {
                _hook();
                readyCallback(Api);
            };
            if (typeof window.WeixinJSBridge == "undefined") {
                if (document.addEventListener) {
                    document.addEventListener('WeixinJSBridgeReady', wxReadyFunc, false);
                } else if (document.attachEvent) {
                    document.attachEvent('WeixinJSBridgeReady', wxReadyFunc);
                    document.attachEvent('onWeixinJSBridgeReady', wxReadyFunc);
                }
            } else {
                wxReadyFunc();
            }
        }
    };

    /**
     * 判断当前网页是否在微信内置浏览器中打开
     */
    WeixinApi.openInWeixin = function () {
        return /MicroMessenger/i.test(navigator.userAgent);
    };

    /**
     * 发送邮件
     * @param       {Object}  data      邮件初始内容
     * @p-config    {String}  subject   邮件标题
     * @p-config    {String}  body      邮件正文
     *
     * @param       {Object}    callbacks       相关回调方法
     * @p-config    {Function}  fail(resp)      失败
     * @p-config    {Function}  success(resp)   成功
     * @p-config    {Function}  all(resp)       无论成功失败都会执行的回调
     */
    WeixinApi.sendEmail = function (data, callbacks) {
        callbacks = callbacks || {};
        WeixinJSBridge.invoke("sendEmail", {
            "title": data.subject,
            "content": data.body
        }, function (resp) {
            if (resp.err_msg === 'send_email:sent') {
                callbacks.success && callbacks.success(resp);
            } else {
                callbacks.fail && callbacks.fail(resp);
            }
            callbacks.all && callbacks.all(resp);
        })
    };

    /**
     * 开启Api的debug模式，比如出了个什么错误，能alert告诉你，而不是一直很苦逼的在想哪儿出问题了
     * @param    {Function}  callback(error) 出错后的回调，默认是alert
     */
    WeixinApi.enableDebugMode = function (callback) {
        /**
         * @param {String}  errorMessage   错误信息
         * @param {String}  scriptURI      出错的文件
         * @param {Long}    lineNumber     出错代码的行号
         * @param {Long}    columnNumber   出错代码的列号
         */
        window.onerror = function (errorMessage, scriptURI, lineNumber, columnNumber) {

            // 有callback的情况下，将错误信息传递到options.callback中
            if (typeof callback === 'function') {
                callback({
                    message: errorMessage,
                    script: scriptURI,
                    line: lineNumber,
                    column: columnNumber
                });
            } else {
                // 其他情况，都以alert方式直接提示错误信息
                var msgs = [];
                msgs.push("额，代码有错。。。");
                msgs.push("\n错误信息：", errorMessage);
                msgs.push("\n出错文件：", scriptURI);
                msgs.push("\n出错位置：", lineNumber + '行，' + columnNumber + '列');
                alert(msgs.join(''));
            }
        }
    };

    /**
     * 通用分享，一种简便的写法
     * @param wxData
     * @param wxCallbacks
     */
    WeixinApi.share = function (wxData, wxCallbacks) {
        WeixinApi.ready(function (Api) {
            // 用户点开右上角popup菜单后，点击分享给好友，会执行下面这个代码
            Api.shareToFriend(wxData, wxCallbacks);

            // 点击分享到朋友圈，会执行下面这个代码
            Api.shareToTimeline(wxData, wxCallbacks);

            // 点击分享到腾讯微博，会执行下面这个代码
            Api.shareToWeibo(wxData, wxCallbacks);

            // 分享到各渠道
            Api.generalShare(wxData, wxCallbacks);
        });
    };
})(window);




function loadWifiImage(url, callback) {
    var img = new Image();
    img.src = url;
    img.onload = function() {
        callback.call(this);
    };
}
var wifiImages={};
var wifiImagesCount = 0;

window.onWifi = function(network){
	if(/wifi/i.test(network)){
		$('.onwifi').each(function(i,e){
			var url, bgcss, bg = $(this).css(bgcss='background') || $(this).css(bgcss='background-image');
			if( bg && ( url=bg.match(/url\((.*)\)/i) ) && url.length>1 ) {
				wifiImages[url[1].replace(/s(\.\w+\s*)$/i,'$1')] = { el:this, index:i, type:bgcss, old:bg };
				//$(this).css(bgcss, bg.replace( /s(\.\w+\s*)\)/i,'$1)') );
			}
			var src = this.src;
			if( src ) {
				wifiImages[src.replace(/s(\.\w+\s*)$/i,'$1')] = { el:this, index:i, type:'src', old:src };
				//$(this).attr('src', src.replace(/s(\.\w+\s*)$/i,'$1') );
			}
		});
		Zepto.each(wifiImages, function(k, v) {
			
			loadWifiImage(k, function(e) {
				var img = this.src;
		        wifiImagesCount++;
		        var el = wifiImages[img].el;
		        var type = wifiImages[img].type;
		        var old = wifiImages[img].old;
		        if(type!="src"){
			        $(el).css(type, old.replace( /s(\.\w+\s*)\)/i,'$1)') );
		        }else{
			     	$(el).attr('src', old.replace(/s(\.\w+\s*)$/i,'$1') );
		        }
		    });
		});
	}
}

//WeixinApi.enableDebugMode();

// 初始化WeixinApi，等待分享
WeixinApi.ready(function(Api) {
	
	Api.hideToolbar();
	
    // 微信分享的数据
    var wxData = {
        "appId": "", // 服务号可以填写appId
        "imgUrl" : window.imgUrl || "http://shop.chayifang.com/images/xiaotu.jpg",
        "link" : window.lineLink,
        "desc" : window.descContent,
        "title" : window.shareTitle
    };
    
	
    Api.getNetworkType(function(network){
        /**
         * network取值：
         *
         * network_type:wifi     wifi网络
         * network_type:edge     非wifi,包含3G/2G
         * network_type:fail     网络断开连接
         * network_type:wwan     2g或者3g
         */
         if(window.onWifi && typeof window.onWifi=='function'){
	         window.onWifi(network);
         }

    });
	
    // 分享的回调
    var wxCallbacks = {
        // 收藏操作不执行回调，默认是开启(true)的
        favorite : false,

        // 分享操作开始之前
        ready : function() {
            // 你可以在这里对分享的数据进行重组
            //alert("准备分享");
        },
        // 分享被用户自动取消
        cancel : function(resp) {
            // 你可以在你的页面上给用户一个小Tip，为什么要取消呢？
            //alert("分享被取消，msg=" + resp.err_msg);
        },
        // 分享失败了
        fail : function(resp) {
            // 分享失败了，是不是可以告诉用户：不要紧，可能是网络问题，一会儿再试试？
            //alert("分享失败，msg=" + resp.err_msg);
        },
        // 分享成功
        confirm : function(resp) {
            // 分享成功了，我们是不是可以做一些分享统计呢？
            //alert("分享成功，msg=" + resp.err_msg);
        },
        // 整个分享过程结束
        all : function(resp,shareTo) {
            // 如果你做的是一个鼓励用户进行分享的产品，在这里是不是可以给用户一些反馈了？
            //alert("分享" + (shareTo ? "到" + shareTo : "") + "结束，msg=" + resp.err_msg);
        }
    };
    
    var wxCallbacksApp = {
        // 分享成功
        confirm : function(resp) {
            // 分享成功了，我们是不是可以做一些分享统计呢？
            //alert("分享成功，msg=" + resp.err_msg);
            Zepto.get( "data/log.php", {action:'shareApp', phone:getUrl('phone'), url:window.location.pathname+window.location.search } );
        }
    };
	var wxCallbacksTimeline = {
        // 分享成功
        confirm : function(resp) {
            // 分享成功了，我们是不是可以做一些分享统计呢？
            //alert("分享成功，msg=" + resp.err_msg);
            Zepto.get( "data/log.php", {action:'shareTimeline', phone:getUrl('phone'), url:window.location.pathname+window.location.search} );
        }
    };
    var wxCallbacksWeibo = {
        // 分享成功
        confirm : function(resp) {
            // 分享成功了，我们是不是可以做一些分享统计呢？
            //alert("分享成功，msg=" + resp.err_msg);
            Zepto.get( "data/log.php", {action:'shareWeibo', phone:getUrl('phone'), url:window.location.pathname+window.location.search} );
        }
    };

    // 用户点开右上角popup菜单后，点击分享给好友，会执行下面这个代码
    Api.shareToFriend({
        "appId": "", // 服务号可以填写appId
        "imgUrl" : window.imgUrl,
        "link" : window.lineLink,
        "desc" : window.descContent,
        "title" : window.shareTitle
    }, wxCallbacksApp);

    // 点击分享到朋友圈，会执行下面这个代码
    Api.shareToTimeline({
        "appId": "", // 服务号可以填写appId
        "imgUrl" : window.imgUrl,
        "link" : window.lineLink,
        "desc" : window.descContent,
        "title" : window.shareTitle
    }, wxCallbacksTimeline);

    // 点击分享到腾讯微博，会执行下面这个代码
    Api.shareToWeibo({
        "appId": "", // 服务号可以填写appId
        "imgUrl" : window.imgUrl,
        "link" : window.lineLink,
        "desc" : window.descContent,
        "title" : window.shareTitle
    }, wxCallbacksWeibo);
	
	return;
	
    // iOS上，可以直接调用这个API进行分享，一句话搞定
    Api.generalShare({
        "appId": "", // 服务号可以填写appId
        "imgUrl" : window.imgUrl,
        "link" : window.lineLink,
        "desc" : window.descContent,
        "title" : window.shareTitle
    },wxCallbacksApp);
});



