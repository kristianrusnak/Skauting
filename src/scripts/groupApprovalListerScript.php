<?php

class GroupApprovalLister{

    private $colors = array("#fdd4b1", "#ff9b9b", "#e3ff9b", "#9bffdc", "#9bedff", "#bc9bff", "#ffe669", "#ff91fa", "#558ffd", "#42fa45");
    private $color_iter = 0;
    private $task_iter = 0;
    private $group_iter = 0;
    private $member_iter = 0;
    private $checkbox_listener = array();
    private $task_listener_mem = array("task_iter" => array(), "task_id" => array(), "user_id" => array(), "is_null" => array());


    function __construct($mysqli){
        if (isset($_COOKIE['position_id']) && $_COOKIE['position_id'] >= 3){
            $this->print_start_and_main_checkbox();
            if ($_COOKIE['position_id'] == 3){
                $mem = $this->list_group($mysqli, $_COOKIE['user_id']);
                $this->checkbox_listener += ["selectAllCheckboxes" => $mem];
            }
            else if ($_COOKIE['position_id'] == 4){
                $this->list_groups($mysqli);
            }
            $this->print_end();
            $this->print_task_listener_from_mem();
            $this->multi_checkbox_script();
        }
        else{
            throw new Exception("Something went wrong or you are not allowed to see this page (try to log out and log in).");
        }
    }

    private function print_scripts(){
        foreach ($this->task_listener as $value){
            echo $value;
        }
        foreach ($this->checkbox_listener as $value){
            echo $value;
        }
    }

    private function print_task($is_null, $task){
        $this->task_iter++;
        echo '<div class="groupContainerTask" id="div_id_'.$this->task_iter.'">
        <input type="checkbox" name="selector" id="checkbox_id_'.$this->task_iter.'" class="groupContainerTaskInput">';
        if ($is_null){
            echo '<input type="number" placeholder="body" min="0" class="groupContainerTaskInputText" id="text_id_'.$this->task_iter.'">';
        }
        echo '<span class="groupContainerTaskSpan" id="span_id_'.$this->task_iter.'">'.$task.'</span>
              <span class="groupContainerTaskRemove" id="remove_id_'.$this->task_iter.'" onclick="removeTask('.$this->task_iter.')">(odstrániť)</span>
                    </div>';
    }

