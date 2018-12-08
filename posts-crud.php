<?php $pageTitle = 'posts crud'?>
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

<!-- TEMPLATE -->
<div class="custom-container">

    <h3 class="mt-5 text-center">Posts crud</h3>
    <div class="table-responsive">
    <table class="table table-hover table-dark mt-5">
    <thead>
        <tr>
        <th scope="col">Id</th>
        <th scope="col">Headline</th>
        <th scope="col">Image</th>
        <th scope="col">OP</th>
        <th scope="col">Comments</th>
        <th scope="col">Upvotes</th>
        <th scope="col">Banned</th>
        <th scope="col">Edit</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <form class="posts-crud-form">
        <td>1234</td>
        <td><input type='text' class='posts-crud-input' name='txtHeadlineCrud' value='{$jUser->name}' disabled></td>
        <td><div class="posts-crud-img"></div></td>
        <td>username</td>
        <td>120</td>
        <td>6000</td>
        <td><input type='text' class='posts-crud-input' name='txtHeadlineCrud' value='1' disabled></td>
        <td><button class='btnSaveChangesAdmin admin-page-input edit' type='submit'><i class='editIcon fas fa-edit'></i><i class='saveIcon fas fa-save'></i></button></td>
        </form>
        </tr>
    </tbody>
    </table>
    </div>
</div>
<!-- TEMPLATE END -->






<?php
require_once('components/bottom.php');
?>