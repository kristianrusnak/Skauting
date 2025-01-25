<?php

class listTasks{
    private $mysqli;
    private $types = array('merit_badge', 'scout_path');
    private $siteType = array("meritBadges.php", "scoutPath.php");
    private $type;

    /**
     * @param $mysqli
     * @param $type {int <0, 2>}
     */
    public function __construct($mysqli, $type){
        $this->mysqli = $mysqli;
        $this->type = $type;
        if ($this->type == 2){
            $this->list_in_progress($mysqli);
        }
        else{
            $this->list();
        }
    }

    private function list(){
        if (!$this->mysqli->connect_errno){
            if ($this->types[$this->type] == 'merit_badge'){
                $sql = "SELECT * FROM merit_badges";
            }
            else{
                $sql = "SELECT * FROM scout_path";
            }
            if (($result = $this->mysqli->query($sql)) && ($result->num_rows > 0)) {
                $last_category_id = 0;
                echo '<div class="tasksContainer">';
                while ($row = $result->fetch_assoc()) {
                    if ($this->type == 0 && $row['category_id'] != $last_category_id){
                        $last_category_id = $row['category_id'];
                        $name = $this->getNameOfTheCategory($row['category_id']);
                        $this->printHeading($name);
                    }
                    $image = $row['image'];
                    if ($this->type == 0 && $this->hasGreenTasks($row['id'])){
                        $image = $image.'_g';
                    }
                    else if ($this->type == 0){
                        $image = $image.'_r';
                    }
                    $this->printLink($row['name'], $image, $row['id']);
                }
                echo '</div>';
            }
        }
    }

