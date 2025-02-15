<?php
include '../scripts/connector.php';
$session->KickIfSessionNotSet();
$body->printMainHeader( "uzivatel");
$differentTaskView->alertHeader();
include '../scripts/handleDifferentTaskView.php';
include 'menu.php';
?>

<div id="userMenuContainer">
    <div class="userMenuContainer2"></div>
    <div class="userMenuContainer2"></div>
    <div class="userMenuContainer2">
        <a>Zmena hesla</a>
        <a>Moja druzina</a>
        <a href="../scripts/logOutUserScript.php"><img src="../images/log_out.png" alt="log out"> Odhlásiť sa</a>
    </div>
    <div class="userMenuContainer2"></div>
</div>

<?php
$body->printFooter();
?>
