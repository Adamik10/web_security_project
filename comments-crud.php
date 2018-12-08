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
?>

<?php 
require_once('components/top.php');
?>


<!-- TEMPLATE START -->
<div class="custom-container">

    <h3 class="mt-5 text-center">Comments crud</h3>
    <h5 class="mt-5 text-center">Post id: <?php echo $_GET['p_id']?></h3>
    <div class="table-responsive">
    <table class="table table-hover table-dark mt-5">
    <thead>
        <tr>
        <th scope="col">Post id</th>
        <th scope="col">User</th>
        <th scope="col">Comment</th>
        <th scope="col">Banned</th>
        <th scope="col">Edit</th>
        </tr>
    </thead>
    <tbody>

<?php 
// get the data from db to display
require('controllers/database.php');

try{
    $stmt = $db->prepare('SELECT users.username, comments.comment, comments.banned, comments.time_stamp FROM comments
    LEFT JOIN users ON comments.id_users = users.id_users
    ORDER BY comments.time_stamp DESC');
    $stmt->execute();
    $aaResult = $stmt->fetchAll();
}catch( PDOException $ex ){
    echo $ex;
    }
    // print_r($aaResult) ;
// DYNAMIC PART OF THE TEMPLATE
    foreach($aaResult as $iIndex => $aResult){
        // echo '<br>'.$aResult['headline'];
        echo '  <tr>
                <form class="posts-crud-form">
                <td><input type="text" class="posts-crud-input" name="txtPostIdCrud" value="'.$aResult['id_posts'].'" disabled></td>
                <td><input type="text" class="posts-crud-input" name="txtHeadlineCrud" value="'.$aResult['headline'].'" disabled></td>
                <td><div class="posts-crud-img" style="background-image: url('.$aResult['image_location'].')"></div></td>
                <td>'.$aResult['username'].'</td>
                <td><a href="comments-crud.php?p_id='.$aResult['id_posts'].'">'.$aResult['comments'].'</a></td>
                <td>'.$aResult['upvotes'].'</td>
                <td><input type="text" class="posts-crud-input" name="txtBannedCrud" value="'.$aResult['banned'].'" disabled></td>
                <td><button class="btnSaveChangesAdmin admin-page-input edit" type="submit"><i class="editIcon fas fa-edit"></i><i class="saveIcon fas fa-save"></i></button></td>
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