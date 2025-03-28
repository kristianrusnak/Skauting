<?php

namespace HtmlBuilder;

require_once dirname(__DIR__) . '/MeritBadge/Service/MeritBadgeService.php';

use MeritBadge\Service\MeritBadgeService as MeritBadge;

class MeritBadgeTaskEditor
{
    private MeritBadge $meritBadge;

    function __construct()
    {
        $this->meritBadge = new MeritBadge();
    }

    public function printScript(): void
    {
        echo '
            <script>
                
                let changedTasks = {};
                let newTasks = {};
                let deletedTasks = [];
                let newCounter = 0;
                
                function alterTask(task_id) {
                    const element = document.getElementById("text_area_id_"+task_id);
                    changedTasks[task_id] = element.value;
                    
                    console.log(changedTasks);
                }
                
                function alterNewTask(new_task_id, level_id, merit_badge_id) {
                    const element = document.getElementById("new_text_area_id_"+new_task_id);
                    newTasks[new_task_id] = {
                        "task": element.value, 
                        "level_id": level_id, 
                        "merit_badge_id": merit_badge_id
                    };
                    
                    console.log("alterNewTask: " + element.value);
                }
                
                function createTask(level_id, merit_badge_id) {
                    let container = document.getElementById("level_container_id_"+level_id);
                    
                    newCounter += 1;
                    
                    let newElementHTML = `
                        <div id="new_task_id_${newCounter}">
                            <textarea id="new_text_area_id_${newCounter}" style="width: 100%" onchange="alterNewTask(${newCounter}, ${level_id}, ${merit_badge_id})"></textarea>
                            <button onclick="deleteNewTask(${newCounter})">odstranit</button>
                        </div>
                    `;
                    
                    container.insertAdjacentHTML("beforeend", newElementHTML);
                }
                
                function deleteNewTask(new_task_id) {
                    document.getElementById("new_task_id_"+new_task_id).style.display = "none";
                    
                    delete newTasks[new_task_id]
                }
                
                function deleteTask(task_id) {
                    document.getElementById("task_container_id_"+task_id).style.display = "none";
                    delete changedTasks[task_id];
                    deletedTasks.push(task_id);
                }
                
                function submitChanges() {
                    let error_message = "";
                    
                    if (Object.keys(changedTasks).length > 0){
                        error_message += executeSubmit(changedTasks, "change");
                    }
                    if (Object.keys(newTasks).length > 0){
                        error_message += executeSubmit(newTasks, "add");
                    }
                    if (deletedTasks.length > 0){
                        error_message += executeSubmit(deletedTasks, "delete");
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
                    
                    const scriptWeb = "../APIs/handleMeritBadgeChange.php"
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
                            return resp["error"];
                        }
                        return "";
                    })
                    .catch(error => console.error("Error: ", error));
                    
