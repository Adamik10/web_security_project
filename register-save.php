
<?php 

require_once("controllers/database.php");

if(isset($_POST['registerUsername']) &&
    isset($_POST['registerEmail']) &&
    isset($_POST['registerPassword2']) &&
    isset($_POST['registerPassword1']) &&
    isset($_POST['registerCheckbox'])
){

    $registerUsername = $_POST['registerUsername'];
    $registerEmail = $_POST['registerEmail'];
    $registerPassword1 = $_POST['registerPassword1'];
    $registerPassword2 = $_POST['registerPassword2'];
    $registerCheckbox = $_POST['registerCheckbox'];
    $userId = uniqid();
    $verificationCode = uniqid();

    //select all users to see if email is available
    try{
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :registerEmail AND username = :registerUsername');
        $stmt->bindValue(':registerEmail', $registerEmail);
        $stmt->bindValue(':registerUsername', $registerUsername);
        $stmt->execute();
        $users = $stmt->fetchAll();
    } catch (PDOException $ex){
        echo 'error selecting users';
        exit();
    }

    // if a user with same email is found then set alreadyUsedEmail to true

    $alreadyUsedEmail = false;
    $alreadyUsedUsername = false;

    foreach($users as $user){
        if($user['email'] == $registerEmail){
            $alreadyUsedEmail = true;
            echo '<br>'.'this email is already in use, try a different one'.'<br>';
        }
        if($user['username'] == $registerUsername){
            $alreadyUsedUsername = true;
            echo 'this username is already in use, try a different one'.'<br>';
        }
    }

    //validation

    if(
        $registerPassword1 == $registerPassword2 &&
        $registerCheckbox == 'on' &&
        strlen($registerUsername) > 2 &&
        strlen($registerUsername) < 20 &&
        filter_var($registerEmail, FILTER_VALIDATE_EMAIL) == TRUE &&
        ($alreadyUsedEmail == false || $alreadyUsedUsername == false)
    ){


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
                $stmt1 = $db->prepare('INSERT INTO users VALUES (:userId, :username, :email, :pass, :salt, :verified)');
                $stmt1->bindValue(':userId', $userId);
                $stmt1->bindValue(':username', $registerUsername);
                $stmt1->bindValue(':email', $registerEmail);
                $stmt1->bindValue(':pass', $pass_hash);
                $stmt1->bindValue(':salt', $salt);
                $stmt1->bindValue(':verified', 0);
                $stmt1->execute();

                $stmt2 = $db->prepare('INSERT INTO verification_codes VALUES (:userId, :verification_code)');
                $stmt2->bindValue(':userId', $userId);
                $stmt2->bindValue(':verification_code', $verificationCode);
                $stmt2->execute();

            } catch (PDOException $ex) {
                echo 'error, database insertion';
                exit();
            }

        
    } else {
        echo 'fields not filled out properly, try again';
    }

    require_once('send-verification-email.php');
}
