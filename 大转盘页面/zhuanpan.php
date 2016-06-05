<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx32ec99ad95ef4d67", "336f993d31942ba281f279c78834e24f");
$signPackage = $jssdk->GetSignPackage();
$root = "http://".$_SERVER['HTTP_HOST']."/xpzpgames/";

header("Content-type:text/html;charset=utf-8");

session_start();
$openid = isset($_SESSION['openid']) ? $_SESSION['openid'] : false;
//$openid = "or9UCj2cj9M19NY0NYLSM0oV30wA";

$chance = 0;
$winning = 0;
$roll_total = 0;

$p1Count = 0;
$p2Count = 0;
$p3Count = 0;
$p4Count = 0;
$p5Count = 0;
$p6Count = 0;
$p7Count = 0;
$p8Count = 0;
$p9Count = 0;


date_default_timezone_set('PRC');
$now = date("Y-m-d");

if($openid)
{
    include 'mysql.db.php';
    $sql = 'SELECT * FROM xp_xpgame_wx_info WHERE wx_openid = "'.$openid.'"';
    $result = $mysqli->query($sql);
    if($result && $result->num_rows>0) {
        $row = $result->fetch_assoc();
        if ($row)
        {
            $chance = $row['roll_total'] - $row['roll_count'];
			$roll_total = $row['roll_total'];
        }
    }

	//判断是否中过奖
	$sql1 = 'SELECT * FROM xp_xpgame_winner_info WHERE wx_openid = "'.$openid.'"';
    $result1 = $mysqli->query($sql1);
    if($result1 && $result1->num_rows>0) {
        $row1 = $result1->fetch_assoc();
        if($row1){//中奖过
            $winning = 1;
        }
    }


    //奖品统计
    $sqlTotal = 'SELECT * FROM xp_xpgame_prize_total WHERE id = 1';
    $result2 =  $mysqli->query($sqlTotal);
    if($result2 && $result2->num_rows>0) {
        $row2 = $result2->fetch_assoc();
        if ($row2)
        {
            $p1Count = $row2['p1'];
            $p2Count = $row2['p2'];
            $p3Count = $row2['p3'];
            $p4Count = $row2['p4'];
            $p5Count = $row2['p5'];
			$p6Count = $row2['p6'];
            $p7Count = $row2['p7'];
            $p8Count = $row2['p8'];
            $p9Count = $row2['p9'];
        }
    }

    $result->free();
    $mysqli->close();
}
else
{
	header('Location:'.$root.'index.php');
	exit;
}

