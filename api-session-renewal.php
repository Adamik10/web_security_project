<?php

// if the user isn't logged in - this redirects to index
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}

//if user is logged in, generate a new session ID for them
$_SESSION['sessionId'] = uniqid();
// echo $_SESSION['sessionId'];