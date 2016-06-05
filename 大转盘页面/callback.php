<?php
@header('Content-type: text/html;charset=UTF-8');
$root = "http://".$_SERVER['HTTP_HOST']."/xpzpgames/";
$appid = "wx32ec99ad95ef4d67";
$secret = "336f993d31942ba281f279c78834e24f";
$code = $_GET["code"];
$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
$res = httpGet($get_token_url);
$json_obj = json_decode($res,true);

//根据openid和access_token查询用户信息
$access_token = $json_obj['access_token'];
$openid = $json_obj['openid'];
$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
$res = httpGet($get_user_info_url);



if($res)
{
	$user_obj = json_decode($res,true);
	
	if(!isset($user_obj['openid']) || empty($user_obj['openid']))
	{
		echo '请关祥鹏航空公众号参加活动';
	}
	else
	{
		session_start();
		$_SESSION['openid'] = $user_obj['openid'];

		include 'mysql.db.php';

		$sql = 'SELECT * FROM xp_xpgame_wx_info WHERE wx_openid = "'.$user_obj['openid'].'"';
		$result = $mysqli->query($sql);

		if($result && $result->num_rows>0) {
			$row = $result->fetch_assoc();
            if($row)
            {
                $updateSql = "UPDATE xp_xpgame_wx_info SET wx_nickname = '".$user_obj['nickname']."',wx_avatar = '".$user_obj['headimgurl']."' WHERE wx_openid = '".$user_obj['openid']."'";
                $res1 = $mysqli->query($updateSql);

				$url = $root.'zhuanpan.php';
                header("Location:".$url);
            }
            else
            {
                //插入数据
                $insertSql = "INSERT INTO xp_xpgame_wx_info (wx_nickname, roll_count, wx_openid,wx_avatar) VALUES ('" . $user_obj['nickname'] . "', 0, '" . $user_obj['openid'] . "','" . $user_obj['headimgurl'] . "')";
				$res2 = $mysqli->query($insertSql);

				$url = $root.'zhuanpan.php';
				header("Location:" . $url);
            }
		}
		else
		{
			//插入数据
			$insertSql = "INSERT INTO xp_xpgame_wx_info (wx_nickname, roll_count, wx_openid,wx_avatar) VALUES ('" . $user_obj['nickname'] . "', 0, '" . $user_obj['openid'] . "','" . $user_obj['headimgurl'] . "')";
			$res3 = $mysqli->query($insertSql);

			$url = $root.'zhuanpan.php';
			header("Location:" . $url);
		}

		$result->free();
		$mysqli->close();
	}
}
else
{
	echo '请关祥鹏航空公众号参加活动';
}

function httpGet($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	curl_setopt($curl, CURLOPT_URL, $url);

	$res = curl_exec($curl);
	curl_close($curl);

	return $res;
}