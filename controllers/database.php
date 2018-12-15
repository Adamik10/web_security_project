<?php

try{
    $sUserName = 'root';
    $sPassword = '';
    $sConnection = "mysql:host=104.248.30.208; dbname=web_sec; charset=utf8";

    $aOptions = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    $db = new PDO( $sConnection, $sUserName, $sPassword, $aOptions );
    // echo 'connected';
}catch( PDOException $e){
    echo 'error';
    exit();
}