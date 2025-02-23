<?php

class ScoutPathTaskEditor
{
    private $scoutPath;

    function __construct($scoutPath)
    {
        $this->scoutPath = $scoutPath;
    }

    public function listTasks($scout_path_id): void
    {
        $scoutPaths = $this->scoutPath->getStructuredScoutPaths()[$scout_path_id];

        echo '
            <div class="tasksLister">
        ';

        foreach ($scoutPaths as $area_name => $array1) {
            $this->printAreaHeading($area_name);

            foreach ($array1 as $chapter_name => $array2) {
                $this->printChapterHeading($chapter_name, $array2);
                $mandatory_flag = true;
                $mem_color = "";

                foreach ($array2 as $mandatory => $tasks) {
                    $mem_color = $tasks[0]['color'];

                    if ($mandatory == 0) {
                        $mandatory_flag = false;
                        $this->printVoluntarilyBeginning($tasks[0]['color']);
                    }

                    foreach ($tasks as $task) {
                        $this->printTask($task['task_id'], $task['task']);
                    }

                }

                if ($mandatory_flag) {
                    $this->printVoluntarilyBeginning($mem_color);
                }

                echo '
                        <br>
                        <button>Pridaj Ulohu</button>
                        </div>
                    </div>
                ';
            }

        }

        echo '
            </div>
        ';
    }

    private function printTask($task_id, $task): void
    {
        echo '
            <div>
                <textarea style="width: 100%" id="edit_task_id'.$task_id.'">'.$task.'</textarea>
                <button>Odstrániť</button>
            </div>
        ';
    }

    private function printAreaHeading($name): void
    {
        echo '
            <h1 class="tasksListerHeading">'.$name.'</h1>
        ';
    }

    private function printChapterHeading($chapter_name, $array): void
    {
        echo '
            <div class="tasksListerContainerMain">
        ';

        if (isset($array[0])) {
            echo '
                <div class="tasksListerContainerFilled" style="background-color: '.$array[0][0]['color'].'">
            ';
        }
        else{
            echo '
                <div class="tasksListerContainerFilled" style="border-radius: 15px; background-color: '.$array[1][0]['color'].';">
            ';
        }

        echo '
            <textarea class="tasksListerContainerHeading">'.$chapter_name.'</textarea>
        ';
    }

    private function printVoluntarilyBeginning($color): void
    {
        echo '
            <br>
            <button>pridaj úlohu</button>
            </div>
            <div class="tasksListerContainerEmpty" style="border: 3px dashed '.$color.'">
            <h1 class="tasksListerContainerSecond">Volitelná časť</h1>
        ';
    }
}