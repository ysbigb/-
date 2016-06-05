<?php
$request = $_POST['request'];

$root = "http://".$_SERVER['HTTP_HOST']."/xpzpgames/";

//$openid = "or9UCj2cj9M19NY0NYLSM0oV30wA";

if($request == 'clear')
{
    session_start();
    $openid = isset($_SESSION['openid']) ? $_SESSION['openid'] : false;

    $resultCode = array('result'=>'failed');
    $chance = 0;

    if($openid)
    {
        include 'mysql.db.php';
        $sql = 'SELECT * FROM xp_xpgame_wx_info WHERE wx_openid = "'.$openid.'"';
        $result = $mysqli->query($sql);
        if($result && $result->num_rows>0)
        {
            $row = $result->fetch_assoc();
            if($row)
            {
                date_default_timezone_set('PRC');
                $now = date("Y-m-d");
                if($row['op_date'])
                {
                    if($now == $row['op_date'])
                    {
                        //当天通关
                        $rol_total = $row['roll_total'] == 100 ? $row['roll_total'] : $row['roll_total'] + 1;
                        $updateSql = "UPDATE xpgame_wx_info SET roll_total = ".$rol_total." WHERE wx_openid = '".$openid."'";
                        $mysqli->query($updateSql);
                    }
                    else if(strtotime($now) > strtotime($row['op_date']))
                    {
                        //隔天通关
                        $updateSql = "UPDATE xp_xpgame_wx_info SET roll_count = 0, roll_total = 1 , op_date = '".$now."' WHERE wx_openid = '".$openid."'";
                        $mysqli->query($updateSql);
                    }
                }
                else
                {
                    //第一次通关
                    $updateSql = "UPDATE xp_xpgame_wx_info SET roll_total = 1 , op_date = '".$now."' WHERE wx_openid = '".$openid."'";
                    $mysqli->query($updateSql);
                }
            }
        }
        $result->free();
        $mysqli->close();
        $resultCode['result'] = 'success';
    }

    echo json_encode($resultCode);
}
else if($request == 'lucky')
{
    session_start();
    $openid = isset($_SESSION['openid']) ? $_SESSION['openid'] : false;

    $resultCode = array('result'=>'failed');

    if($openid)
    {
        $prize = $_POST['p'];

        date_default_timezone_set('PRC');
        $now = date("Y-m-d");

        include 'mysql.db.php';
        $sql = 'SELECT * FROM xp_xpgame_wx_info WHERE wx_openid = "'.$openid.'"';
        $result = $mysqli->query($sql);
        if($result && $result->num_rows>0)
        {
            $row = $result->fetch_assoc();

            if($row)
            {
                if($prize)
                {
                    $insertSql = "INSERT INTO xp_xpgame_winner_info (wx_openid, prize, op_date) VALUES ('".$openid."','".$prize."','".$now."')";
                    $res = $mysqli->query($insertSql);
                }

                $roll_count = $row['roll_count'] == 3 ? $row['roll_count'] : $row['roll_count'] + 1;
                $updateSql = "UPDATE xp_xpgame_wx_info SET roll_count = ".$roll_count." WHERE wx_openid = '".$openid."'";
                $mysqli->query($updateSql);
                $resultCode = array('result'=>'success');
            }
        }

        $result->free();
        $mysqli->close();
    }

    echo json_encode($resultCode);
}
else if($request == 'info-submit')
{
    $name = $_POST['name'];
    $mobile = $_POST['phone'];
    $address = $_POST['address'];

    session_start();
    $openid = isset($_SESSION['openid']) ? $_SESSION['openid'] : false;

    if($openid)
    {
        include 'mysql.db.php';

        $updateSql = "UPDATE xp_xpgame_winner_info SET user_name = '".$name."',mobile = '".$mobile."', address = '".$address."' WHERE wx_openid = '".$openid."' AND user_name IS NULL";
        $res = $mysqli->query($updateSql);

        if($res){
            $sqlSel = "SELECT * FROM xp_xpgame_winner_info WHERE wx_openid = '$openid'";
            $result1 = $mysqli->query($sqlSel);
            if($result1){
                $row1 = $result1->fetch_assoc();
                if($row1){
                    $prize = $row1['prize'];
                    if($prize){
                        $sqlTotal = 'SELECT * FROM xp_xpgame_prize_total WHERE id = 1';
                        $result2 = $mysqli->query($sqlTotal);

                        if($result2)
                        {
                            $row2 = $result2->fetch_assoc();
                            if($row2 && $row2[$prize] > 0)
                            {
                                $row2[$prize]--;
                                $updateSqlT = "UPDATE xp_xpgame_prize_total SET ".$prize." = ".$row2[$prize]." WHERE id = 1";
                                $mysqli->query($updateSqlT);

                                $resultCode['result'] = 'success';
                            }

                        }
                    }
                }
            }
        }

        $result1->free();
        $mysqli->close();

    }
	//zhong jiang  tips
    header("Location:http://www.ynzhiwu.com/xpzpgames/zhuanpan.php");

}else if($request == 'give-up'){

    session_start();
    $openid = isset($_SESSION['openid']) ? $_SESSION['openid'] : false;

    $resultCode = array('result'=>'failed');

    if($openid)
    {
        include "mysql.db.php";
        $sql = 'SELECT * FROM xp_xpgame_wx_info WHERE wx_openid = "'.$openid.'"';
        $result = $mysqli->query($sql);
        if($result)
        {
            $row = $result->fetch_assoc();
            if($row)
            {
                $roll_count = $row['roll_count'] - 1;
                $updateSql = "UPDATE xp_xpgame_wx_info SET roll_count = ".$roll_count." WHERE wx_openid = '".$openid."'";
                $deleteSql = "DELETE FROM xp_xpgame_winner_info WHERE wx_openid = '".$openid."'";

                $mysqli->query($updateSql);
                $mysqli->query($deleteSql);
                $resultCode = array('result'=>'success');
            }
        }
        $result->free();
        $mysqli->close();
    }
    echo json_encode($resultCode);
}

function my_curl_post($data, $url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function random($length, $numeric = 0) {
    $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
    if($numeric) {
        $hash = '';
    } else {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    $max = strlen($seed) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}