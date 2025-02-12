<?php
class TasksLister{
    private $task_id_mem = array();

    function __construct($mysqli, $id, $category){
        $id = sanitizeInput($id);
        if ($category == 'merit_badges'){
            $this->list_merit_badge($mysqli, $id);
        }
        else if ($category == 'scout_path') {
            $this->list_scout_path($mysqli, $id);
        }
        $this->create_javascript_for_tasks_lister($mysqli);
    }

    private function list_merit_badge($mysqli, $id){
        if (!$mysqli->connect_errno){
            echo '<div class="tasksLister">';
            $sql = "SELECT * FROM merit_badge_tasks WHERE merit_badge_id = '$id' AND level_id = 1";
            $this->list_merit_badges_tasks($mysqli, $sql, "Zelený stupeň", "#8fff79");
            $sql = "SELECT * FROM merit_badge_tasks WHERE merit_badge_id = '$id' AND level_id = 2";
            $this->list_merit_badges_tasks($mysqli, $sql, "Červený stupeň", "#ff7979");
            echo '</div>';
        }
    }

    private function list_merit_badges_tasks($mysqli, $sql, $level, $color){
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
            echo '<div class="tasksListerContainerMain">';
            echo '<div class="tasksListerContainerFilled" style="background-color: '.$color.';">';
            echo '<h1 class="tasksListerHeading">'.$level.'</h1>';
            echo '</div>';
            echo '<div class="tasksListerContainerEmpty" style="border: 3px dashed '.$color.'">';
            while ($row = $result->fetch_assoc()){
                $task = $this->get_content_of_task($mysqli, $row['task_id']);
                echo '<div class="tasksListerContainerTask" id="task_container_'.$row['task_id'].'" '.$this->line_through_task($mysqli, $row['task_id'], $_COOKIE["user_id"]).'>
                            <input id="task_id_'.$row['task_id'].'" type="checkbox" '.$this->is_input_checked($mysqli, $row["task_id"], $_COOKIE["user_id"]).' '.can_be_task_unchecked($mysqli,  $row['task_id']).'>
                            <span class="wait_message" id="wait_id_'.$row['task_id'].'" '.$this->show_wait_message($mysqli, $row['task_id'], $_COOKIE["user_id"]).'>(Čakajúce schválenie)</span>
                            <span class="tasksListerContainerTask">'.$task.'</span>
                        </div>';
                $this->appand_task_id_to_mem($row['task_id']);
            }
            echo '</div>';
            echo '</div>';
        }
    }

    private function list_scout_path($mysqli, $id){
        $last_area = 0;
        if (!$mysqli->connect_errno){
            $sql1 = "SELECT * FROM chapters_of_scout_path WHERE scout_path_id = ".$id;
            if (($result1 = $mysqli->query($sql1)) && ($result1->num_rows > 0)){
                echo '<div class="tasksLister">';
                //outer loop
                while ($row1 = $result1->fetch_assoc()){
                    if ($row1['area_id'] != $last_area) {
                        $last_area = $row1['area_id'];
                        $name = $this->get_name_of_area_of_scout_path($mysqli, $row1['area_id'], $row1['scout_path_id']);
                        echo '<h1 class="tasksListerHeading">'.$name.'</h1>';
                    }
                    //inner loop
                    echo '<div class="tasksListerContainerMain">';
                    $sql2 = "SELECT * FROM scout_path_tasks WHERE chapter_id = ".$row1['id'];
                    if (($result2 = $mysqli->query($sql2)) && ($result2->num_rows > 0)){
                        $flag = true;
                        $color = $this->get_color_of_scout_path_area($mysqli, $row1['area_id']);
                        if ($this->are_all_scout_path_tasks_mandatory($result2)){
                            echo '<div class="tasksListerContainerFilled" style="border-radius: 15px; background-color: '.$color.';">';
                        }
                        else{
                            echo '<div class="tasksListerContainerFilled" style="background-color: '.$color.'">';
                        }
                        $result2->data_seek(0);
                        echo '<h1 class="tasksListerContainerHeading">'.$row1['name'].'</h1>';
                        while ($row2 = $result2->fetch_assoc()){
                            if ($row2['mandatory'] == 0 && $flag){
                                $flag = false;
                                echo '</div>';
                                echo '<div class="tasksListerContainerEmpty" style="border: 3px dashed '.$color.'">';
                                echo '<h1 class="tasksListerContainerSecond">Volitelná časť</h1>';
                            }
                            $name = $this->get_name_of_task($mysqli, $row2['task_id']);
                            $task = $this->get_content_of_task($mysqli, $row2['task_id']);
                            $position_icon = $this->get_task_icon_position($mysqli, $row2['task_id']);
                            $detail = $this->get_details_about_scout_path_chapter($mysqli, $id, $row1['area_id']);
                            if ($detail['type_of_points'] == 'Uloha'){
                                echo '<div class="tasksListerContainerTask" id="task_container_'.$row2['task_id'].'" '.$this->line_through_task($mysqli, $row2['task_id'], $_COOKIE["user_id"]).'>
                                    <input id="task_id_'.$row2['task_id'].'" type="checkbox" '.$this->is_input_checked($mysqli, $row2["task_id"], $_COOKIE["user_id"]).' '.can_be_task_unchecked($mysqli,  $row2['task_id']).'>
                                    <span class="wait_message" id="wait_id_'.$row2['task_id'].'" '.$this->show_wait_message($mysqli, $row2['task_id'], $_COOKIE["user_id"]).'>(Čakajúce schválenie)</span>
                                    <span class="tasksListerContainerTaskName">'.$name.'</span>
                                    <span> - </span>
                                    <span class="tasksListerContainerTask">'.$task.'</span>
                                    <img class="tasksListerContainerImage" src="../images/'.$position_icon.'.png" alt="Kdo kontroluje úlohu">
                                  </div>';
                            }
                            else{
                                if ($row2['points'] == null){
                                    $position_id = $this->get_position_id_of_task($mysqli, $row2['task_id']);
                                    if ($position_id == 3){
                                        $row2['points'] = "(".$detail['type_of_points']." určí radca)";
                                    }
                                    else {
                                        $row2['points'] = "(".$detail['type_of_points']." určí vodca)";
                                    }
                                }
                                echo '<div class="tasksListerContainerTask" id="task_container_'.$row2['task_id'].'" '.$this->line_through_task($mysqli, $row2['task_id'], $_COOKIE["user_id"]).'>
                                    <input id="task_id_'.$row2['task_id'].'" type="checkbox" '.$this->is_input_checked($mysqli, $row2["task_id"], $_COOKIE["user_id"]).' '.can_be_task_unchecked($mysqli,  $row2['task_id']).'>
                                    <span class="wait_message" id="wait_id_'.$row2['task_id'].'" '.$this->show_wait_message($mysqli, $row2['task_id'], $_COOKIE["user_id"]).'>(Čakajúce schválenie)</span>
                                    <span class="tasksListerContainerTaskName">'.$name.'</span>
                                    <span class="tasksListerContainerPoints">'.$row2['points'].' <img class="tasksListerContainerImage" src="../images/'.$detail['icon'].'.png" alt="Ikona Bodov"></span>
                                    <span> - </span>
                                    <span class="tasksListerContainerTask">'.$task.'</span>
                                    <img class="tasksListerContainerImage" src="../images/'.$position_icon.'.png" alt="Kdo kontroluje úlohu">
                                  </div>';
                            }
                            $this->appand_task_id_to_mem($row2['task_id']);
                        }
                        echo '</div>';
                    }
                    //end of inner loop
                    echo '</div>';
                }
                //end of outer loop
                echo '</div>';
            }
        }
    }

    private function create_javascript_for_tasks_lister($mysqli){
        for ($i = 0; $i < count($this->task_id_mem); $i++){
            echo '
            <script>
                document.getElementById("task_id_'.$this->task_id_mem[$i].'").addEventListener("click", function(){
                    let div_element = document.getElementById("task_container_'.$this->task_id_mem[$i].'");
                    let input_element = document.getElementById("task_id_'.$this->task_id_mem[$i].'");
                    let wait_element = document.getElementById("wait_id_'.$this->task_id_mem[$i].'");
                    const xhr = new XMLHttpRequest();
                    
                    if (input_element.checked){
                        xhr.open("POST", "../scripts/addTaskToUser.php", true);
                    }
                    else{
                        xhr.open("POST", "../scripts/removeTaskFromUser.php", true);
                    }
                    
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    // Key-value pairs as a query string
                    const data = "task_id='.$this->task_id_mem[$i]. '";
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
                                if (input_element.checked){
                                    input_element.checked = false;
                                }
                                else {
                                    input_element.checked = true;
                                }
                                //console.log("Response from PHP:", xhr.responseText);
                            }
                        } else {
                            if (input_element.checked){
                                    input_element.checked = false;
                                }
                                else {
                                    input_element.checked = true;
                                }
                            //console.log("Response from PHP:", xhr.responseText);
                        }
                    };
                })
            </script>
            ';
        }
    }

    private function appand_task_id_to_mem($task_id){
        if (!in_array($task_id, $this->task_id_mem)){
            $this->task_id_mem[] = $task_id;
        }
    }

    private function is_input_checked($mysqli, $task_id, $user_id){
        if (!$mysqli->connect_errno){
            $sql = "SELECT * FROM complited_tasks WHERE task_id = ".$task_id." AND user_id = ".$user_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                return 'checked';
            }
        }
        return '';
    }

    private function show_wait_message($mysqli, $task_id, $user_id){
        if (!$mysqli->connect_errno){
            $sql = "SELECT * FROM complited_tasks WHERE task_id = ".$task_id." AND user_id = ".$user_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $row = $result->fetch_assoc();
                if ($row['verified'] == 0){
                    return 'style="display: inline;"';
                }
                return 'style="display: none;"';
            }
        }
        return 'style="display: none;"';
    }

    private function line_through_task($mysqli, $task_id, $user_id){
        if (!$mysqli->connect_errno){
            $sql = "SELECT * FROM complited_tasks WHERE task_id = ".$task_id." AND user_id = ".$user_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $row = $result->fetch_assoc();
                if ($row['verified'] == 1){
                    return 'style="text-decoration: line-through;"';
                }
                return '';
            }
        }
        return '';
    }

    private function are_all_scout_path_tasks_mandatory($result){
        while ($row = $result->fetch_assoc()){
            if ($row['mandatory'] == 0){
                return false;
            }
        }
        return true;
    }

    private function get_name_of_area_of_scout_path($mysqli, $area_id, $scout_path_id){
        if (!$mysqli->connect_errno){
            $sql = "SELECT * FROM required_points WHERE area_id = ".$area_id." AND scout_path_id = ".$scout_path_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $row = $result->fetch_assoc();
                return $row['name'];
            }
        }
        return '';
    }

    private function get_name_of_task($mysqli, $task_id){
        if (!$mysqli->connect_errno){
            $sql = "SELECT * FROM tasks WHERE id = ".$task_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $row = $result->fetch_assoc();
                return $row['name'];
            }
        }
        return '';
    }

    private function get_color_of_scout_path_area($mysqli, $area_id){
        if (!$mysqli->connect_errno) {
            $sql = "SELECT * FROM areas_of_progress WHERE id = " . $area_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
                $row = $result->fetch_assoc();
                return $row['color'];
            }
        }
        return '#000000';
    }

    private function get_content_of_task($mysqli, $task_id){
        if (!$mysqli->connect_errno){
            $sql = "SELECT * FROM tasks WHERE id = ".$task_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $row = $result->fetch_assoc();
                return $row['task'];
            }
        }
        return '';
    }

    private function get_details_about_scout_path_chapter($mysqli, $scout_path_id, $area_id){
        if (!$mysqli->connect_errno){
            $sql = "SELECT * FROM required_points WHERE scout_path_id = ".$scout_path_id." AND area_id = ".$area_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $row = $result->fetch_assoc();
                return $row;
            }
        }
        throw new Exception("Error");
    }

    private function get_task_icon_position($mysqli, $task_id){
        if (!$mysqli->connect_errno){
            $sql = "SELECT * FROM tasks WHERE id = ".$task_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $row = $result->fetch_assoc();
                if ($row['position_id'] == 1){
                    return 'letter_j';
                }
                else if ($row['position_id'] == 2){
                    return 'letter_r';
                }
                else if ($row['position_id'] >= 3){
                    return 'letter_v';
                }
            }
        }
        throw new Exception("Error");
    }

    private function get_position_id_of_task($mysqli, $task_id)
    {
        if (!$mysqli->connect_errno){
            $sql = "SELECT * FROM tasks WHERE id = ".$task_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $row = $result->fetch_assoc();
                return $row['position_id'];
            }
        }
        return '';
    }
}
?>