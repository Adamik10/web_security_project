<?php

// if the user isn't logged in - this redirects to index
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}

//if user is logged in, this will generate a session token for them
//the session token should always be empty when user is trying to get one
if(!isset($_SESSION['token'])){
    $_SESSION['token'] = uniqid();
}else{
    session_destroy();
    header('location: login.php?status=better_luck_next_time');
    exit;
}