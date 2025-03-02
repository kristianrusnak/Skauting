<?php

class ScoutPathTaskEditor
{
    private ScoutPathService $scoutPath;

    private UserService $user;

    function __construct($scoutPath, $user)
    {
        $this->scoutPath = $scoutPath;
        $this->user = $user;
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
                    
                    newChapters[newChapterCounter] = {scout_path_id: scout_path_id, area_id: area_id};
                    
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
                    if (Object.keys(newChapters).length > 0) {
                        executeSubmit(newChapters, "?create_chapter=true");
                    }
                    if (Object.keys(changedChapters).length > 0) {
                        executeSubmit(changedChapters, "?update_chapter=true");                        
                    }
                    if (deletedChapters.length > 0) {
                        executeSubmit(deletedChapters, "?delete_chapter=true");
                    }
                    if (Object.keys(changedTasks).length > 0) {
                        executeSubmit(changedTasks, "?update_task=true");
                    }
                    if (Object.keys(newTasks).length > 0) {
                        executeSubmit(newTasks, "?create_task=true");
                    }
                    if (deletedTasks.length > 0) {
                        executeSubmit(deletedTasks, "?delete_task=true");
                    }
                    location.reload();
                }
                
                function executeSubmit(data, type) {
                    const scriptWeb = "../scripts/handleScoutPathChange.php" + type
                    fetch(scriptWeb, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(data)  // Convert the array to JSON
                    })
                    .then(response => response.text())
                    .then(resp => {
                        //console.log(resp); // Handle PHP response
                    }) // Handle PHP response
                    .catch(error => console.error("Error: ", error));
                }
            </script>
        ';
    }

    public function deleteScoutPath($scout_path_id): void {
        echo '
            <div id="deleteDisableClick">
                <div id="deleteWindow">
                    <span>Naozaj chces vymazat tento skautsky chodnik?</span>
                    <button onclick="cancleDelete('.$scout_path_id.')">Nie</button>
                    <form method="POST">
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
        $this->printCreateForm("createScoutPath");
        echo '</div>';
    }

    public function listUpdateForm($scout_path_id): void
    {
        $path = $this->scoutPath->getScoutPath($scout_path_id);

        echo '<div class="tasksLister">';
        $this->printCreateForm("updateScoutPath", $path['name'], $path['color'], $path['required_points'], false);
        echo '</div>';
    }

    public function printCreateForm($submit, $name = "", $color = "", $points = 1, $required = true): void
    {
        $text = "";
        if ($required) {
            $text = "required";
        }
        echo '
            <form method="post" enctype="multipart/form-data">
                <label>
                    <span>Meno skautskeho chodníka: </span>
                    <input type="text" name="scoutPathName" value="'.$name.'" '.$text.'>
                </label>
                
                <br>
                
                <label>
                    <span>Pozadovane body</span>
                    <input type="number" name="scoutPathPoints" value="'.$points.'" '.$text.'>
                </label>
                
                <br>
                
                <label>
                    <span>Farba: </span>
                    <input type="color" name="scoutPathColor" value="'.$color.'" '.$text.'>
                </label>
                
                <br>
                
                <label>
                    <span>Obrazok: </span>
                    <input type="file" id="inputImage" name="scoutPathImage" '.$text.'>
                </label>
                
                <br>
                
                <input type="submit" name="'.$submit.'" value="Vytvorit Odborku">
            </form>
        ';
    }

    public function listTasks($scout_path_id): void
    {
        $areas = $this->scoutPath->getAreasOfScoutPath();

        echo '<div class="tasksLister">';

        foreach ($areas as $area) {
            $this->printAreaHeading($area['name'], $scout_path_id, $area['id'], $area['color']);
            $chapters = $this->scoutPath->getChaptersOfScoutPath($scout_path_id, $area['id']);

            foreach ($chapters as $chapter) {
                $this->printChapterHeading($chapter['name'], $chapter['id'], $area['color']);
                $tasks = $this->scoutPath->getTasksFromChapter($chapter['id']);
                $mandatory_flag = true;

                foreach ($tasks as $task) {
                    if ($task['mandatory'] == 0 && $mandatory_flag) {
                        $mandatory_flag = false;
                        $this->printVoluntarilyBeginning($area['color'], $chapter['id']);
                    }

                    $this->printTask($task['task_id'], $task['task'], $task['points'], $task['position_id'], $task['task_name']);
                }

                if ($mandatory_flag) {
                    $this->printVoluntarilyBeginning($area['color'], $chapter['id']);
                }

                $this->printChapterEnd($chapter['id']);
            }

            echo '</div>';
        }

        $this->printSubmitButton();
        $this->printScript();
    }

    private function printTask($task_id, $task, $points, $position_id, $name): void
    {
        echo '
            <div id="task_container_id_'.$task_id.'">
                <textarea style="width: 100%" onchange="alterTask('.$task_id.')" id="task_id_'.$task_id.'">'.$task.'</textarea>
                <input id="name_id_'.$task_id.'" onchange="alterTask('.$task_id.')" type="text" value="'.$name.'">
                <input id="points_id_'.$task_id.'" onchange="alterTask('.$task_id.')" type="number" value="'.$points.'">
                <select id="position_id_'.$task_id.'" onchange="alterTask('.$task_id.')">
        ';

        $this->printPositionOptions($position_id);

        echo '
                </select>
                <button onclick="deleteTask('.$task_id.')">Odstrániť</button>
            </div>
        ';
    }

    private function printAreaHeading($name, $scout_path_id, $area_id, $color): void
    {
        echo '
            <h1 class="tasksListerHeading">'.$name.'</h1>
            <button onclick="createChapter('.$scout_path_id.', '.$area_id.', \''.$color.'\')">Pridaj</button>
            <div id="area_id_'.$area_id.'">
        ';
    }

    private function printChapterHeading($chapter_name, $chapter_id, $color): void
    {
        echo '
            <div class="tasksListerContainerMain" id="container_id_'.$chapter_id.'">
                <div id="filled_container_id_'.$chapter_id.'" class="tasksListerContainerFilled" style="background-color: '.$color.'">
                    <textarea id="chapter_id_'.$chapter_id.'" onchange="alterChapter('.$chapter_id.')" class="tasksListerContainerHeading">'.$chapter_name.'</textarea>
        ';
    }

    private function printChapterEnd($chapter_id): void
    {
        echo '
            <br>
            <button onclick="createTask(\'empty_container_id_'.$chapter_id.'\', 0, '.$chapter_id.')">Pridaj Ulohu</button>
            </div>
            <button onclick="deleteChapter('.$chapter_id.')">odstranit</button>
        </div>
        ';
    }

    private function printVoluntarilyBeginning($color, $chapter_id): void
    {
        echo '
            <br>
            <button onclick="createTask(\'filled_container_id_'.$chapter_id.'\', 1, '.$chapter_id.')">pridaj úlohu</button>
            </div>
            <div id="empty_container_id_'.$chapter_id.'" class="tasksListerContainerEmpty" style="border: 3px dashed '.$color.'">
            <h1 class="tasksListerContainerSecond">Volitelná časť</h1>
        ';
    }

    private function printPositionOptions($position_id): void
    {
        $positions = $this->user->getPositions();
        foreach ($positions as $position) {
            if ($position['id'] == $position_id) {
                echo '
                <option value="'.$position['id'].'" selected>'.$position['name'].'</option>
            ';
            }
            else {
                echo '
                <option value="'.$position['id'].'">'.$position['name'].'</option>
            ';
            }
        }
    }

    private function PositionOptions(): String
    {
        $result = '';
        $positions = $this->user->getPositions();
        foreach ($positions as $position) {
            $result .= '<option value="'.$position['id'].'">'.$position['name'].'</option>';
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