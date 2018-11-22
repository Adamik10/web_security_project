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
    exit; 
}

// now we need to get all the IDs already loaded into one string from that array we got so we can use it in an SQL statement
// echo print_r($listOfIds);
// echo count($listOfIds);
$finalListOfIds = ''; 
for( $i = 0; $i < count($listOfIds); $i++ ){
    if($i != 0){
        $finalListOfIds = $finalListOfIds." AND posts.id_posts <> '".$listOfIds[$i]."'";
    }else{
        $finalListOfIds = "'".$listOfIds[$i]."'";
    } 
}
// echo $listOfIds[0]; 
// echo $finalListOfIds;


require('controllers/database.php');
// we need - image of user, user's nickname, post headline, post picture location
try{
$stmt = $db->prepare("SELECT posts.id_posts, posts.headline, posts.image_location, posts.image_name, posts.datetime, users.username, users.user_image_location, users.user_image_name 
                        FROM posts INNER JOIN users ON posts.id_users = users.id_users WHERE posts.id_posts <> :listOfPostsAlreadyLoaded ORDER BY posts.datetime DESC LIMIT 5");
$stmt->bindValue(':listOfPostsAlreadyLoaded', $finalListOfIds);
$stmt->execute();
$aOfPosts = $stmt->fetchAll();
}catch (PDOException $exception){
    echo $exception;
}
echo json_encode($aOfPosts);
// wtf this statement returns bullshiet but it works when entered into mySQL 
// echo "SELECT posts.id_posts, posts.headline, posts.image_location, posts.image_name, posts.datetime, users.username, users.user_image_location, users.user_image_name 
// FROM posts INNER JOIN users ON posts.id_users = users.id_users 
// WHERE posts.id_posts != '5bf6f31fb54d0' AND posts.id_posts != '5bf6edd742e11' AND posts.id_posts != '5bf6e98092933' AND posts.id_posts != '5bf6c78b74ecf' 
// AND posts.id_posts != '5bf6c4ca6b7cf' ORDER BY posts.datetime DESC LIMIT 5";