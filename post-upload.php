<?php
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}
// if the user isn't logged in - this redirects to index

if( isset($_FILES['postFile']) && $_FILES['postFile']['size'] != 0 && !empty($_POST['postHeader'])){

    require('controllers/database.php');

    $newPostId = uniqid();
    $newPostUserId = $_SESSION['userId'];
    $newPostHeadline = $_POST['postHeader'];
    $newPostImageLocation;
    $newPostImageName;
    if(isset($_POST['postSensitive'])){
        $newPostSensitivity = '1';
    }else{
        $newPostSensitivity = '0';
    }
    
    // echo $_FILES['postFile']['size'];
    if( $_FILES['postFile']['size'] < 4000000 ){ // unit is bytes
        // use the image
        $aImage = $_FILES['postFile'];
        // print_r( $aImage );
        // Array ( [name] => logo.svg [type] => image/svg+xml [tmp_name] => C:\xampp\tmp\php3128.tmp [error] => 0 [size] => 2668 )
        $sOldPath = $aImage['tmp_name'];
        // Create an id that will be unique for the file that we will save
        $sUniqueImageName = uniqid();
        $newPostImageName = $sUniqueImageName;
        // Extract the extension of the image
        $sImageName = $aImage['name'];
        $aImageName = explode( '.' , $sImageName ); // logo.svg ['logo','svg']
        // loop through all of the items in the exploded array except the first one - if any contain 'exe', redirect back to index and log this attempt
        for($i = 1; $i < sizeof($aImageName)-1; $i++){
            if($aImageName[$i] == 'exe'){
                // FINISH ADAM - write logs here on which user did it
                //...
                header('location: index.php');
                exit;
            }
        }
        // get extension knowing that the last element is the extension
        $sExtension = $aImageName[count($aImageName)-1];
        // Create a variable with the new path
        $sPathToSaveFile = "$sUniqueImageName.$sExtension";
        $newPostImageLocation = $sPathToSaveFile;
        // save the image to a folder
        if( move_uploaded_file( $sOldPath , '/var/www/images/posts/'.$sPathToSaveFile ) ){
            echo "SUCCESS UPLOADING FILE"; 
            echo '<br> This is the old path: '.$sOldPath.'<br>And this is the new path: '.$sPathToSaveFile;
        }else{
            echo "ERROR UPLOADING FILE";
            echo '<br> This is the old path: '.$sOldPath.'<br>And this is the new path: '.$sPathToSaveFile;
        } 
    }else{
    echo "FILE TOO LARGE"; // FINISH ADAM - just redirect back to index and show reason
    }

    // FINISH ADAM - maybe it would be nice to have a transaction here since the image is already in the folder
    try{
        $stmt = $db->prepare('INSERT INTO posts (id_posts, id_users, headline, image_location, image_name, sensitive_content) 
                                VALUES ( :newPostId , :newPostUserId , :newPostHeadline , :newPostImageLocation , :newPostImageName , :newPostSensitivity )');
        $stmt->bindValue(':newPostId', $newPostId);
        $stmt->bindValue(':newPostUserId', $newPostUserId);
        $stmt->bindValue(':newPostHeadline', $newPostHeadline);
        $stmt->bindValue(':newPostImageLocation', $newPostImageLocation);
        $stmt->bindValue(':newPostImageName', $newPostImageName);
        $stmt->bindValue(':newPostSensitivity', $newPostSensitivity);
        $stmt->execute();
    } catch (PDOException $ex){
        echo $ex;
        exit();
    }
    // header('location: index.php');
}else{
    header('location: index.php?status=post_invalid'); //ADAM TOTO this
}
?>