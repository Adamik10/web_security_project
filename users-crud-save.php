
<?php

require_once("controllers/database.php");

// check if user is logged in
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}

if( isset($_POST['$txtUserId']) &&
    isset($_POST['txtUsernameCrud']) && 
    isset($_POST['txtEmailCrud']) && 
    isset($_POST['txtPasswordCrud']) && 
    isset($_POST['txtBannedCrud']) && 
    isset($_POST['txtVerifiedCrud'])
   ){

    $txtIdCrud = htmlentities($_POST['txtUserId']);
    $txtUsernameCrud = htmlentities($_POST['txtUsernameCrud']);
    $txtEmailCrud = htmlentities($_POST['txtEmailCrud']);
    $txtPasswordCrud = htmlentities($_POST['txtPasswordCrud']);
    $txtBannedCrud = htmlentities($_POST['txtBannedCrud']);
    $txtVerifiedCrud = htmlentities($_POST['txtVerifiedCrud']);

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
            header('location: index.php'); //in this case we can redirect and exit because if we didn't it could affect the database
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
        }
    
        if(filter_var($txtEmailCrud, FILTER_VALIDATE_EMAIL) == false){
            array_push($thingsThatWentGrong, 'email pattern invalid');
            $validationPass = 0;
        }
    
        if($alreadyUsedEmail == 1){
            array_push($thingsThatWentGrong, 'email already in use');
            $validationPass = 0;
        }

        if($alreadyUsedUsername == 1){
            array_push($thingsThatWentGrong, 'username already in use');
            $validationPass = 0;
        }

    if($validationPass == 1){

        //if validation passes then update email and username
        try {
            $stmt1 = $db->prepare('UPDATE users SET username=:username, email=:email WHERE id_users=:loggedUserId');
            $stmt1->bindValue(':username', $txtUsernameCrud);
            $stmt1->bindValue(':email', $txtEmailCrud);
            $stmt1->bindValue(':loggedUserId', $sUserIdFromDb);
            $stmt1->execute();

        } catch (PDOException $ex) {
            echo 'error, database update email and username: '.$ex;
            array_push($thingsThatWentGrong, 'username and email could not be updated');
        }
    } else {
        echo 'fields not filled out properly, try again - USERNAME and EMAIL';
        array_push($thingsThatWentGrong, 'incorrect data provided');
    }
}


    // if(isset($_POST['changedPassword1']) && 
    //     !empty($_POST['changedPassword1']) && 
    //     isset($_POST['changedPassword2']) && 
    //     !empty($_POST['changedPassword2'])){
    
    //     echo '123';
    
    //     $changedPassword1 = $_POST['changedPassword1'];
    //     $changedPassword2 = $_POST['changedPassword2'];
    
    //     //validation
    
    //     $validationPass2 = 1;
    
    //         //if password1 and password2 are not matching
    //         if($changedPassword2 !== $changedPassword1){
    //             array_push($thingsThatWentGrong, 'password and repeat password are not matching');
    //             $validationPass2 = 0; 
    //             echo 'password not matching';
    //         }
    
    //         //password pattern validation 
    //         $uppercase = preg_match('@[A-Z]@', $changedPassword2);
    //         $lowercase = preg_match('@[a-z]@',$changedPassword2);
    //         $number    = preg_match('@[0-9]@', $changedPassword2);
    //         if($changedPassword2 < 7  || !$uppercase || !$lowercase || !$number){
    //             array_push($thingsThatWentGrong, 'password criteria not met');
    //             $validationPass2 = 0;
    //         }
    
    //         if($validationPass2 == 1){
    
    //             //hashing pattern:
    //             $salt = rand(100000, 999999);
    //             $peber = "MaciejStopHackingUs";
    //             $options = [
    //                 'cost' => 12
    //             ];
    //             //PASSWORD_DEFAULT - uses bcrypt algorithm - designed to change over time so the length of the result might change over time - DB column should have at least 60 characters
    //             $pass_hash = password_hash($changedPassword2.$peber.$salt, PASSWORD_DEFAULT, $options);
    
    //             //if validation passes then update password
    //             try {
    //                 $stmt1 = $db->prepare('UPDATE users SET password_hash = :password, salt = :salt WHERE id_users=:loggedUserId');
    //                 $stmt1->bindValue(':password', $pass_hash);
    //                 $stmt1->bindValue(':salt', $salt);
    //                 $stmt1->bindValue(':loggedUserId', $sUserIdFromDb);
    //                 $stmt1->execute();
        
    //             } catch (PDOException $ex) {
    //                 echo 'error, database update password: '.$ex;
    //                 array_push($thingsThatWentGrong, 'password could not be updated');
    //             }
    //         } else {
    //             echo 'fields not filled out properly, try again - PASSWORD';
    //             array_push($thingsThatWentGrong, 'incorrect password data provided');
    //         }
    
    // } 