    private function list_task($mysqli, $is_null, $task_id){
        if (!$mysqli->connect_errno){
            $sql = "SELECT * FROM tasks WHERE id = '$task_id'";
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $row = $result->fetch_assoc();
                $this->print_task($is_null, $row['task']);
            }
        }
    }

    private function print_main_member_checkbox($color, $name){
        $this->member_iter++;
        echo '<div class="selectAllFromTheMember" style="background-color: '.$color.'">
                    <input type="checkbox" name="selector" id="selectAllFromTheMember'.$this->member_iter.'" class="selectAllInTheGroupInput">
                    <label for="selectAllFromTheMember'.$this->member_iter.'" class="selectAllInTheGroupLabel"></label>
                    <span class="selectAllInTheGroupSpan">'.$name.'</span>
                </div>';
    }

    private function list_member($mysqli, $user_id, $name){
        $mem = array();
        if (!$mysqli->connect_errno){
            $sql = 'SELECT *
                    FROM complited_tasks AS ct
                    INNER JOIN tasks AS t ON ct.task_id = t.id
                    WHERE ct.user_id = '.$user_id.' AND ct.verified = 0 AND t.position_id <= '.$_COOKIE['position_id'];
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $color = $this->get_color();
                echo '<div class="memberOfTheGroup">';
                $this->print_main_member_checkbox($color, $name);
                echo '<div class="groupContainerTasks" style="border: 2px dashed '.$color.'">';
                while ($row = $result->fetch_assoc()){
                    if ($row['points'] == null && is_task_scout_path($mysqli, $row['task_id'])){
                        $this->list_task($mysqli, true, $row['task_id']);
                        //$this->print_task_script($this->task_iter, $row['task_id'], $user_id,"true");
                        $this->task_listener_mem["is_null"][] = "true";
                        }
                    else{
                        $this->list_task($mysqli, false, $row['task_id']);
                        //$this->print_task_script($this->task_iter, $row['task_id'], $user_id);
                        $this->task_listener_mem["is_null"][] = "false";
                    }
                    $this->task_listener_mem["task_iter"][] = $this->task_iter;
                    $this->task_listener_mem["task_id"][] = $row['task_id'];
                    $this->task_listener_mem["user_id"][] = $user_id;

                    $mem[] = "checkbox_id_".$this->task_iter;
                }
                echo '</div>';
                echo '</div>';
            }
        }
        return $mem;
    }

    private function list_members($mysqli, $leader_id){
        $mem = array();
        $temp = array();
        if (!$mysqli->connect_errno){
            $sql = 'SELECT * FROM groups WHERE leader_id = '.$leader_id.' ORDER BY user_id ASC';
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                echo '<div class="membersOfTheGroup">';
                while ($row = $result->fetch_assoc()){
                    $user_sql = "SELECT * FROM users WHERE id = ".$row['user_id'];
                    if (($user_result = $mysqli->query($user_sql)) && ($user_result->num_rows > 0)){
                        $user_row = $user_result->fetch_assoc();
                        $temp = $this->list_member($mysqli, $user_row['id'], $user_row['name']);
                    }
                    $this->checkbox_listener += ["selectAllFromTheMember".$this->member_iter => $temp];
                    $mem = array_merge($mem, $temp);
                }
                echo '</div>';
            }
        }
        return $mem;
    }

    private function print_main_group_checkbox($name){
        $this->group_iter++;
        echo '<div class="headOfTheGroup">
                    <input type="checkbox" name="selector" id="selectAllInTheGroup'.$this->group_iter.'" class="selectAllInTheGroupInput">
                    <label for="selectAllInTheGroup'.$this->group_iter.'" class="selectAllInTheGroupLabel"></label>
                    <span class="selectAllInTheGroupSpan">'.$name.'</span>
                </div>';
    }

    private function list_group($mysqli, $leader_id){
        $mem = array();
        if (!$mysqli->connect_errno){
            $sql = 'SELECT * FROM users WHERE id = '.$leader_id;
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                $row = $result->fetch_assoc();
                echo '<div class="taskCheckerContainer">';
                $this->print_main_group_checkbox($row['name']);
                $mem = $this->list_members($mysqli, $leader_id);
                $this->checkbox_listener += ["selectAllInTheGroup".$this->group_iter => $mem];
                echo '</div>';
            }
        }
        return $mem;
    }

    private function list_groups($mysqli){
        $mem = array();
        $temp = array();
        if (!$mysqli->connect_errno){
            $sql = 'SELECT * FROM groups GROUP BY leader_id ORDER BY leader_id ASC';
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)){
                while ($row = $result->fetch_assoc()){
                    $temp = $this->list_group($mysqli, $row['leader_id']);
                    $mem = array_merge($mem, $temp);
                }
            }
        }
        $this->checkbox_listener += ["selectAllCheckboxes" => $mem];
    }

    private function print_start_and_main_checkbox(){
        echo '<div id="groupContainer">
                <div id="selectAllCheckboxesContainer">
                    <input type="checkbox" name="selectAllCheckboxes" id="selectAllCheckboxes">
                    <label for="selectAllCheckboxes" id="selectAllCheckboxesLabel"></label>
                    <span id="selectAllCheckboxesSpan">Označ všetko</span>
                </div>';
    }

    private function print_end(){
        echo '</div>';
    }

    private function get_color(){
        $color = $this->colors[$this->color_iter];
        $this->color_iter++;
        $this->color_iter %= count($this->colors);
        return $color;
    }

    private function multi_checkbox_script(){
        /*var_dump($this->checkbox_listener);*/
        foreach ($this->checkbox_listener as $element => $tasks){
            echo '<script> document.getElementById("'.$element.'").addEventListener("change", function(){
                  let element = document.getElementById("'.$element.'");';
            foreach ($tasks as $task){
                echo '
                document.getElementById("'.$task.'").checked = element.checked;
                document.getElementById("'.$task.'").dispatchEvent(new Event("change"));
                ';
            }
            echo  '})</script>';
        }
    }

    private function print_task_listener_from_mem(){
        for ($i = 0; $i < count($this->task_listener_mem["task_iter"]); $i++){
            $this->print_task_script($this->task_listener_mem["task_iter"][$i], $this->task_listener_mem["task_id"][$i],
                                        $this->task_listener_mem["user_id"][$i], $this->task_listener_mem["is_null"][$i]);
        }
    }

    private function print_task_script($task_iter, $task_id, $user_id, $is_null = "false"){
        echo '
        <script>
            document.getElementById("checkbox_id_'.$task_iter.'").addEventListener("change", function(){
                const checkbox = document.getElementById("checkbox_id_'.$task_iter.'");
                let span = document.getElementById("span_id_'.$task_iter.'");
                let text = "-1";
                let data = "";
                
                if ('.$is_null.') {
                    text = document.getElementById("text_id_' .$task_iter.'").value;
                    if (text === ""){
                        checkbox.checked = false;
                        return;
                    }
                }
               
                const xhr = new XMLHttpRequest();
                
                if (checkbox.checked){
                    xhr.open("POST", "../scripts/handleTaskApproval.php", true);
                    // Key-value pairs as a query string
                    data = "task_id='.$task_id.'&user_id='.$user_id.'&points=" + text;
                }
                else{
                    xhr.open("POST", "../scripts/handleTaskDisapproval.php", true);
                    // Key-value pairs as a query string
                    data = "task_id='.$task_id.'&user_id='.$user_id.'";
                }
                
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                
                xhr.send(data);
                
                xhr.onload = function() {
                    if (xhr.status === 200){
                        if (xhr.responseText === "verified"){
                            console.log("verified");
                            span.style.textDecoration = "line-through";
                        }
                        else if (xhr.responseText === "unVerified"){
                            console.log("unVerified");
                            span.style.textDecoration = "none";
                        }
                        else{
                            console.log(xhr.responseText);
                            checkbox.checked = !checkbox.checked;
                        }
                    }
                    else{
                        console.log("error function");
                        checkbox.checked = !checkbox.checked;
                    }
                }  
            });
        </script>
        ';
    }
}

?>