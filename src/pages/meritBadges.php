<?php
include '../scripts/cookieCheckerScript.php';
include '../scripts/headerScript.php';
getMainHeader("odborky");
include '../scripts/dbFunctions.php';
include 'menu.php';

if (isset($_GET['id']) && check_get_for_merit_badge($mysqli, $_GET['id'])){
?>
<div id="tasksContainer1" >
    <?php
    echo '<h1>'.get_name_of_merit_badge($mysqli, $_GET['id']).'</h1>';
    new TasksLister($mysqli, $_GET['id'], "merit_badges");
    ?>
</div>
<?php
}
else{
?>
<div id="tasksContainer1" >
    <h1>Odborky</h1>
    <?php new listTasks($mysqli, 0)?>
</div>
<?php
}
include 'end.php'; ?>