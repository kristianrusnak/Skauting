<?php

namespace HtmlBuilder;

require_once dirname(__DIR__) . '/Tasks/Service/CompletedTasksService.php';
require_once dirname(__DIR__) . '/ScoutPath/Service/ScoutPathService.php';
require_once dirname(__DIR__) . '/MeritBadge/Service/MeritBadgeService.php';

use Task\Service\CompletedTasksService as CompletedTasks;
use ScoutPath\Service\ScoutPathService as ScoutPath;
use MeritBadge\Service\MeritBadgeService as MeritBadge;

class Containers
{
    /**
     * @var CompletedTasks
     */
    private CompletedTasks $completedTasks;

    /**
     * @var ScoutPath
     */
    private ScoutPath $scoutPath;

    /**
     * @var MeritBadge
     */
    private MeritBadge $meritBadge;

    function __construct()
    {
        $this->completedTasks = new CompletedTasks();
        $this->scoutPath = new ScoutPath();
        $this->meritBadge = new MeritBadge();
    }

    public function listMeritBadges(int $user_id): void
    {
        echo '<div class="tasksContainer">';

        $categories = $this->meritBadge->getAllCategories();

        foreach ($categories as $category) {

            $this->printCategoryHeader($category->name);
            $meritBadges = $this->meritBadge->getAllMeritBadgesByCategoryId($category->id);

            foreach ($meritBadges as $meritBadge){

                $name = $meritBadge->name;

                $levels = $this->meritBadge->getLevels();

                foreach ($levels as $level){
                    $points = $this->completedTasks->getUsersProgressPointsForMeritBadge($user_id, $meritBadge->id, $level->id);

                    $level_sign = [
                        1 => " 🟢",
                        2 => " 🔴",
                    ];

                    if ($points['finished'] && $points['started']) {
                        $name .= $level_sign[$level->id] . " ✔️";
                    }
                    else if ($points['started']) {
                        $name .= $level_sign[$level->id] . " 🏃";
                    }
                }

                $image = $this->getImageOfMeritBadge($meritBadge->image);

                $data = [
                    'name' => $name,
                    'image' => $image,
                    'color' => $meritBadge->color,
                    'id' => $meritBadge->id,
                    'siteType' => 'meritBadges.php'
                ];

                $this->printContainer($data);

            }

        }

        echo '</div>';
    }

    public function listMeritBadgesWithFilter(int $user_id, $type = "finished"): void
    {
        $meritBadges = $this->meritBadge->getMeritBadges();

        foreach ($meritBadges as $meritBadge){

            $levels = $this->meritBadge->getLevels();

            foreach ($levels as $level){

                $points = $this->completedTasks->getUsersProgressPointsForMeritBadge($user_id, $meritBadge->id, $level->id);

                $name = $meritBadge->name;
                if ($type == "finished" && (!$points['finished'] || !$points['started'])) {
                    continue;
                }
                else if ($type == "finished"){
                    $name .= " ✅";
                }

                if ($type == "started" && ($points['finished'] || !$points['started'])) {
                    continue;
                }
                else if ($type == "started"){
                    $name .= " 🏃‍♂️";
                }

                $image = $meritBadge->image . $level->image;

                $data = [
                    "name" => $name,
                    "image" => $image,
                    "color" => $meritBadge->color,
                    "id" => $meritBadge->id,
                    "siteType" => 'meritBadges.php'
                ];

                $this->printContainer($data);
            }
        }
    }

    public function listScoutPaths(int $user_id): void
    {
        $scoutPaths = $this->scoutPath->getScoutPaths();

        foreach ($scoutPaths as $scoutPath){
            $is_completed = true;
            $has_any_result = false;

            if (!empty($scoutPath->required_points)) {
                $points = $this->completedTasks->getUsersProgressPointsForScoutPathWithOneTypeOfPoints($user_id, $scoutPath->id);

                if ($points['started']) {
                    $has_any_result = true;
                }
                if (!$points['finished']) {
                    $is_completed = false;
                }
            }
            else {
                $areas = $this->scoutPath->getAreas();

                foreach ($areas as $area){

                    $temp = $this->completedTasks->getUsersProgressPointsForScoutPathWithFourTypesOfPoints($user_id, $scoutPath->id, $area->id);

                    if (!$temp['finished']) {
                        $is_completed = false;
                    }

                    if ($temp['started']) {
                        $has_any_result = true;
                    }
                }
            }

            $name = $scoutPath->name;
            if ($is_completed) {
                $name .= " ✅";
            }
            else if ($has_any_result) {
                $name .= " 🏃‍♂️";
            }

            $data = [
                'name' => $name,
                'image' => $scoutPath->image,
                'color' => $scoutPath->color,
                'id' => $scoutPath->id,
                'siteType' => 'scoutPath.php'
            ];

            $this->printContainer($data);

        }

    }

