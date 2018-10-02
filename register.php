<?php $pageTitle = 'register'?>
<?php require_once('components/top.php');?>

<!-- ------------------------------------------ REGISTER BODY ------------------------------------------ -->

<div class="container">

    <!-- REGISTER FORM START -->
    <form id="frmRegister" method="post" action="register-save.php">
    <div class="form-group">
        <label for="registerFirstName">First name</label>
        <input name="registerFirstName" type="text" class="form-control" id="registerFirstName" placeholder="Enter first name">
    </div>
    <div class="form-group">
        <label for="registerLastName">Last name</label>
        <input name="registerLastName" type="text" class="form-control" id="registerLastName" placeholder="Enter last name">
    </div>
    <div class="form-group">
        <label for="registerUsername">Username</label>
        <input name="registerUsername" type="text" class="form-control" id="registerUsername" placeholder="Enter username">
    </div>
    <div class="form-group">
        <label for="registerEmail">Email address</label>
        <input name="registerEmail" type="email" class="form-control" id="registerEmail" aria-describedby="emailHelp" placeholder="Enter email">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group">
        <label for="registerPassword1">Password</label>
        <input name="registerPassword1" type="password" class="form-control" id="registerPassword1" placeholder="Password">
    </div>
    <div class="form-group">
        <label for="registerPassword2">Repeat password</label>
        <input name="registerPassword2" type="password" class="form-control" id="registerPassword2" placeholder="Password">
    </div>
    <div class="form-group form-check">
        <input name="registerCheckbox" type="checkbox" class="form-check-input" id="registerCheckbox">
        <label class="form-check-label" for="registerCheckbox">I agree to privacy statements.</label>
    </div>
    <span><p>insert captcha</p></span>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <!-- REGISTER FORM END -->

</div>

<!-- ------------------------------------------ REGISTER BODY END ------------------------------------------ -->

<?php require_once('components/bottom.php');?>