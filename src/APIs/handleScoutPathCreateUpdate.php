<?php

if (isset($_POST['createScoutPath'])) {
    if (isset($_POST['scoutPathName']) && isset($_POST['scoutPathPoints']) &&
    isset($_POST['scoutPathColor']) && isset($_FILES['scoutPathImage'])) {

        $name = sanitizeInput($_POST['scoutPathName']);
        $points = sanitizeInput($_POST['scoutPathPoints']);
        $color = sanitizeInput($_POST['scoutPathColor']);

        $image = uniqid("image", true);
        $targetFilePath = '../images/' . $image . ".png";  // Saving as .php (not recommended)
        uploadImage($_FILES['scoutPathImage'], $targetFilePath);

        if ($scoutPaths->createScoutPath($name, $image, $color, $points)) {
            header('location: ../pages/scoutPath.php');
            exit;
        }
        else {
            echo '<p>Skautsky chodnik sa nepodarilo vytvorit</p>';
        }
    }
    else {
        echo '<p>Neboly zadane vsetky udaje</p>';
    }
}
else if (isset($_POST['deleteScoutPath']) && isset($_GET['id'])) {
        $image = $scoutPaths->getScoutPath($_GET['id'])['image'];

        if ($scoutPaths->deleteScoutPath($_GET['id'])) {

            header('location: ../pages/scoutPath.php');
            exit;
        }
        else {
            echo '<p>Nepodarilo sa odstranit skautsky chodnik</p>';
        }
}
else if (isset($_POST['updateScoutPath']) && isset($_GET['id'])) {
    if (isset($_POST['scoutPathName'])) {
        $name = sanitizeInput($_POST['scoutPathName']);
        $scoutPaths->updateScoutPath($_GET['id'], "name", $name);
    }
    if (isset($_POST['scoutPathPoints'])) {
        $points = sanitizeInput($_POST['scoutPathPoints']);
        $scoutPaths->updateScoutPath($_GET['id'], "required_points", $points);
    }
    if (isset($_POST['scoutPathColor'])) {
        $color = sanitizeInput($_POST['scoutPathColor']);
        $scoutPaths->updateScoutPath($_GET['id'], "color", $color);
    }

    $path = $scoutPaths->getScoutPath($_GET['id']);

    if (!empty($_FILES['scoutPathImage']['tmp_name']) && $_FILES['scoutPathImage']['error'] === UPLOAD_ERR_OK) {
        $targetFilePath = '../images/' . $path['image'] . ".png";
        uploadImage($_FILES['scoutPathImage'], $targetFilePath);
    }

    header('location: ../pages/scoutPath.php');
    exit;
}
?>