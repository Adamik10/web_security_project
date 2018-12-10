<?php $pageTitle = 'users crud'?>
<?php 
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}
if(empty($_SESSION['userPrivileges']) && $_SESSION['userPrivileges'] != 'admin'){
    header('location: busted.php');
    exit;
}
?>

<?php 
require_once('components/top.php');
require_once('controllers/database.php');

try{
    $stmt = $db->prepare('SELECT * FROM users');
    $stmt->execute();
    $users = $stmt->fetchAll();
} catch (PDOException $ex){
    echo 'error selecting users: '.$ex;
    header('location: profile.php'); //in this case we can redirect and exit because if we didn't it could affect the database
    exit();
}

?>


<!-- TEMPLATE -->
<div class="custom-container">

    <h3 class="mt-5 text-center">Users crud</h3>
    <div class="table-responsive">
    <table class="table table-hover table-dark mt-5">
    <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Password</th>
            <th scope="col">Profile picture</th>
            <th scope="col">Banned</th>
            <th scope="col">Verified</th>     
        </tr>
    </thead>
    <tbody>
    <?php 

    foreach($users as $user){
        $uId = $user['id_users'];
        $uUsername = $user['username'];
        $uEmail = $user['email'];
        $uImageLocation = $user['user_image_location'];  

        if ($userImageLocation == NULL){
            $userImageLocation = 'images/users/default.png';
        } else {
            $uImageLocation = $user['user_image_location'];  
        }

        $uBanned = $user['banned'];
        $uVerified = $user['verified'];

        echo "
        <tr>
        <form class='posts-crud-form'>
            <td><input type='text' class='posts-crud-input' name='txtUserId' value='".$uId."' disabled></td>
            <td><input type='text' class='posts-crud-input' name='txtUsernameCrud' value='".$uUsername."' disabled></td>
            <td><input type='text' class='posts-crud-input' name='txtEmailCrud' value='".$uEmail."' disabled></td>
            <td><input type='password' class='posts-crud-input' name='txtPasswordCrud' value='password' disabled></td>

            <td>
                <div id='uploadImgThumbnailUserCrud' class='float'>
                    <img src=".$uImageLocation." 
                    id='uploadImgUsersCrud' class='users-crud-img img-fluid d-block align-self-center justify-content-center responsive'/>
                </div>
            </td>
            <td><input type='text' class='posts-crud-input' name='txtBannedCrud' value='".$uBanned."' disabled></td>
            <td><input type='text' class='posts-crud-input' name='txtVerifiedCrud' value='".$uVerified."' disabled></td>
            <td><button class='btnSaveChangesAdmin admin-page-input edit' type='submit'><i class='editIcon fas fa-edit'></i><i class='saveIcon fas fa-save'></i></button></td>
            <td><button class='btnSaveChangesAdmin admin-page-input' type='submit'><i class='editIcon fas fa-trash'></i></button></td>
        </form>
        </tr>       
        ";
    }
    ?>    
    </tbody>
    </table>
    </div>
</div>
<!-- TEMPLATE END -->



<?php 
require_once('components/bottom.php');
?>