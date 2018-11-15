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
        $stmt = $db->prepare('SELECT posts.id_posts, posts.headline, posts.image_location, posts.image_name, users.username 
                                FROM posts INNER JOIN users ON posts.id_users = users.id_users 
                                WHERE posts.id_posts = :postId');
        $stmt->bindValue(':postId', $post_id);
        $stmt->execute();
        $aResult = $stmt->fetchAll();
    }catch (PDOException $exception){
        echo $exception;
    }

    // save all variables from result to display them in the template
    // print_r($aResult);
    foreach($aResult as $result){
        $currentPostId = $result['id_posts'];
        $currentPostHeadline = $result['headline'];
        $currentPostImageLocation = $result['image_location'];
        $currentPostImageName = $result['image_name'];
        $currentPostUsername = $result['username'];
        break;
    }
    
    // display html template with data
    echo '<div class="card align-self-center card-custom mt-2 mb-2">
    <div class="card-header">
        <div class="row">
            <div style="background-image: url('.$currentPostImageLocation.');" class="OP-img mr-3"></div>
            <a href="#">'.$currentPostUsername.'</a>
        </div>
    <div class="row mt-1">
        <a href="#" class="card-link post-link"># Upvotes</a>
        <a href="#comment" class="card-link post-link"># Comments</a>
    </div>
    </div>
    <h4 class="card-title mt-1">'.$currentPostHeadline.'</h4>
    <img class="card-img-top" src="'.$currentPostImageLocation.'" alt="'.$currentPostImageName.'">
    
    <h6 class="card-title mt-3"># comments</h6>
    
    </div>';


   
}else{
    // redirect to index because p_id wasn´t passed to this page
    header('location: index.php');
}?>

<!-- WRITE COMMENT -->
<form class="container container-custom align-self-center mt-2 mb-5">
        <div class="row row-custom">
            <div class="col-2 col-custom">
                <div style="background-image: url(data:image/jpeg;base64,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)" id="comment-user-img"></div>
            </div>
            <div class="col-10 col-custom">
                <textarea class="form-control" aria-label="With textarea" placeholder="Write a comment..."></textarea>
                <button type="submit" class="btn btn-info ml-auto">Post</button>
            </div>
        </div> 
</form>
<!-- WRITE COMMENT END -->


<!-- DISPLAY COMMENTS TEMPLATE START -->
<div class="container container-custom align-self-center mt-2 mb-2">
        <div class="row row-custom">
            <div class="col-2 col-custom">
                <div style="background-image: url(data:image/jpeg;base64,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)" id="comment-user-img"></div>
            </div>
            <div class="col-10 col-custom">
                <h6>Katkabobe</h6>
                <p>This is my comment bobe.</p>
            </div>
        </div> 
</div>
<!-- DISPLAY COMMENTS TEMPLATE END -->

<?php  require_once('components/bottom.php');?>



<!-- TEMPLATE START -->
    <!-- <div class="card align-self-center card-custom mt-2 mb-2">
        <div class="card-header">
        <div class="row">
            <div class="mr-3">img</div>
            <a href="#">OP´s name</a>
        </div>
        <div class="row mt-1">
            <a href="#" class="card-link post-link"># Upvotes</a>
            <a href="#comment" class="card-link post-link"># Comments</a>
        </div>
        </div>
        <h4 class="card-title mt-1">Wise words.</h4>
        <img class="card-img-top" src="https://jolicode.com/media/original/2017/password.png" alt="Card image cap">
        
        <h6 class="card-title mt-3"># comments</h6>
        
    </div> -->
<!-- TEMPLATE END -->