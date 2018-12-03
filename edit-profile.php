<?php 
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}

// if new image was added
if( isset($_FILES['profileImgFile']) && $_FILES['profileImgFile']['size'] != 0 ){
    
    require_once('controllers/database.php');
    // store user id from session
    $userId = $_SESSION['userId'];
    $username = $_SESSION['userUsername'];
    // store new img variables
    $newProfileImgLocation;
    $newProfileImgName;

    if( $_FILES['profileImgFile']['size'] < 4000000 ){ // unit is bytes
        // use the image
        $aImage = $_FILES['profileImgFile'];
        // Array ( [name] => logo.svg [type] => image/svg+xml [tmp_name] => C:\xampp\tmp\php3128.tmp [error] => 0 [size] => 2668 )
        $sOldPath = $aImage['tmp_name'];
        // Create an id that will be unique for the file that we will save
        $sUniqueImageName = uniqid();
        $newProfileImgName = $sUniqueImageName;
        // Extract the extension of the image
        $sImageName = $aImage['name'];
        $aImageName = explode( '.' , $sImageName ); // logo.svg ['logo','svg']
        // loop through all of the items in the exploded array except the first one - if any contain 'exe', redirect back to index and log this attempt
        for($i = 1; $i < sizeof($aImageName)-1; $i++){
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
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'PNG', 'JPG', 'JPEG'];
        for($j = 0; $j < sizeof($allowedExtensions)-1; $j++){
            if($allowedExtensions[$j] == $sExtension){
                $bCorrectExtension = true;
            }
        }
        if($bCorrectExtension == false){
            header('location: profile.php?status=wrong_file_format');
            exit;
        }
        // Create a variable with the new path
        $sPathToSaveFile = "images/users/$sUniqueImageName.$sExtension";
        $newProfileImageLocation = $sPathToSaveFile;
        // save the image to a folder
        if( move_uploaded_file( $sOldPath , $sPathToSaveFile ) ){
            echo "SUCCESS UPLOADING FILE"; 
        }else{
            echo "ERROR UPLOADING FILE";
        } 

        // TO DO - maybe it would be nice to have a transaction here since the image is already in the folder
        try{
            $update = $db->prepare('UPDATE users 
                                    SET user_image_location = :newPostImageLocation , user_image_name = :newPostImageName WHERE id_users = :userId');
            $update->bindValue(':newPostImageLocation', $newProfileImageLocation);
            $update->bindValue(':newPostImageName', $newProfileImgName);
            $update->bindValue(':userId', $userId);
            $update->execute();
        } catch (PDOException $ex){
            echo $ex;
            exit();
        }
        $_SESSION['userImgLocation'] = $newProfileImageLocation;
        header('location: profile.php');

    }else{
        // echo "FILE TOO LARGE"; 
        // redirect to profile with status file too large
        header('location: profile.php?status=file_too_large');
    }
}

// if user wants to change username and email
if(isset($_POST['changedUsername']) 
    // isset($_POST['changedEmail'])
    // isset($_POST['changedPassword1']) &&
    // isset($_POST['changedPassword2']) &&
){

    require_once('controllers/database.php');

    $changedUsername = htmlentities($_POST['changedUsername']);
    // $changedEmail = htmlentities($_POST['changedEmail']);
    $sUserIdFromDb = $_SESSION['userId'];

    //select all users to see if email is available
    try{
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :changedUsername');
        // $stmt->bindValue(':changedEmail', $changedEmail);
        $stmt->bindValue(':changedUsername', $changedUsername);
        $stmt->execute();
        $users = $stmt->fetchAll();
    } catch (PDOException $ex){
        echo 'error selecting users';
        exit();
    }

    // if a user with same email is found then set alreadyUsedEmail to true

    $alreadyUsedEmail = 0;
    $alreadyUsedUsername = 0;

    foreach($users as $user){
    //     if($user['email'] == $changedEmail){
    //         $alreadyUsedEmail = 1;
    //         // echo '<br>'.'this email is already in use, try a different one'.'<br>';
    //     }
        if($user['username'] == $changedUsername){
            if ($user['username'] != $_SESSION['userUsername']){
                $alreadyUsedUsername = 1;
                // echo 'this username is already in use, try a different one'.'<br>';
                header('location: profile.php?status=username_or_email_used');
            }
        }
    }


    //validation

    $validationPass = 1;

        if(strlen($changedUsername) < 2 || strlen($changedUsername) > 20){
            header('location: profile.php?status=username_length');
            $validationPass = 0;
        }
    
        // if(filter_var($changedEmail, FILTER_VALIDATE_EMAIL) == false){
        //     header('location: profile.php?status=email_pattern');
        //     $validationPass = 0;
        // }
    
        if($alreadyUsedEmail == 1 || $alreadyUsedUsername == 1){
            header('location: profile.php?status=already_exists');
            $validationPass = 0;
        }

        // //if password1 and password2 are not matching
        // if($registerPassword1 !== $registerPassword2){
        //     header('location: register.php?status=password_not_matching');
        //     $validationPass = 0;
        //     echo 'password not matching';
        // }

        // //password pattern validation 
        // $uppercase = preg_match('@[A-Z]@', $registerPassword2);
        // $lowercase = preg_match('@[a-z]@',$registerPassword2);
        // $number    = preg_match('@[0-9]@', $registerPassword2);
        // if($registerPassword2 < 7  || !$uppercase || !$lowercase || !$number){
        //     header('location: register.php?status=password_criteria');
        //     $validationPass = 0;
        // }



    if($validationPass == 1){

            //if validation passes then update user info

            try {
                $stmt1 = $db->prepare('UPDATE users SET username=:username WHERE id_users=:loggedUserId');
                $stmt1->bindValue(':username', $changedUsername);
                // $stmt1->bindValue(':email', $changedEmail);
                $stmt1->bindValue(':loggedUserId', $sUserIdFromDb);
                $stmt1->execute();

            } catch (PDOException $ex) {
                echo 'error, database update email and username: '.$ex;
                exit();
            }

            header('location: profile.php');

    } else {
        echo 'fields not filled out properly, try again';
    }
};
