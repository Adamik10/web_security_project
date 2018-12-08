<?php

// echo $_POST['txtPostIdCrud'];
if(isset($_POST['txtBannedCrudComments'])){
    require('controllers/database.php');
    // SAVE DATA FROM FORM
    $postId = htmlentities($_POST['txtPostIdCrud']);
    $newBanned = htmlentities($_POST['txtBannedCrudComments']);
    
    // UPDATE THE DB WITH FORM DATA
    try{
        $sUpdate = $db->prepare( 'UPDATE posts SET headline = :newHeadline, banned = :newBanned  WHERE id_posts = :postId' );
        $sUpdate->bindValue( ':newHeadline' , $newHeadline );
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