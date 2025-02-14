<?php

if (isset($_POST['endDifferentTaskView'])) {
    echo "endDifferentTaskView";
    $differentTaskView->endDifferentTaskView();
}
else if (isset($_POST['changeToUserView'])) {
    echo "changeToUserView";
    var_dump($_POST);
    if (isset($_POST['idOfUser']) && isset($_POST['nameOfUser'])) {
        $differentTaskView->setDifferentTaskView($_POST['idOfUSer'], $_POST['nameOfUser']);
    }
}
?>