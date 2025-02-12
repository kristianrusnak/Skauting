<?php

class Containers
{
    /**
     * @var CompletedTasksService
     */
    private CompletedTasksService $completedTasks;

    /**
     * @var ScoutPathService
     */
    private ScoutPathService $scoutPath;

    /**
     * @var MeritBadgeService
     */
    private MeritBadgeService $meritBadge;

    /**
     * @throws Exception
     */
    function __construct($completedTasks, $scoutPath, $meritBadge)
    {
        $this->completedTasks = $completedTasks;
        $this->scoutPath = $scoutPath;
        $this->meritBadge = $meritBadge;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function listMeritBadges(): void
    {
        echo '<div class="tasksContainer">';
        $meritBadges = $this->meritBadge->getAllMeritBadges();
        foreach ($meritBadges as $category_id => $meritBadgesInCategory){
            $this->printHeader($category_id);
            foreach ($meritBadgesInCategory as $meritBadge){
                $image = $this->getImageOfMeritBadge($meritBadge['id'], $meritBadge['image']);
                $this->printContainer($meritBadge['name'], $image, $meritBadge['color'], $meritBadge['id'], "meritBadges.php");
            }
        }
        echo '</div>';
    }

    /**
     * @return void
     */
    public function listScoutPaths(): void
    {
        $scoutPaths = $this->scoutPath->getScoutPaths();
        foreach ($scoutPaths as $scoutPath){
            $this->printContainer($scoutPath['name'], $scoutPath['image'], $scoutPath['color'], $scoutPath['id'], "scoutPath.php");
        }
    }

    /**
     * @return void
     */
    public function listScoutPathsInProgress(): void
    {
        $scoutPaths = $this->completedTasks->getScoutPathsInProgress($_COOKIE['user_id']);

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

    /**
     * @return void
     */
    public function listMeritBadgesInProgress(): void
    {
        $meritBadges = $this->completedTasks->getMeritBadgesInProgress($_COOKIE['user_id']);

        foreach ($meritBadges as $meritBadge){
            $image = $meritBadge['image'] . $meritBadge['level_image'];

            $this->printContainerInProgressStart($meritBadge['name'], $image,
                $meritBadge['color'], $meritBadge['merit_badge_id'], "meritBadges.php");
            $this->printContainerInProgressMid($meritBadge['finished'], $meritBadge['total'], 'task');
            $this->printContainerInProgressEnd();
        }
    }

    /**
     * @param $merit_Badge_id
     * @param $image
     * @return string
     * @throws Exception
     */
    private function getImageOfMeritBadge($merit_Badge_id, $image): string
    {
        return $image . $this->meritBadge->getMeritBadgeImage($merit_Badge_id);
    }

    /**
     * @param $name
     * @return void
     */
    private function printHeader($name): void
    {
        echo '<span class="tasksContainerCategory">'.$name.'</span>';
    }

    /**
     * @param $name
     * @param $image
     * @param $color
     * @param $id
     * @param $siteType
     * @return void
     */
    private function printContainer($name, $image, $color, $id, $siteType): void
    {
        echo '<a href="'.$siteType.'?id='.$id.'" class="taskContainer">
            <span class="taskContainerHeading" style="background-color: '.$color.'">'.$name.'</span>
            <img class="taskContainerImage" src="../images/'.$image.'.png" alt="'.$name.'">
            </a>';
    }

    /**
     * @param $name
     * @param $image
     * @param $color
     * @param $id
     * @param $siteType
     * @return void
     */
    private function printContainerInProgressStart($name, $image, $color, $id, $siteType): void
    {
        echo '
            <a href="../pages/'.$siteType.'?id='.$id.'" class="taskContainer taskInProgressContainer">
            <span class="taskContainerHeading" style="background-color: '.$color.'">'.$name.'</span>
            <img class="taskContainerImage" src="../images/'.$image.'.png" alt="'.$name.'">
            <div class="tasksInProgress">
        ';
    }

    /**
     * @param $finished
     * @param $total
     * @param $icon
     * @param $span_class
     * @param $img_class
     * @return void
     */
    private function printContainerInProgressMid($finished, $total, $icon, $span_class = 'bigText', $img_class = 'bigIcon'): void
    {
        echo '
            <span class="tasksContainerPoints '.$span_class.'">'.$finished.' / '.$total.' &nbsp;<img class="taskContainerIcon '.$img_class.'" src="../images/'.$icon.'.png" alt="icon"></span>
        ';
    }

    /**
     * @return void
     */
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