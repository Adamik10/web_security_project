<?php 
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}

// a token was created if the real person wanted to do this - does it match what we got?
if(!isset($_SESSION['token']) || !isset($_POST['activityToken'])){
    //echo 'The token is not set';
    header('location: ups.php');
    exit;
}else{
    // if there is a token, compare it to the one we got from the form
    if ($_SESSION['token'] != $_POST['activityToken']){
        // redirect to UPS THIS WASN'T SUPPOSED TO HAPPEN page 
        header('location: ups.php');
        exit;
    }
}

// we cannot redirect after we hit a problem, because we need to check for other things passed too
// so we log what went wrong and then in the end redirect with all the messages
$thingsThatWentGrong = [];
$toAddToURL = '';

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
                    // echo $ex;
                    array_push($thingsThatWentGrong, 'user was not banned due to an error updating database');
                    $toAddToURL = $toAddToURL.'0a';
                    exit;
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
            array_push($thingsThatWentGrong, 'wrong file format');
            $toAddToURL = $toAddToURL.'0b';
        }else{
            // Create a variable with the new path
            $sPathToSaveFile = "images/users/$sUniqueImageName.$sExtension";
            $newProfileImageLocation = $sPathToSaveFile;
            // save the image to a folder
            if( move_uploaded_file( $sOldPath , $sPathToSaveFile ) ){
                echo "SUCCESS UPLOADING FILE"; 
                try{
                    $update = $db->prepare('UPDATE users 
                                            SET user_image_location = :newPostImageLocation , user_image_name = :newPostImageName WHERE id_users = :userId');
                    $update->bindValue(':newPostImageLocation', $newProfileImageLocation);
                    $update->bindValue(':newPostImageName', $newProfileImgName);
                    $update->bindValue(':userId', $userId);
                    $update->execute();
                } catch (PDOException $ex){
                    echo $ex;
                    array_push($thingsThatWentGrong, 'new user image data was not updated in database');
                    $toAddToURL = $toAddToURL.'1a';
                    exit;
                }
                $_SESSION['userImgLocation'] = $newProfileImageLocation;
            }else{
                echo "ERROR UPLOADING FILE";
                // if the new image couldn't be written into the database
                array_push($thingsThatWentGrong, 'file could not be uploaded');
                $toAddToURL = $toAddToURL.'1b';
            } 
        }
        

    }else{
        // echo "FILE TOO LARGE"; 
        array_push($thingsThatWentGrong, 'file too large');
        $toAddToURL = $toAddToURL.'1c';
    }
} 

    // change username or email

    if(isset($_POST['changedUsername']) && isset($_POST['changedEmail'])){

    require_once('controllers/database.php');

    $changedUsername = $_POST['changedUsername'];
    $changedEmail = $_POST['changedEmail'];
    $sUserIdFromDb = $_SESSION['userId'];

        //first we need to find out 
        // a) if the changed username or email are already used by someone else
        // b) if the changed username is the same as if already is for the logged in user

        $emailTouched = true;
        $usernameTouched = true;
        try{
            $stmt = $db->prepare('SELECT * FROM users WHERE username = :changedUsername OR email = :changedEmail');
            $stmt->bindValue(':changedEmail', $changedEmail);
            $stmt->bindValue(':changedUsername', $changedUsername);
            $stmt->execute();
            $users = $stmt->fetchAll();
        } catch (PDOException $ex){
            echo 'error selecting users: '.$ex;
            header('location: profile.php?status=something_went_wrong?spec=6a'); //in this case we can redirect and exit because if we didn't it could affect the database
            exit();
        }

        // if the changed username is not touched at all
    
        foreach($users as $user){
            if($user['id_users'] == $_SESSION['userId']){
                if($changedEmail == $user['email']){
                    $emailTouched = false;
                }
                if($changedUsername == $user['username']){
                    $usernameTouched = false;
                }
            }
        }

        // echo $emailTouched;
        // echo $usernameTouched;

    // if the changed username or email are already used by someone else

    $alreadyUsedEmail = 0;
    $alreadyUsedUsername = 0;

    foreach($users as $user){
  
        if($user['email'] == $changedEmail && $emailTouched == true){
                $alreadyUsedEmail = 1;
                // echo 'this username is already in use, try a different one'.'<br>';
        }
        if($user['username'] == $changedUsername && $usernameTouched == true){
                $alreadyUsedUsername = 1;
                // echo 'this username is already in use, try a different one'.'<br>';
        }
    }

    //validation

    $validationPass = 1;

        if(strlen($changedUsername) < 2 || strlen($changedUsername) > 20){
            array_push($thingsThatWentGrong, 'username invalid length');
            $validationPass = 0;
            $toAddToURL = $toAddToURL.'3b';
        }
    
        if(filter_var($changedEmail, FILTER_VALIDATE_EMAIL) == false){
            array_push($thingsThatWentGrong, 'email pattern invalid');
            $validationPass = 0;
            $toAddToURL = $toAddToURL.'2b';
        }
    
        if($alreadyUsedEmail == 1){
            array_push($thingsThatWentGrong, 'email already in use');
            $validationPass = 0;
            $toAddToURL = $toAddToURL.'2a';
        }

        if($alreadyUsedUsername == 1){
            array_push($thingsThatWentGrong, 'username already in use');
            $validationPass = 0;
            $toAddToURL = $toAddToURL.'3a';
        }

    if($validationPass == 1){

        //if validation passes then update email and username
        try {
            $stmt1 = $db->prepare('UPDATE users SET username=:username, email=:email WHERE id_users=:loggedUserId');
            $stmt1->bindValue(':username', $changedUsername);
            $stmt1->bindValue(':email', $changedEmail);
            $stmt1->bindValue(':loggedUserId', $sUserIdFromDb);
            $stmt1->execute();

        } catch (PDOException $ex) {
            echo 'error, database update email and username: '.$ex;
            array_push($thingsThatWentGrong, 'username and email could not be updated');
            $toAddToURL = $toAddToURL.'4a';
            exit;
        }
    } else {
        echo 'fields not filled out properly, try again - USERNAME and EMAIL';
        array_push($thingsThatWentGrong, 'incorrect data provided');
        // here we don't need to add anything to URL because we already established what's wrong in the validation part up
    }
}

