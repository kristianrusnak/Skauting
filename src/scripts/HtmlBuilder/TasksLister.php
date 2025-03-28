<?php

namespace HtmlBuilder;

require_once dirname(__DIR__) . '/Tasks/Service/CompletedTasksService.php';
require_once dirname(__DIR__) . '/ScoutPath/Service/ScoutPathService.php';
require_once dirname(__DIR__) . '/MeritBadge/Service/MeritBadgeService.php';
require_once dirname(__DIR__) . '/Tasks/Service/MatchTaskService.php';

use Task\Service\CompletedTasksService as CompletedTasks;
use ScoutPath\Service\ScoutPathService as ScoutPath;
use MeritBadge\Service\MeritBadgeService as MeritBadge;
use Task\Service\MatchTaskService as MatchTask;

class TasksLister
{
    /**
     * @var array
     */
    private array $task_mem = array();

    private array $num_text_mem = array();

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

    /**
     * @var MatchTask
     */
    private MatchTask $matchTask;

    /**
     * @param $completedTasks
     * @param $scoutPath
     * @param $meritBadge
     */
    function __construct($database)
    {
        $this->completedTasks = new CompletedTasks($database);
        $this->scoutPath = new ScoutPath();
        $this->meritBadge = new MeritBadge();
        $this->matchTask = new MatchTask();
    }

