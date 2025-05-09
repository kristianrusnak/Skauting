<?php

require_once '../scripts/connector.php';
require_once '../scripts/HtmlBuilder/HtmlBody.php';
require_once '../scripts/Utilities/SessionManager.php';
require_once '../scripts/HtmlBuilder/DifferentTasksManager.php';

use HtmlBuilder\HtmlBody as Body;
use Utility\SessionManager as Session;
use HtmlBuilder\DifferentTasksManager as DifferentTasksManager;

Session::KickIfSessionNotSet();

Body::printMainHeader( "uživateľ");
DifferentTasksManager::alertHeader();
Body::printMenu();
?>

<div id="userMenuContainer">
    <div class="userMenuContainer2"></div>
    <div class="userMenuContainer2"></div>
    <div class="userMenuContainer2">
        <a href="../APIs/logOutUserScript.php"><img src="../images/log_out.png" alt="log out"> Odhlásiť sa</a>
    </div>
    <div class="userMenuContainer2"></div>
</div>

<?php
Body::printFooter();
?>
