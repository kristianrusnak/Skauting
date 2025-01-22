<?php
class TasksLister{
    private $mem;

    function __construct($mysqli, $id, $category){
        $id = sanitizeInput($id);
        if ($category == 'merit_badges'){
            $this->list_merit_badge($mysqli, $id);
        }
        else if ($category == 'scout_path') {
            $this->list_scout_path($mysqli, $id);
        }
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
            echo '<h1 class="tasksListerHeading">'.$level.'</h1>';
            echo '<div class="tasksListerContainerMain">';
            echo '<div class="tasksListerContainerFilled" style="border-radius: 15px; background-color: '.$color.';">';
            while ($row = $result->fetch_assoc()){
                $task = $this->get_content_of_task($mysqli, $row['task_id']);
                echo '<div class="tasksListerContainerTask">
                            <input id="task_id_1" type="checkbox">
                            <span class="tasksListerContainerTask">'.$task.'</span>
                        </div>';
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
                    echo '<div class="tasksListerContainerMain">';
                    //inner loop
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
                            echo '<div class="tasksListerContainerTask">
                                    <input id="task_id_'.$row2['task_id'].'" type="checkbox">
                                    <span class="tasksListerContainerTaskName">'.$name.'</span>
                                    <span class="tasksListerContainerTask">'.$task.'</span>
                                  </div>';
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
}
?>