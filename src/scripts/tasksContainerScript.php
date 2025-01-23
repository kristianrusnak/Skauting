<?php

class listTasks{
    private $mysqli;
    private $types = array('merit_badges', 'scout_path');
    private $siteType = array("meritBadges.php", "scoutPath.php");
    private $type;

    /**
     * @param $mysqli
     * @param $type {int <0, 1>}
     */
    public function __construct($mysqli, $type){
        $this->mysqli = $mysqli;
        $this->type = $type;
        $this->list();
    }

    private function list(){
        if (!$this->mysqli->connect_errno){
            if ($this->types[$this->type] == 'merit_badges'){
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