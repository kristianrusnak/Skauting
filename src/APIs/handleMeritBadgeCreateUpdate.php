<?php

if (isset($_POST['createMeritBadge'])) {
    if (isset($_POST['MeritBadgeName']) && isset($_POST['MeritBadgeCategory']) &&
         isset($_POST['MeritBadgeColor']) &&
        (isset($_FILES['MeritBadgeImageG']) || isset($_FILES['MeritBadgeImageR']))) {

        $name = sanitizeInput($_POST['MeritBadgeName']);
        $category = sanitizeInput($_POST['MeritBadgeCategory']);
        $color = sanitizeInput($_POST['MeritBadgeColor']);
        $image = uniqid("image", true);

        if (isset($_FILES['MeritBadgeImageG']) && $_FILES['MeritBadgeImageG']['name'] != "") {
            $imageName = $image . "_g";  // Example: image_650ff9c13b6a2.12345678
            $targetFilePath = '../images/' . $imageName . ".png";  // Saving as .php (not recommended)
            echo uploadImage($_FILES['MeritBadgeImageG'], $targetFilePath);
        }

        if (isset($_FILES['MeritBadgeImageR']) && $_FILES['MeritBadgeImageR']['name'] != "") {
            $imageName = $image . "_r";
            $targetFilePath = '../images/' . $imageName . ".png";
            echo uploadImage($_FILES['MeritBadgeImageR'], $targetFilePath);
        }

        if ($meritBadges->createNewMeritBadge($name, $category, $image, $color)){
            header('location: ../pages/meritBadges.php');
            exit;
        }
        else {
            echo 'Nepodarilo sa vytvorit odborku';
        }
    }
}
else if (isset($_POST['meritBadgeDelete'])) {
    if (isset($_POST['meritBadgeId'])) {
        $merit_badge_id = sanitizeInput($_POST['meritBadgeId']);
        $image = $meritBadges->getMeritBadgeImage($merit_badge_id);

        if ($meritBadges->deleteMeritBadge($merit_badge_id)){
            $filePath = "../images/" . $image . "_g" . ".png";
            deleteImage($filePath);
            $filePath = "../images/" . $image . "_r" . ".png";
            deleteImage($filePath);

            header('location: ../pages/meritBadges.php');
            exit;
        }
        else {
            echo 'Nepodarilo sa odstranit odborku.';
        }
    }
}
else if (isset($_POST['updateMeritBadge']) && isset($_GET['id'])) {
    if (isset($_POST['MeritBadgeName'])) {
        $name = sanitizeInput($_POST['MeritBadgeName']);
        $meritBadges->updateMeritBadge($_GET['id'], 'name', $name);
    }
    if (isset($_POST['MeritBadgeCategory'])) {
        $category = sanitizeInput($_POST['MeritBadgeCategory']);
        $meritBadges->updateMeritBadge($_GET['id'], 'category_id', $category);
    }
    if (isset($_POST['MeritBadgeColor'])) {
        $color = sanitizeInput($_POST['MeritBadgeColor']);
        $meritBadges->updateMeritBadge($_GET['id'], 'color', $color);
    }

    $meritBadge = $meritBadges->getMeritBadge($_GET['id']);

    if (isset($_FILES['MeritBadgeImageG']) && $_FILES['MeritBadgeImageG']['name'] != "") {
        $targetFilePath = '../images/' . $meritBadge['image'] . "_g" . ".png";
        uploadImage($_FILES['MeritBadgeImageG'], $targetFilePath);
    }

    if (isset($_FILES['MeritBadgeImageR']) && $_FILES['MeritBadgeImageR']['name'] != "") {
        $targetFilePath = '../images/' . $meritBadge['image'] . "_r" . ".png";
        uploadImage($_FILES['MeritBadgeImageR'], $targetFilePath);
    }

    header('location: ../pages/meritBadges.php');
    exit;
}

?>