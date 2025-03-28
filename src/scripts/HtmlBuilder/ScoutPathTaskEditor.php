<?php

namespace HtmlBuilder;

require_once dirname(__DIR__) . '/ScoutPath/Service/ScoutPathService.php';
require_once dirname(__DIR__) . '/Users/Service/UserService.php';

use ScoutPath\Service\ScoutPathService as ScoutPath;
use User\Service\UserService as User;

class ScoutPathTaskEditor
{
    private ScoutPath $scoutPath;

    private User $user;

    function __construct()
    {
        $this->scoutPath = new ScoutPath();
        $this->user = new User;
    }

    public function printScript(): void
    {
        echo '
            <script>
                let changedChapters = {};
                
                let newChapters = {};
            
                let deletedChapters = [];
            
                let changedTasks = {};
            
                let newTasks = {};
                
                let deletedTasks = [];
                
                let newChapterCounter = 0;
                let newTaskCounter = 0;
                
                function alterChapter(id) {
                    element = document.getElementById("chapter_id_" + id);
                    changedChapters[id] = element.value;
                    
                    console.log(changedChapters);
                }
                
                function createChapter(scout_path_id, area_id, color) {
                    let element = document.getElementById("area_id_" + area_id);
                    
                    newChapterCounter += 1;
                    
                    newChapters[newChapterCounter] = {
                        scout_path_id: scout_path_id, 
                        area_id: area_id
                    };
                    
                    let newElementHTML = `
                        <div class="tasksListerContainerMain" id="new_container_id_${newChapterCounter}">
                            <div id="new_filled_container_id_${newChapterCounter}" class="tasksListerContainerFilled" style="border-radius: 15px; background-color: ${color}">
                                <textarea id="new_chapter_id_${newChapterCounter}" onchange="alterNewChapter(${newChapterCounter})" class="tasksListerContainerHeading"></textarea>
                                <p>pre pridavanie uloh potvrd zmeny</p>
                            </div>
                            <div id="new_empty_container_id_${newChapterCounter}" class="tasksListerContainerEmpty" style="border: 3px dashed ${color}">
                                <h1 class="tasksListerContainerSecond">Volitelná časť</h1>
                                <p>pre pridavanie uloh potvrd zmeny</p>
                            </div>
                            <button onclick="deleteNewChapter(${newChapterCounter})">odstranit</button>
                        </div>
                    `;
                    
                    element.insertAdjacentHTML("afterbegin", newElementHTML);
                    console.log(newChapters);
                }
                
                function alterNewChapter(id) {
                    let element = document.getElementById("new_chapter_id_" + id);
                    
                    newChapters[newChapterCounter]["name"] = element.value;
                    console.log(newChapters);
                }
                
                function deleteNewChapter(id) {
                    let element = document.getElementById("new_container_id_" + id);
                    
                    element.style.display = "none";
                    delete newChapters[id]
                }
                
                function deleteChapter(id) {
                    let element = document.getElementById("container_id_" + id);
                    
                    element.style.display = "none";
                    deletedChapters.push(id)
                    
                    for (const task_id in newTasks) {
                        if (newTasks.hasOwnProperty(task_id) && newTasks[task_id]["chapter_id"] === id) {
                            delete newTasks[task_id];
                        }
                    }
                    
                    console.log(deletedChapters);
                    console.log(newTasks);
                }
                
                function alterTask(id) {
                    let task = document.getElementById("task_id_" + id);
                    let name = document.getElementById("name_id_" + id);
                    let points = document.getElementById("points_id_" + id);
                    let position = document.getElementById("position_id_" + id);
                    
                    changedTasks[id] = {task: task.value, name: name.value, points: points.value, position: position.value};
                    console.log(changedTasks);
                }
                
                function deleteTask(id) {
                    let element = document.getElementById("task_container_id_" + id);
                    
                    element.style.display = "none";
                    deletedTasks.push(id);
                    delete changedTasks[id];
                    console.log(deletedTasks);
                }
                
                function createTask(id, mandatory, chapter_id) {
                    let element = document.getElementById(id);
                    
                    newTaskCounter += 1;
                    
                    let newElementHTML = `
                        <div id="new_task_container_id_${newTaskCounter}">
                            <textarea style="width: 100%" onchange="alterNewTask(${newTaskCounter})" id="new_task_id_${newTaskCounter}"></textarea>
                            <input id="new_name_id_${newTaskCounter}" onchange="alterNewTask(${newTaskCounter})" type="text">
                            <input id="new_points_id_${newTaskCounter}" onchange="alterNewTask(${newTaskCounter})" type="number">
                            <select id="new_position_id_${newTaskCounter}" onchange="alterNewTask(${newTaskCounter})">
                                ' .$this->PositionOptions().'
                            </select>
                            <button onclick="deleteNewTask(${newTaskCounter})">Odstrániť</button>
                        </div>
                    `;
                    
                    newTasks[newTaskCounter] = {chapter_id: chapter_id, mandatory: mandatory};
                    
                    element.insertAdjacentHTML("beforeend", newElementHTML);
                    console.log(newTasks);
                }
                
                function alterNewTask(id) {
                    let task = document.getElementById("new_task_id_" + id);
                    let name = document.getElementById("new_name_id_" + id);
                    let points = document.getElementById("new_points_id_" + id);
                    let position = document.getElementById("new_position_id_" + id);
                    
                    newTasks[id]["task"] = task.value;
                    newTasks[id]["name"] = name.value;
                    newTasks[id]["points"] = points.value;
                    newTasks[id]["position"] = position.value;
                    
                    console.log(newTasks);
                }
                
                function deleteNewTask(id) {
                    let element = document.getElementById("new_task_container_id_" + id);
                    
                    element.style.display = "none";
                    delete newTasks[id];
                    console.log(newTasks);
                }
                
                function submitChanges() {
                    let error_message = "";
                    
                    if (Object.keys(newChapters).length > 0) {
                        error_message += executeSubmit(newChapters, "create_chapter");
                    }
                    if (Object.keys(changedChapters).length > 0) {
                        error_message += executeSubmit(changedChapters, "update_chapter");                        
                    }
                    if (deletedChapters.length > 0) {
                        error_message += executeSubmit(deletedChapters, "delete_chapter");
                    }
                    if (Object.keys(changedTasks).length > 0) {
                        error_message += executeSubmit(changedTasks, "update_task");
                    }
                    if (Object.keys(newTasks).length > 0) {
                        error_message += executeSubmit(newTasks, "create_task");
                    }
                    if (deletedTasks.length > 0) {
                        error_message += executeSubmit(deletedTasks, "delete_task");
                    }
                    
                    if (error_message !== "") {
                        alert(error_message);
                    }
                    else {
                        location.reload();
                    }
                }
                
                function executeSubmit(array, operation) {
                    let data = {
                        "data": array,
                        "operation": operation
                    }
                    
                    const scriptWeb = "../APIs/handleScoutPathChange.php"
                    fetch(scriptWeb, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(data)  // Convert the array to JSON
                    })
                    .then(response => response.json())
                    .then(resp => {
                        if (resp["error"]) {
                            return resp["error_message"];
                        }
                        return "";
                    })
                    .catch(error => console.error("Error: ", error));
                    
                    return "";
                }
            </script>
        ';
    }

    public function deleteScoutPath(int $scout_path_id): void {
        echo '
            <div id="deleteDisableClick">
                <div id="deleteWindow">
                    <span>Naozaj chces vymazat tento skautsky chodnik?</span>
                    <button onclick="cancleDelete('.$scout_path_id.')">Nie</button>
                    <form method="POST">
                        <input type="hidden" name="scoutPathId" value="'.$scout_path_id.'">
                        <input type="submit" name="deleteScoutPath" value="Ano">
                    </form>
                </div>
            </div>
            
            <script>
            function cancleDelete(id) {
                window.location.replace("../pages/scoutPath.php?id="+id);
            }
            </script>
        ';
    }

    public function listCreateForm(): void
    {
        echo '<div class="tasksLister">';

        $data = [
            'name' => '',
            'color' => '',
            'points' => 1,
            'submit' => 'createScoutPath',
            'required' => 'required',
            'id' => 0
        ];

        $this->printCreateForm($data);

        echo '</div>';
    }

    public function listUpdateForm(int $scout_path_id): void
    {
        echo '<div class="tasksLister">';

        $path = $this->scoutPath->getScoutPath($scout_path_id);

        $data = [
            'name' => $path->name,
            'color' => $path->color,
            'points' => $path->required_points,
            'submit' => 'updateScoutPath',
            'required' => '',
            'id' => $scout_path_id
        ];

        $this->printCreateForm($data);

        echo '</div>';
    }

    public function printCreateForm(array $data): void
    {
        echo '
            <form method="post" enctype="multipart/form-data">
                <label>
                    <span>Meno skautskeho chodníka: </span>
                    <input type="text" name="scoutPathName" value="'.$data['name'].'" '.$data['required'].'>
                </label>
                
                <br>
                
                <label>
                    <span>Pozadovane body</span>
                    <input type="number" name="scoutPathPoints" value="'.$data['points'].'" '.$data['required'].'>
                </label>
                
                <br>
                
                <label>
                    <span>Farba: </span>
                    <input type="color" name="scoutPathColor" value="'.$data['color'].'" '.$data['required'].'>
                </label>
                
                <br>
                
                <label>
                    <span>Obrazok: </span>
                    <input type="file" id="inputImage" name="scoutPathImage" '.$data['required'].'>
                </label>
                
                <br>
                
                <input type="hidden" name="scoutPathId" value="'.$data['id'].'">
                <input type="submit" name="'.$data['submit'].'" value="Vytvorit Odborku">
            </form>
        ';
    }

    public function listTasks(int $scout_path_id): void
    {
        $areas = $this->scoutPath->getAreas();

        echo '<div class="tasksLister">';

        foreach ($areas as $area) {

            $this->printAreaHeading($scout_path_id, $area);
            $chapters = $this->scoutPath->getChaptersOfScoutPath($scout_path_id, $area->id);

            foreach ($chapters as $chapter) {

                $this->printChapterHeading($area, $chapter);
                $tasks = $this->scoutPath->getTasksFromChapter($chapter->id);
                $mandatory_flag = true;

                foreach ($tasks as $task) {
                    if ($task->mandatory == 0 && $mandatory_flag) {
                        $mandatory_flag = false;
                        $this->printVoluntarilyBeginning($chapter->id, $area->color);
                    }

                    $this->printTask($task);
                }

                if ($mandatory_flag) {
                    $this->printVoluntarilyBeginning($chapter->id, $area->color);
                }

                $this->printChapterEnd($chapter->id);
            }

            echo '</div>';
        }

        $this->printSubmitButton();
        $this->printScript();
    }

    private function printTask(object $task): void
    {
        echo '
            <div id="task_container_id_'.$task->id.'">
                <textarea style="width: 100%" onchange="alterTask('.$task->id.')" id="task_id_'.$task->id.'">'.$task->task.'</textarea>
                <input id="name_id_'.$task->id.'" onchange="alterTask('.$task->id.')" type="text" value="'.$task->name.'">
                <input id="points_id_'.$task->id.'" onchange="alterTask('.$task->id.')" type="number" value="'.$task->points.'">
                <select id="position_id_'.$task->id.'" onchange="alterTask('.$task->id.')">
        ';

        $this->printPositionOptions($task->position_id);

        echo '
                </select>
                <button onclick="deleteTask('.$task->id.')">Odstrániť</button>
            </div>
        ';
    }

    private function printAreaHeading(int $scout_path_id, object $area): void
    {
        echo '
            <h1 class="tasksListerHeading">'.$area->name.'</h1>
            <button onclick="createChapter('.$scout_path_id.', '.$area->id.', \''.$area->color.'\')">Pridaj</button>
            <div id="area_id_'.$area->id.'">
        ';
    }

    private function printChapterHeading(object $area, object $chapter): void
    {
        echo '
            <div class="tasksListerContainerMain" id="container_id_'.$chapter->id.'">
                <div id="filled_container_id_'.$chapter->id.'" class="tasksListerContainerFilled" style="background-color: '.$area->color.'">
                    <textarea id="chapter_id_'.$chapter->id.'" onchange="alterChapter('.$chapter->id.')" class="tasksListerContainerHeading">'.$chapter->name.'</textarea>
        ';
    }

    private function printChapterEnd(int $chapter_id): void
    {
        echo '
            <br>
            <button onclick="createTask(\'empty_container_id_'.$chapter_id.'\', 0, '.$chapter_id.')">Pridaj Ulohu</button>
            </div>
            <button onclick="deleteChapter('.$chapter_id.')">odstranit</button>
        </div>
        ';
    }

    private function printVoluntarilyBeginning(int $chapter_id, string $color): void
    {
        echo '
            <br>
            <button onclick="createTask(\'filled_container_id_'.$chapter_id.'\', 1, '.$chapter_id.')">pridaj úlohu</button>
            </div>
            <div id="empty_container_id_'.$chapter_id.'" class="tasksListerContainerEmpty" style="border: 3px dashed '.$color.'">
            <h1 class="tasksListerContainerSecond">Volitelná časť</h1>
        ';
    }

    private function printPositionOptions(int $position_id): void
    {
        $positions = $this->user->getPositions();

        foreach ($positions as $position) {
            if ($position->id == $position_id) {
                echo '
                <option value="'.$position->id.'" selected>'.$position->name.'</option>
            ';
            }
            else {
                echo '
                <option value="'.$position->id.'">'.$position->name.'</option>
            ';
            }
        }
    }

    private function PositionOptions(): String
    {
        $result = '';
        $positions = $this->user->getPositions();

        foreach ($positions as $position) {
            $result .= '<option value="'.$position->id.'">'.$position->name.'</option>';
        }

        return $result;
    }

    private function printSubmitButton(): void
    {
        echo '
                <div id="groupSubmitButton">
                    <button onclick="submitChanges()">Potvrdiť</button>
                </div>
            </div>
        ';
    }
}