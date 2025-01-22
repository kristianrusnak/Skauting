<?php
include '../scripts/cookieCheckerScript.php';
include '../scripts/headerScript.php';
getMainHeader("skautsky-chodnik");
include '../scripts/dbFunctions.php';
include 'menu.php';

if (isset($_GET['id']) && check_get_for_scout_path($mysqli, $_GET['id'])){
?>
<div id="tasksContainer1" >
    <?php
    echo '<h1>'.get_name_of_scout_path($mysqli, $_GET['id']).'</h1>';
    new TasksLister($mysqli, $_GET['id'], "scout_path")
    ?>
</div>
<?php
}
else{
?>
<div id="tasksContainer1" >
    <h1>Skautský chodník</h1>
    <?php
    new listTasks($mysqli, 1);
    ?>
</div>
<?php
}
include 'end.php'; ?>