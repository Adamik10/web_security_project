<?php $pageTitle = 'profile'?>
<?php 
require_once('components/top.php');

?>

<div class="container">

    <div class="card align-self-center card-custom mt-2 mb-2" style="border:0px solid white;">
    <h2 class="text-center mt-5">Profile</h2>

     <form class="py-3" action="whatever.php" method="post" enctype="multipart/form-data">
     
        <div class="form-group"> 
            <label for="inputFile" class="mt-4">
            <div id="uploadImgThumbnail" class="float" style="background-image: url(images/profile.jpg)"></div>
            <div class="float ml-4 mt-2">
            <label for="inputFile">Click to upload a new profile picture</label>
            <input type="file" class="form-control-file align-self-center justify-content-center mx-auto" id="inputFile" name="fileToUpload">
            </div>  
            </label>
        </div>

        <div class="form-group">
        <label for="registerUsername">Username</label>
        <input name="registerUsername" type="text" class="form-control" id="registerUsername" placeholder="Enter username">
    </div>
    <div class="form-group">
        <label for="registerEmail">Email address</label>
        <input name="registerEmail" type="email" class="form-control" id="registerEmail" aria-describedby="emailHelp" placeholder="Enter email">
    </div>
    <div class="form-group">
        <label for="registerPassword1">Password</label>
        <input name="registerPassword1" type="password" class="form-control" id="registerPassword1" placeholder="Password">
    </div>
    <div class="form-group">
        <label for="registerPassword2">Repeat password</label>
        <input name="registerPassword2" type="password" class="form-control" id="registerPassword2" placeholder="Password">
    </div>
    
    <button type="submit" class="btn btn-primary">Save changes</button>

      </form>

    </div>

</div>






<?php
require_once('components/bottom.php');
?>