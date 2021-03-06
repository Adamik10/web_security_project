<?php $pageTitle = 'comments crud'?>
<?php 
session_start();
if(!isset($_SESSION['sessionId'])){
    // if someone not logged in tried going here
    header('location: login.php?status=not_logged_in');
    exit;
}
if(empty($_SESSION['userPrivileges']) && $_SESSION['userPrivileges'] != 'admin'){
    // if someone who is not an admin tries going here
    header('location: busted.php');
    exit;
}
if(!isset($_GET['p_id'])){
    // if post id is not passed
    header('location: posts-crud.php');
    exit;
}

$postId = urlencode($_GET['p_id']);
?>

<?php 
require_once('components/top.php');
?>


<!-- TEMPLATE START -->
<div class="custom-container mb-5">

    <h3 class="mt-5 text-center">Comments crud</h3>
    <h5 class="mt-5 text-center">Post id: <?php echo $postId?></h3>
    <div class="table-responsive">
    <table class="table table-hover table-dark mt-5">
    <thead>
        <tr>
        <th scope="col">Comment id</th>
        <th scope="col">User</th>
        <th scope="col">Comment</th>
        <th scope="col">Banned</th>
        <th scope="col">Edit</th>
        </tr>
    </thead>
    <tbody>

<?php 
// TOKENS
session_start();
//if user is logged in, or on login/register page this will generate a session token for them
$newToken = uniqid();
$newTokenHashed = hash('sha256', $newToken);
$_SESSION['token'] = $newToken;


// get the data from db to display
require('controllers/database.php');

try{
    $stmt = $db->prepare('SELECT users.user_image_location, comments.id_comments, comments.comment, comments.banned, comments.time_stamp FROM comments
    LEFT JOIN users ON comments.id_users = users.id_users
    WHERE comments.id_posts = :postId
    ORDER BY comments.time_stamp DESC');
    $stmt->bindValue('postId', $postId);
    $stmt->execute();
    $aaResult = $stmt->fetchAll();
}catch( PDOException $ex ){
    echo $ex;
    exit;
    }
    // print_r($aaResult) ;
// DYNAMIC PART OF THE TEMPLATE
    foreach($aaResult as $iIndex => $aResult){
        // echo '<br>'.$aResult['headline'];
        echo '  <tr>
                <form class="comments-crud-form">
                <input name="activityToken" type="text" value="'.$newTokenHashed.'" hidden>
                <td><input type="text" class="posts-crud-input" name="txtCommentsIdCrud" value="'.htmlentities($aResult['id_comments']).'" disabled></td>
                <td><div class="posts-crud-img" style="background-image: url('.htmlentities($aResult['user_image_location']).')"></div></td>
                <td>'.htmlentities($aResult['comment']).'</td>
                <td><input type="text" class="posts-crud-input" name="txtBannedCrudComments" value="'.htmlentities($aResult['banned']).'" disabled></td>
                <td><button class="btnSaveChangesAdminComments admin-page-input edit" type="submit"><i class="editIcon fas fa-edit"></i><i class="saveIcon fas fa-save"></i></button></td>
                </form>
                </tr>';
    }
// DYNAMIC PART END
?>


        
    </tbody>
    </table>
    </div>
</div>
<!-- TEMPLATE END -->



<?php 
require_once('components/bottom.php');
?>