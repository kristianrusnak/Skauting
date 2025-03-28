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

    function __construct($database)
    {
        $this->completedTasks = new CompletedTasks($database);
        $this->scoutPath = new ScoutPath();
        $this->meritBadge = new MeritBadge();
    }

    public function listMeritBadges(): void
    {
        echo '<div class="tasksContainer">';

        $categories = $this->meritBadge->getAllCategories();

        foreach ($categories as $category) {

            $this->printCategoryHeader($category->name);
            $meritBadges = $this->meritBadge->getAllMeritBadgesByCategoryId($category->id);

            foreach ($meritBadges as $meritBadge){

                $image = $this->getImageOfMeritBadge($meritBadge->image);

                $data = [
                    'name' => $meritBadge->name,
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

    public function listScoutPaths(): void
    {
        $scoutPaths = $this->scoutPath->getScoutPaths();

        foreach ($scoutPaths as $scoutPath){

            $data = [
                'name' => $scoutPath->name,
                'image' => $scoutPath->image,
                'color' => $scoutPath->color,
                'id' => $scoutPath->id,
                'siteType' => 'scoutPath.php'
            ];

            $this->printContainer($data);

        }

    }

    //TODO
    public function listScoutPathsInProgress(): void
    {
        $scoutPaths = $this->completedTasks->getScoutPathsInProgress($_SESSION['user_id']);

        foreach ($scoutPaths as $scoutPath){
            for ($i = 0; $i < count($scoutPath); $i++){
                if (count($scoutPath) == 1){
                    $this->printContainerInProgressStart($scoutPath[$i]['name'], $scoutPath[$i]['image'], $scoutPath[$i]['color'], $scoutPath[$i]['scout_path_id'], "scoutPath.php");
                    $this->printContainerInProgressMid($scoutPath[$i]['finished'], $scoutPath[$i]['total'], $scoutPath[$i]['icon']);
                    $this->printContainerInProgressEnd();
                }
                else{
                    if ($i == 0){
                        $this->printContainerInProgressStart($scoutPath[$i]['name'], $scoutPath[$i]['image'], $scoutPath[$i]['color'], $scoutPath[$i]['scout_path_id'], "scoutPath.php");
                    }
                    $this->printContainerInProgressMid($scoutPath[$i]['finished'], $scoutPath[$i]['total'], $scoutPath[$i]['icon'], '', '');
                    if ($i == 3){
                        $this->printContainerInProgressEnd();
                    }
                }
            }
        }
    }

    //TODO
    public function listMeritBadgesInProgress(): void
    {
        $meritBadges = $this->completedTasks->getMeritBadgesInProgress($_SESSION['user_id']);

        foreach ($meritBadges as $meritBadge){
            $image = $meritBadge['image'] . $meritBadge['level_image'];

            $this->printContainerInProgressStart($meritBadge['name'], $image,
                $meritBadge['color'], $meritBadge['merit_badge_id'], "meritBadges.php");
            $this->printContainerInProgressMid($meritBadge['finished'], $meritBadge['total'], 'task');
            $this->printContainerInProgressEnd();
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
    private function printContainerInProgressStart($name, $image, $color, $id, $siteType): void
    {
        echo '
            <a href="../pages/'.$siteType.'?id='.$id.'" class="taskContainer taskInProgressContainer">
            <span class="taskContainerHeading" style="background-color: '.$color.'">'.$name.'</span>
            <img class="taskContainerImage" src="../images/'.$image.'.png" alt="'.$name.'">
            <div class="tasksInProgress">
        ';
    }

    //TODO
    private function printContainerInProgressMid($finished, $total, $icon, $span_class = 'bigText', $img_class = 'bigIcon'): void
    {
        echo '
            <span class="tasksContainerPoints '.$span_class.'">'.$finished.' / '.$total.' &nbsp;<img class="taskContainerIcon '.$img_class.'" src="../images/'.$icon.'.png" alt="icon"></span>
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