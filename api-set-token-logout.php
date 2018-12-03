<?php

// if the user just typed this link - this redirects to index
if(!isset($pageTitle)){
    header('location: login.php?status=not_logged_in');
    exit;
}

session_start();

//if user is logged in, this will generate a logout session token for them
$newLogoutToken = uniqid();
$newLogoutTokenHashed = hash('sha256', $newLogoutToken);
$_SESSION['logout_token'] = $newLogoutToken;

//this file will be used as an require_once() in other files so now we need to echo an input in the form that uses the same values
echo '<input name="activityToken" type="text" value="'.$newLogoutTokenHashed.'" hidden>';
