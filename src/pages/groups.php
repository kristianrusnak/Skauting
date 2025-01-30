<?php
include '../scripts/cookieCheckerScript.php';
include '../scripts/checkPermissionScript.php';
include '../scripts/headerScript.php';
getMainHeader("domov");
include '../scripts/dbFunctions.php';
include 'menu.php';
?>
    <div id="tasksContainer1" >
        <h1>Kontrola úloh</h1>
        <?php
        /*include "groupContainer.php"*/
        new GroupApprovalLister($mysqli);
        ?>
    </div>
<?php include 'end.php'; ?>