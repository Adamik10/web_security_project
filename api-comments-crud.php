<?php

// echo $_POST['txtPostIdCrud'];
if(isset($_POST['txtBannedCrudComments']) && isset($_POST['txtCommentsIdCrud'])){
    
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
    $commentsId = $_POST['txtCommentsIdCrud'];
    $newBanned = $_POST['txtBannedCrudComments'];
    
    // UPDATE THE DB WITH FORM DATA
    try{
        $sUpdate = $db->prepare( 'UPDATE comments SET banned = :newBanned  WHERE id_comments = :commentsId' );
        $sUpdate->bindValue( ':newBanned' , $newBanned );
        $sUpdate->bindValue( ':commentsId' , $commentsId );
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