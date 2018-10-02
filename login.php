<?php $pageTitle = 'login'?>
<?php require_once('components/top.php');?>

<!-- ------------------------------------------ LOGIN BODY ------------------------------------------ -->

<div class="container">

<!-- LOGIN FORM START -->
<form id="frmLogin" method="post" action="login-verify.php">
    <div class="form-group">
        <label for="loginUsername">Username</label>
        <input name="loginUsername" type="text" class="form-control" id="loginUsername" placeholder="Enter username">
    </div>
    <div class="form-group">
        <label for="loginPassword1">Password</label>
        <input name="loginPassword1" type="password" class="form-control" id="loginPassword1" placeholder="Password">
    </div>
    <span><p>insert captcha</p></span>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>





<!-- LOGIN FORM END -->

</div>

<!-- ------------------------------------------ LOGIN BODY END ------------------------------------------ -->

<?php require_once('components/bottom.php');?>