<?php $pageTitle = '8gag'?>

<?php require_once('components/top.php');?>
<?php require_once('controllers/database.php');?>

<?php
echo 'Current PHP version: ' . phpversion();
?>

<!-- ------------------------------------------ INDEX BODY ------------------------------------------ -->

<div class="container">

    <?php 
        if(!empty($_SESSION['userEmail'])){
        echo 'session is not empty';
        print_r($_SESSION);
        }
    ?>

    <!-- TEMPLATE START -->
<div class="card align-self-center card-custom mt-2 mb-2">

    <div class="row ml-3 mr-3 mt-1">
        <div class="mr-3">img</div>
        <a href="#">OP´s name</a>
    </div>

    <h4 class="card-title mr-3 ml-3 mt-3">Wise words.</h4>

    <img class="card-img-top" src="https://jolicode.com/media/original/2017/password.png" alt="Card image cap">
    
    <div class="card-body">
        <a href="#" class="btn btn-primary">Upvote</a>
        <a href="#" class="btn btn-primary">Comment</a>
    </div>
</div>
    <!-- TEMPLATE END -->

</div>

<!-- ------------------------------------------ INDEX BODY END ------------------------------------------ -->

<?php require_once('components/bottom.php');?>