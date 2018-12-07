<?php 
$salt = rand(100000, 999999);
$peber = "MaciejStopHackingUs";
$options = [
           'cost' => 12
       ];
$registerPassword1 = '';
//PASSWORD_DEFAULT - uses bcrypt algorithm - designed to change over time so the length of the result might change over time - DB column should have at least 60 characters
$pass_hash = password_hash($registerPassword1.$peber.$salt, PASSWORD_DEFAULT, $options);

echo 'salt: '.$salt;
echo 'pass hash: '.$pass_hash;
?>