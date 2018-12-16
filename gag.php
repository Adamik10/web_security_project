<?php $pageTitle = '8gag'?>

<?php
// only display content if you get a post id from url
if(isset($_GET['p_id'])){
    // require database and top
    require_once('components/top.php');
    require_once('controllers/database.php');
    
    // save post id from url
    $post_id = $_GET['p_id'];

    // get all the data from database - name of OP, img of OP, post headline, post imageURL, post image name 
    try{
        $stmt = $db->prepare('SELECT posts.id_posts, posts.headline, posts.image_location, posts.image_name, users.username, users.user_image_location, users.user_image_name 
                                FROM posts INNER JOIN users ON posts.id_users = users.id_users 
                                WHERE posts.id_posts = :postId');
        $stmt->bindValue(':postId', $post_id);
        $stmt->execute();
        $aResult = $stmt->fetchAll();
    }catch (PDOException $exception){
        echo $exception;
        exit;
    }

    // save all variables from result to display them in the template
    // print_r($aResult);
    $result = $aResult[0];
    $currentPostId = $result['id_posts'];
    $currentPostHeadline = $result['headline'];
    $currentPostImageLocation = $result['image_location'];
    $currentPostImageName = $result['image_name'];
    $currentPostUsername = $result['username'];
    $currentUserImgLocation = $result['user_image_location'];
    $currentUserImgName = $result['user_image_name'];

    // count comments for each post from db
    try{
    $stmt2 = $db->prepare(' SELECT COUNT(*) AS comments_count
                            FROM comments
                            WHERE id_posts = :currentPostId AND comments.banned = :banned' );
    $stmt2->bindValue(':currentPostId', $currentPostId);
    $stmt2->bindValue(':banned', 0);
    $stmt2->execute();
    $aaCommentCount = $stmt2->fetchAll();

    }catch (PDOException $ex){
        echo $ex;
        exit;
    }

    $aCommentCount = $aaCommentCount[0];
    $iCommentCount = $aCommentCount['comments_count'];

    // count upvotes for each post from db
    try{
        $stmt2 = $db->prepare(' SELECT COUNT(*) AS upvotes_count
                                FROM upvotes
                                WHERE id_posts = :currentPostId');
        $stmt2->bindValue(':currentPostId', $currentPostId);
        $stmt2->execute();
        $aaUpvotesCount = $stmt2->fetchAll();

        }catch (PDOException $ex){
            echo $ex;
            exit;
        }
    $aUpvotesCount = $aaUpvotesCount[0];
    $iUpvotesCount = $aUpvotesCount['upvotes_count'];

    echo '<div class="card align-self-center card-custom mt-5 mb-2" id="'.htmlentities($currentPostId).'">
        <div class="card-header">
            <div class="row">
            <div style="background-image: url('.htmlentities($currentUserImgLocation).');" class="OP-img mr-3"></div>
            <a href="#">'.htmlentities($currentPostUsername).'</a>
            </div>
        </div>
        <h4 class="card-title mt-1">'.htmlentities($currentPostHeadline).'</h4>
        <a href="gag.php?p_id='.htmlentities($currentPostId).'"><img class="card-img-top" src="'.htmlentities($currentPostImageLocation).'" alt="'.htmlentities($currentPostImageName).'"></a>
        <div class="card-body">
            <div class="row">
                <a href="gag.php?p_id='.htmlentities($currentPostId).'" class="card-link post-link upvote noUpvotes" data-post-id="'.htmlentities($currentPostId).'">'.htmlentities($iUpvotesCount).' Upvotes</a>
                <a href="gag.php?p_id='.htmlentities($currentPostId).'#comment" class="card-link post-link">'.htmlentities($iCommentCount).' Comments</a>
            </div>
            <div class="row mt-3">
                <i class="clickable upvote far fa-hand-point-up fa-2x mr-3" data-id="'.$currentPostId.'"></i>
                <a href="gag.php?p_id='.htmlentities($currentPostId).'#comment"><i class="far fa-comment fa-2x"></i></a>
            </div>
        </div>
    </div>';
    

    // echo html for posting a comment only if you are logged in
    if(!empty($_SESSION['userId'])){
        $loggedInUserImgLocation = $_SESSION['userImgLocation'];

        echo '<!-- WRITE COMMENT -->
        <form class="container container-custom align-self-center mt-2 mb-5" method="post" action="comment-save.php">
                <div class="row row-custom">
                    <div class="col-2 col-custom">
                        <div style="background-image: url('.htmlentities($loggedInUserImgLocation).')" id="comment-user-img"></div>
                    </div>
                    <div class="col-10 col-custom">
                        <input name="postId" type="text" value="'.htmlentities($currentPostId).'" hidden>
                        ';
                        require_once('api-set-token.php');
                        echo '<textarea class="form-control" name="postNewComment" aria-label="With textarea" placeholder="Write a comment..."></textarea>
                        <button type="submit" class="btn btn-info ml-auto" id="commentSubmitButton">Post</button>
                    </div>
                </div> 
        </form>
        <!-- WRITE COMMENT END -->';
    
    }

    // ECHO ERROR MESSAGE FOR TOO LONG COMMENT
    if(isset($_GET['status'])){
        if ($_GET['status'] == 'wrong'){
            echo '  <div>
                    <p class="login-error text-center">Your comment can only be 300 characters long.</p>
                    </div>';
        }
    }

    // get all necessary for displaying comments from db (only not banned comments)
    try{
        $stmt = $db->prepare('SELECT comments.id_comments, comments.comment, users.username, users.user_image_location, users.user_image_name 
                                FROM comments INNER JOIN users ON comments.id_users = users.id_users WHERE comments.id_posts = :postId AND comments.banned = :banned ORDER BY comments.time_stamp DESC LIMIT 10');
        $stmt->bindValue(':banned', 0);
        $stmt->bindValue(':postId', $post_id);
        $stmt->execute();
        $aResult2 = $stmt->fetchAll();
    }catch (PDOException $exception){
        echo $exception;
        exit;
    }

    // save data from db to variables and echo them in the template
    for($i = 0; $i < sizeof($aResult2); $i++){
        $commentId = $aResult2[$i]['id_comments'];
        $comment = $aResult2[$i]['comment'];
        $username = $aResult2[$i]['username'];
        $imageLocation = $aResult2[$i]['user_image_location'];
        $imageName = $aResult2[$i]['user_image_name'];

        echo '<!-- DISPLAY COMMENTS TEMPLATE START -->
        <div class="container container-custom align-self-center mt-2 mb-2">
                <div class="row row-custom">
                    <div class="col-2 col-custom">
                        <div style="background-image: url('.htmlentities($imageLocation).')" id="comment-user-img"></div>
                    </div>
                    <div class="col-10 col-custom">
                        <h6>'.htmlentities($username).'</h6>
                        <p>'.htmlentities($comment).'</p>
                    </div>
                </div> 
        </div>
        <!-- DISPLAY COMMENTS TEMPLATE END -->';
    }

   
}else{
    // redirect to index because p_id wasn´t passed to this page
    header('location: index.php');
    exit;
}?>


<!-- DISPLAY COMMENTS TEMPLATE START -->
<!-- <div class="container container-custom align-self-center mt-2 mb-2">
        <div class="row row-custom">
            <div class="col-2 col-custom">
                <div style="background-image: url(http://www.eindhovenstartups.com/wp-content/uploads/2016/08/blank_male_avatar.jpg)" id="comment-user-img"></div>
            </div>
            <div class="col-10 col-custom">
                <h6>Katkabobe</h6>
                <p>This is my comment bobe.</p>
            </div>
        </div> 
</div> -->
<!-- DISPLAY COMMENTS TEMPLATE END -->

<?php
require_once('new-post.php');
?>

<?php  require_once('components/bottom.php');?>