<?php

try{
    $sUserName = 'root';
    $sPassword = '3people1appendix';
    $sConnection = "mysql:host=localhost; dbname=web_sec; charset=utf8";

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