<?php
session_start();
// a token was created if the real person wanted to do this - does it match what we got?
if(!isset($_SESSION['token']) || !isset($_POST['activityToken'])){
    //echo 'The token is not set';
    header('location: ups.php');
    exit;
}else{
    // if there is a token, compare it to the one we got from the form
    if (hash('sha256', $_SESSION['token']) != $_POST['activityToken']){
        // redirect to UPS THIS WASN'T SUPPOSED TO HAPPEN page 
        header('location: ups.php');
        exit;
    }
}
?>


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

// ------------------------------------ LOGIN FUNCTION FOR ADMINS - IF USERNAME DOESNT MATCH ANYONE FROM USERS TABLE (gets called in tryToLogin) ;) ------------------------------------
function tryLoginAsAdmin(){
    require('controllers/database.php');
    $enteredUsername = $_POST['loginUsername'];
    $enteredPassword = $_POST['loginPassword'];
    $peber = 'MaciejStopHackingUs';
    // this is supposed to influence the time of hashing - ask adam (already resoleved)
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

    // get the hashed password and the salt from db - admins table
    try{
        $stmt = $db->prepare('SELECT salt, password_hash, id_admins, first_name, last_name, username, email, admin_image_location, admin_image_name, privilege_level FROM admins 
                                                                                    WHERE username = :enteredName LIMIT 1');
        $stmt->bindValue('enteredName', $enteredUsername);
        $stmt->execute();
        $aaResult = $stmt->fetchAll();

        // echo '<br>this is the admin that matched the username';
        // print_r($aaResult);
        //if this is an empty array - there is no admin with that username 
        if(empty($aaResult)){
            // echo '<br>there is no admin with that username';
            header('location: login.php?username='.$enteredUsername.'&status=doesnt_exist');
        }else{ //if there is a match, verify whether the password matches
            $aResult = $aaResult[0];
            $sAdminSaltFromDb = $aResult['salt'];
            $sAdminPasswordFromDb = $aResult['password_hash'];
            $sAdminIdFromDb = $aResult['id_admins'];
            $sAdminFirstname = $aResult['first_name'];
            $sAdminLastname = $aResult['last_name'];
            $sAdminUsernameFromDb = $aResult['username'];
            $sAdminEmailFromDb = $aResult['email'];
            $sAdminImgLocationFromDb = $aResult['admin_image_location'];
            $sAdminImgNameFromDb = $aResult['admin_image_name'];
            $sAdminPrivilegeLevelFromDb = $aResult['privilege_level'];
            
            // echo '<br>This is the array we got'.print_r($aaUserSaltAndPass).'<br>';
            // echo '<br>This is the pass entered: '.$enteredPassword;
            // echo '<br>This is the pass from db: '.$sUserPasswordFromDb;
            // echo '<br>This is the salt: '.$sUserSaltFromDb;
            // echo '<br>This is the peber: '.$peber; 

            // check if the user is verified, if verified is 0 then redirect to login with status not verified
            

            $doTheyEqual = password_verify($enteredPassword.$peber.$sAdminSaltFromDb, $sAdminPasswordFromDb);
            // $registerPassword1.$peber.$salt, PASSWORD_DEFAULT, $options
            // echo '<br> do they equal: ';
            // print_r($doTheyEqual);

            // check if they equal 
            if($doTheyEqual == 1){

                // if password is correct start session, clean attempts and redirect to index
                session_start();
                $_SESSION['sessionId']=uniqid();
                $_SESSION['userId'] = $sAdminIdFromDb;
                $_SESSION['userUsername'] = $sAdminUsernameFromDb;
                $_SESSION['userEmail'] = $sAdminEmailFromDb;
                $_SESSION['userImgLocation'] = $sAdminImgLocationFromDb ;
                $_SESSION['userImgName'] = $sAdminImgNameFromDb;
                $_SESSION['userFirstname'] = $sAdminFirstname;
                $_SESSION['userLastname'] = $sAdminLastname;
                $_SESSION['userPrivileges'] = 'admin';
                if(isset($_SESSION['token'])){
                    unset($_SESSION['token']);
                }

                //CLEAR NUMBER OF ATTEMPTS FOR THIS USERNAME and IP
                try{
                    $sUpdate = $db->prepare( 'UPDATE logging_in SET attempts = :default WHERE ip = :ip && username = :username' );
                    $sUpdate->bindValue( ':default' , 0 );
                    $sUpdate->bindValue( ':ip' , $currentIp );
                    $sUpdate->bindValue( ':username' , $enteredUsername );
                    $sUpdate->execute();
                    // redirect to index
                    header('location: index.php');
                }catch( PDOException $ex ){
                echo $ex;
                }
               
                    
            }else{ //if the password is incorrect, redirect to login
                // echo 'incorrect password in admins';
                header('location: login.php?username='.$enteredUsername.'&status=doesnt_exist');
            }
        }
        
    }catch (PDOException $exception){
        echo $exception;
    }
    
}
// ------------------------------------ END OF LOGIN AS ADMIN FUNCTION --------------------------------------------------


// ------------------------------------ LOGIN FUNCTION that gets called in various places below ;) ------------------------------------
function tryToLogin(){
    require('controllers/database.php');
    $enteredUsername = $_POST['loginUsername'];
    $enteredPassword = $_POST['loginPassword'];
    $peber = 'MaciejStopHackingUs';
    // this is supposed to influence the time of hashing - ask adam (already resoleved)
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

    //get the hashed password and the salt from DB users table
    try{
        $stmt = $db->prepare('SELECT salt, password_hash, id_users, username, email, verified, user_image_location, user_image_name, banned FROM users 
                                                                                    WHERE username = :enteredName LIMIT 1');
        $stmt->bindValue('enteredName', $enteredUsername);
        $stmt->execute();
        $aaResult = $stmt->fetchAll();

        //if this is an empty array - there is no user with that username 
        if(empty($aaResult)){
            // echo '<br> trying to login as admin';
            tryLoginAsAdmin();
            // header('location: login.php?username='.$enteredUsername.'&status=doesnt_exist');
        }else{ //if there is a match, verify whether the password matches
            $aResult = $aaResult[0];
            $sUserSaltFromDb = $aResult['salt'];
            $sUserPasswordFromDb = $aResult['password_hash'];
            $sUserIdFromDb = $aResult['id_users'];
            $sUserUsernameFromDb = $aResult['username'];
            $sUserEmailFromDb = $aResult['email'];
            $sUserVerifiedFromDb = $aResult['verified'];
            $sUserImgLocationFromDb = $aResult['user_image_location'];
            $sUserImgNameFromDb = $aResult['user_image_name'];
            $bBanned = $aResult['banned'];
            
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
                    header('location: login.php?username='.$enteredUsername.'&status=not_verified');
                }else{
                    // check if the account is banned
                    if($bBanned == 1){
                        header('location: login.php?username='.$enteredUsername.'&status=banned');
                    }else{
                        // if its verified and not banned start session, clean attempts and redirect to index
                        session_start();
                        $_SESSION['sessionId']=uniqid();
                        $_SESSION['userId'] = $sUserIdFromDb;
                        $_SESSION['userUsername'] = $sUserUsernameFromDb;
                        $_SESSION['userEmail'] = $sUserEmailFromDb;
                        $_SESSION['userImgLocation'] = $sUserImgLocationFromDb ;
                        $_SESSION['userImgName'] = $sUserImgNameFromDb;
                        if(isset($_SESSION['token'])){
                            unset($_SESSION['token']);
                        }

                        //CLEAR NUMBER OF ATTEMPTS FOR THIS USERNAME and IP
                        try{
                            $sUpdate = $db->prepare( 'UPDATE logging_in SET attempts = :default WHERE ip = :ip && username = :username' );
                            $sUpdate->bindValue( ':default' , 0 );
                            $sUpdate->bindValue( ':ip' , $currentIp );
                            $sUpdate->bindValue( ':username' , $enteredUsername );
                            $sUpdate->execute();
                            // redirect to index
                            header('location: index.php');
                        }catch( PDOException $ex ){
                        echo $ex;
                        }
                    }
                }

                    
            }else{ //if the password is incorrect, redirect to login
                header('location: login.php?username='.$enteredUsername.'&status=doesnt_exist');
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
    $enteredUsername = htmlentities($_POST['loginUsername']);
    $enteredPassword = htmlentities($_POST['loginPassword']);
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


    // get an array of IPs with the same username that match from the database - logging in table
    try{
        $stmt = $db->prepare('SELECT * FROM logging_in 
                                        WHERE ip = :currentIP AND username = :enteredUsername');
        $stmt->bindValue('currentIP', $currentIp);
        $stmt->bindValue('enteredUsername', $enteredUsername);
        $stmt->execute();
        $aOfMatchedIPs = $stmt->fetchAll();
    }catch (PDOException $exception){
        echo $exception;
    }
    // echo 'This is the records IPs + username that match from the database: '.json_encode($aOfMatchedIPs).'<br>';



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
                                            WHERE ip = :currentIP AND username = :enteredUsername');
            $stmt->bindValue('currentIP', $currentIp);
            $stmt->bindValue( ':enteredUsername' , $enteredUsername );
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
                $sUpdate = $db->prepare( 'UPDATE logging_in SET attempts = :increment WHERE ip = :ip AND username = :enteredUsername' );
                $sUpdate->bindValue( ':increment' , ($attempt*1)+1 );
                $sUpdate->bindValue( ':ip' , $currentIp );
                $sUpdate->bindValue( ':enteredUsername' , $enteredUsername );
                $sUpdate->execute();
                // echo 'Incrementation done<br>';
                tryToLogin();
            }catch( PDOException $ex ){
                echo $ex;
            }
        }else{
            // IF THIS IS 4th TIME
            // still increment and save to database
            try{
                $sUpdate = $db->prepare( 'UPDATE logging_in SET attempts = :increment WHERE ip = :ip AND username = :enteredUsername' );
                $sUpdate->bindValue( ':increment' , ($attempt*1)+1 );
                $sUpdate->bindValue( ':ip' , $currentIp );
                $sUpdate->bindValue( ':enteredUsername' , $enteredUsername );
                $sUpdate->execute();
            }catch( PDOException $ex ){
                echo $ex;
            }


            // if RECAPTCHA IS SUBMITTED CHECK RESPONSE
            if ($_POST["g-recaptcha-response"]) {
                $response = $reCaptcha->verifyResponse(
                    $_SERVER["REMOTE_ADDR"],
                    $_POST["g-recaptcha-response"]
                );
            }
            
            // IF RESPONSE IS OKE TRY TO LOGIN
            if ($response != null && $response->success) {
                // echo '<br>trying to login 351';
                tryToLogin();
            }else{
                header('location: login.php?username='.$enteredUsername.'&status=wrong_captcha');
            }
            
            

            // -------------------------------------------------- IF THIS IS 15th TIME -------------------------------------------------
            // send an email to the admins
            if($attempt*1 >= 15){
            
                // first find the ID in USERS TABLE
                try{
                    $stmt = $db->prepare('SELECT id_users, email FROM users 
                                                    WHERE username = :enteredUsername');
                    $stmt->bindValue('enteredUsername', $enteredUsername);
                    $stmt->execute();
                    $aaMatchedUserProfileId = $stmt->fetchAll();
                }catch (PDOException $exception){
                    echo $exception;
                }
                // echo 'This is the ID of user that has been trying to log in for 15 times: '.json_encode($aaMatchedProfileId).'<br>';
                    if(empty($aaMatchedUserProfileId)){
                        // ------------------------- TRY TO MATCH ADMINS IF USERS ARE NOT MATCHED
                        try{
                            $stmt2 = $db->prepare('SELECT id_admins, email FROM admins 
                                                            WHERE username = :enteredUsername');
                            $stmt2->bindValue('enteredUsername', $enteredUsername);
                            $stmt2->execute();
                            $aaMatchedAdminProfileId = $stmt2->fetchAll();
                        }catch (PDOException $exception){
                            echo $exception;
                        }

                        if(empty($aaMatchedAdminProfileId)){
                            // IF NOTHING IS MATCHED FROM USERS OR ADMINS TABLE
                            header('location: login.php?username='.$enteredUsername.'&status=doesnt_exist');
                        }

                        $aMatchedAdminProfileId = $aaMatchedAdminProfileId[0];
                        $AdminProfileId = $aMatchedAdminProfileId['id_admins'];
                        // write logs into the db on which Admin did it
                        $AdminProfileEmail = $aMatchedAdminProfileId['email'];
                        $attack_description = 'Unsuccessful login 15 times or more from admin account';
                        date_default_timezone_set("UTC");
                        $time_of_attack = date('Y-m-d H:i:s');
                        try{
                            $stmt = $db->prepare('INSERT INTO security_logs VALUES ( :id_security_logs , :description_of_attack , :ip_address , :user_og_id, :time_of_attack)');
                            $id_security_logs = uniqid();
                            $stmt->bindValue(':id_security_logs', $id_security_logs);
                            $stmt->bindValue(':description_of_attack', $attack_description);
                            $stmt->bindValue(':ip_address', $currentIp);
                            $stmt->bindValue(':user_og_id', $AdminProfileId);
                            $stmt->bindValue(':time_of_attack', $time_of_attack);
                            $stmt->execute();
                        } catch (PDOException $exce){
                            echo $exce;
                        }
                        // send mail
                        require_once('send_email_potential_attack.php');

                }else{
                // ---------------------------------- IF USERS ARE MATCHED
                $aMatchedUserProfileId = $aaMatchedUserProfileId[0];
                // write logs into the db on which user did it
                $userProfileId = $aMatchedUserProfileId['id_users'];
                $userProfileEmail = $aMatchedUserProfileId['email'];
                $attack_description = 'Unsuccessful login 15 times or more from user account';
                date_default_timezone_set("UTC");
                $time_of_attack = date('Y-m-d H:i:s');
                try{
                    $stmt = $db->prepare('INSERT INTO security_logs VALUES ( :id_security_logs , :description_of_attack , :ip_address , :user_og_id, :time_of_attack)');
                    $id_security_logs = uniqid();
                    $stmt->bindValue(':id_security_logs', $id_security_logs);
                    $stmt->bindValue(':description_of_attack', $attack_description);
                    $stmt->bindValue(':ip_address', $currentIp);
                    $stmt->bindValue(':user_og_id', $userProfileId);
                    $stmt->bindValue(':time_of_attack', $time_of_attack);
                    $stmt->execute();
                } catch (PDOException $exce){
                    echo $exce;
                }

                // BAN THE MATCHED USER
                try{
                    $stmt3 = $db->prepare('UPDATE users SET banned = :banned WHERE id_users = :id_users');
                    $stmt3->bindValue(':banned', 1);
                    $stmt3->bindValue(':id_users', $userProfileId);
                    $stmt3->execute();
                } catch (PDOException $exce){
                    echo $exce;
                }

                require_once('send_email_potential_attack.php');
                }

            //end of 15th attempt 
            }

            


        }
 
    }

}else{
    // if form data wasnt passed to this page
    header('location: login.php?status=not_logged_in');   
}


?>