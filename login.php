<?php $pageTitle = 'login'?>
<?php require_once('components/top.php'); ?>
<?php require_once("components/recaptchalib.php"); 

session_start();
if(isset($_SESSION['sessionId'])){
    header('location: index.php');
    exit;
}

?>

<!-- ------------------------------------------ LOGIN BODY ------------------------------------------ -->

<div class="container">

<!-- LOGIN FORM START -->
<form id="frmLogin" method="post" action="login-verify.php">
    <div class="form-group">
        <label for="loginUsername">Username</label>
        <input name="loginUsername" type="text" class="form-control" id="loginUsername" placeholder="Enter username">
    </div>
    <div class="form-group">
        <label for="loginPassword">Password</label>
        <input name="loginPassword" type="password" class="form-control" id="loginPassword" placeholder="Password">
    </div>


        <!-- DISPLAY CAPTCHA IF ITS MORE THAN 3rd ATTEMPT FROM SAME IP -->
        <?php
            //get username from url
        if(isset($_GET['username'])){
            $enteredUsername = $_GET['username'];

            // connect db
            require('controllers/database.php');
            // get the IP
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $currentIp = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $currentIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $currentIp = $_SERVER['REMOTE_ADDR'];
                }
            // check if that ip is in the db 
            try{ 
                $stmt = $db->prepare('SELECT ip, attempts FROM logging_in 
                                                            WHERE ip = :currentIp AND username = :enteredUsername LIMIT 1');
                $stmt->bindValue('currentIp', $currentIp);
                $stmt->bindValue('enteredUsername', $enteredUsername);
                $stmt->execute();
                $aaResult = $stmt->fetchAll();

                if(!empty($aaResult)){
                    $aResult = $aaResult[0];
                    $iAttempts = $aResult['attempts'];
                    // echo $iAttempts;

                    // If its 3 echo display captcha
                    if($iAttempts*1 !== 3){
                        
                    }else{
                        echo '<div class="g-recaptcha mb-3" data-sitekey="6LdDwHsUAAAAAGwUfBr7T46DGkBC6_ICkBFRLMdF"></div>';
                    }
                }
                

            }catch (PDOException $exception){
                echo $exception;
            }
        }
        // no username passed
            
            
        ?>

    <!-- DISPLAY ERROR MESSAGES HERE -->
    <?php
    if(isset($_GET['status'])){
        if ($_GET['status'] == 'doesnt_exist'){
            echo '  <div>
                    <p class="login-error">Username or password doesn´t exist.</p>
                    </div>';
        }
        if ($_GET['status'] == 'not_verified'){
            echo '  <div>
                    <p class="login-error">Please verify your account first.</p>
                    </div>';
        }
        if ($_GET['status'] == 'wrong_captcha'){
            echo '  <div>
                    <p class="login-error">Please check the recaptcha.</p>
                    </div>';
        }
        if ($_GET['status'] == 'not_logged_in'){
            echo '  <div>
                    <p class="login-error">Please login first.</p>
                    </div>';
        }
    } 
    ?>
    <!-- ERROR MESSAGES END -->

    <button type="submit" class="btn btn-primary">Submit</button>
    </form>

<!-- LOGIN FORM END -->

</div>

<!-- ------------------------------------------ LOGIN BODY END ------------------------------------------ -->

<?php require_once('components/bottom.php');?>