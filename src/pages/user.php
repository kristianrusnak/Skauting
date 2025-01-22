<?php
include '../scripts/cookieCheckerScript.php';
include '../scripts/headerScript.php';
getMainHeader("používateľ");
include 'menu.php';
include '../scripts/dbFunctions.php';
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
<?php include 'end.php'; ?>
