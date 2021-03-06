
<?php
// check if user is logged in
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}
require_once("controllers/database.php");


$postId = $_POST['p_id'];
$loggedInUserId = $_SESSION['userId'];
$upvoteId = uniqid();

// if someone clicked upvote then 
//    if(the upvote is not there){
//    write to the upvotes table } else {
//    delete the upvote from the table
// }

$done = false;

//go to database and see if this post has already been liked by the logged in user
    try{
        $stmt = $db->prepare('SELECT * FROM upvotes WHERE id_posts = :upvotedPost AND id_users = :loggedInUserId');
        $stmt->bindValue(':upvotedPost', $postId);
        $stmt->bindValue(':loggedInUserId', $loggedInUserId);
        $stmt->execute();
        $users = $stmt->fetchAll();
    } catch (PDOException $ex){
        // echo 'error selecting upvotes: '.$ex;
        //recirect either to index or gag.php
        exit();
    }

    if(count($users) == 0){
        // echo 'like';
        try{
            $stmt2 = $db->prepare('INSERT INTO upvotes(id_upvotes, id_posts, id_users) VALUES (:id_upvotes, :id_posts, :id_users)');
            $stmt2->bindValue(':id_upvotes',  $upvoteId);
            $stmt2->bindValue(':id_posts', $postId);
            $stmt2->bindValue(':id_users', $loggedInUserId);
            $stmt2->execute();
            $done = true;
        } catch (PDOException $ex){
            // echo 'error saving upvote: '.$ex;

            exit();
        }
    } else {
        // echo 'this post has been liked by you already';
        try{
            $stmt = $db->prepare('DELETE FROM upvotes WHERE id_posts = :upvotedPost AND id_users = :loggedInUserId');
            $stmt->bindValue(':upvotedPost', $postId);
            $stmt->bindValue(':loggedInUserId', $loggedInUserId);
            $stmt->execute();
            $done = true;
        } catch (PDOException $ex){
            // echo 'error deleting upvote: '.$ex;
        //recirect either to index or gag.php
            exit();
        }

    }


//return to ajax number of upvotes for this post

if($done){
    try{
        $stmt = $db->prepare('SELECT COUNT(*) AS new_upvotes_count FROM upvotes WHERE id_posts = :upvotedPost');
        $stmt->bindValue(':upvotedPost', $postId);
        $stmt->execute();
        $aaUpvotesAfterChange = $stmt->fetchAll();
    } catch (PDOException $ex){
        // echo 'error selecting upvotes: '.$ex;
    //recirect either to index or gag.php
        exit();
    }
    $aUpvotesAfterChange = $aaUpvotesAfterChange[0];
    $iNumberOfUpvotes = $aUpvotesAfterChange['new_upvotes_count'];
    
    echo $iNumberOfUpvotes;
}