    private function list_in_progress($mysqli){
        if (!$this->mysqli->connect_errno){
            echo '<div class="tasksContainer">';
            $is_empty = true;
            $sql = entire_sql_scout_path();
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
                $is_empty = false;
                $last_id = '';
                $first = true;
                while ($row = $result->fetch_assoc()) {
                    if ($first){
                        $this->print_task_in_progress_container($row, 1, true, true, false);
                        $last_id = $row['scout_path_id'];
                        $first = false;
                    }
                    else if ($row['scout_path_id'] != $last_id){
                        $this->print_task_in_progress_container($row, 1, false, false, true);
                        $this->print_task_in_progress_container($row, 1, true, true, false);
                        $last_id = $row['scout_path_id'];
                    }
                    else{
                        $this->print_task_in_progress_container($row, 1, false, true, false);
                    }
                }
                echo '</div>';
                echo '</a>';
            }

            $sql = $this->get_sql_for_merit_badge_tasks_in_progress();
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
                $is_empty = false;
                while ($row = $result->fetch_assoc()) {
                    $this->print_task_in_progress_container($row, 0, true, true, true);
                }
            }
            if ($is_empty){
                echo '<h1>Nemáš rozpracované žiadne úlohy</h1>';
            }
            echo '</div>';
        }
    }

    private function print_task_in_progress_container($row, $type, $begin, $mid, $end){
        $id_string = $this->types[$type].'_id';
        $span_class = 'bigText';
        $img_class = 'bigIcon';
        if (isset($row['scout_path_id']) && $row['scout_path_id'] == 2){
            $span_class = '';
            $img_class = '';
        }
        if (isset($row['merit_badge_id'])){
            if ($row['level_id'] == 1){
                $row['image'] = $row['image'].'_g';
            }
            else{
                $row['image'] = $row['image'].'_r';
            }
            $row['icon'] = 'task';
            $row['alt'] = 'obrazok';
        }

        if ($begin){
            echo '<a href="../pages/'.$this->siteType[$type].'?id='.$row[$id_string].'" class="taskContainer taskInProgressContainer">';
            echo '<span class="taskContainerHeading">'.$row['name'].'</span>';
            echo '<img class="taskContainerImage" src="../images/'.$row['image'].'.png" alt="'.$row['alt'].'">';
            echo '<div class="tasksInProgress">';
        }
        if ($mid){
            echo '<span class="tasksContainerPoints '.$span_class.'">'.$row['finished'].' / '.$row['total'].' &nbsp;<img class="taskContainerIcon '.$img_class.'" src="../images/'.$row['icon'].'.png" alt="'.$row['alt'].'"></span>';

        }
        if ($end){
            echo '</div>';
            echo '</a>';
        }
    }

    private function get_sql_for_merit_badge_tasks_in_progress(){
        return 'SELECT 
                    t1.merit_badge_id, 
                    t1.level_id, 
                    t1.finished, 
                    t2.total,
                    mb.name,
                    mb.image,
                    mb.color
                FROM
                    (SELECT mbt.merit_badge_id, mbt.level_id, count(*) AS finished
                    FROM complited_tasks AS ct
                    INNER JOIN merit_badge_tasks AS mbt ON mbt.task_id = ct.task_id AND ct.user_id = 1 AND ct.verified = 1
                    GROUP BY mbt.merit_badge_id, mbt.level_id) AS t1
                INNER JOIN
                    (SELECT merit_badge_id, level_id, count(*) AS total
                    FROM merit_badge_tasks
                    GROUP BY merit_badge_id, level_id) AS t2
                ON t1.merit_badge_id = t2.merit_badge_id AND t1.level_id = t2.level_id
                INNER JOIN
                merit_badges AS mb ON t1.merit_badge_id = mb.id
                WHERE t1.finished < t2.total
                ORDER BY t1.merit_badge_id, t1.level_id;';
    }

    private function get_sql_for_scout_path_tasks_in_progress(){
        return 'SELECT 
                    t1.scout_path_id,
                    t1.area_id,
                    t1.name,
                    t1.image,
                    t1.color,
                    t2.finished,
                    t1.total,
                    rp.type_of_points,
                    rp.name AS alt,
                    rp.icon
                FROM
                    (SELECT
                        sp.id as scout_path_id,
                        CASE
                            WHEN rp.area_id IS NULL THEN 1
                            ELSE rp.area_id
                        END AS area_id,
                        COALESCE(sp.required_points, rp.required_points) AS total,
                        sp.name,
                        sp.image,
                        sp.color
                    FROM scout_path AS sp
                    LEFT JOIN required_points AS rp ON sp.required_points IS NULL
                    WHERE sp.id = rp.scout_path_id OR rp.scout_path_id IS NULL) AS t1
                INNER JOIN
                    (SELECT csp.scout_path_id, csp.area_id, sum(ct.points) as `finished`
                    FROM complited_tasks AS ct
                    INNER JOIN scout_path_tasks AS spt ON ct.task_id = spt.task_id AND ct.user_id = '.$_COOKIE['user_id'].' AND ct.verified = 1
                    INNER JOIN chapters_of_scout_path AS csp ON csp.id = spt.chapter_id
                    INNER JOIN scout_path AS sp ON sp.id = csp.scout_path_id
                    GROUP BY csp.scout_path_id,
                        CASE
                            WHEN sp.required_points IS NULL THEN csp.area_id
                            ELSE NULL
                        END) AS t2
                ON t1.scout_path_id = t2.scout_path_id AND t1.area_id = t2.area_id
                INNER JOIN required_points AS rp ON t1.area_id = rp.area_id AND t1.scout_path_id = rp.scout_path_id
                WHERE t2.finished < t1.total
                ORDER BY t1.scout_path_id, t1.area_id;';
    }

    private function hasGreenTasks($id){
        if (!$this->mysqli->connect_errno){
            $sql = "SELECT * FROM merit_badge_tasks WHERE merit_badge_id = ".$id." AND level_id = 1";
            if (($result = $this->mysqli->query($sql)) && ($result->num_rows > 0)) {
                return true;
            }
            return false;
        }
    }

    private function getNameOfTheCategory($id){
        if (!$this->mysqli->connect_errno){
            $sql = "SELECT * FROM categories_of_merit_badges WHERE id = ".$id;
            if (($result = $this->mysqli->query($sql)) && ($result->num_rows >= 0)) {
                $row = $result->fetch_assoc();
                return $row['name'];
            }
        }
    }

    private function printHeading($name){
        echo '<span class="tasksContainerCategory">'.$name.'</span>';
    }

    private function printLink($name, $image, $id){
        echo '<a href="'.$this->siteType[$this->type].'?id='.$id.'" class="taskContainer">
            <span class="taskContainerHeading">'.$name.'</span>
            <img class="taskContainerImage" src="../images/'.$image.'.png" alt="'.$name.'">
            </a>';
    }
}
?>