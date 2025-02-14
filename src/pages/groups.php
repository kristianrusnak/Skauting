<?php
include '../scripts/connector.php';
$cookies->KickIfCookiesNotSet();
$body->printMainHeader( "domov");
$differentTaskView->alertHeader();
include '../scripts/handleDifferentTaskView.php';
include 'menu.php';
?>

    <div id="tasksContainer1" >
        <h1>Správca používateľov</h1>
        <?php

        $groupsLister->printGroupContainerStart();
        $groupsLister->listLeaders();
        $groupsLister->listUncategorized();
        $groupsLister->listTeams();
        $groupsLister->printGroupContainerEnd();
        $groupsLister->printSubmitButton();
        $groupsLister->printCssScript();
        $groupsLister->printScript();
        ?>
    </div>

<?php
$body->printFooter();
?>