<?php

// echo $_POST['txtPostIdCrud'];
if(isset($_POST['txtBannedCrud']) && isset($_POST['txtPostIdCrud'])){

    // TOKENS
    session_start();
    // a token was created if the real person wanted to do this - does it match what we got?
    if(!isset($_SESSION['token']) || !isset($_POST['activityToken'])){
        //echo 'The token is not set';
        header('location: ups.php');
        exit;
    }else{
        // if there is a token, compare it to the one we got from the form
        if (hash('sha256', $_SESSION['token']) != $_POST['activityToken']){
            // redirect to UPS THIS WASN'T SUPPOSED TO HAPPEN page 
            header('location: ups.php');
            exit;
        }
    }

    require('controllers/database.php');
    // SAVE DATA FROM FORM
    $postId = $_POST['txtPostIdCrud'];
    // $newHeadline = $_POST['txtHeadlineCrud'];
    $newBanned = $_POST['txtBannedCrud'];
    
    // UPDATE THE DB WITH FORM DATA
    try{
        $sUpdate = $db->prepare( 'UPDATE posts SET banned = :newBanned  WHERE id_posts = :postId' );
        $sUpdate->bindValue( ':newBanned' , $newBanned );
        $sUpdate->bindValue( ':postId' , $postId );
        $sUpdate->execute();
    }catch( PDOException $ex ){
    echo $ex;
    exit;
    }

    echo 'update done';
    exit;
}else{
    echo 'there was no data';
    exit; 
}



?>