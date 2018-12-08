<?php
//here we get the data from the AJAX function from jQuery
$listOfIds;
if(isset($_POST['kvcArray'])){
    $listOfIds = $_POST['kvcArray'];
}else{
    echo 'there was no data';
    exit; 
}


?>