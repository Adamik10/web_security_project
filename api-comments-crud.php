<?php

// echo $_POST['txtPostIdCrud'];
if(isset($_POST['txtBannedCrudComments']) && isset($_POST['txtCommentsIdCrud'])){
    require('controllers/database.php');
    // SAVE DATA FROM FORM
    $commentsId = htmlentities($_POST['txtCommentsIdCrud']);
    $newBanned = htmlentities($_POST['txtBannedCrudComments']);
    
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