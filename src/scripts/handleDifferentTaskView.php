<?php
if (isset($_POST['endDifferentTaskView'])) {
    $differentTaskView->endDifferentTaskView();
}
else if (isset($_POST['changeToUserView'])) {
    if (isset($_POST['idOfUser']) && isset($_POST['nameOfUser'])) {
        $differentTaskView->setDifferentTaskView($_POST['idOfUser'], $_POST['nameOfUser']);
        if (isset($_POST['website'])) {
            header('Location: ' . $_POST['website']);
        }
        else {
            header("Location: " . $_SERVER['PHP_SELF']);
        }
        exit;
    }
}
?>