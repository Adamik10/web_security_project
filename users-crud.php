<?php $pageTitle = 'users crud'?>
<?php 
session_start();
if(!isset($_SESSION['sessionId'])){
    header('location: login.php?status=not_logged_in');
    exit;
}
if(empty($_SESSION['userPrivileges']) && $_SESSION['userPrivileges'] != 'admin'){
    header('location: busted.php');
    exit;
}
?>


<?php 
require_once('components/top.php');
?>


<?php 
require_once('components/bottom.php');
?>