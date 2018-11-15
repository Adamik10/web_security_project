<?php
$pageTitle = 'Account activated';
require_once("controllers/database.php");
require_once('components/top.php');

if(isset($_GET['user'])){
    $verification_code = $_GET['user'];

    //select all users and find the one with this verif code
    try{
        $stmt = $db->prepare('SELECT * FROM verification_codes WHERE verification_code = :verif_code ');
        $stmt->bindValue(':verif_code', $verification_code);
        $stmt->execute();
        $verif_code_db = $stmt->fetchAll();
    } catch (PDOException $ex){
        echo 'error selecting verif_code users';
        exit();
    }

    foreach($verif_code_db as $code){
        if($code['verification_code'] ==  $verification_code){
            // echo 'yes boi you in db';
            $userId = $code['id_users'];
            try {
                $stmt1 = $db->prepare('UPDATE users SET verified = :verified WHERE id_users = :users_id');
                $stmt1->bindValue(':verified', 1);
                $stmt1->bindValue(':users_id', $userId);
                $stmt1->execute();

            } catch (PDOException $ex) {
                echo 'error, inserting 1 for verified';
                exit();
            } 
            //echo template
            echo '<div class="container">
                    <!-- TEMPLATE START -->
                        <div class="card align-self-center card-custom mt-2 mb-2">
                            <p style="text-align:center; margin-top:15px;">Your account is verified. You can log in.</p>
                        </div>
                    <!-- TEMPLATE END -->
                </div>'; 
            break;
        }
    }
}



?>

<?php

require_once('components/bottom.php');

?>







