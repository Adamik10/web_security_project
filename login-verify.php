<?php require_once("components/recaptchalib.php"); 
// https://github.com/google/recaptcha/blob/1.0.0/php/recaptchalib.php
?>

<?php 
// RECAPTCHA VARIABLES
            // your secret key
            $secret = "6LcsjHUUAAAAAMpZyBF1M9u3KnQgoT-PCTtEP0B3";
            
            // empty response
            $response = null;
            
            // check secret key
            $reCaptcha = new ReCaptcha($secret);


// ------------------------------------ LOGIN FUNCTION that gets called in various places below ;) ------------------------------------
function tryToLogin(){
    require('controllers/database.php');
    $enteredUsername = $_POST['loginUsername'];
    $enteredPassword = $_POST['loginPassword'];
    $peber = 'MaciejStopHackingUs';
    // this is supposed to influence the time of hashing - ask adam
    $options = [
        'cost' => 12
    ];
    // getting the IP adress
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $currentIp = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $currentIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $currentIp = $_SERVER['REMOTE_ADDR'];
    }

    //get the hashed password and the salt from DB
    try{
        $stmt = $db->prepare('SELECT salt, password_hash, id_users, username, email, verified FROM users 
                                                                                    WHERE username = :enteredName LIMIT 1');
        $stmt->bindValue('enteredName', $enteredUsername);
        $stmt->execute();
        $aaResult = $stmt->fetchAll();

        //if this is an empty array - there is no user with that username 
        if(empty($aaResult)){
            header('location: login.php?status=doesnt_exist');
        }else{ //if there is a match, verify whether the password matches
            $aResult = $aaResult[0];
            $sUserSaltFromDb = $aResult['salt'];
            $sUserPasswordFromDb = $aResult['password_hash'];
            $sUserIdFromDb = $aResult['id_users'];
            $sUserUsernameFromDb = $aResult['username'];
            $sUserEmailFromDb = $aResult['email'];
            $sUserVerifiedFromDb = $aResult['verified'];
            
            // echo '<br>This is the array we got'.print_r($aaUserSaltAndPass).'<br>';
            // echo '<br>This is the pass entered: '.$enteredPassword;
            // echo '<br>This is the pass from db: '.$sUserPasswordFromDb;
            // echo '<br>This is the salt: '.$sUserSaltFromDb;
            // echo '<br>This is the peber: '.$peber;

            // check if the user is verified, if verified is 0 then redirect to login with status not verified
            

            $doTheyEqual = password_verify($enteredPassword.$peber.$sUserSaltFromDb, $sUserPasswordFromDb);
            // $registerPassword1.$peber.$salt, PASSWORD_DEFAULT, $options
            // echo '<br> do they: '.$doTheyEqual;

            // check if they equal 
            if($doTheyEqual == 1){

                // check if the account is verified
                if($sUserVerifiedFromDb == 0){
                    header('location: login.php?status=not_verified');
                }else{
                    // if its verified start session, clean attempts and redirect to index
                    session_start();
                    $_SESSION['sessionId']=uniqid();
                    $_SESSION['userId'] = $sUserIdFromDb;
                    $_SESSION['userUsername'] = $sUserUsernameFromDb;
                    $_SESSION['userEmail'] = $sUserEmailFromDb;

                    //CLEAR NUMBER OF ATTEMPTS FOR THIS IP
                    try{
                        $sUpdate = $db->prepare( 'UPDATE logging_in SET attempts = :default WHERE ip = :ip' );
                        $sUpdate->bindValue( ':default' , 0 );
                        $sUpdate->bindValue( ':ip' , $currentIp );
                        $sUpdate->execute();
                        // redirect to index
                        header('location: index.php');
                    }catch( PDOException $ex ){
                    echo $ex;
                    }
                }

                    
            }else{ //if the password is incorrect, redirect to login
                header('location: login.php?status=doesnt_exist');
            }
        }
        
    }catch (PDOException $exception){
        echo $exception;
    }
}
// ------------------------------------ END OF LOGIN FUNCTION --------------------------------------------------



