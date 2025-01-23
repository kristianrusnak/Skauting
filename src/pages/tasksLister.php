<?php
echo '
<div class="tasksLister">
    <h1 class="tasksListerHeading">Oblasť úloh</h1>
    <div class="tasksListerContainerMain">
        <div class="tasksListerContainerFilled">
            <h1 class="tasksListerContainerHeading">Kategória úloh</h1>
            <div class="tasksListerContainerTask">
                <input id="task_id_1" type="checkbox">
                <span>toto je znenie ulohy</span>
            </div>
            <div class="tasksListerContainerTask">
                <input id="task_id_2" type="checkbox">
                <span>toto je znenie ulohy</span>
            </div>
            <div class="tasksListerContainerTask">
                <input id="task_id_3" type="checkbox">
                <span>toto je znenie ulohy</span>
            </div>
        </div>
        <div class="tasksListerContainerEmpty">
            <h1 class="tasksListerContainerSecond">Volitelná časť</h1>
            <div class="tasksListerContainerTask">
                <input id="task_id_4" type="checkbox" >
                <span>toto je znenie ulohy</span>
            </div>
            <div class="tasksListerContainerTask">
                <input id="task_id_5" type="checkbox">
                <span>toto je znenie ulohy</span>
            </div>
            <div class="tasksListerContainerTask">
                <input id="task_id_6" type="checkbox">
                <span>toto je znenie ulohy</span>
            </div>
        </div>
    </div>
    
    
    <h1 class="tasksListerHeading">Oblasť úloh</h1>
    <div class="tasksListerContainerMain">
        <div class="tasksListerContainerFilled">
            <h1 class="tasksListerContainerHeading">Kategória úloh</h1>
        </div>
        <div class="tasksListerContainerEmpty">
            <h1 class="tasksListerContainerSecond">Volitelná časť</h1>
            <div class="tasksListerContainerTask">
                <input type="checkbox" >
                <span>toto je znenie ulohy</span>
            </div>
            <div class="tasksListerContainerTask">
                <input type="checkbox">
                <span>toto je znenie ulohy</span>
            </div>
            <div class="tasksListerContainerTask">
                <input type="checkbox">
                <span>toto je znenie ulohy</span>
            </div>
        </div>
    </div>
    
    <h1 class="tasksListerHeading">Oblasť úloh</h1>
    <div class="tasksListerContainerMain">
        <div class="tasksListerContainerFilled" style="border-radius: 15px">
            <h1 class="tasksListerContainerHeading">Kategória úloh</h1>
            <div class="tasksListerContainerTask">
                <input type="checkbox" >
                <span>toto je znenie ulohy</span>
            </div>
            <div class="tasksListerContainerTask">
                <input type="checkbox">
                <span>toto je znenie ulohy</span>
            </div>
            <div class="tasksListerContainerTask">
                <input type="checkbox">
                <span>toto je znenie ulohy</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("task_id_1").addEventListener("change", function(){
        const data = {user_id: 0, task_id: 1};
        if (document.getElementById("task_id_1").checked){
            //TODO prida danu ulohu pre daneho pouzivatela ako splnenu
        }else {
            //TODO odoberu danu ulohu pre daneho pouzivatela z hotovych uloh
        }
    });
</script>
';
?>