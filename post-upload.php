<?php
// if the user isn't logged in - this redirects to index
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}

// now we know that the person trying to post is logged in 
// but we still don't know whether it's the real person or just someone using their session ID
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

if( isset($_FILES['postFile']) && $_FILES['postFile']['size'] != 0 && !empty($_POST['postHeader'])){

    require('controllers/database.php');

    $newPostId = uniqid();
    $newPostUserId = $_SESSION['userId'];
    $newPostHeadline = htmlentities($_POST['postHeader']);
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
        for($i = 1; $i < sizeof($aImageName); $i++){
            if($aImageName[$i] == 'exe'){
                // write logs here on which user did it
                $currentIp = 'template';
                $attack_description = 'Upload of an exe file';
                date_default_timezone_set("UTC");
                $time_of_attack = date('Y-m-d H:i:s');
                try{
                    $db->beginTransaction();

                    $stmt = $db->prepare('INSERT INTO security_logs VALUES ( :id_security_logs , :description_of_attack , :ip_address , :user_og_id, :time_of_attack)');
                    $id_security_logs = uniqid();
                    $stmt->bindValue(':id_security_logs', $id_security_logs);
                    $stmt->bindValue(':description_of_attack', $attack_description);
                    //get IP address https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php
                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        $currentIp = $_SERVER['HTTP_CLIENT_IP'];
                    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                        $currentIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    } else {
                        $currentIp = $_SERVER['REMOTE_ADDR'];
                    }
                    $stmt->bindValue(':ip_address', $currentIp);
                    $stmt->bindValue(':user_og_id', $_SESSION['userId']);
                    $stmt->bindValue(':time_of_attack', $time_of_attack);
                    if($stmt->execute()){ //true or false   -> doesn't throw a fatal error if it returns false . so we need to use if statement to check for it
                        // ban the user who tried to upload an exe file
                        $stmtTwo = $db->prepare('UPDATE users SET banned = 1 WHERE id_users = :user_og_id');
                        $stmtTwo->bindValue(':user_og_id', $_SESSION['userId']);
                        if($stmtTwo->execute()){ //true or false  -> doesn't throw a fatal error if it returns false . so we need to use if statement to check for it
                            $db->commit();
                        }else{
                            $db->rollBack();  //if the if is false, the database roolsback all the changes
                            echo 'we rolledback the changes in db';
                        }
                    }else{
                        $db->rollBack(); // same as above
                        echo 'we rolledback ALL the changes in db';
                    }
                } catch (PDOException $ex){
                    echo $ex;
                    exit();
                }

                $userProfileId = $_SESSION['userId'];
                $userProfileEmail = $_SESSION['userEmail'];
                $enteredUsername = $_SESSION['userUsername'];
                session_destroy();
                header('location: index.php?status=banned');
                require_once('send_email_potential_attack.php');
                exit;
            }
        }
        // get extension knowing that the last element is the extension
        $sExtension = $aImageName[count($aImageName)-1];
        // now we whitelist PNG JPG JPEG
        // if the extention isn't any of these then tell the user only they are allowed
        $bCorrectExtension = false;
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'PNG', 'JPG', 'JPEG', 'GIF'];
        for($j = 0; $j < sizeof($allowedExtensions)-1; $j++){
            if($allowedExtensions[$j] == $sExtension){
                $bCorrectExtension = true;
            }
        }
        if($bCorrectExtension == false){
            header('location: index.php?status=wrong_file_format');
            exit;
        }
        // Create a variable with the new path
        $sPathToSaveFile = "images/posts/$sUniqueImageName.$sExtension";
        $newPostImageLocation = $sPathToSaveFile;
        // save the image to a folder
        if( move_uploaded_file( $sOldPath , $sPathToSaveFile ) ){
            echo "SUCCESS UPLOADING FILE"; 
            // now we can update the database when the image is in the folder
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
                // if the new post couldn't be written into the database, pretend the whole thing failed
                header('location: index.php?status=error_uploading_image');
                exit();
            }
            header('location: index.php');

        }else{
            echo "ERROR UPLOADING FILE";
            header('location: index.php?status=error_uploading_image');
            exit;
        } 
    }else{
        echo "FILE TOO LARGE"; 
        header('location: index.php?status=file_too_large');
        exit;
    }

}else{
    header('location: index.php?status=post_invalid'); 
}
?>