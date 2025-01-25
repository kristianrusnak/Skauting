<?php
include '../scripts/cookieCheckerScript.php';
include '../scripts/headerScript.php';
getMainHeader("domov");
include '../scripts/dbFunctions.php';
include 'menu.php';
?>
<div id="tasksContainer1" >
    <h1>Rozpracované úlohy</h1>
    <?php new listTasks($mysqli, 2);?>
</div>
<?php include 'end.php'; ?>