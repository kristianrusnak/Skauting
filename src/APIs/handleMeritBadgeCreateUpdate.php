<?php

require_once dirname(__DIR__) . '/scripts/connector.php';
require_once dirname(__DIR__) . '/scripts/Utilities/Functions.php';
require_once dirname(__DIR__) . '/scripts/MeritBadge/Service/MeritBadgeService.php';

use Utility\Functions as Functions;
use MeritBadge\Service\MeritBadgeService as MeritBadge;

$badge = new MeritBadge();

$create = $_POST['createMeritBadge'] ?? false;
$delete = $_POST['meritBadgeDelete'] ?? false;
$update = $_POST['updateMeritBadge'] ?? false;
$merit_badge_id = $_POST['meritBadgeId'] ?? 0;
$name = $_POST['MeritBadgeName'] ?? "";
$category = $_POST['MeritBadgeCategory'] ?? "";
$color = $_POST['MeritBadgeColor'] ?? "";

$create = Functions::sanitizeInput($create);
$delete = Functions::sanitizeInput($delete);
$update = Functions::sanitizeInput($update);
$merit_badge_id = Functions::sanitizeInput($merit_badge_id);
$name = Functions::sanitizeInput($name);
$category = Functions::sanitizeInput($category);
$color = Functions::sanitizeInput($color);

if ($create) {
    if ($name && $category && $color && (isset($_FILES['MeritBadgeImageG']) || isset($_FILES['MeritBadgeImageR']))) {

        $image = uniqid("image", true);

        if (isset($_FILES['MeritBadgeImageG']) && $_FILES['MeritBadgeImageG']['name'] != "") {
            $imageName = $image . "_g";  // Example: image_650ff9c13b6a2.12345678
            $targetFilePath = dirname(__DIR__) . '/images/' . $imageName . ".png";  // Saving as .php (not recommended)
            echo Functions::uploadImage($_FILES['MeritBadgeImageG'], $targetFilePath);
        }

        if (isset($_FILES['MeritBadgeImageR']) && $_FILES['MeritBadgeImageR']['name'] != "") {
            $imageName = $image . "_r";
            $targetFilePath = dirname(__DIR__) . '/images/' . $imageName . ".png";
            echo Functions::uploadImage($_FILES['MeritBadgeImageR'], $targetFilePath);
        }

        $data = [
            'name' => $name,
            'category' => $category,
            'color' => $color,
            'image' => $image
        ];

        if ($badge->createNewMeritBadge($data)){
            header('location: ../pages/meritBadges.php');
            exit;
        }
        else {
            echo 'Nepodarilo sa vytvorit odborku';
        }
    }
}
else if ($delete && $merit_badge_id) {

    $meritBadge = $badge->getMeritBadge($merit_badge_id);

    $badge->deleteMeritBadge($merit_badge_id);

    $filePath = dirname(__DIR__) . "/images/" . $meritBadge->image . "_g" . ".png";
    Functions::deleteImage($filePath);
    $filePath = dirname(__DIR__) . "/images/" . $meritBadge->image . "_r" . ".png";
    Functions::deleteImage($filePath);

    header('location: ../pages/meritBadges.php');
    exit;
}
else if ($update && $merit_badge_id) {

    $data = [
        'name' => $name,
        'category_id' => $category,
        'color' => $color
    ];

    $badge->updateMeritBadge($merit_badge_id, $data);

    $meritBadge = $badge->getMeritBadge($merit_badge_id);

    if (isset($_FILES['MeritBadgeImageG']) && $_FILES['MeritBadgeImageG']['name'] != "") {
        $targetFilePath = dirname(__DIR__) . '/images/' . $meritBadge->image . "_g" . ".png";
        Functions::uploadImage($_FILES['MeritBadgeImageG'], $targetFilePath);
    }

    if (isset($_FILES['MeritBadgeImageR']) && $_FILES['MeritBadgeImageR']['name'] != "") {
        $targetFilePath = dirname(__DIR__) . '/images/' . $meritBadge->image . "_r" . ".png";
        Functions::uploadImage($_FILES['MeritBadgeImageR'], $targetFilePath);
    }

    header('location: ../pages/meritBadges.php');
    exit;
}

?>