<?php
if (isset($_POST['endDifferentTaskView'])) {
    $differentTaskView->endDifferentTaskView();
}
else if (isset($_POST['changeToUserView'])) {
    if (isset($_POST['idOfUser']) && isset($_POST['nameOfUser'])) {
        $differentTaskView->setDifferentTaskView($_POST['idOfUser'], $_POST['nameOfUser']);
    }
}
?>