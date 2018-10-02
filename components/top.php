<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/styles.css">

    <title><?php echo $pageTitle; ?></title>
</head>
<body>

<!-- --------------------------------------- NAVBAR --------------------------------------- -->
<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">Logo</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item <?php if($pageTitle == 'register'){echo 'active';}?>">
        <a class="nav-link" href="register.php">Register</a>
      </li>
      <li class="nav-item <?php if($pageTitle == 'login'){echo 'active';}?>">
        <a class="nav-link" href="login.php">Login</a>
      </li>
      <!-- Profile and post should be displayed instead of login and register after the user logs in -->
      <li class="nav-item <?php if($pageTitle == 'profile'){echo 'active';}?>" id="profileNav">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item <?php if($pageTitle == 'post'){echo 'active';}?>" id="postNav">
        <a class="nav-link" href="post.php">Post</a>
      </li>
    </ul>
    
  </div>
</nav>