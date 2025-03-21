<?php

class MeritBadgeTaskEditor
{
    private MeritBadgeService $meritBadge;

    function __construct($meritBadge)
    {
        $this->meritBadge = $meritBadge;
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
                    newTasks[new_task_id] = [element.value, level_id, merit_badge_id];
                    
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
                    executeSubmit(changedTasks, "?change_task=true");
                    executeSubmit(newTasks, "?add_task=true");
                    executeSubmit(deletedTasks, "?delete_task=true");
                    location.reload();
                }
                
                function executeSubmit(data, type) {
                    const scriptWeb = "../APIs/handleMeritBadgeChange.php" + type
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
                window.location.replace("../../pages/meritBadges.php?id="+id);
            }
            </script>
        ';
    }

    public function listUpdateForm($merit_badge_id): void
    {
        echo '<div class="tasksLister">';
        $categories = $this->meritBadge->getMeritBadgeCategories();
        $meritBadge = $this->meritBadge->getMeritBadge($merit_badge_id);
        $this->printCreateForm($categories, "updateMeritBadge", $meritBadge['name'], $meritBadge['category_real_id'], $meritBadge['color']);
        $this->printCreateFormScript();
        echo '</div>';
    }

    public function listCreateForm(): void
    {
        echo '<div class="tasksLister">';
        $categories = $this->meritBadge->getMeritBadgeCategories();
        $this->printCreateForm($categories, "createMeritBadge");
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

    private function printCreateForm($categories, $submit, $name = "", $category = "", $color = ""): void
    {
        echo '
            <form method="POST" enctype="multipart/form-data">
                <label>
                    <span>Meno odborky: </span>
                    <input type="text" name="MeritBadgeName" value="'.$name.'" required>
                </label>
                
                <br>
                
                <label>
                    <span>Kategoria odborky: </span>
                    <select name="MeritBadgeCategory" required>
        ';

        $this->printCategoryOptions($categories, $category);

        echo '
                    </select>
                </label>
                
                <br>
                
                <label>
                    <span>Farba odborky: </span>
                    <input type="color" name="MeritBadgeColor" value="'.$color.'" required>
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
                
                <input type="submit" name="'.$submit.'" value="Vytvorit Odborku">
            </form>
        ';
    }

    private function printCategoryOptions($categories, $default): void
    {
        foreach ($categories as $category) {
            if ($category['id'] === $default) {
                echo '
                    <option value="' . $category['id'] . '" selected>' . $category['name'] . '</option>
                ';
            }
            else {
                echo '
                    <option value="' . $category['id'] . '">' . $category['name'] . '</option>
            ';
            }
        }
    }

    public function listTasks($merit_badge_id): void
    {
        $meritBadges = $this->meritBadge->getTasksFromMeritBadge($merit_badge_id);
        $levels = $this->meritBadge->getMeritBadgeLevels();

        echo '
            <div class="tasksLister">
        ';

        foreach ($levels as $level) {
            $first = true;

            if (!isset($meritBadges[$level['id']])) {
                $this->printStartOfTaskListerContainer($level['name'], $level['color'], $level['id'], $merit_badge_id);
                $this->printEndOfTaskListerContainer();
                continue;
            }

            foreach ($meritBadges[$level['id']] as $task) {
                if ($first) {
                    $first = false;
                    $this->printStartOfTaskListerContainer($task['level_name'], $task['level_color'], $level['id'], $task['merit_badge_id']);
                }
                $this->printTask($task['task_id'], $task['task']);
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

    private function printStartOfTaskListerContainer($level_name, $color, $level_id, $merit_badge_id): void
    {
        echo '
        <div class="tasksListerContainerMain">
            <div class="tasksListerContainerFilled" style="background-color: '.$color.';">
                <h1 class="tasksListerHeading">'.$level_name.'</h1>
            </div>
            <div class="tasksListerContainerEmpty" id="level_container_id_'.$level_id.'" style="border: 3px dashed '.$color.'">
            <button onclick="createTask('.$level_id.', '.$merit_badge_id.')">Pridať úlohu</button>
        ';
    }

    private function printTask($task_id, $task): void
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