<?php $pageTitle = 'profile'?>
<?php 
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}
?>

<?php 
require_once('components/top.php');
require_once('controllers/database.php');

//select user that is logged in 

$sUserIdFromDb = $_SESSION['userId'];

try{
    $stmt = $db->prepare('SELECT * FROM users WHERE id_users = :loggedInUserId');
    $stmt->bindValue(':loggedInUserId', $sUserIdFromDb);
    $stmt->execute();
    $user = $stmt->fetchAll();
} catch(PDOException $ex){
    echo 'error getting user data';
    exit();
}

//get username and email from the array so we can put them in the input values

foreach($user as $a){
    $username = $a['username'];
    $email = $a['email'];
    $userImageLocation = $a['image_location'];
}
?>


    <div class="container mb-5">

    <div class="card align-self-center card-custom mt-2 mb-2" style="border:0px solid white;">
    <h2 class="text-center mt-4">Profile</h2>

    <form class="py-3" action="edit-profile.php" method="post" enctype="multipart/form-data">
    
        <div class="form-group"> 
            <label for="inputFile" class="mt-4">
            <div id="uploadImgThumbnail" class="float">
                <img src="<?php if ($userImageLocation == NULL){echo 'images/profile.jpg';}else{echo $userImageLocation;} ?>" id="uploadImg" class="img-fluid d-block align-self-center justify-content-center responsive"/>
            </div>
            <div class="float ml-4 mt-2">
                <label for="inputFile">Click to upload a new profile picture</label>
                <input type="file" class="form-control-file align-self-center justify-content-center mx-auto" id="profileImgFile" name="profileImgFile">
            
                <?php 
                // DISPLAY ERROR MESSAGES HERE
                if(isset($_GET['status'])){
                    if ($_GET['status'] == 'file_too_large'){
                        echo '  <div>
                                <br>
                                <p class="login-error">The file is too large.</p>
                                </div>';
                    }
                } 
                // ERROR MESSAGES END
                ?>
            
            </div>  
            </label>
        </div>

    <h5 class="mt-4">Account</h5>

        <div class="form-group">
        <label for="changedUsername">Username</label>
        <input name="changedUsername" type="text" class="form-control" value="<?php echo $username; ?>">
    </div>
    <div class="form-group">
        <label for="changedEmail">Email address</label>
        <input name="changedEmail" type="email" class="form-control" aria-describedby="emailHelp" value="<?php echo $email; ?>">
    </div>

    <button type="submit" class="btn btn-primary">Save changes</button>
    </form>

    <form action="edit-profile.php" method="post"> 

    <h5 class="mt-4">Change password</h5>

    <div class="form-group">
        <label for="changedPassword1">Password</label>
        <input name="changedPassword1" type="password" class="form-control" placeholder="New password">
    </div>
    <div class="form-group">
        <label for="changedPassword2">Repeat password</label>
        <input name="changedPassword2" type="password" class="form-control" placeholder="Repeat new password">
    </div>

    <button type="submit" class="btn btn-primary">Save changes</button>

    </form>

        <a class="mt-5" href="delete-account.php">Delete my account</a>

    </div>

    </div>

    <?php
    require_once('components/bottom.php');
    ?>








