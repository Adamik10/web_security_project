<?php
require_once("controllers/database.php");

if(isset($_POST['registerUsername']) && !empty($_POST['registerUsername']) &&
    isset($_POST['registerEmail']) && !empty($_POST['registerEmail']) &&
    isset($_POST['registerPassword2']) && !empty($_POST['registerPassword2']) &&
    isset($_POST['registerPassword1']) && !empty($_POST['registerPassword1']) &&
    isset($_POST['registerCheckbox']) && !empty($_POST['registerCheckbox'])
){

    $registerUsername = htmlentities($_POST['registerUsername']);
    $registerEmail = htmlentities($_POST['registerEmail']);
    $registerPassword1 = htmlentities($_POST['registerPassword1']);
    $registerPassword2 = htmlentities($_POST['registerPassword2']);
    $registerCheckbox = $_POST['registerCheckbox'];
    $userId = uniqid();
    $verificationCode = uniqid();
    $idVerificationCode = uniqid();

    //select all users to see if email is available
    try{
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :registerEmail OR username = :registerUsername');
        $stmt->bindValue(':registerEmail', $registerEmail);
        $stmt->bindValue(':registerUsername', $registerUsername);
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
        if($user['email'] == $registerEmail){
            $alreadyUsedEmail = 1;
            echo '<br>'.'this email is already in use, try a different one'.'<br>';
        }
        if($user['username'] == $registerUsername){
            $alreadyUsedUsername = 1;
            echo 'this username is already in use, try a different one'.'<br>';
        }
    }

    //validation

    if(
        $registerPassword1 == $registerPassword2 &&
        $registerCheckbox == 'on' &&
        strlen($registerUsername) > 2 &&
        strlen($registerUsername) < 20 &&
        filter_var($registerEmail, FILTER_VALIDATE_EMAIL) == true &&
        $alreadyUsedEmail == 0 && 
        $alreadyUsedUsername == 0
    ){
        echo $registerPassword1.' should equal '.$registerPassword2.'<br>'.$registerCheckbox.'<br>'.strlen($registerUsername).
        ' should be less than 20 and more than 2<br>'.filter_var($registerEmail, FILTER_VALIDATE_EMAIL).' should be email<br> already used email:'.$alreadyUsedEmail.
        ' <br> already used username: '.$alreadyUsedUsername;
        
            //hashing pattern:
            $salt = rand(100000, 999999);
            $peber = "MaciejStopHackingUs";
            $options = [
                'cost' => 12
            ];
            //PASSWORD_DEFAULT - uses bcrypt algorithm - designed to change over time so the length of the result might change over time - DB column should have at least 60 characters
            $pass_hash = password_hash($registerPassword1.$peber.$salt, PASSWORD_DEFAULT, $options);

            //if validation passes then insert into database try catch

            try {
                $stmt1 = $db->prepare('INSERT INTO users(id_users, username, email, password_hash, salt, verified, user_image_location, user_image_name) 
                                        VALUES (:userId, :username, :email, :pass, :salt, :verified, :user_image_location, :user_image_name)');
                $stmt1->bindValue(':userId', $userId);
                $stmt1->bindValue(':username', $registerUsername);
                $stmt1->bindValue(':email', $registerEmail);
                $stmt1->bindValue(':pass', $pass_hash);
                $stmt1->bindValue(':salt', $salt);
                $stmt1->bindValue(':verified', 0);
                $stmt1->bindValue(':user_image_location', 'images/users/default.JPG');
                $stmt1->bindValue(':user_image_name', 'default');
                $stmt1->execute();

                $stmt2 = $db->prepare('INSERT INTO verification_codes VALUES (:id_verification_code, :userId, :verification_code)');
                $stmt2->bindValue(':id_verification_code', $idVerificationCode);
                $stmt2->bindValue(':userId', $userId);
                $stmt2->bindValue(':verification_code', $verificationCode);
                $stmt2->execute(); 

            } catch (PDOException $ex) {
                echo 'error, database insertion<br>';
                echo $ex;
                exit();
            }
            require_once('send-verification-email.php');
        
    } else {
        header('location: register.php?status=already_exists');
    }

    
}else{
    header('location: register.php');
}
