<?php $pageTitle = '8gag'?>

<?php require_once('components/top.php');?>
<?php require_once('controllers/database.php');?>

<?php
// echo 'Current PHP version: ' . phpversion();
?>

<!-- ------------------------------------------ INDEX BODY ------------------------------------------ -->

<div class="container" id="postsContainer">

    <?php 
        // if(!empty($_SESSION['userEmail'])){
        // echo 'session is not empty';
        // print_r($_SESSION);
        // }
    ?>

    <?php 
        // we need - image of user, user's nickname, post headline, post picture location
        try{
        $stmt = $db->prepare('SELECT posts.id_posts, posts.headline, posts.image_location, posts.image_name, posts.datetime, posts.banned, users.username, users.user_image_location, users.user_image_name 
                                FROM posts INNER JOIN users ON posts.id_users = users.id_users WHERE posts.banned = 0 ORDER BY posts.datetime DESC LIMIT 5');
        $stmt->execute();
        $aOfPosts = $stmt->fetchAll();
        }catch (PDOException $exception){
            echo $exception;
            exit;
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
        $currentPostBanned = $aOfPosts[$j]['banned'];

        // count comments for each post from db
        try{
        $stmt2 = $db->prepare(' SELECT COUNT(*) AS comments_count
                                FROM comments
                                WHERE id_posts = :currentPostId AND comments.banned = :banned');
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

        echo '<div class="card align-self-center card-custom mt-5 mb-2 postHolder" id="'.$currentPostId.'">
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
                    <p class="clickable noUpvotes" data-post-id='.$currentPostId.'>'.$iUpvotesCount.' Upvotes</p>
                    <a href="gag.php?p_id='.$currentPostId.'#comment" class="card-link post-link">'.$iCommentCount.' Comments</a>
                </div>
                <div class="row mt-3">
                    <i class="clickable upvote far fa-hand-point-up fa-2x mr-3" data-id="'.$currentPostId.'"></i>
                    <a href="gag.php?p_id='.$currentPostId.'#comment"><i class="far fa-comment fa-2x"></i></a>
                </div>
            </div>
        </div>';
    }
    ?>

</div>
<button type='button' id='loadMorePostsButton'>Load more recent posts</button>

<?php
require_once('new-post.php');
?>

<!-- ------------------------------------------ INDEX BODY END ------------------------------------------ -->

<?php require_once('components/bottom.php');?>