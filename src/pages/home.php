<?php
include '../scripts/cookieCheckerScript.php';
include '../scripts/headerScript.php';
getMainHeader("domov");
include 'menu.php';
?>
<div id="tasksContainer1" >
    <h1>Rozpracované úlohy</h1>
    <?php include 'tasksContainer.php'; ?>
</div>
<?php include 'end.php'; ?>