                    return "";
                }
                
            </script>
        ';
    }

    public function deleteMeritBadge($merit_badge_id): void
    {
        echo '
            <div id="deleteDisableClick">
                <div id="deleteWindow">
                    <span>Naozaj chces vymazat tuto odborku?</span>
                    <button onclick="cancleDelete('.$merit_badge_id.')">Nie</button>
                    <form method="POST">
                        <input type="hidden" name="meritBadgeId" value="'.$merit_badge_id.'">
                        <input type="submit" name="meritBadgeDelete" value="Ano">
                    </form>
                </div>
            </div>
            
            <script>
            function cancleDelete(id) {
                window.location.replace("../pages/meritBadges.php?id="+id);
            }
            </script>
        ';
    }

    public function listUpdateForm($merit_badge_id): void
    {
        echo '<div class="tasksLister">';
        $meritBadge = $this->meritBadge->getMeritBadge($merit_badge_id);

        $data = [
            'submit' => "updateMeritBadge",
            'name' => $meritBadge->name,
            'category' => $meritBadge->category_id,
            'color' => $meritBadge->color,
            'id' => $merit_badge_id
        ];

        $this->printCreateForm($data);
        $this->printCreateFormScript();

        echo '</div>';
    }

    public function listCreateForm(): void
    {
        echo '<div class="tasksLister">';

        $data = [
            'submit' => "createMeritBadge",
            'name' => "",
            'category' => 0,
            'color' => "",
            'id' => 0
        ];

        $this->printCreateForm($data);
        $this->printCreateFormScript();

        echo '</div>';
    }

    private function printCreateFormScript(): void
    {
        echo '
            <script>
            document.getElementById("inputImageG").addEventListener("change", function () {
                const file = this.files[0];
                if (file && file.type !== "image/png") {
                    alert("Only .png files are allowed!");
                    this.value = ""; // Clear the input
                }
            });
            
            document.getElementById("inputImageR").addEventListener("change", function () {
                const file = this.files[0];
                if (file && file.type !== "image/png") {
                    alert("Only .png files are allowed!");
                    this.value = ""; // Clear the input
                }
            });
            </script>
        ';
    }

    private function printCreateForm(array $data): void
    {
        echo '
            <form method="POST" enctype="multipart/form-data">
                <label>
                    <span>Meno odborky: </span>
                    <input type="text" name="MeritBadgeName" value="'.$data['name'].'" required>
                </label>
                
                <br>
                
                <label>
                    <span>Kategoria odborky: </span>
                    <select name="MeritBadgeCategory" required>
        ';

        $this->printCategoryOptions($data['category']);

        echo '
                    </select>
                </label>
                
                <br>
                
                <label>
                    <span>Farba odborky: </span>
                    <input type="color" name="MeritBadgeColor" value="'.$data['color'].'" required>
                </label>
                
                <br>
                
                <label>
                    <span>Zelený stupeň: </span>
                    <input type="file" id="inputImageG" name="MeritBadgeImageG">
                </label>
                
                <br>
                
                <label>
                    <span>Červený stupeň: </span>
                    <input type="file" id="inputImageR" name="MeritBadgeImageR">
                </label>
                
                <br>
                
                <input type="hidden" name="meritBadgeId" value="'.$data['id'].'">
                <input type="submit" name="'.$data['submit'].'" value="Vytvorit Odborku">
            </form>
        ';
    }

    private function printCategoryOptions(int $default): void
    {
        $categories = $this->meritBadge->getAllCategories();

        foreach ($categories as $category) {
            if ($category->id === $default) {
                echo '
                    <option value="' . $category->id . '" selected>' . $category->name . '</option>
                ';
            }
            else {
                echo '
                    <option value="' . $category->id . '">' . $category->name . '</option>
            ';
            }
        }
    }

    public function listTasks(int $merit_badge_id): void
    {
        $levels = $this->meritBadge->getLevels();

        echo '
            <div class="tasksLister">
        ';

        foreach ($levels as $level) {

            $this->printStartOfTaskListerContainer($merit_badge_id, $level);
            $tasks = $this->meritBadge->getTasksByMeritBadgeIdAndLevelId($merit_badge_id, $level->id);

            foreach ($tasks as $task) {
                $this->printTask($task->id, $task->task);
            }

            $this->printEndOfTaskListerContainer();
        }

        echo '
            </div>
        ';

        $this->printSubmitButton();
    }

    private function printSubmitButton(): void
    {
        echo '
            <div id="groupSubmitButton">
                <button onclick="submitChanges()">Potvrdiť</button>
            </div>
        ';
    }

    private function printEndOfTaskListerContainer(): void
    {
        echo '
            </div>
        </div>
        ';
    }

    private function printStartOfTaskListerContainer(int $merit_badge_id, object $level): void
    {
        echo '
            <div class="tasksListerContainerMain">
                <div class="tasksListerContainerFilled" style="background-color: '.$level->color.';">
                    <h1 class="tasksListerHeading">'.$level->name.'</h1>
                </div>
                <div class="tasksListerContainerEmpty" id="level_container_id_'.$level->id.'" style="border: 3px dashed '.$level->color.'">
                <button onclick="createTask('.$level->id.', '.$merit_badge_id.')">Pridať úlohu</button>
        ';
    }

    private function printTask(int $task_id, string $task): void
    {
        echo '
            <div id="task_container_id_'.$task_id.'">
                <textarea id="text_area_id_'.$task_id.'" style="width: 100%" onchange="alterTask('.$task_id.')">'.$task.'</textarea>
                <button onclick="deleteTask('.$task_id.')">odstranit</button>
            </div>
        ';
    }
}

?>