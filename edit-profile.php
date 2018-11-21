<?php 

require_once('components/top.php');
require_once('controllers/database.php');

if(isset($_POST['changedUsername']) &&
    isset($_POST['changedEmail'])
){

    $changedUsername = htmlentities($_POST['changedUsername']);
    $changedEmail = htmlentities($_POST['changedEmail']);
    $sUserIdFromDb = $_SESSION['userId'];

    //select all users to see if email is available
    try{
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :changedEmail AND username = :changedUsername');
        $stmt->bindValue(':changedEmail', $changedEmail);
        $stmt->bindValue(':changedUsername', $changedUsername);
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
        if($user['email'] == $changedEmail){
            $alreadyUsedEmail = true;
            echo '<br>'.'this email is already in use, try a different one'.'<br>';
        }
        if($user['username'] == $changedUsername){
            $alreadyUsedUsername = true;
            echo 'this username is already in use, try a different one'.'<br>';
        }
    }

    //validation

    if(
        strlen($changedUsername) > 2 &&
        strlen($changedUsername) < 20 &&
        filter_var($changedEmail, FILTER_VALIDATE_EMAIL) == TRUE &&
        ($alreadyUsedEmail == false || $alreadyUsedUsername == false)
    ){

            //if validation passes then update user info

            try {
                $stmt1 = $db->prepare('UPDATE users SET username=:username, email=:email WHERE id_users=:loggedUserId');
                $stmt1->bindValue(':username', $changedUsername);
                $stmt1->bindValue(':email', $changedEmail);
                $stmt1->bindValue(':loggedUserId', $sUserIdFromDb);
                $stmt1->execute();

            } catch (PDOException $ex) {
                echo 'error, database update email and username';
                exit();
            }

        
    } else {
        echo 'fields not filled out properly, try again';
    }

}else{
    header('location: profile.php');
};
