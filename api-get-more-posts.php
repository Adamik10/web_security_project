<?php
// the user doesn't have to be logged in to get more posts
// we need to know whether they are over 18 or not 
// if not logged in - counts as below 18
$overEighteen = false; 
if(isset($_SESSION['sessionId'])){
    $overEighteen = true;
}

//here we get the data from the AJAX function from jQuery
$listOfIds;
if(isset($_POST['kvcArray'])){
    $listOfIds = $_POST['kvcArray'];
}else{
    echo 'there was no data';
    //header('location: index.php') DO NOT ENABLE THIS - THEN LOAD MORE POSTS FAILS
    exit; 
}

// now we need to get all the IDs already loaded into one string from that array we got so we can use it in an SQL statement
// echo json_encode($listOfIds);
// echo count($listOfIds);
$clause = ''; 
for( $i = 0; $i < count($listOfIds); $i++ ){
    if($i != 0){
        $clause = $clause." AND posts.id_posts != ? ";
    }else{
        $clause = " ? ";
    } 
}
// echo $listOfIds[0]; 
// echo $clause;
 

require('controllers/database.php');
// we need - image of user, user's nickname, post headline, post picture location
try{
$stmt = $db->prepare('SELECT posts.id_posts, posts.headline, posts.image_location, posts.image_name, posts.datetime, users.username, users.user_image_location, users.user_image_name FROM posts 
INNER JOIN users ON posts.id_users = users.id_users 
WHERE posts.id_posts != '.$clause.' AND posts.banned = 0 
ORDER BY posts.datetime DESC LIMIT 5');
// die(json_encode($stmt));
// echo $clause;
$stmt->execute($listOfIds);
$aOfPosts = $stmt->fetchAll();
}catch (PDOException $exception){
    //echo $exception;
    exit;
}

//COMMENT COUNT
for($j = 0; $j < sizeof($aOfPosts); $j++){
    $currentPostId = $aOfPosts[$j]['id_posts'];

    // count comments for each post from db
    try{
    $stmt2 = $db->prepare(' SELECT COUNT(*) AS comments_count FROM comments WHERE id_posts = :currentPostId AND comments.banned = :banned');
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
    $aOfPosts[$j]['comment_count'] = $iCommentCount;
}

//UPVOTES COUNT
for($j = 0; $j < sizeof($aOfPosts); $j++){
    $currentPostId = $aOfPosts[$j]['id_posts'];

    // count upvotes for each post from db
    try{
    $stmt3 = $db->prepare(' SELECT COUNT(*) AS upvotes_count FROM upvotes WHERE id_posts = :currentPostId');
    $stmt3->bindValue(':currentPostId', $currentPostId);
    $stmt3->execute();
    $aaUpvoteCount = $stmt3->fetchAll();

    }catch (PDOException $ex){
        echo $ex;
    }


    $aUpvoteCount = $aaUpvoteCount[0];
    $iUpvoteCount = $aUpvoteCount['upvotes_count'];
    $aOfPosts[$j]['upvote_count'] = $iUpvoteCount;
}


echo json_encode($aOfPosts);