    public function listScoutPathWithFilter(int $user_id, $type = "finished"): void
    {
        $scoutPaths = $this->scoutPath->getScoutPaths();

        foreach ($scoutPaths as $scoutPath){
            $is_completed = true;
            $has_any_result = false;

            if (!empty($scoutPath->required_points)) {
                $points = $this->completedTasks->getUsersProgressPointsForScoutPathWithOneTypeOfPoints($user_id, $scoutPath->id);

                if ($points['started']) {
                    $has_any_result = true;
                }
                if (!$points['finished']) {
                    $is_completed = false;
                }
            }
            else {
                $areas = $this->scoutPath->getAreas();

                foreach ($areas as $area){

                    $temp = $this->completedTasks->getUsersProgressPointsForScoutPathWithFourTypesOfPoints($user_id, $scoutPath->id, $area->id);

                    if (!$temp['finished']) {
                        $is_completed = false;
                    }

                    if ($temp['started']) {
                        $has_any_result = true;
                    }
                }
            }

            $name = $scoutPath->name;
            if ($type == "finished" && $is_completed) {
                $name .= " ✅";
            }
            else if ($type == "started" && $has_any_result && !$is_completed) {
                $name .= " 🏃‍♂️";
            }
            else {
                continue;
            }

            $data = [
                'name' => $name,
                'image' => $scoutPath->image,
                'color' => $scoutPath->color,
                'id' => $scoutPath->id,
                'siteType' => 'scoutPath.php'
            ];

            $this->printContainer($data);

        }
    }

    public function listScoutPathsInProgress(int $user_id): void
    {
        $scoutPaths = $this->scoutPath->getScoutPaths();

        foreach ($scoutPaths as $scoutPath){
            $points = array();

            if (!empty($scoutPath->required_points)) {
                $points = $this->completedTasks->getUsersProgressPointsForScoutPathWithOneTypeOfPoints($user_id, $scoutPath->id);

                if (!$points['started'] || $points['finished']) {
                    continue;
                }
            }
            else {
                $areas = $this->scoutPath->getAreas();
                $is_completed = true;
                $has_any_result = false;

                foreach ($areas as $area){

                    $temp = $this->completedTasks->getUsersProgressPointsForScoutPathWithFourTypesOfPoints($user_id, $scoutPath->id, $area->id);

                    if (!$temp['finished']) {
                        $is_completed = false;
                    }

                    if ($temp['started']) {
                        $has_any_result = true;
                    }


                    $points[] = $temp;
                }

                if ($is_completed) {
                    continue;
                }

                if (!$has_any_result) {
                    continue;
                }
            }

            $data = [
                'name' => $scoutPath->name,
                'image' => $scoutPath->image,
                'color' => $scoutPath->color,
                'id' => $scoutPath->id,
                'siteType' => 'scoutPath.php'
            ];

            $this->printContainerInProgressStart($data);

            if (!empty($scoutPath->required_points)) {
                $this->printContainerInProgressMid($points);
            }
            else {

                foreach ($points as $point){

                    $this->printContainerInProgressMid($point, '', '');

                }
            }

            $this->printContainerInProgressEnd();
        }
    }

    public function listMeritBadgesInProgress(int $user_id): void
    {
        $meritBadges = $this->meritBadge->getMeritBadges();

        foreach ($meritBadges as $meritBadge){

            $levels = $this->meritBadge->getLevels();

            foreach ($levels as $level){

                $points = $this->completedTasks->getUsersProgressPointsForMeritBadge($user_id, $meritBadge->id, $level->id);

                if (!$points['started'] || $points['finished']) {
                    continue;
                }

                $image = $meritBadge->image . $level->image;

                $data = [
                    "name" => $meritBadge->name,
                    "image" => $image,
                    "color" => $meritBadge->color,
                    "id" => $meritBadge->id,
                    "siteType" => 'meritBadges.php'
                ];

                $this->printContainerInProgressStart($data);
                $this->printContainerInProgressMid($points);
                $this->printContainerInProgressEnd();
            }
        }
    }

    private function getImageOfMeritBadge(string $image): string
    {
        $levels = $this->meritBadge->getLevels();

        foreach ($levels as $level){
            $filename = dirname(__DIR__, 2) . '/images/' . $image . $level->image . '.png';

            if (file_exists($filename)){
                return $image . $level->image;
            }
        }

        return '';
    }

    private function printCategoryHeader(string $name): void
    {
        echo '<span class="tasksContainerCategory">'.$name.'</span>';
    }

    private function printContainer(array $data): void
    {
        echo '
            <a href="'.$data['siteType'].'?id='.$data['id'].'" class="taskContainer">
                <span class="taskContainerHeading" style="background-color: '.$data['color'].'">'.$data['name'].'</span>
                <img class="taskContainerImage" src="../images/'.$data['image'].'.png" alt="'.$data['name'].'">
            </a>
            ';
    }

    //TODO
    private function printContainerInProgressStart($data): void
    {
        echo '
            <a href="../pages/'.$data['siteType'].'?id='.$data['id'].'" class="taskContainer taskInProgressContainer">
            <span class="taskContainerHeading" style="background-color: '.$data['color'].'">'.$data['name'].'</span>
            <img class="taskContainerImage" src="../images/'.$data['image'].'.png" alt="'.$data['name'].'">
            <div class="tasksInProgress">
        ';
    }

    private function printContainerInProgressMid($data, $span_class = 'bigText', $img_class = 'bigIcon'): void
    {
        echo '
            <span class="tasksContainerPoints '.$span_class.'">'.$data['in_progress'].' / '.$data['total'].' &nbsp;<img class="taskContainerIcon '.$img_class.'" src="../images/'.$data['icon'].'.png" alt="icon"></span>
        ';
    }

    private function printContainerInProgressEnd(): void
    {
        echo'
            </div>
            </a>
        ';
    }

    public function printContainerStart(): void
    {
        echo '<div class="tasksContainer">';
    }

    public function printContainerEnd(): void
    {
        echo '</div>';
    }
}