    public function printScript(): void
    {
        echo '
            <script>
            
            function showMatchTasks(task_id) {
                document.getElementById("outer_match_container_"+task_id).style.display = "block";
                document.getElementById("inner_match_container_"+task_id).style.display = "block";
                document.body.style.overflow = "hidden"; // Disable scrolling
            }
            
            function hideMatchTasks(task_id) {
                document.getElementById("outer_match_container_"+task_id).style.display = "none";
                document.getElementById("inner_match_container_"+task_id).style.display = "none";
                document.body.style.overflow = ""; // Restore scrolling
            }
            
            function handleServerResponse(task_id, response) {
                let divElement = document.getElementById("task_container_"+task_id);
                let checkbox = document.getElementById("task_id_"+task_id);
                let wait_element = document.getElementById("wait_id_" + task_id);
                
                if (response["error"]) {
                    checkbox.checked = !checkbox.checked;
                    alert(response["errorMessage"]);
                }
                
                if (response["operation"] === "add") {
                    if (response["has_to_be_approved"]) {
                        divElement.style.textDecoration = "none";
                        wait_element.style.display = "inline";
                    }
                    else if (response["is_approved"]) {
                        divElement.style.textDecoration = "line-through";
                        wait_element.style.display = "none";
                        
                        try {
                            document.getElementById("button_id_"+task_id).style.display = "none";
                        }catch (error) {}
                    }
                }
                else if (response["operation"] === "remove") {
                    divElement.style.textDecoration = "none";
                    wait_element.style.display = "none";
                    
                    try {
                        document.getElementById("input_id_"+task_id).value = "";
                    }
                    catch (error) {}
                    try {
                        document.getElementById("button_id_"+task_id).style.display = "none";
                    }catch (error) {}
                }
            }
            
            function changePoints(task_id) {
                let points = document.getElementById("input_id_"+task_id).value;
                
                if (points === "") {
                    document.getElementById("task_id_"+task_id).checked = false;
                    submitTask(task_id, points);
                }
                else if (points <= 0) {
                    alert("Zadajte body alebo opravte body na kladne cislo");
                }
                else {
                    document.getElementById("task_id_"+task_id).checked = true;
                    submitTask(task_id, points);
                }
            }
            
            function submitTask(task_id, points = null) {
                console.log(task_id, points);
                
                let checkbox = document.getElementById("task_id_"+task_id);
                
                try{
                    let temp_points = document.getElementById("input_id_"+task_id).value;
                    if (temp_points === "" && checkbox.checked) {
                        alert("Zadajte body alebo opravte body na kladne cislo");
                        checkbox.checked = false;
                        return;
                    }
                } catch (error) {}
                
                let pathway;
                if (checkbox.checked) {
                    pathway = "../APIs/addTaskToUser.php";
                }
                else {
                    pathway = "../APIs/removeTaskFromUser.php";
                }
                
                const data = {
                    task_id: task_id, 
                    points: points 
                };
                
                fetch(pathway, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(response => handleServerResponse(task_id, response))
                .catch(error => alert(error.message));
            }
            
            </script>
        ';
    }

    public function listMeritBadgeTasks(int $merit_badge_id): void
    {
        $levels = $this->meritBadge->getLevels();

        echo '<div class="tasksLister">';

        foreach ($levels as $level) {
            $this->printStartOfTaskListerContainerForMeritBadge($level->name, $level->color);
            $tasks = $this->meritBadge->getTasksByMeritBadgeIdAndLevelId($merit_badge_id, $level->id);

            foreach ($tasks as $task) {
                $this->printTaskListerContainerForMeritBadge($task->task_id, $task->task);
            }

            $this->printEndOfTaskListerContainerForMeritBadge();
        }

        echo '</div>';

        //$this->listMatchTasks();
    }

    private function printStartOfTaskListerContainerForMeritBadge($level_name, $color): void
    {
        echo '
        <div class="tasksListerContainerMain">
        <div class="tasksListerContainerFilled" style="background-color: '.$color.';">
        <h1 class="tasksListerHeading">'.$level_name.'</h1>
        </div>
        <div class="tasksListerContainerEmpty" style="border: 3px dashed '.$color.'">
        ';
    }

    private function printEndOfTaskListerContainerForMeritBadge(): void
    {
        echo '
        </div>
        </div>
        ';
    }

    private function printTaskListerContainerForMeritBadge($task_id, $task, $show = true): void
    {
        $this->task_mem[] = $task_id;

        $line_through = $this->line_through_task($task_id);
        $is_checked = $this->is_checked($task_id);
        $can_be_unchecked = $this->can_be_unchecked($task_id);
        $wait_message = $this->wait_message($task_id);

        $match = "";
        if ($show) {
            $match = '<img class="tasksListerContainerImage cursor_pointer" src="../images/match.png" onclick="showMatchTasks('.$task_id.')">';
        }

        echo '
        <div class="tasksListerContainerTask" id="task_container_'.$task_id.'" '.$line_through.'>
            <input id="task_id_'.$task_id.'" type="checkbox" '.$is_checked.' '.$can_be_unchecked.' onchange="submitTask('.$task_id.')">
        ';

        $tasks = $this->meritBadge->getMeritBadgeTask($task_id);

        if ($this->showApprovalButton($task_id) && $_SESSION['position_id'] >= $tasks['position_id']) {
            echo '
                <button id="button_id_'.$task_id.'" onclick="submitTask('.$task_id.')">Schváliť</button>
            ';
        }

        echo '
            <span class="wait_message" id="wait_id_'.$task_id.'" '.$wait_message.'>(Čakajúce schválenie)</span>
            <span class="tasksListerContainerTask">'.$task.'</span>
            '.$match.'
        </div>
        ';
    }

    public function listScoutPathTasks($scout_path_id): void
    {
        $areas = $this->scoutPath->getAreas();

        echo '<div class="tasksLister">';

        foreach ($areas as $area) {
            $this->printAreaOfScoutPathHeading($area->name);
            $chapters = $this->scoutPath->getChaptersOfScoutPath($scout_path_id, $area->id);

            foreach ($chapters as $chapter) {
                $this->printChapterOfScoutPathHeading($chapter->name, $area->color);
                $tasks = $this->scoutPath->getTasksFromChapter($chapter->id);
                $mandatory_flag = true;

                foreach ($tasks as $task) {

                    if ($task->mandatory == 0 && $mandatory_flag) {
                        $mandatory_flag = false;
                        $this->printVoluntarilyBeginning($area->color);
                    }

                    $rp = $this->scoutPath->getRequired($scout_path_id, $area->id);
                    $this->printScoutPathTask($task, $rp);
                }

                echo '</div>';
                echo '</div>';
            }

        }

        echo '</div>';

        //$this->listMatchTasks();
    }

    private function printScoutPathTask($task, $rp, $show = true): void
    {
        $this->task_mem[] = $task->task_id;

        $line_through = $this->line_through_task($task->task_id);
        $is_checked = $this->is_checked($task->task_id);
        $can_be_unchecked = $this->can_be_unchecked($task->task_id);
        $wait_message = $this->wait_message($task->task_id);

        $match = "";
        if ($show) {
            $match = '<img class="tasksListerContainerImage cursor_pointer" src="../images/match.png" onclick="showMatchTasks('.$task->task_id.')">';
        }

        $position_icon = 'letter_';
        if ($task->position_id == 1){
            $position_icon .= 'j';
        }
        else if ($task->position_id  == 2){
            $position_icon .= 'r';
        }
        else if ($task->position_id  >= 3){
            $position_icon .= 'v';
        }

        $points = $task->points;
        $points_flag = false;
        $value_flag = false;

        if ($points == null){
            $points_flag = true;

            if ($this->completedTasks->isTaskVerified($task->task_id, $_SESSION['view_users_task_id'])) {
                $points = $this->completedTasks->getPointsForCompletedTask($task->task_id, $_SESSION['view_users_task_id']);
                $value_flag = true;
            }
            else if ($task->position_id == 3) {
                $points = "(Body určí radca)";
            }
            else if ($task->position_id == 4) {
                $points = "(body určí vodca)";
            }
        }

        echo '
            <div class="tasksListerContainerTask" id="task_container_'.$task->task_id.'" '.$line_through.'>
                <input id="task_id_'.$task->task_id.'" type="checkbox" '.$is_checked.' '.$can_be_unchecked.' onchange="submitTask('.$task->task_id.')">
        ';

        if ($this->showApprovalButton($task->task_id) && !$points_flag && $_SESSION['position_id'] >= $task->position_id) {
            echo '
                    <button id="button_id_'.$task->task_id.'" onclick="submitTask('.$task->task_id.')">Schváliť</button>
                ';
        }

        echo '
                <span class="wait_message" id="wait_id_'.$task->task_id.'" '.$wait_message.'>(Čakajúce schválenie)</span>
                <span class="tasksListerContainerTaskName">'.$task->name.'</span>        
        ';

        if ($points_flag && $_SESSION['position_id'] >= $task->position_id) {
            $this->num_text_mem[] = $task->task_id;
            $num_value = "";

            if ($value_flag) {
                $num_value = $points;
            }

            echo '
                <input type="number" min="1" value="'.$num_value.'" onchange="changePoints('.$task->task_id.')" class="tasksListerPointsInput" id="input_id_'.$task->task_id.'">
                <span class="tasksListerContainerPoints"><img class="tasksListerContainerImage" src="../images/'.$rp->icon.'.png" alt="Ikona Bodov"></span>
            ';
        }
        else {
            echo '
                <span class="tasksListerContainerPoints">'.$points.' <img class="tasksListerContainerImage" src="../images/'.$rp->icon.'.png" alt="Ikona Bodov"></span>
            ';
        }

        echo '
                <span> - </span>
                <span class="tasksListerContainerTask">'.$task->task.'</span>
                <img class="tasksListerContainerImage" src="../images/'.$position_icon.'.png" alt="Kdo kontroluje úlohu">
                '.$match.'
            </div>
        ';
    }

    private function printVoluntarilyBeginning($color): void
    {
        echo '
            </div>
            <div class="tasksListerContainerEmpty" style="border: 3px dashed '.$color.'">
            <h1 class="tasksListerContainerSecond">Volitelná časť</h1>
        ';
    }

    private function printChapterOfScoutPathHeading($chapter_name, $color, $rounded = true): void
    {
        echo '<div class="tasksListerContainerMain">';
        if ($rounded) {
            echo '<div class="tasksListerContainerFilled" style="background-color: '.$color.'">';
        }
        else{
            echo '<div class="tasksListerContainerFilled" style="border-radius: 15px; background-color: '.$color.';">';
        }
        echo '<h1 class="tasksListerContainerHeading">'.$chapter_name.'</h1>';
    }

    private function printAreaOfScoutPathHeading($name): void
    {
        echo '<h1 class="tasksListerHeading">'.$name.'</h1>';
    }

    private function printScoutPathHeader(object $path): void
    {

        echo '
            <div class="matchTaskHeader" style="border-top: 3px dashed '.$path->color.'">
                <span>Skautský chodník:</span>
                <a href="../pages/scoutPath.php?id='.$path->id.'">'.$path->name.'</a>
            </div>
        ';
    }

    private function printMeritBadgeHeader($badge): void
    {
        echo '
            <div class="matchTaskHeader" style="border-top: 3px dashed '.$badge->color.'">
                <span>Odborka:</span>
                <a href="../pages/meritBadges.php?id='.$badge->id.'">'.$badge->name.'</a>
            </div>
        ';
    }

    private function listMatchTasks(): void
    {
        foreach ($this->task_mem as $task_id) {
            $this->printMatchTasksContainer($task_id);
        }
    }

    private function printMatchTasksContainer($task_id): void
    {
        $matches = $this->matchTask->getMatchTask($task_id);

        echo '
            <div id="outer_match_container_'.$task_id.'" class="outerMatchContainer">
                <div id="inner_match_container_'.$task_id.'" class="innerMatchContainer">
                    <button onclick="hideMatchTasks('.$task_id.')">zavriet</button>
                    <span>Ulohy s rovnakym obsahom</span>
        ';

        foreach ($matches as $match) {

            $task = $this->meritBadge->getMeritBadgeTask($match->match_task_id);
            if (isset($task->task_id)) {
                $badge = $this->meritBadge->getMeritBadge($task->merit_badge_id);
                $this->printMeritBadgeHeader($badge);
                $this->printTaskListerContainerForMeritBadge($task->task_id, $task->task, false);
                continue;
            }

            $task = $this->scoutPath->getScoutPathTask($match->match_task_id);
            if (!empty($task)) {
                $rp = $this->scoutPath->getRequiredByChapterId($task->chapter_id);
                $path = $this->scoutPath->getScoutPathByChapterId($task->chapter_id);
                $this->printScoutPathHeader($path);
                $this->printScoutPathTask($task, $rp, false);
                continue;
            }
        }

        echo '
                </div>
            </div>
        ';
    }

    private function line_through_task($task_id): string
    {
        if ($this->completedTasks->isTaskVerified($task_id, $_SESSION['view_users_task_id'])) {
            return 'style="text-decoration: line-through"';
        }
        return '';
    }

    private function is_checked($task_id): string
    {
        if ($this->completedTasks->isTaskInCompletedTasks($task_id, $_SESSION['view_users_task_id'])) {
            return 'checked';
        }
        return '';
    }

    private function can_be_unchecked($task_id): string
    {
        if (!$this->completedTasks->canUncheckTask($task_id, $_SESSION['view_users_task_id'], $_SESSION['position_id'])) {
            return 'disabled';
        }
        return '';
    }

    private function showApprovalButton($task_id): bool
    {
        if ($this->completedTasks->isTaskUnverified($task_id, $_SESSION['view_users_task_id'])) {
            return true;
        }
        return false;
    }

    private function wait_message($task_id): string
    {
        if ($this->completedTasks->isTaskUnverified($task_id, $_SESSION['view_users_task_id'])) {
            return 'style="display: inline;"';
        }
        return 'style="display: none;"';
    }
}

?>