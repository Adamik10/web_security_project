<?php $pageTitle = '8gag'?>

<?php require_once('components/top.php');?>
<?php require_once('controllers/database.php');?>

<?php
echo 'Current PHP version: ' . phpversion();
?>

<!-- ------------------------------------------ INDEX BODY ------------------------------------------ -->

<div class="container">

    <?php 
        if(!empty($_SESSION['userEmail'])){
        echo 'session is not empty';
        print_r($_SESSION);
        }
    ?>

    <!-- TEMPLATE START -->
    <div class="card align-self-center card-custom mt-2 mb-2">
        <div class="row ml-3 mr-3 mt-1">
            <div class="mr-3">img</div>
            <a href="#">OP´s name</a>
        </div>
        <h4 class="card-title mr-3 ml-3 mt-3">Wise words.</h4>
        <img class="card-img-top" src="https://jolicode.com/media/original/2017/password.png" alt="Card image cap">
        <div class="card-body">
            <a href="#" class="btn btn-primary">Upvote</a>
            <a href="#" class="btn btn-primary">Comment</a>
        </div>
    </div>
    <!-- TEMPLATE END -->
    <?php
        require('controllers/database.php');
        // we need - image of user, user's nickname, post headline, post picture location
        try{
        $stmt = $db->prepare('SELECT posts.id_posts, posts.headline, posts.image_location, posts.image_name, users.username 
                                FROM posts INNER JOIN users ON posts.id_users = users.id_users LIMIT 5');
        $stmt->execute();
        $aOfPosts = $stmt->fetchAll();
    }catch (PDOException $exception){
        echo $exception;
    }
    // echo 'These are the posts from the database: '.json_encode($aOfPosts).'<br>';

    for($j = 0; $j < sizeof($aOfPosts); $j++){
        $currentPostId = $aOfPosts[$j]['id_posts'];
        $currentPostHeadline = $aOfPosts[$j]['headline'];
        $currentPostImageLocation = $aOfPosts[$j]['image_location'];
        $currentPostImageName = $aOfPosts[$j]['image_name'];
        $currentPostUsername = $aOfPosts[$j]['username'];

        echo '<div class="card align-self-center card-custom mt-2 mb-2" id="'.$currentPostId.'">
            <div class="row ml-3 mr-3 mt-1">
                <div class="mr-3">img</div>
                <a href="#">'.$currentPostUsername.'</a>
            </div>
            <h4 class="card-title mr-3 ml-3 mt-3">'.$currentPostHeadline.'</h4>
            <img class="card-img-top" src="'.$currentPostImageLocation.'" alt="'.$currentPostImageName.'">
            <div class="card-body">
                <a href="#" class="btn btn-primary">Upvote</a>
                <a href="#" class="btn btn-primary">Comment</a>
            </div>
        </div>';
    }
    ?>

</div>

<div id='screenBlind'>
</div>

<div id='uploadBox'>
        <div id="posting_header">
            <span id="closePostingPopup"><i class="fas fa-window-close"></i></span>
            <h3>Upload your post</h3>
            <p>Select a file you wish to upload and choose a headline.<br><br></p>
        </div>
        <form action="post-upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="postFile" onchange="readUrl(this)">
            <div id='preview-image-placeholder'>
                <img id="preview-image" src="">
            </div>
            <textarea type="text" name='postHeader' placeholder="Post headline up to 280 characters." rows="5" cols="40"></textarea>
            <input id="naughtyCheckbox" type="checkbox" name="postSensitive">This post has sensitive content.<br>
            <button type="submit" class="btn btn-primary littleExtraSpaceTop" >Post</button>
        </form>
</div>

<!-- ------------------------------------------ INDEX BODY END ------------------------------------------ -->

<?php require_once('components/bottom.php');?>