// change password

if(isset($_POST['changedPassword1']) && !empty($_POST['changedPassword1']) && isset($_POST['changedPassword2']) && !empty($_POST['changedPassword2']) 
 && isset($_POST['changedPasswordOld']) && !empty($_POST['changedPasswordOld'])){

    $changedPassword1 = $_POST['changedPassword1'];
    $changedPassword2 = $_POST['changedPassword2'];
    $changedPasswordOld = $_POST['changedPasswordOld'];
    $userId = $_SESSION['userId'];

    //validation

    $validationPass2 = 1;

        //first we need to find out what the original password and salt were
        try{
            $stmt = $db->prepare('SELECT * FROM users WHERE id_users = :userIdFromSession');
            $stmt->bindValue(':userIdFromSession', $userId);
            $stmt->execute();
            $aaUsers = $stmt->fetchAll();
        } catch (PDOException $ex){
            echo 'error selecting users: '.$ex;
            header('location: profile.php?status=something_went_wrong?spec=6a'); //in this case we can redirect and exit because if we didn't it could affect the database
            exit();
        }
        $aUser = $aaUsers[0];

        $oldPasswordHashFromDb = $aUser['password_hash'];
        $oldPasswordSalt = $aUser['salt'];
        $peber = 'MaciejStopHackingUs';
        
        $doTheyEqual = password_verify($changedPasswordOld.$peber.$oldPasswordSalt, $oldPasswordHashFromDb);
        //PASSWORD_DEFAULT - uses bcrypt algorithm - designed to change over time so the length of the result might change over time - DB column should have at least 60 characters
        //does the old password match with the one from the form??
        if($doTheyEqual != 1){
            array_push($thingsThatWentGrong, 'old password and old password from db are not matching');
            $validationPass2 = 0; 
            echo 'old password not matching:<br>old password hash:'.$oldPasswordHash.'<br>do they equal?:'.$doTheyEqual.'<br>old password Salt from db:'.$oldPasswordSalt;
            $toAddToURL = $toAddToURL.'5d';
        }


        //if password1 and password2 are not matching
        if($changedPassword2 !== $changedPassword1){
            array_push($thingsThatWentGrong, 'password and repeat password are not matching');
            $validationPass2 = 0; 
            echo 'password not matching';
            $toAddToURL = $toAddToURL.'5a';
        }

        //password pattern validation 
        $uppercase = preg_match('@[A-Z]@', $changedPassword2);
        $lowercase = preg_match('@[a-z]@',$changedPassword2);
        $number    = preg_match('@[0-9]@', $changedPassword2);
        if(strlen($changedPassword2) < 7  || $uppercase == 0 || $lowercase == 0 || $number == 0){
            array_push($thingsThatWentGrong, 'password criteria not met');
            $validationPass2 = 0;
            $toAddToURL = $toAddToURL.'5b';
        }

        if($validationPass2 == 1){

            //hashing pattern:
            $salt = base64_encode(uniqid());
            $peber = "MaciejStopHackingUs";
            $options = [
                'cost' => 12
            ];
            //PASSWORD_DEFAULT - uses bcrypt algorithm - designed to change over time so the length of the result might change over time - DB column should have at least 60 characters
            $pass_hash = password_hash($changedPassword2.$peber.$salt, PASSWORD_DEFAULT, $options);

            //if validation passes then update password
            try {
                $stmt1 = $db->prepare('UPDATE users SET password_hash = :new_password, salt = :salt WHERE id_users=:loggedUserId');
                $stmt1->bindValue(':new_password', $pass_hash);
                $stmt1->bindValue(':salt', $salt);
                $stmt1->bindValue(':loggedUserId', $sUserIdFromDb);
                $stmt1->execute();
    
            } catch (PDOException $ex) {
                echo 'error, database update password: '.$ex;
                array_push($thingsThatWentGrong, 'password could not be updated');
                $toAddToURL = $toAddToURL.'5c';
                exit;
            }
        } else {
            echo '<br>fields not filled out properly, try again - PASSWORD';
            array_push($thingsThatWentGrong, 'incorrect password data provided');
            // we don't need to add to URL here because we already established what's wron in the validation2 section
        }

} 

if(sizeof($thingsThatWentGrong) == 0){
    header('location: profile.php?status=all_good');
}else{
    // if something went wrong, then we need to let the user know what it was
    //loop through the array and add stuff into the url
    $finalURL = 'profile.php?status=something_went_wrong?spec='.$toAddToURL;
    header('location: '.$finalURL);
}
