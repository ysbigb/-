<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx32ec99ad95ef4d67", "336f993d31942ba281f279c78834e24f");
$signPackage = $jssdk->GetSignPackage();

//print_r($signPackage);
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="UTF-8" />
	<title>祥鹏航空摸蛋小快手</title>
	<meta name="viewport" content="width=640, user-scalable=no">
	<meta name="description" content="" />
	<meta name="keywords"  content="" />
	<link type="text/css" rel="stylesheet" href="css/main.css" />
	<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
	
		<style>
            .mask2{height:100%;width:100%;background:#000;opacity: 0.6;position: fixed;z-index:99;}
            .adv{position:fixed; width:100%;top:10%; z-index:99;display:none;text-align: center;}
            .adv-close{width:50px;height:50px;border-radius: 50%;position: absolute;top:15px;left: 13%;overflow: hidden;}

    </style>
	
	
</head>
<body  onload="advload()" >

<div class="banquan" style="">

</div>

<div id="hitmouse">

	
		<div style="display: none;"  class="mask2"></div>

    <div class="adv">
        <div class="adv-close">
            <img src="img/close.png" />
        </div>
        <a href="http://mp.weixin.qq.com/s?__biz=MjM5OTMyNzY2Mw==&mid=400915607&idx=2&sn=a5810079fb7ec2fbde025ab6e512317e&scene=23&srcid=1225oGam63rkE2LMXJ4n9gpn#rd">
        <img style="width:78%;border-radius: 10px;" src="img/adv.jpg" rel="external nofollow" />
        </a>
    </div>
	
	


	<!-- 预备界面 -->
	<div id="gameCover" class="block background">
		<!-- 声音控制按钮 -->
		<a  style="display:none" href="javascript:void(0)" id="btnSound" class="icon">&nbsp;</a>
		<!-- 开始 -->
		<a href="javascript:void(0)" id="btnPlay" class="icon">&nbsp;</a>
		<!-- 帮助 -->
		<a  style="display:none" href="javascript:void(0)" id="btnHelp" class="icon">&nbsp;</a>
		<!-- 关于我 -->
		<a  style="display:none" href="javascript:void(0)" id="btnAboutMe" class="icon">&nbsp;</a>
		<!-- 加载资源 -->
		<span style="display:none" id="progressText"></span>
	</div>
	<!-- 帮助界面 -->
	<div id="HelpDiv" >
		<!-- 帮助图片 -->
		<img src="images/help.png"/>
		<a href="javascript:void(0)" id="btnBack" class="icon">&nbsp;</a>
	</div>
	<!-- 游戏主体 -->
	<div id="gameBody" class="block">
	
	

	
	
		<div id="gameCanvas" class="block">
			<!-- Main背景层 -->
			<canvas width="640" height="1008" id="maincanvas" ></canvas>
		</div>

		<div>
			<!---wuhao 修改对应图片-->
			<img id="quest-img" src="images/pic4.png">
		</div>

		<div id="timetxt" >
			30
		</div>

		<!-- 分数及暂停按钮 -->
		<div id="numberAndPause" class="block">
			<!-- 分数 -->
			<div style="display:none" id="numberBefore" class="icon"></div>
			<div id="number" ></div>
			<!-- 暂停 -->
			<a style="display:none" href="javascript:void(0)" id="btnPause" class="icon">&nbsp;</a>
		</div>
		<!-- 下一关提示 -->
		<div id="nextLoding">
			<div style="" id="mask" class="mask"></div>

			<div id="gamepass-img">
				<img src="images/next.png">
				<a id="btnpass" href="zhuanpan.php" title=""></a>
			</div>

			<!-- 分数 -->
			<span style="display:none" id="currentScore"></span>
			<span style="display:none" id="requireScore" ></span>
		</div>
	</div>
	<!-- 游戏结束 -->
	<div id="gameOver" class="block">
		<div style="" id="mask" class="mask"></div>
		<div id="gamover-img">
			<img src="images/bg_gameover.png">
			<!-- 准备 -->
			<a href="javascript:void(0)" id="btnRetry" class="icon">&nbsp;</a>
		</div>
		<!-- 得分 -->
		<span id="score" style="display:none"></span>

		<!-- 返回主菜单 -->
		<a  style="display:none" href="javascript:void(0)" id="btnBackToMenu" class="icon">&nbsp;</a>
	</div>
</div>
	<!--我使用这个框架的代码并不多，主要是工具类使用-->

	<script type="text/javascript" src="myEngine/core/my.js"></script>
	<script type="text/javascript" src="myEngine/component/Component.js"></script>
	<script type="text/javascript" src="myEngine/component/DisplayObject.js"></script>
	<script type="text/javascript" src="myEngine/component/Bitmap.js"></script>
	<script type="text/javascript" src="myEngine/utils/ImageManager.js"></script>
	<script type="text/javascript" src="myEngine/utils/DOM.js"></script>
	<script type="text/javascript" src="myEngine/utils/Math.js"></script>
	<script type="text/javascript" src="myEngine/utils/buzz.js"></script>

	<script type="text/javascript" src="js/resources/images.js"></script>
	<script type="text/javascript" src="js/resources/audios.js"></script>
	<script type="text/javascript" src="js/frames/mouse.js"></script>
	<script type="text/javascript" src="js/frames/star.js"></script>
	<script type="text/javascript" src="js/frames/score.js"></script>
	<script type="text/javascript" src="js/classes/Audio.js"></script>
	<script type="text/javascript" src="js/classes/Animation.js"></script>
	<script type="text/javascript" src="js/classes/star.js"></script>
	<script type="text/javascript" src="js/classes/score.js"></script>
	<script type="text/javascript" src="js/classes/hammer.js"></script>
	<script type="text/javascript" src="js/classes/mouse.js"></script>
	<script type="text/javascript" src="js/classes/MouseHit.js"></script>
	<script type="text/javascript" src="js/classes/UI.js?a=1"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script>
    function advload(){
        $(".adv").show();
        $(".mask2").show();

        $(".adv-close").click(function(){
            $(".adv").hide();
            $(".mask2").hide();
        });

        $('.mask2').click(function(){
            $(".adv").hide();
            $(this).hide();
        });
    }
</script>
	<script type="text/javascript">
            document.body.addEventListener('touchmove', function (event) {
                event.preventDefault(); }, false);                
        </script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>

    var title = "祥鹏航空摸蛋小快手玩游戏抽大奖";
    var des = '玩“摸蛋小快手”游戏，赢取祥鹏保温杯、马克杯、机模等大奖...';
    var logo = 'http://www.ynzhiwu.com/xpgame/logo.png';
    var link = 'http://www.ynzhiwu.com/xpgame/index.php';
    wx.config({
        //debug: true,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'onMenuShareTimeline',
            'onMenuShareAppMessage'
        ]
    });

    wx.ready(function(){

        wx.onMenuShareTimeline({
            title: title, // 分享标题
            link: link, // 分享链接
            imgUrl: 'http://www.ynzhiwu.com/xpgame/logo.jpg', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

        wx.onMenuShareAppMessage({
            title: title, // 分享标题
            desc: des, // 分享描述
            link: link, // 分享链接
            imgUrl: 'http://www.ynzhiwu.com/xpgame/logo.jpg', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });



</script>
		
</body>
</html>