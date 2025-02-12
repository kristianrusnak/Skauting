<?php

class TasksLister
{
    /**
     * @var array
     */
    private array $task_mem = array();

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
     * @param $completedTasks
     * @param $scoutPath
     * @param $meritBadge
     */
    function __construct($completedTasks, $scoutPath, $meritBadge)
    {
        $this->completedTasks = $completedTasks;
        $this->scoutPath = $scoutPath;
        $this->meritBadge = $meritBadge;
    }

    public function printScript(): void
    {
        foreach ($this->task_mem as $task_id) {
            echo '
                <script>
                    document.getElementById("task_id_'.$task_id.'").addEventListener("click", function(){
                        let div_element = document.getElementById("task_container_'.$task_id.'");
                        let input_element = document.getElementById("task_id_'.$task_id.'");
                        let wait_element = document.getElementById("wait_id_'.$task_id.'");
                        const xhr = new XMLHttpRequest();
                        
                        if (input_element.checked){
                            xhr.open("POST", "../scripts/addTaskToUser.php", true);
                        }
                        else{
                            xhr.open("POST", "../scripts/removeTaskFromUser.php", true);
                        }
                        
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
                        // Key-value pairs as a query string
                        const data = "task_id='.$task_id.'";
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
                                    console.log("line");
                                }
                                else if(xhr.responseText === "text"){
                                    div_element.style.textDecoration = "none";
                                    wait_element.style.display = "inline";
                                    console.log("text");
                                }
                                else {
                                    input_element.checked = !input_element.checked;
                                    console.log("Response from PHP:", xhr.responseText);
                                }
                            } else {
                                input_element.checked = !input_element.checked;
                                console.log("Response from PHP:", xhr.responseText);
                            }
                        };
                    })
                </script>
            ';
        }
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

    private function printTaskListerContainerForMeritBadge($task_id, $task): void
    {
        $this->task_mem[] = $task_id;

        $line_through = $this->line_through_task($task_id);
        $is_checked = $this->is_checked($task_id);
        $can_be_unchecked = $this->can_be_unchecked($task_id);
        $wait_message = $this->wait_message($task_id);

        echo '
        <div class="tasksListerContainerTask" id="task_container_'.$task_id.'" '.$line_through.'>
            <input id="task_id_'.$task_id.'" type="checkbox" '.$is_checked.' '.$can_be_unchecked.'>
            <span class="wait_message" id="wait_id_'.$task_id.'" '.$wait_message.'>(Čakajúce schválenie)</span>
            <span class="tasksListerContainerTask">'.$task.'</span>
        </div>
        ';
    }

    public function listScoutPathTasks($scout_path_id): void
    {
        $scoutPaths = $this->scoutPath->getStructuredScoutPaths()[$scout_path_id];
        echo '<div class="tasksLister">';

        foreach ($scoutPaths as $area_name => $array1) {
            $this->printAreaOfScoutPathHeading($area_name);

            foreach ($array1 as $chapter_name => $array2) {
                $this->printChapterOfScoutPathHeading($chapter_name, $array2);

                foreach ($array2 as $mandatory => $tasks) {

                    if ($mandatory == 0) {
                        $this->printVoluntarilyBeginning($tasks[0]['color']);
                    }

                    foreach ($tasks as $task) {
                        $this->printScoutPathTask($task);
                    }


                }

                echo '</div>';
                echo '</div>';
            }

        }

        echo '</div>';
    }

    private function printScoutPathTask($task): void
    {
        $this->task_mem[] = $task['task_id'];

        $line_through = $this->line_through_task($task['task_id']);
        $is_checked = $this->is_checked($task['task_id']);
        $can_be_unchecked = $this->can_be_unchecked($task['task_id']);
        $wait_message = $this->wait_message($task['task_id']);

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
        if ($points == null){
            if ($task['position_id'] == 3){
                $points = "Body určí radca";
            }
            else if ($task['position_id'] == 4){
                $points = "body určí vodca";
            }
        }

        echo '
            <div class="tasksListerContainerTask" id="task_container_'.$task['task_id'].'" '.$line_through.'>
                <input id="task_id_'.$task['task_id'].'" type="checkbox" '.$is_checked.' '.$can_be_unchecked.'>
                <span class="wait_message" id="wait_id_'.$task['task_id'].'" '.$wait_message.'>(Čakajúce schválenie)</span>
                <span class="tasksListerContainerTaskName">'.$task['task_name'].'</span>
                <span class="tasksListerContainerPoints">'.$points.' <img class="tasksListerContainerImage" src="../images/'.$task['icon'].'.png" alt="Ikona Bodov"></span>
                <span> - </span>
                <span class="tasksListerContainerTask">'.$task['task'].'</span>
                <img class="tasksListerContainerImage" src="../images/'.$position_icon.'.png" alt="Kdo kontroluje úlohu">
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

    private function printChapterOfScoutPathHeading($chapter_name, $array): void
    {
        echo '<div class="tasksListerContainerMain">';
        if (isset($array[0])) {
            echo '<div class="tasksListerContainerFilled" style="background-color: '.$array[0][0]['color'].'">';
        }
        else{
            echo '<div class="tasksListerContainerFilled" style="border-radius: 15px; background-color: '.$array[1][0]['color'].';">';
        }
        echo '<h1 class="tasksListerContainerHeading">'.$chapter_name.'</h1>';
    }

    private function printAreaOfScoutPathHeading($name): void
    {
        echo '<h1 class="tasksListerHeading">'.$name.'</h1>';
    }

    private function line_through_task($task_id): string
    {
        if ($this->completedTasks->isTaskVerified($task_id, $_COOKIE['view_users_task_id'])) {
            return 'style="text-decoration: line-through"';
        }
        return '';
    }

    private function is_checked($task_id): string
    {
        if ($this->completedTasks->isTaskInCompletedTasks($task_id, $_COOKIE['view_users_task_id'])) {
            return 'checked';
        }
        return '';
    }

    private function can_be_unchecked($task_id): string
    {
        if (!$this->completedTasks->canUncheckTask($task_id, $_COOKIE['view_users_task_id'], $_COOKIE['position_id'])) {
            return 'disabled';
        }
        return '';
    }

    private function wait_message($task_id): string
    {
        if ($this->completedTasks->isTaskUnverified($task_id, $_COOKIE['view_users_task_id'])) {
            return 'style="display: inline;"';
        }
        return 'style="display: none;"';
    }
}

?>