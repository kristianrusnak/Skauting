<?php

class GroupsLister
{
    private array $colors = array("#fdd4b1", "#ff9b9b", "#e3ff9b", "#9bffdc", "#9bedff", "#bc9bff", "#ffe669", "#ff91fa", "#558ffd", "#42fa45");

    private int $iter = 0;

    private int $id_count = 1;

    private array $user_id_mem = array();

    private UserService $user;

    function __construct($user)
    {
        $this->user = $user;
    }

    public function listLeaders(): void
    {
        $leaders = $this->user->getAllLeaders();

        $this->printGroupStart('Vodcovia');

        foreach ($leaders as $leader) {
            $color = $this->getColor();
            $this->printMemberHeader($leader['name'], $color);
            $this->printMember($leader['user_id'], $leader['position_id'], 0, $color);
        }
        $this->printGroupEnd();
    }

    public function listUncategorized():void
    {
        $members = $this->user->getAllUncategorizedUsers();

        $this->printGroupStart('Nezaradený');

        foreach ($members as $member) {
            $color = $this->getColor();
            $this->printMemberHeader($member['name'], $color);
            $this->printMember($member['user_id'], $member['position_id'], 0, $color);
        }
        $this->printGroupEnd();
    }

    public function listTeams(): void
    {
        $teams = $this->user->getAllGroups();

        foreach ($teams as $leader_name => $team) {
            $this->printGroupStart('Družina: '.$leader_name);

            foreach ($team as $member) {
                $color = $this->getColor();
                $this->printMemberHeader($member['member_name'], $color);
                $this->printMember($member['member_id'], $member['member_position_id'], $member['leader_id'], $color);
            }

            $this->printGroupEnd();
        }
    }

    public function printScript(): void
    {
        echo '
            <script>
                let data = {};
            
                function submitChanges(){
                    fetch("../scripts/handleGroupChange.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(data)  // Convert the array to JSON
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log(data); // Handle PHP response
                        data = {};
                        location.reload(); // Refresh the page
                    }) // Handle PHP response
                    .catch(error => console.error("Error: ", error));
                }
            
                function alter(user_id, position_id, leader_id) {
                    data[user_id] = [position_id, leader_id]
                    console.log(data);
                }
        ';

        foreach ($this->user_id_mem as $user_id) {
            echo '
                document.getElementById("position_select_id_'.$user_id.'").addEventListener("change", function(){
                    const position_id = document.getElementById("position_select_id_'.$user_id.'").value;
                    const leader_id = document.getElementById("group_select_id_'.$user_id.'").value;
                    
                    alter('.$user_id.', position_id, leader_id);
                })
                
                document.getElementById("group_select_id_'.$user_id.'").addEventListener("change", function(){
                    const position_id = document.getElementById("position_select_id_'.$user_id.'").value;
                    const leader_id = document.getElementById("group_select_id_'.$user_id.'").value;
                    
                    alter('.$user_id.', position_id, leader_id);
                })
            ';
        }
        echo '
            </script>
        ';
    }

    public function printCssScript(): void
    {
        echo '
            <script>
                function containerChange(id)
                {
                    const container = document.getElementById(id);
                    if (container.style.display === "block"){
                        container.style.display = "none";
                    }
                    else {
                        container.style.display = "block";
                    }
                }
            </script>
        ';
    }

    public function printSubmitButton(): void
    {
        echo '
            <div id="groupSubmitButton">
                <button onclick="submitChanges()">Potvrdiť</button>
            </div>
        ';
    }

    public function printGroupContainerStart(): void
    {
        echo '<div id="groupContainer">';
    }

    public function printGroupContainerEnd(): void
    {
        echo '</div>';
    }

    private function printGroupStart($name): void
    {
        echo '
            <div class="taskCheckerContainer">
            <div class="headOfTheGroup">
                <span class="selectAllInTheGroupSpan">'.$name.'</span>
                <img class="groupsIcon" src="../images/arrows.png" alt="rozbal/zabal" onclick="containerChange(\'container'.$this->id_count.'\')">
            </div>
            <div class="membersOfTheGroup" id="container'.$this->id_count.'">
        ';
        $this->id_count++;
    }

    private function printGroupEnd(): void
    {
        echo '
                </div>
            </div>
        ';
    }

    private function printMemberHeader($name, $color): void
    {
        echo '
            <div class="memberOfTheGroup">

                <div class="selectAllFromTheMember" style="background-color: '.$color.'">
                    <span class="selectAllInTheGroupSpan">'.$name.'</span>
                    <img class="groupsIcon" src="../images/arrows.png" alt="rozbal/zabal" onclick="containerChange(\'container'.$this->id_count.'\')">
                </div>
        ';
    }

    private function printMember($member_id, $member_position_id, $leader_id, $color): void
    {
        $this->user_id_mem[] = $member_id;

        echo '
            <div class="groupContainerTasks" id="container'.$this->id_count.'" style="border: 2px dashed '.$color.'">
                <div class="groupContainerTask">
                    <label>
                        <span class="groupContainerTaskSpan">Pozícia: </span>
                        <select id="position_select_id_'.$member_id.'">
        ';

        $this->printPositionOptions($member_position_id);

        echo '                
                        </select>
                    </label>
                </div>
                <div class="groupContainerTask">
                    <span class="groupContainerTaskSpan">Družina: </span>
                    <label>
                        <select id="group_select_id_'.$member_id.'">
        ';

        $this->printTeamOptions($leader_id);

        echo '
                        </select>
                    </label>
                </div>
            </div>
            </div>
        ';

        $this->id_count++;
    }

    private function printPositionOptions($position_id): void
    {
        $positions = $this->user->getPositions();

        foreach ($positions as $position) {
            if ($position['id'] != 2 && $position['id'] != 5){
                if ($position['id'] == $position_id){
                    echo '<option value="'.$position['id'].'" style="font-weight: bolder" selected>'.$position['name'].'</option>';
                }
                else{
                    echo '<option value="'.$position['id'].'">'.$position['name'].'</option>';
                }
            }
        }
    }

    private function printTeamOptions($members_leader): void
    {
        $leaders = $this->user->getAllGroupLeaders();

        if ($members_leader == 0) {
            echo '<option value="0" style="font-weight: bolder" selected>Nie je</option>';
        }
        else {
            echo '<option value="0">Nie je</option>';
        }

        foreach ($leaders as $leader) {
            if ($leader['leader_id'] == $members_leader) {
                echo '<option value="'.$leader['leader_id'].'" style="font-weight: bolder" selected>'.$leader['name'].'</option>';
            }
            else {
                echo '<option value="'.$leader['leader_id'].'">'.$leader['name'].'</option>';
            }
        }
    }

    private function getColor(): string
    {
        $color = $this->colors[$this->iter];
        $this->iter++;
        if ($this->iter >= count($this->colors)) {
            $this->iter = 0;
        }
        return $color;
    }
}

?>