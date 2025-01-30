<?php
echo '
<!-- Main container-->
<div id="groupContainer">
    <!-- Main checkbox for selecting all tasks in all groups -->
    <div id="selectAllCheckboxesContainer">
        <input type="checkbox" name="selectAllCheckboxes" id="selectAllCheckboxes">
        <label for="selectAllCheckboxes" id="selectAllCheckboxesLabel">
        </label>
        <span id="selectAllCheckboxesSpan">Označ všetko</span>
    </div>
    <div id="approvedSubmitButtonContainer">
        <button id="approvedSubmitButton">Potvrdiť</button>
    </div>
    <!-- Group Container -->
    <div class="taskCheckerContainer">
        <!-- Main group checkbox that selects all tasks in the group-->
        <div class="headOfTheGroup">
            <input type="checkbox" name="selector" id="selectAllInTheGroup1" class="selectAllInTheGroupInput">
            <label for="selectAllInTheGroup1" class="selectAllInTheGroupLabel"></label>
            <span class="selectAllInTheGroupSpan">Meno a Priezvisko Vodcu</span>
        </div>
        
        <!-- Container with each member and its tasks -->
        <div class="membersOfTheGroup">
            <div class="memberOfTheGroup">
                <!-- Checkbox for selecting all tasks of the member -->
                <div class="selectAllFromTheMember" style="background-color: #fdd4b1">
                    <input type="checkbox" name="selector" id="selectAllFromTheMember1" class="selectAllInTheGroupInput">
                    <label for="selectAllFromTheMember1" class="selectAllInTheGroupLabel"></label>
                    <span class="selectAllInTheGroupSpan">Meno a Priezvisko Skauta</span>
                </div>
                <!-- each task of the member -->
                <div class="groupContainerTasks" style="border: 2px dashed #fdd4b1">
                    <div class="groupContainerTask">
                        <input type="checkbox" name="selector" id="task_id_1" class="groupContainerTaskInput">
                        <span class="groupContainerTaskSpan">toto je znenie ulohy</span>
                    </div>
                    <div class="groupContainerTask">
                        <input type="checkbox" name="selector" id="task_id_2" class="groupContainerTaskInput">
                        <input type="number" placeholder="body" min="0" class="groupContainerTaskInputText">
                        <span class="groupContainerTaskSpan">toto je znenie ulohy</span>
                    </div>
                </div>
            </div>

            <div class="memberOfTheGroup">
                <!-- Checkbox for selecting all tasks of the member -->
                <div class="selectAllFromTheMember" style="background-color: #ff9b9b">
                    <input type="checkbox" name="selector" id="selectAllFromTheMember1" class="selectAllInTheGroupInput">
                    <label for="selectAllFromTheMember1" class="selectAllInTheGroupLabel"></label>
                    <span class="selectAllInTheGroupSpan">Meno a Priezvisko Skauta</span>
                </div>
                <!-- each task of the member -->
                <div class="groupContainerTasks" style="border: 2px dashed #ff9b9b">
                    <div class="groupContainerTask">
                        <input type="checkbox" name="selector" id="task_id_1" class="groupContainerTaskInput">
                        <span class="groupContainerTaskSpan">toto je znenie ulohy</span>
                    </div>
                    <div class="groupContainerTask">
                        <input type="checkbox" name="selector" id="task_id_2" class="groupContainerTaskInput">
                        <input type="number" placeholder="body" min="0" class="groupContainerTaskInputText">
                        <span class="groupContainerTaskSpan">toto je znenie ulohy</span>
                    </div>
                </div>
            </div>
            
            <div class="memberOfTheGroup">
                <!-- Checkbox for selecting all tasks of the member -->
                <div class="selectAllFromTheMember" style="background-color: #93f6ff">
                    <input type="checkbox" name="selector" id="selectAllFromTheMember1" class="selectAllInTheGroupInput">
                    <label for="selectAllFromTheMember1" class="selectAllInTheGroupLabel"></label>
                    <span class="selectAllInTheGroupSpan">Meno a Priezvisko Skauta</span>
                </div>
                <!-- each task of the member -->
                <div class="groupContainerTasks" style="border: 2px dashed #93f6ff">
                    <div class="groupContainerTask">
                        <input type="checkbox" name="selector" id="task_id_1" class="groupContainerTaskInput">
                        <span class="groupContainerTaskSpan">toto je znenie ulohy</span>
                    </div>
                    <div class="groupContainerTask">
                        <input type="checkbox" name="selector" id="task_id_2" class="groupContainerTaskInput">
                        <input type="number" placeholder="body" min="0" class="groupContainerTaskInputText">
                        <span class="groupContainerTaskSpan">toto je znenie ulohy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';
?>