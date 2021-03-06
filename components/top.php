<?php 

// Turn off all error reporting
error_reporting(0);

// Redirect from http to https
/*
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
  $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: ' . $redirect);
  exit();
} */

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css?family=Gothic+A1" rel="stylesheet">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- DATATABLE -->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/styles.css">

    <title><?php echo $pageTitle; ?></title>
</head>
<body>

<?php 
  $sUserImgLocation = $_SESSION['userImgLocation'];
  $sUserImgName = $_SESSION['userImgName'];
?>

<!-- --------------------------------------- NAVBAR --------------------------------------- -->
<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php"><img style="width:70px;" src="images/LOGO.png"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">

      <li class="nav-item 
      <?php if($pageTitle == 'register'){echo ' active';}
            if(!empty($_SESSION['userEmail'])){echo ' hide';}?>"
            id="profileRegister">
        <a class="nav-link" href="register.php">Register</a>
      </li>
      <li class="nav-item 
      <?php if($pageTitle == 'login'){echo ' active';}
            if(!empty($_SESSION['userEmail'])){echo ' hide';}?>"
            id="profileLogin">
        <a class="nav-link" href="login.php">Login</a>
      </li>


      <!-- Profile, post and logout should be displayed instead of login and register after the user logs in -->

      <li class="nav-item 
      <?php if($pageTitle == 'users crud'){echo ' active';}
            if(!empty($_SESSION['userEmail']) && $_SESSION['userPrivileges']=='admin'){echo ' display';}?>"
            id="usersCrudNav">
        <a class="nav-link" href="users-crud.php" class="nav-link">Users crud</a>
      </li>
      
      <li class="nav-item 
      <?php if($pageTitle == 'posts crud'){echo ' active';}
            if(!empty($_SESSION['userEmail']) && $_SESSION['userPrivileges']=='admin'){echo ' display';}?>"
            id="postsCrudNav">
        <a class="nav-link" href="posts-crud.php" class="nav-link">Posts crud</a>
      </li>

      <li class="nav-item 
      <?php if($pageTitle == 'profile'){echo ' active';}
            if(!empty($_SESSION['userEmail']) && $_SESSION['userPrivileges']!='admin'){echo ' display';}?>"
            id="profileNav">
        <a class="nav-link" href="profile.php"><div id="nav-profile-div" style="background:url(<?php echo $sUserImgLocation;?>)"></div></a>
      </li>

      <li class="nav-item 
      <?php if($pageTitle == 'post'){echo ' active';}
            if(!empty($_SESSION['userEmail']) && $_SESSION['userPrivileges']!='admin'){echo ' display';}?>"
            id="postNav">
        <span class="nav-link">Upload +</span>
      </li>

      <li class="nav-item 
      <?php if($pageTitle == 'logout'){echo ' active';}
            if(!empty($_SESSION['userEmail'])){echo ' display';}?>"
            id="logoutNav">
        <!-- <a class="nav-link" href="logout.php">Logout</a> -->
        <form method="post" action="logout.php" id="logout_form">
          <?php
              require_once('api-set-token-logout.php');
          ?>
          <button type="submit" id="logout_button">Logout</button>
        </form>
      </li>

    </ul>
    
  </div>
</nav>