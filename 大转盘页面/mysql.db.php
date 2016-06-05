<?php
$mysqli = new mysqli('127.0.0.1','root','spancul@2015','xpzp');
if($mysqli->connect_errno){
    die('CONNECT ERROR:'.$mysqli->connect_error);
}
$mysqli->set_charset('utf8');



