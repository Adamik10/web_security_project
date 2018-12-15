<?php

require_once("controllers/database.php");

// check if user is logged in
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}

// now we know that the person trying to post is logged in 
// but we still don't know whether it's the real person or just someone using their session
// a token was created if the real person wanted to do this - does it match what we got?
if(!isset($_SESSION['token']) || !isset($_POST['activityToken'])){
    //echo 'The token is not set';
    header('location: ups.php');
    exit;
}else{
    // if there is a token, compare it to the one we got from the form
    if (hash('sha256', $_SESSION['token']) != $_POST['activityToken']){
        // redirect to UPS THIS WASN'T SUPPOSED TO HAPPEN page 
        header('location: ups.php');
        exit;
    }
}

// check if data was passed through the form
if(isset($_POST['postNewComment']) && !empty($_POST['postNewComment'])){
    // store post variables, sanitize using htmlentities!
    $newComment = htmlentities($_POST['postNewComment']);
    $postId = htmlentities($_POST['postId']);
    // store user id and token from session
    $loggedInUserId = $_SESSION['userId'];
    $newCommentId = uniqid();

    
    // echo "comment: ".$newComment;
    // echo "<br> post id: ".$postId;
    // echo "<br> user id of logged in user: ".$loggedInUserId."<br>";
    // echo strlen( $newComment);

    if(strlen($newComment) > 300){
        // echo "here";
        header("location: gag.php?p_id={$postId}&status=wrong");
        exit;
    }

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
    exit;

    
}else{
    // post variables werent passed throught the form so redirect to the index
    header('location: index.php');
    exit;
} 