// check if the form was passed
if(!empty($_POST['loginUsername']) && !empty($_POST['loginPassword'])){
    require('controllers/database.php');
    $enteredUsername = $_POST['loginUsername'];
    $enteredPassword = $_POST['loginPassword'];
    echo '<br>username: '.$enteredUsername.' and password: '.$enteredPassword.'<br>';


    //get IP address https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $currentIp = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $currentIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $currentIp = $_SERVER['REMOTE_ADDR'];
    }
    echo 'This is the current IP: '.$currentIp.'<br>';


    // get an array of IPs that match from the database
    try{
        $stmt = $db->prepare('SELECT * FROM logging_in 
                                        WHERE ip = :currentIP');
        $stmt->bindValue('currentIP', $currentIp);
        $stmt->execute();
        $aOfMatchedIPs = $stmt->fetchAll();
    }catch (PDOException $exception){
        echo $exception;
    }
    echo 'This is the IPs that match from the database: '.json_encode($aOfMatchedIPs).'<br>';



    if(empty($aOfMatchedIPs)){

        //if the array of matched ips is empty write it into the database together with the number of attempts and time of the attempt
        try{
            $sCreate = $db->prepare( 'INSERT INTO logging_in VALUES(:id_loggin_in, :username, :ip, :attempts)' );
            $sCreate->bindValue( ':id_loggin_in' , null );
            $sCreate->bindValue( ':username' , $enteredUsername );
            $sCreate->bindValue( ':ip' , $currentIp );
            $sCreate->bindValue( ':attempts' , 1);
            $sCreate->execute();
            tryToLogin();
        }catch( PDOException $ex){
            echo $ex;
        }

    }else{
        //if the array of matched ips is NOT EMPTY, check which attempt in row was it
        try{
            $stmt = $db->prepare('SELECT *  FROM logging_in 
                                            WHERE ip = :currentIP');
            $stmt->bindValue('currentIP', $currentIp); 
            $stmt->execute();
            $aaIPinfo = $stmt->fetchAll();
        }catch (PDOException $exception){
            echo $exception;
        }
        // echo 'This is the IPs that match from the database: '.print_r($aaIPinfo).'<br>';
        // echo print_r($aaIPinfo[0]).'<br>';
        $aIPinfo = $aaIPinfo[0];
        // echo 'This is the number of attempts: '.$aIPinfo['number_of_attempts'].'<br>';
        $attempt = $aIPinfo['attempts'];

        //if this is the first, or second attempt, or 3rd just increment the value in the database and try to login
        if($attempt*1 == 0 || $attempt*1 == 1 || $attempt*1 == 2 ){
            try{
                $sUpdate = $db->prepare( 'UPDATE logging_in SET attempts = :increment WHERE ip = :ip' );
                $time_of_third_attempt = date('Y/m/d H:i:s');
                $sUpdate->bindValue( ':increment' , ($attempt*1)+1 );
                $sUpdate->bindValue( ':ip' , $currentIp );
                $sUpdate->execute();
                // echo 'Incrementation done<br>';
                tryToLogin();
            }catch( PDOException $ex ){
                echo $ex;
            }
        }else{
            // IF THIS IS 4th TIME

            // if RECAPTCHA IS SUBMITTED CHECK RESPONSE
            if ($_POST["g-recaptcha-response"]) {
                $response = $reCaptcha->verifyResponse(
                    $_SERVER["REMOTE_ADDR"],
                    $_POST["g-recaptcha-response"]
                );
            }
            
            // IF RESPONSE IS OKE TRY TO LOGIN
            if ($response != null && $response->success) {
                tryToLogin();
            }else{
                header('location: login.php?status=wrong_captcha');
            }
        }
 
    }

}else{
    // if form data wasnt passed to this page
    header('location: login.php');   
}


?>