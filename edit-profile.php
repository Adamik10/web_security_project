<?php 
require_once('components/top.php');

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
                // TO DO - write logs here on which user did it
                //...
                header('location: profile.php');
                exit;
            }
        }
        // get extension knowing that the last element is the extension
        $sExtension = $aImageName[count($aImageName)-1];
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
                                    SET image_location = :newPostImageLocation , image_name = :newPostImageName WHERE id_users = :userId');
            $update->bindValue(':newPostImageLocation', $newProfileImageLocation);
            $update->bindValue(':newPostImageName', $newProfileImgName);
            $update->bindValue(':userId', $userId);
            $update->execute();
        } catch (PDOException $ex){
            echo $ex;
            exit();
        }
        
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
            $alreadyUsedUsername = 1;
            // echo 'this username is already in use, try a different one'.'<br>';
            header('location: profile.php?status=username_or_email_used');
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



    if(
        $validationPass == 1  
    ){

            //if validation passes then update user info

            try {
                $stmt1 = $db->prepare('UPDATE users SET username=:username WHERE id_users=:loggedUserId');
                $stmt1->bindValue(':username', $changedUsername);
                // $stmt1->bindValue(':email', $changedEmail);
                $stmt1->bindValue(':loggedUserId', $sUserIdFromDb);
                $stmt1->execute();

            } catch (PDOException $ex) {
                echo 'error, database update email and username';
                exit();
            }

    } else {
        echo 'fields not filled out properly, try again';
    }
};
