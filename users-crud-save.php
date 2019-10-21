
<?php

require_once("controllers/database.php");

// check if user is logged in
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}
//here check for a token 

// echo '1 - '. $_POST['txtUserId'].'   ';
// echo '2 - '. $_POST['txtUsernameCrud'].'   ';
// echo '3 - '. $_POST['txtEmailCrud'].'   ';
// echo '4 - '. $_POST['txtPasswordCrud'].'   ';
// echo '5 - '. $_POST['txtBannedCrud'].'   ';
// echo '6 - '. $_POST['txtVerifiedCrud'].'   ';

$thingsThatWentGrong = [];

if( isset($_POST['txtUserId']) &&
    isset($_POST['txtUsernameCrud']) && 
    isset($_POST['txtEmailCrud']) && 
    isset($_POST['txtPasswordCrud']) && 
    isset($_POST['txtBannedCrud']) && 
    isset($_POST['txtVerifiedCrud'])
   ){
    echo 'hooo';
    $txtIdCrud = $_POST['txtUserId'];
    $txtUsernameCrud = $_POST['txtUsernameCrud'];
    $txtEmailCrud = $_POST['txtEmailCrud'];
    $txtPasswordCrud = $_POST['txtPasswordCrud'];
    $txtBannedCrud = $_POST['txtBannedCrud'];
    $txtVerifiedCrud = $_POST['txtVerifiedCrud'];

    require_once('controllers/database.php');

    //first we need to find out 
    // a) if the changed username or email are already used by someone else
    // b) if the changed username is the same as if already is for the targeted user

        $emailTouched = true;
        $usernameTouched = true;
        try{
            $stmt = $db->prepare('SELECT * FROM users WHERE username = :changedUsername OR email = :changedEmail');
            $stmt->bindValue(':changedEmail', $txtEmailCrud);
            $stmt->bindValue(':changedUsername', $txtUsernameCrud);
            $stmt->execute();
            $users = $stmt->fetchAll();
        } catch (PDOException $ex){
            echo 'error selecting users: '.$ex;
            // header('location: index.php'); //in this case we can redirect and exit because if we didn't it could affect the database
            exit();
        }

        // if the changed username is not touched at all
    
        foreach($users as $user){
            if($user['id_users'] == $txtIdCrud){
                if($txtEmailCrud == $user['email']){
                    $emailTouched = false;
                }
                if($txtUsernameCrud == $user['username']){
                    $usernameTouched = false;
                }
            }
        }

    // if the changed username or email are already used by someone else

    $alreadyUsedEmail = 0;
    $alreadyUsedUsername = 0;

    foreach($users as $user){
  
        if($user['email'] == $txtEmailCrud && $emailTouched == true){
                $alreadyUsedEmail = 1;
                // echo 'this username is already in use, try a different one'.'<br>';
                array_push($thingsThatWentGrong, 'email already in use');
        }
        if($user['username'] == $txtUsernameCrud && $usernameTouched == true){
                $alreadyUsedUsername = 1;
                // echo 'this username is already in use, try a different one'.'<br>';
                array_push($thingsThatWentGrong, 'username already in use');
        }
    }

    //validation

    $validationPass = 1;

        if(strlen($txtUsernameCrud) < 2 || strlen($txtUsernameCrud) > 20){
            array_push($thingsThatWentGrong, 'username invalid length');
            $validationPass = 0;
            echo '1';
        }
    
        if(filter_var($txtEmailCrud, FILTER_VALIDATE_EMAIL) == false){
            array_push($thingsThatWentGrong, 'email pattern invalid');
            $validationPass = 0;
            echo '2';
        }
    
        if($alreadyUsedEmail == 1){
            array_push($thingsThatWentGrong, 'email already in use');
            $validationPass = 0;
            echo '3';
        }

        if($alreadyUsedUsername == 1){
            array_push($thingsThatWentGrong, 'username already in use');
            $validationPass = 0;
            echo '4';
        }


        if($txtBannedCrud < 0 && $txtBannedCrud > 1){
            array_push($thingsThatWentGrong, 'ban status is not expressed with 0 or 1');
            $validationPass = 0;
            echo '5';
        }

        if($txtVerifiedCrud < 0 && $txtVerifiedCrud > 1){
            array_push($thingsThatWentGrong, 'verified status is not expressed with 0 or 1');
            $validationPass = 0;
            echo '6';
        }

         //password pattern validation 
         $uppercase = preg_match('@[A-Z]@', $txtPasswordCrud);
         $lowercase = preg_match('@[a-z]@',$txtPasswordCrud);
         $number    = preg_match('@[0-9]@', $txtPasswordCrud);
         if($txtPasswordCrud < 7  || !$uppercase || !$lowercase || !$number){
             array_push($thingsThatWentGrong, 'password criteria not met');
             $validationPass = 0;
            //  echo $uppercase.'       '.$lowercase.'       '.$number.'       '.$txtPasswordCrud;
             echo 'password drama';
         }

        $sUserId = $_SESSION['userId'];

    if($validationPass == 1){
        //if validation passes then update username, email, banned, verified
        try {;
            $stmt1 = $db->prepare('UPDATE users SET username=:username, email=:email, verified=:verified, banned=:banned WHERE id_users=:loggedUserId');
            $stmt1->bindValue(':username', $txtUsernameCrud);
            $stmt1->bindValue(':email', $txtEmailCrud);
            $stmt1->bindValue(':verified', $txtVerifiedCrud);
            $stmt1->bindValue(':banned', $txtBannedCrud);
            $stmt1->bindValue(':loggedUserId', $sUserId);
            $stmt1->execute();

        } catch (PDOException $ex) {
            echo 'error, database update user data: '.$ex;
            array_push($thingsThatWentGrong, 'user data could not be updated');
            exit;
        }

        //if password is altered then update password
    if($_POST['txtPasswordCrud'] !== 'password'){

        echo 'yes I am trying to save something';
    
        //hashing pattern:
        $salt = base64_encode(uniqid());
        $peber = "MaciejStopHackingUs";
        $options = [
            'cost' => 12
        ];
        //PASSWORD_DEFAULT - uses bcrypt algorithm - designed to change over time so the length of the result might change over time - DB column should have at least 60 characters
        $pass_hash = password_hash($txtPasswordCrud.$peber.$salt, PASSWORD_DEFAULT, $options);

        //if validation passes then update password
        try {
            $stmt1 = $db->prepare('UPDATE users SET password_hash = :password, salt = :salt WHERE id_users=:loggedUserId');
            $stmt1->bindValue(':password', $pass_hash);
            $stmt1->bindValue(':salt', $salt);
            $stmt1->bindValue(':loggedUserId', $sUserId);
            $stmt1->execute();

        } catch (PDOException $ex) {
            echo 'error, database update password: '.$ex;
            array_push($thingsThatWentGrong, 'password could not be updated');
        }
    } else {
        echo 'fields not filled out properly, try again - PASSWORD';
        array_push($thingsThatWentGrong, 'incorrect password data provided');
    }
} 


    } else {
        echo 'fields not filled out properly, try again';
        array_push($thingsThatWentGrong, 'incorrect data provided');
    }



    