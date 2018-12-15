<?php

// if the user just typed this link - this redirects to index
if(!isset($pageTitle)){
    header('location: login.php?status=not_logged_in');
    exit;
}

session_start();

//if user is logged in, or on login/register page this will generate a session token for them
$newTokenPost = uniqid();
$newTokenPostHashed = hash('sha256', $newTokenPost);
$_SESSION['tokenPost'] = $newTokenPostHashed;

//this file will be used as an require_once() in other files so now we need to echo an input in the form that uses the same values
echo '<input name="activityToken" type="text" value="'.$newTokenPostHashed.'" hidden>';