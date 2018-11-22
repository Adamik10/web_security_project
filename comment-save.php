<?php
require_once("controllers/database.php");
require_once("components/top.php");

// check if user is logged in
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}

// now we know that the person trying to post is logged in 
// but we still don't know whether it's the real person or just someone using their session ID
// a token was created if the real person wanted to post something - otherwise there is no token
if(!isset($_SESSION['token'])){
    //echo 'The token is not set';
    session_destroy();
    header('location: login.php?status=security_logout');
}else{
    // if there is a token, now we can destroy it
    unset($_SESSION['token']);
}

// check if data was passed through the form
if(isset($_POST['postNewComment']) && !empty($_POST['postNewComment'])
){
    // echo 'yeah boi';
    // store post variables
    $newComment = htmlentities($_POST['postNewComment']);
    $postId = $_POST['postId'];
    // store user id and token from session
    $loggedInUserId = $_SESSION['userId'];
    $newCommentId = uniqid();
    
    // echo "comment: ".$newComment;
    // echo "<br> post id: ".$postId;
    // echo "<br> user id of logged in user: ".$sUserIdFromDb;

    try {
        $stmt1 = $db->prepare('INSERT INTO comments (id_comments, id_posts, id_users, comment) 
                                VALUES (:commentId, :postId, :userId, :newComment)');
        $stmt1->bindValue(':commentId', $newCommentId);
        $stmt1->bindValue(':postId', $postId);
        $stmt1->bindValue(':userId', $loggedInUserId);
        $stmt1->bindValue(':newComment', $newComment);
        $stmt1->execute();

    } catch (PDOException $ex) {
        echo 'error, database insertion<br>';
        echo $ex;
        exit();
    }

    header("location: gag.php?p_id={$postId}");

    
}else{
    // post variables werent passed throught the form so redirect to the index
    header('location: index.php');
}