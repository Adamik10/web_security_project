<?php $pageTitle = 'posts crud'?>
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
?>

<!-- TEMPLATE START -->
<div class="custom-container mb-5">

    <h3 class="mt-5 text-center">Posts crud</h3>
    <div class="table-responsive">
    <table class="table table-hover table-dark mt-5">
    <thead>
        <tr>
        <th scope="col">Id</th>
        <th scope="col">Headline</th>
        <th scope="col">Image</th>
        <th scope="col">OP</th>
        <th scope="col">Comments</th>
        <th scope="col">Upvotes</th>
        <th scope="col">Banned</th>
        <th scope="col">Edit</th>
        </tr>
    </thead>
    <tbody>

<?php 
// get the data from db to display
require('controllers/database.php');

try{
    $stmt = $db->prepare('SELECT posts.id_posts, posts.headline, posts.image_location, posts.image_name, posts.banned, users.username, COUNT(comments.id_posts) AS comments, COUNT(upvotes.id_posts) AS upvotes FROM posts
    LEFT JOIN users ON posts.id_users = users.id_users
    LEFT JOIN comments ON posts.id_posts = comments.id_posts
    LEFT JOIN upvotes ON posts.id_posts = upvotes.id_posts
    GROUP BY posts.id_posts');
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
                <td><input type="text" class="posts-crud-input" name="txtPostIdCrud" value="'.htmlentities($aResult['id_posts']).'" disabled></td>
                <td><input type="text" class="posts-crud-input" name="txtHeadlineCrud" value="'.htmlentities($aResult['headline']).'" disabled></td>
                <td><div class="posts-crud-img" style="background-image: url('.htmlentities($aResult['image_location']).')"></div></td>
                <td>'.htmlentities($aResult['username']).'</td>
                <td><a href="comments-crud.php?p_id='.htmlentities($aResult['id_posts']).'">'.htmlentities($aResult['comments']).'</a></td>
                <td>'.htmlentities($aResult['upvotes']).'</td>
                <td><input type="text" class="posts-crud-input" name="txtBannedCrud" value="'.htmlentities($aResult['banned']).'" disabled></td>
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