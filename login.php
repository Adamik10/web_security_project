<?php $pageTitle = 'login'?>
<?php require_once('components/top.php'); ?>
<?php require_once("components/recaptchalib.php"); ?>

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
                                                                WHERE ip = :currentIp LIMIT 1');
                $stmt->bindValue('currentIp', $currentIp);
                $stmt->execute();
                $aaResult = $stmt->fetchAll();

                if(!empty($aaResult)){
                    $aResult = $aaResult[0];
                    $iAttempts = $aResult['attempts'];
                    // echo $iAttempts;

                    // If its 3 echo display captcha
                    if($iAttempts*1 !== 3){
                        
                    }else{
                        echo '<div class="g-recaptcha mb-3" data-sitekey="6LcsjHUUAAAAACkdWpdCFL7XudBrpByJaCyMAiix"></div>';
                    }
                }
                

            }catch (PDOException $exception){
                echo $exception;
            }
            
        ?>

    <button type="submit" class="btn btn-primary">Submit</button>
    </form>

<!-- LOGIN FORM END -->

</div>

<!-- ------------------------------------------ LOGIN BODY END ------------------------------------------ -->

<?php require_once('components/bottom.php');?>