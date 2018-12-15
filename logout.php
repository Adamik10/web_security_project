<?php $pageTitle = 'logout'?>

<?php 
session_start();

// session_destroy();
// header('location: index.php');

// a token was created if the real person wanted to do this - does it match what we got?
if(!isset($_SESSION['logout_token']) || !isset($_POST['activityToken'])){
    //echo 'The token is not set';
    header('location: ups.php');
    exit;
}else{
    // if there is a token, compare it to the one we got from the form
    if ($_SESSION['logout_token'] != $_POST['activityToken']){
        // redirect to UPS THIS WASN'T SUPPOSED TO HAPPEN page 
        // echo 'The tokens dont match';
        header('location: ups.php');
        exit;
    }else{
        // if it matches - logout
        // echo 'Everything matches - logout';
        session_destroy();
        header('location: index.php');
        exit;
    }
}
?>