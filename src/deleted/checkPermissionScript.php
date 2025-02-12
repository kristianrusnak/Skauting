<?php
if ($_COOKIE['position_id'] < 2){
    header('Location: ../pages/home.php');
    exit;
}
?>