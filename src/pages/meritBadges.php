<?php include 'header.php'; ?>
<?php include '../scripts/dbFunctions.php'; ?>
<?php include 'menu.php'; ?>
<div id="tasksContainer1" >
    <?php new listTasks($mysqli, 0)?>
</div>
<?php include 'end.php'; ?>