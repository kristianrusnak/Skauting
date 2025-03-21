<?php

class TasksLister
{
    /**
     * @var array
     */
    private array $task_mem = array();

    private array $num_text_mem = array();

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
     * @var MatchTaskService
     */
    private MatchTaskService $matchTask;

    /**
     * @param $completedTasks
     * @param $scoutPath
     * @param $meritBadge
     */
    function __construct($completedTasks, $scoutPath, $meritBadge, $matchTask)
    {
        $this->completedTasks = $completedTasks;
        $this->scoutPath = $scoutPath;
        $this->meritBadge = $meritBadge;
        $this->matchTask = $matchTask;
    }

    public function printScript(): void
    {
        echo '
            <script>
            
            function submitTask(task_id) {
                try {
                    let checkbox = document.getElementById("task_id_"+task_id);
                    checkbox.dispatchEvent(new Event("click"))
                } catch (error) {}
            }
            
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
        ';

        foreach ($this->num_text_mem as $task_id) {
            echo '
                    document.getElementById("input_id_'.$task_id.'").addEventListener("change", function(){
                        const checkbox = document.getElementById("task_id_'.$task_id.'");
                        let number = document.getElementById("input_id_'.$task_id.'");
                        
                        if (number.value < 1 && number.value !== "") {
                            number.value = 1;
                        }
                        
                        if (number.value === "") {
                            checkbox.checked = false;
                        }
                        else {
                            checkbox.checked = true;
                        }
                        checkbox.dispatchEvent(new Event("click"));
                    })
            ';
        }

        foreach ($this->task_mem as $task_id) {
            echo '
                    document.getElementById("task_id_'.$task_id.'").addEventListener("click", function(){
                        let div_element = document.getElementById("task_container_'.$task_id.'");
                        let checkbox = document.getElementById("task_id_'.$task_id.'");
                        let wait_element = document.getElementById("wait_id_'.$task_id.'");
                        const xhr = new XMLHttpRequest();
                        
                        if (checkbox.checked){
                            xhr.open("POST", "../scripts/addTaskToUser.php", true);
                        }
                        else{
                            xhr.open("POST", "../scripts/removeTaskFromUser.php", true);
                        }
                        
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
                        // Key-value pairs as a query string
                        let data = "task_id='.$task_id.'";
                        
                        try {
                            const number = document.getElementById("input_id_'.$task_id.'").value;
                            if (number === "" && checkbox.checked === true) {
                                checkbox.checked = false;
                                return;
                            }
                            data += "&points=" + number;
                        } catch (error) {}
                        
                        xhr.send(data);
            
                        // Handle the response from PHP
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                if (xhr.responseText === "delete"){
                                    div_element.style.textDecoration = "none";
                                    wait_element.style.display = "none";
                                    console.log("delete");
                                }
                                else if(xhr.responseText === "line"){
                                    div_element.style.textDecoration = "line-through";
                                    wait_element.style.display = "none";
                                    
                                    try {
                                        document.getElementById("button_id_'.$task_id.'").style.display = "none";
                                    }catch (error) {}
                                    
                                    console.log("line");
                                }
                                else if(xhr.responseText === "text"){
                                    div_element.style.textDecoration = "none";
                                    wait_element.style.display = "inline";
                                    console.log("text");
                                }
                                else {
                                    checkbox.checked = !checkbox.checked;
                                    console.log("Response from PHP1:", xhr.responseText);
                                }
                            } else {
                                checkbox.checked = !checkbox.checked;
                                console.log("Response from PHP2:", xhr.responseText);
                            }
                        };
                    })
            ';
        }

        echo '
            </script>
        ';
    }

    public function listMeritBadgeTasks($merit_badge_id): void
    {
        $meritBadges = $this->meritBadge->getTasksFromMeritBadge($merit_badge_id);

        echo '<div class="tasksLister">';

        foreach ($meritBadges as $level_id => $meritBadge) {
            $first = true;
            foreach ($meritBadge as $task) {
                if ($first) {
                    $first = false;
                    $this->printStartOfTaskListerContainerForMeritBadge($task['level_name'], $task['level_color']);
                }
                $this->printTaskListerContainerForMeritBadge($task['task_id'], $task['task']);
            }
            $this->printEndOfTaskListerContainerForMeritBadge();
        }

        echo '</div>';

        $this->listMatchTasks();
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
            <input id="task_id_'.$task_id.'" type="checkbox" '.$is_checked.' '.$can_be_unchecked.'>
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
        $areas = $this->scoutPath->getAreasOfScoutPath();

        echo '<div class="tasksLister">';

        foreach ($areas as $area) {
            $this->printAreaOfScoutPathHeading($area['name']);
            $chapters = $this->scoutPath->getChaptersOfScoutPath($scout_path_id, $area['id']);

            foreach ($chapters as $chapter) {
                $this->printChapterOfScoutPathHeading($chapter['name'], $area['color']);
                $tasks = $this->scoutPath->getTasksFromChapter($chapter['id']);
                $mandatory_flag = true;

                foreach ($tasks as $task) {

                    if ($task['mandatory'] == 0 && $mandatory_flag) {
                        $mandatory_flag = false;
                        $this->printVoluntarilyBeginning($tasks[0]['color']);
                    }

                    $this->printScoutPathTask($task);
                }

                echo '</div>';
                echo '</div>';
            }

        }

        echo '</div>';

        $this->listMatchTasks();
    }

    private function printScoutPathTask($task, $show = true): void
    {
        $this->task_mem[] = $task['task_id'];

        $line_through = $this->line_through_task($task['task_id']);
        $is_checked = $this->is_checked($task['task_id']);
        $can_be_unchecked = $this->can_be_unchecked($task['task_id']);
        $wait_message = $this->wait_message($task['task_id']);

        $match = "";
        if ($show) {
            $match = '<img class="tasksListerContainerImage cursor_pointer" src="../images/match.png" onclick="showMatchTasks('.$task['task_id'].')">';
        }

        $position_icon = 'letter_';
        if ($task['position_id'] == 1){
            $position_icon .= 'j';
        }
        else if ($task['position_id'] == 2){
            $position_icon .= 'r';
        }
        else if ($task['position_id'] >= 3){
            $position_icon .= 'v';
        }

        $points = $task['points'];
        $points_flag = false;
        $value_flag = false;

        if ($points == null){
            $points_flag = true;

            if ($this->completedTasks->isTaskVerified($task['task_id'], $_SESSION['view_users_task_id'])) {
                $points = $this->completedTasks->getPointsForCompletedTask($task['task_id'], $_SESSION['view_users_task_id']);
                $value_flag = true;
            }
            else if ($task['position_id'] == 3) {
                $points = "(Body určí radca)";
            }
            else if ($task['position_id'] == 4) {
                $points = "(body určí vodca)";
            }
        }

        echo '
            <div class="tasksListerContainerTask" id="task_container_'.$task['task_id'].'" '.$line_through.'>
                <input id="task_id_'.$task['task_id'].'" type="checkbox" '.$is_checked.' '.$can_be_unchecked.'>
        ';

        if ($this->showApprovalButton($task['task_id']) && !$points_flag && $_SESSION['position_id'] >= $task['position_id']) {
            echo '
                    <button id="button_id_'.$task['task_id'].'" onclick="submitTask('.$task['task_id'].')">Schváliť</button>
                ';
        }

        echo '
                <span class="wait_message" id="wait_id_'.$task['task_id'].'" '.$wait_message.'>(Čakajúce schválenie)</span>
                <span class="tasksListerContainerTaskName">'.$task['task_name'].'</span>        
        ';

        if ($points_flag && $_SESSION['position_id'] >= $task['position_id']) {
            $this->num_text_mem[] = $task['task_id'];
            $num_value = "";
            if ($value_flag) {
                $num_value = $points;
            }

            echo '
                <input type="number" min="1" value="'.$num_value.'" class="tasksListerPointsInput" id="input_id_'.$task['task_id'].'">
                <span class="tasksListerContainerPoints"><img class="tasksListerContainerImage" src="../images/'.$task['icon'].'.png" alt="Ikona Bodov"></span>
            ';
        }
        else {

            echo '
                <span class="tasksListerContainerPoints">'.$points.' <img class="tasksListerContainerImage" src="../images/'.$task['icon'].'.png" alt="Ikona Bodov"></span>
            ';
        }

        echo '
                <span> - </span>
                <span class="tasksListerContainerTask">'.$task['task'].'</span>
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

    private function printScoutPathHeader($scout_path_id): void
    {
        $scout_path = $this->scoutPath->getScoutPath($scout_path_id);

        echo '
            <div class="matchTaskHeader" style="border-top: 3px dashed '.$scout_path['color'].'">
                <span>Skautský chodník:</span>
                <a href="../pages/scoutPath.php?id='.$scout_path_id.'">'.$scout_path['name'].'</a>
            </div>
        ';
    }

    private function printMeritBadgeHeader($merit_badge_id): void
    {
        $merit_badge = $this->meritBadge->getMeritBadge($merit_badge_id);

        echo '
            <div class="matchTaskHeader" style="border-top: 3px dashed '.$merit_badge['color'].'">
                <span>Odborka:</span>
                <a href="../pages/meritBadges.php?id='.$merit_badge_id.'">'.$merit_badge['name'].'</a>
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
        $tasks = $this->matchTask->getMatchTask($task_id);

        echo '
            <div id="outer_match_container_'.$task_id.'" class="outerMatchContainer">
                <div id="inner_match_container_'.$task_id.'" class="innerMatchContainer">
                    <button onclick="hideMatchTasks('.$task_id.')">zavriet</button>
                    <span>Ulohy s rovnakym obsahom</span>
        ';

        foreach ($tasks as $match_task_id) {
            $task = $this->meritBadge->getMeritBadgeTask($match_task_id['match_task_id']);
            if (empty($task)) {
                $task = $this->scoutPath->getScoutPathTask($match_task_id['match_task_id']);
            }
            if (empty($task)) {
                continue;
            }

            if (isset($task['scout_path_id'])) {
                $this->printScoutPathHeader($task['scout_path_id']);
                $this->printScoutPathTask($task, false);
            }
            else if (isset($task['merit_badge_id'])) {
                $this->printMeritBadgeHeader($task['level_id'], $task['merit_badge_id']);
                $this->printTaskListerContainerForMeritBadge($task['task_id'], $task['task'], false);
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