?> 

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>招聘抽奖</title>
    <meta name="viewport" content="width=640, user-scalable=no">
    <style>
        *{margin:0;padding:0;list-style:none;}

        .rotary { position: relative; width: 626px; height: 643px; margin:0 auto;top:36px; background:  url(img/pan.png) no-repeat;}
        .rotaryArrow {    position: absolute;
    left: 246px;
    top: 217px;
    width: 136px;
    height: 190px;
    cursor: pointer;
    background: url(img/arrow2.png) no-repeat;}



        .result { display: none; position: absolute; left: 0; top: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.7) 0 0 no-repeat;}
        .result .r-content{
            background:  url(img/zhong.png) 0 0 no-repeat;
            width: 382px;
            height: 520px;
            margin: 35% auto;
            position:relative;
        }
        .result a {
            display:inline-block;
            position: absolute;
            height:87px;
        }
        .result .a-replay{
            width: 380px;
    left: 2px;
    top: 433px;
    opacity: 0;
        }
        .result .a-ling{
            width: 185px;
            left: 2px;
            top: 433px;
            opacity: 0;
        }

        .a-sure{
            width: 380px;
    left: 2px;
    top: 433px;
    opacity: 0;
        }

        .result .give-up{
            width: 185px;
            left: 198px;
            top: 433px;
            opacity: 0;
        }

        .result p { padding: 275px 15px 0; font: 20px "Microsoft Yahei"; color: #ffea00; text-align: center;}
        .result em { color: #ffea76; font-style: normal;}
        .rimgbox{
            position:absolute;
            left:103px;
            top:27px;
            width:178px;
            height:178px;
            text-align: center;

        }
        #resultImg{

        }

        .pan-instru{
            width: 592px;
            height: 313px;
            background: url(img/pan-instru.png) 0 0 no-repeat;
            margin: 20px auto;
        }
    </style>
</head>
<body>
<div id="web_bg" style="position:absolute; width:100%; height:100%; z-index:-1">
    <img style="position:fixed;" src="img/bg3.png" rel="external nofollow"  height="100%" width="100%" />
</div>
<!-- pan start  -->
<div class="rotary">
    <div class="rotaryArrow" id="rotaryArrow"></div>
</div>
<!-- pan end -->
<div class="result" id="result">
    <div class="r-content">
        <p id="resultTxt"></p>
        <!-- <a href="javascript:" id="resultBtn" title="关闭">关闭</a> -->
        <div class="rimgbox">
            <img src="img/fjp.png" alt="" id="resultImg">
        </div>
        <a href="http://www.ynzhiwu.com/xpzp" title="" class="a-replay">去评价（回首页）</a>
        <a href="submit.html" title="" class="a-ling">去领奖</a>
        <a style="display: none;" href="#" title="" class="give-up">放弃</a>
        <a href="http://www.ynzhiwu.com/xpzp" title="" class="a-sure">确 定</a>
    </div>
</div>
<div class="pan-instru">
</div>

<script src="js/jquery-1.8.3.min.js"></script>
<script src="js/jquery.rotate.min.js"></script>
<script>
    $(function(){

        var p1Count = parseInt(<?php echo $p1Count?>);
        var p2Count = parseInt(<?php echo $p2Count?>);
        var p3Count = parseInt(<?php echo $p3Count?>);
        var p4Count = parseInt(<?php echo $p4Count?>);
        var p5Count = parseInt(<?php echo $p5Count?>);
        var p6Count = parseInt(<?php echo $p6Count?>);
        var p7Count = parseInt(<?php echo $p7Count?>);
        var p8Count = parseInt(<?php echo $p8Count?>);
        var p9Count = parseInt(<?php echo $p9Count?>);



        var $rotaryArrow = $('#rotaryArrow');
        var $result = $('#result');
        var $resultTxt = $('#resultTxt');
        var $resultImg = $('#resultImg');
        var chance = parseInt(<?php echo $chance?>);
        var roll_total = parseInt(<?php echo $roll_total?>);
        var ajaxSuccess = true;
        var winning = parseInt(<?php echo $winning?>);//ys

         //var chance = 3;
//        var roll_total = 0;
//        var ajaxSuccess = false;
//        var winning = 0;

        $rotaryArrow.click(function(){
         
             if(winning){
                $('.r-content').css('background',"url(img/winning.png)");
                $resultImg.hide();
                $resultTxt.html('');
                $result.show();
                $('.a-ling').hide();
                $('.a-replay').hide();
                $('.give-up').hide();
                $('.a-sure').show();

                return false;
            }
             //alert(chance);
            if(chance == 0)
            {
				//alert(roll_total+":"+chance);
                /*
				if(roll_total <3){
                    $('.r-content').css('background',"url(img/nochance1.png)");
                }else{
                    $('.r-content').css('background',"url(img/nochance.png)");
                }
				*/
				$('.r-content').css('background',"url(img/nochance1.png)");
                $resultImg.hide();
                $resultTxt.html('');
                $result.show();
                $('.a-ling').hide();
                $('.a-replay').hide();
                $('.give-up').hide();
                $('.a-sure').show();
            }
            else
            {
                chance--;

                var Base = 12000;
                var p1Base =1;
                var p2Base =5;
                var p3Base =12;
                var p4Base =12;
                var p5Base =50;
                var p6Base =50;
                var p7Base =50;
                var p8Base =50;
                var p9Base =200;


                var p = 'p7';

                var random = getRandom(Base);

                if (random == p1Base && p1Count > 0) 
                {
                    //iWatch
                    p = 'p1';
                }
                else if(random <= p2Base && p2Count > 0)
                {
                    //飞机模型
                    p = 'p2';
                }
               else if(random <= p3Base && p3Count > 0)
                {
                    //300元京东卡
                    p = 'p3';
                } else if(random <= p4Base && p4Count > 0)
                {
                    //100元京东卡
                    p = 'p4';
                }
                else if(random <= p5Base && p5Count > 0)
                {
                    //保温杯
                    p = 'p5';
                }
                else if(random <= p6Base && p6Count > 0)
                {
                    //效率手册
                    p = 'p6';
                }
                else if(random <= p7Base && p7Count > 0)
                {
                    //马克杯
                    p = 'p7';
                }
                 else if(random <= p8Base && p8Count > 0)
                {
                    //充电宝
                    p = 'p8';
                }
                 else if(random <= p9Base && p9Count > 0)
                {
                    //钥匙扣
                    p = 'p9';
                }
                
                //p="p2";

                var url = "<?php echo $root;?>"+'ajax.php';
                jQuery.ajax( {
                    url : url,
                    data: {request:'lucky', p:p},
                    type : "POST",
                    // 期待的返回值类型
                    dataType: "json",
                    complete : function(xhr, result) {
                        if(!xhr){
                            alert('网络连接失败！');
                            return false;
                        }

                        var text = xhr.responseText;
                        if(!text){
                            alert('网络错误！');
                            return false;
                        }

                        var json = eval("("+text+")");

                        if(json.result == 'failed'){
                            return false;
                        }
                        else if(json.result == 'success')
                        {
                            ajaxSuccess = true;
                        }
                    }
                });

                switch(p){
                    case 'p1':
                        rotateFunc(1,72,'抽中苹果iWatch一个','iwatch');
                        break;
                    case 'p2':
                        rotateFunc(2,144,'抽中祥鹏飞机模型一个','fjmx');
                        break;
                    case 'p3':
                        rotateFunc(3,324,'300元购物卡','300gwk');
                        break;
                    case 'p4':
                        rotateFunc(4,288,'百元购物卡','100gwk');
                        break;
                    case 'p5':
                        rotateFunc(5,216,'保温杯一个','bwb');
                        break;
                    case 'p6':
                        rotateFunc(6,0,'抽中效率手册一本','sc');
                        break;
                    case 'p7':
                        rotateFunc(7,180,'抽中马克杯一个','mkb');
                        break;
                    case 'p8':
                        rotateFunc(8,36,'移动电源','cdb');
                        break;                                      
                    case 'p9':
                        rotateFunc(9,108,'抽中祥鹏钥匙扣一个','ysk');
                        break;
                    
                    default:
                        rotateFunc(10,252,'很遗憾，这次您未抽中奖，继续加油吧');
                }
                
            }
        });

        var rotateFunc = function(awards,angle,text,imgsrc){  //awards:奖项，angle:奖项对应的角度
            $rotaryArrow.stopRotate();
            $rotaryArrow.rotate({
                angle: 0,
                duration: 5000,
                animateTo: angle + 1440,  //angle是图片上各奖项对应的角度，1440是让指针固定旋转4圈
                callback: function(){
                    if(angle==252){
                        $('.r-content').css('background',"url(img/thank.png)");
                        $resultImg.hide();
                        $resultImg.hide();
                        $resultTxt.html('');
                        $result.show();
                        $('.a-ling').hide();
                        $('.a-replay').show();
                        $('.give-up').hide();
                        $('.a-sure').hide();
                    }
                    else{
                        if(ajaxSuccess)
                        {
                            $('.r-content').css('background',"url(img/zhong.png)");
                            $resultImg.attr('src', 'img/'+imgsrc+'.png');
                            $resultImg.show();
                            $resultTxt.html(text);
                            $result.show();
                            $('.a-ling').show();
                            $('.a-replay').hide();
                            $('.give-up').show();
                            $('.a-sure').hide();
                        }
                    }
                }
            });
        };

        $('.give-up').click(function(){
            if(confirm('放弃可以重新抽奖一次，确认放弃吗？') == true){
                var url = "<?php echo $root;?>"+'ajax.php';
                jQuery.ajax( {
                    url : url,
                    data: {request:'give-up'},
                    type : "POST",
                    // 期待的返回值类型
                    dataType: "json",
                    complete : function(xhr, result) {
                        if(!xhr){
                            alert('网络连接失败！');
                            return false;
                        }

                        var text = xhr.responseText;
                        if(!text){
                            alert('网络错误！');
                            return false;
                        }

                        var json = eval("("+text+")");
                        if(json.result == 'success')
                        {
                            ajaxSuccess = true;
                            $result.hide();
                        }
                    }
                });
            }else{
                ajaxSuccess = false;
            }
        });

        function getRandom(n){
            return Math.floor(Math.random()*n+1)
        }
    });
</script>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>

    var title = "祥鹏航空明星产品体验“总监”招聘——抽奖";
    var des = '参与“祥鹏航空明星产品体验总监”活动，成功点评好友即可参与抽奖，iwatch、飞机模型、百元购物卡、高级保温杯等430个大奖等你领取!';
    var logo = 'http://www.ynzhiwu.com/xpzpgames/logo.png';
    var link = 'http://www.ynzhiwu.com/xpzpgames/index.php';
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
            imgUrl: 'http://www.ynzhiwu.com/xpzpgames/logo.jpg', // 分享图标
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
            imgUrl: 'http://www.ynzhiwu.com/xpzpgames/logo.jpg', // 分享图标
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
