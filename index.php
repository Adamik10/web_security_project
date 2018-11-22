<?php $pageTitle = '8gag'?>

<?php require_once('components/top.php');?>
<?php require_once('controllers/database.php');?>

<?php
// echo 'Current PHP version: ' . phpversion();
?>

<!-- ------------------------------------------ INDEX BODY ------------------------------------------ -->

<div class="container">

    <?php 
        // if(!empty($_SESSION['userEmail'])){
        // echo 'session is not empty';
        // print_r($_SESSION);
        // }
    ?>

    <?php
        require('controllers/database.php');
        // we need - image of user, user's nickname, post headline, post picture location
        try{
        $stmt = $db->prepare('SELECT posts.id_posts, posts.headline, posts.image_location, posts.image_name, posts.datetime, users.username, users.user_image_location, users.user_image_name 
                                FROM posts INNER JOIN users ON posts.id_users = users.id_users ORDER BY posts.datetime DESC LIMIT 5');
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
        $currentUserImgLocation = $aOfPosts[$j]['user_image_location'];
        $currentUserImgName = $aOfPosts[$j]['user_image_name'];

        // count comments for each post from db
        try{
        $stmt2 = $db->prepare(' SELECT COUNT(*) AS comments_count
                                FROM comments
                                WHERE id_posts = :currentPostId');
        $stmt2->bindValue(':currentPostId', $currentPostId);
        $stmt2->execute();
        $aaCommentCount = $stmt2->fetchAll();
    
        }catch (PDOException $ex){
            echo $ex;
        }

        $aCommentCount = $aaCommentCount[0];
        $iCommentCount = $aCommentCount['comments_count'];

        // if there is no profile img echo default profile image else echo the profile img from db
        if($currentUserImgLocation == NULL){
            echo '<div class="card align-self-center card-custom mt-5 mb-2" id="'.$currentPostId.'">
            <div class="card-header">
                <div class="row">
                <div style="background-image: url(images/profile.jpg);" class="OP-img mr-3"></div>
                <a href="#">'.$currentPostUsername.'</a>
                </div>
            </div>
            <h4 class="card-title mt-1">'.$currentPostHeadline.'</h4>
            <a href="gag.php?p_id='.$currentPostId.'"><img class="card-img-top" src="'.$currentPostImageLocation.'" alt="'.$currentPostImageName.'"></a>
            <div class="card-body">
                <div class="row">
                    <a href="gag.php?p_id='.$currentPostId.'" class="card-link post-link"># Upvotes</a>
                    <a href="gag.php?p_id='.$currentPostId.'#comment" class="card-link post-link">'.$iCommentCount.' Comments</a>
                </div>
                <div class="row mt-3">
                    <a href="#"><i class="far fa-hand-point-up fa-2x mr-3"></i></a>
                    <a href="gag.php?p_id='.$currentPostId.'#comment"><i class="far fa-comment fa-2x"></i></a>
                </div>
            </div>
        </div>';
        }else{
            echo '<div class="card align-self-center card-custom mt-5 mb-2" id="'.$currentPostId.'">
            <div class="card-header">
                <div class="row">
                <div style="background-image: url('.$currentUserImgLocation.');" class="OP-img mr-3"></div>
                <a href="#">'.$currentPostUsername.'</a>
                </div>
            </div>
            <h4 class="card-title mt-1">'.$currentPostHeadline.'</h4>
            <a href="gag.php?p_id='.$currentPostId.'"><img class="card-img-top" src="'.$currentPostImageLocation.'" alt="'.$currentPostImageName.'"></a>
            <div class="card-body">
                <div class="row">
                    <a href="gag.php?p_id='.$currentPostId.'" class="card-link post-link"># Upvotes</a>
                    <a href="gag.php?p_id='.$currentPostId.'#comment" class="card-link post-link">'.$iCommentCount.' Comments</a>
                </div>
                <div class="row mt-3">
                    <a href="#"><i class="far fa-hand-point-up fa-2x mr-3"></i></a>
                    <a href="gag.php?p_id='.$currentPostId.'#comment"><i class="far fa-comment fa-2x"></i></a>
                </div>
            </div>
        </div>';
        }
        
    }
    ?>

    <button type='button' id='loadMorePostsButton'>Load more recent posts</button>
</div>


<!-- NEW POST POSTING START -->

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
            <span id="naughtyCheckboxSpan"><input type="checkbox" id="naughtyCheckbox" name="postSensitive">This post has sensitive content.<br><span>
            <button type="submit" class="btn btn-primary littleExtraSpaceTop" id="postSubmitButton" >Post</button>
            <?php
                if(isset($_GET['status'])){
                    if($_GET['status'] == 'post_invalid'){
                        echo '<div class="error_message">Sorry mate, something went wrong with your post. 
                        Make sure to upload a valid picture file and write a headline for your masterpiece.</div>';
                    }else if($_GET['status'] == 'file_too_large'){
                       echo '<div class="error_message">That was a really big file champ. Try cutting down a bit and then we can post it.</div>';
                    }else if($_GET['status'] == 'error_uploading_image'){
                        echo '<div class="error_message">Unfortunately, there were some shinanigans going on uploading your post image. 
                        Please try it again ..and cross your fingers this time.</div>';
                    }
                }
            ?>
        </form>
</div>
<!-- NEW POST POSTING END -->

<!-- ------------------------------------------ INDEX BODY END ------------------------------------------ -->

<?php require_once('components/bottom.php');?>