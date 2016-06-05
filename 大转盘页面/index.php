<?php
$appid = "wx32ec99ad95ef4d67";
$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=http://www.ynzhiwu.com/xpzpgames/callback.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
header("Location:".$url);