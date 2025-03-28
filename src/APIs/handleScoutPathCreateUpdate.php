<?php

require_once dirname(__DIR__) . '/scripts/connector.php';
require_once dirname(__DIR__) . '/scripts/Utilities/Functions.php';
require_once dirname(__DIR__) . '/scripts/ScoutPath/Service/ScoutPathService.php';

use Utility\Functions as Functions;
use ScoutPath\Service\ScoutPathService as ScoutPathService;

$path = new ScoutPathService();

$create = $_POST['createScoutPath'] ?? false;
$delete = $_POST['deleteScoutPath'] ?? false;
$update = $_POST['updateScoutPath'] ?? false;
$scout_path_id = $_POST['scoutPathId'] ?? 0;
$name = $_POST['scoutPathName'] ?? '';
$points = $_POST['scoutPathPoints'] ?? '';
$color = $_POST['scoutPathColor'] ?? '';

$create = Functions::sanitizeInput($create);
$delete = Functions::sanitizeInput($delete);
$update = Functions::sanitizeInput($update);
$scout_path_id = Functions::sanitizeInput($scout_path_id);
$name = Functions::sanitizeInput($name);
$points = Functions::sanitizeInput($points);
$color = Functions::sanitizeInput($color);

if ($create) {
    if ($name && $points && $color && isset($_FILES['scoutPathImage'])) {

        $image = uniqid("image", true);

        $targetFilePath = dirname(__DIR__) . '/images/' . $image . ".png";  // Saving as .php (not recommended)

        Functions::uploadImage($_FILES['scoutPathImage'], $targetFilePath);

        $data = [
            'name' => $name,
            'image' => $image,
            'color' => $color,
            'required_points' => $points
        ];

        if ($path->createScoutPath($data)) {
            header('location: ../pages/scoutPath.php');
            exit;
        }
        else {
            Functions::deleteImage($targetFilePath);

            echo '<p>Skautsky chodnik sa nepodarilo vytvorit</p>';
        }
    }
    else {
        echo '<p>Neboly zadane vsetky udaje</p>';
    }
}
else if ($delete && $scout_path_id) {
    try {
        $scoutPath = $path->getScoutPath($scout_path_id);

        $path->deleteScoutPath($scout_path_id);

        $filePath = dirname(__DIR__) . '/images/' . $scoutPath->image . ".png";
        Functions::deleteImage($filePath);

        header('location: ../pages/scoutPath.php');
        exit;
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
    catch (Error $e) {
        echo $e->getMessage();
    }
}
else if ($update && $scout_path_id) {
    if ($name) {
        $path->updateScoutPath($scout_path_id, "name", $name);
    }
    if ($points) {
        $path->updateScoutPath($scout_path_id, "required_points", $points);
    }
    if (isset($_POST['scoutPathColor'])) {
        $path->updateScoutPath($scout_path_id, "color", $color);
    }

    $scoutPath = $path->getScoutPath($scout_path_id);

    if (isset($_FILES['scoutPathImage']) && $_FILES['scoutPathImage']['name'] != "") {
        $filePath = '../images/' . $scoutPath->image . ".png";
        Functions::uploadImage($_FILES['scoutPathImage'], $filePath);
    }

    header('location: ../pages/scoutPath.php');
    exit;
}
?>