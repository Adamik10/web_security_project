
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

    //verification
    //TODO check if it is already existing user
    if(
        $registerPassword1 == $registerPassword2 &&
        $registerCheckbox == 'on' &&
        strlen($registerUsername) > 2 &&
        strlen($registerUsername) < 20 &&
        filter_var($registerEmail, FILTER_VALIDATE_EMAIL) == TRUE
    ){
            //verification code for email verify - why do we even need to save it? 
            $verificationCode = 'verification code here';

            //hashing pattern:
            $salt = random_int(100000, 999999);
            $peber = "BetterSafeThanSorry";
            $options = [
                'cost' => 12
            ];
            //PASSWORD_DEFAULT - uses bcrypt algorithm - designed to change over time so the length of the result might change over time - DB column should have at least 60 characters
            $pass_hash = password_hash($registerPassword1.$peber.$salt, PASSWORD_DEFAULT, $options);

            //if validation passes then try catch

            try {
                $stmt = $db->prepare('INSERT INTO users VALUES (:userId, :username, :email, :password, :salt, :verification_code, :verified)');
                $stmt->bindValue(':userId', $userId);
                $stmt->bindValue(':username', $registerUsername);
                $stmt->bindValue(':email', $registerEmail);
                $stmt->bindValue(':password', $pass_hash);
                $stmt->bindValue(':salt', $salt);
                $stmt->bindValue(':verification_code', $verificationCode);
                $stmt->bindValue(':verified', 0);
                $stmt->execute();
            } catch (PDOException $ex) {
                echo 'error, database insertion';
                exit();
            }

    } else {
        echo 'fields not filled out properly, try again';
    }

}
