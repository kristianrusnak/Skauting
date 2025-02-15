<?php
include '../scripts/connector.php';
$session->KickIfSessionNotSet();
$body->printMainHeader( "spravca-uzivatelov");
$differentTaskView->alertHeader();
include '../scripts/handleDifferentTaskView.php';
include 'menu.php';
?>

    <div id="tasksContainer1" >
        <h1>Správca používateľov</h1>
        <?php
        if ($_SESSION['position_id'] == 3) {
            $groupsLister->printGroupContainerStart();
            $groupsLister->listTeam($_SESSION['user_id']);
            $groupsLister->printGroupContainerEnd();
            $groupsLister->printCssScript();
        }
        else if ($_SESSION['position_id'] >= 4) {
            $groupsLister->printGroupContainerStart();
            $groupsLister->listLeaders();
            $groupsLister->listUncategorized();
            $groupsLister->listTeams();
            $groupsLister->printGroupContainerEnd();
            $groupsLister->printSubmitButton();
            $groupsLister->printCssScript();
            $groupsLister->printScript();
        }
        else {
            header("Location: ../pages/home.php");
            exit();
        }
        ?>
    </div>

<?php
$body->printFooter();
?>