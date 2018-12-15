<?php $pageTitle = 'register'?>
<?php require_once('components/top.php');

session_start();
if(isset($_SESSION['sessionId'])){
    header('location: index.php');
    exit;
}
?>

<!-- ------------------------------------------ REGISTER BODY ------------------------------------------ -->

<div class="container"> 

    <!-- REGISTER FORM START -->
    <form id="frmRegister" method="post" action="register-save.php">


    <input name="byeBot" type="text" id="byeBot" hidden>
    
    <div class="form-group">
        <label for="registerUsername">Username*</label>
        <input name="registerUsername" type="text" class="form-control" id="registerUsername" placeholder="Enter username">
    </div>
    <div class="form-group">
        <label for="registerEmail">Email address*</label>
        <input name="registerEmail" type="email" class="form-control" id="registerEmail" aria-describedby="emailHelp" placeholder="Enter email">
    </div>
    <div class="form-group">
        <label for="registerPassword1">Password*</label>
        <input name="registerPassword1" type="password" class="form-control" id="registerPassword1" placeholder="Password">
        <small id="emailHelp" class="form-text text-muted">Password must have at least 8 characters, contain uppercase and lowercase letters and a number.</small>
    </div>
    <div class="form-group">
        <label for="registerPassword2">Repeat password*</label>
        <input name="registerPassword2" type="password" class="form-control" id="registerPassword2" placeholder="Password">
    </div>
    <div class="form-group form-check">
        <input name="registerCheckbox" type="checkbox" class="form-check-input" id="registerCheckbox">
        <label class="form-check-label" for="registerCheckbox">I agree to terms and conditions.</label>
    </div>
    
    <!-- DISPLAY ERROR MESSAGES HERE -->
    <?php
    if(isset($_GET['status'])){
        if ($_GET['status'] == 'already_exists'){
            echo '  <div>
                    <p class="login-error">Username or email already exists.</p>
                    </div>';
        }
        if ($_GET['status'] == 'password_not_matching'){
            echo '  <div>
                    <p class="login-error">Password and repeat password do not match.</p>
                    </div>';
        }
        if ($_GET['status'] == 'password_criteria'){
            echo '  <div>
                    <p class="login-error">Password must have at least 8 characters, contain uppercase and lowercase letters and a number.</p>
                    </div>';
        }
        if ($_GET['status'] == 'privacy_statement'){
            echo '  <div>
                    <p class="login-error">In order to register you must agree to privacy statements.</p>
                    </div>';
        }
        if ($_GET['status'] == 'username_length'){
            echo '  <div>
                    <p class="login-error">Username must be between 2 and 20 characters.</p>
                    </div>';
        }
        if ($_GET['status'] == 'email_pattern'){
            echo '  <div>
                    <p class="login-error">Email invalid.</p>
                    </div>';
        }
        if ($_GET['status'] == 'all_required'){
            echo '  <div>
                    <p class="login-error">All fields are required.</p>
                    </div>';
        }
    } 
    
    
    ?>
    <!-- ERROR MESSAGES END -->
    
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <!-- REGISTER FORM END -->


</div>

<!-- ------------------------------------------ REGISTER BODY END ------------------------------------------ -->

<?php require_once('components/bottom.php');?>