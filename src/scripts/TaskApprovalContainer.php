<?php

class TaskApprovalContainer
{
    private int $container_iter;

    private UserService $user;

    private CompletedTasksService $completedTasks;

    function __Construct($user, $completedTasks)
    {
        $this->container_iter = 0;
        $this->user = $user;
        $this->completedTasks = $completedTasks;
    }

    public  function printCssScript(): void
    {
        echo '
            <script>
                function foldUnfoldContainer(id)
                {
                    let element = document.getElementById("TaskContainer_id_" + id);
                    
                    if (element.style.display === "flex") {
                        element.style.display = "none";
                    }
                    else {
                        element.style.display = "flex";
                    }
                }
            </script>
        ';
    }

    public function printScript(): void
    {
        echo '
            <script>
                function linkToSideAndChangeUser(id, siteType, user_id, user_name)
                {
                    const site_url = "../pages/" + siteType + ".php?id=" + id;
                    
                    const form = document.createElement("form");
                    form.method = "POST";
                    
                    // No need to set form.action, it defaults to the same page
                
                    // Add hidden input fields
                    const input1 = document.createElement("input");
                    input1.type = "hidden";
                    input1.name = "nameOfUser";
                    input1.value = user_name;
                
                    const input2 = document.createElement("input");
                    input2.type = "hidden";
                    input2.name = "idOfUser";
                    input2.value = user_id;
                    
                    const input3 = document.createElement("input");
                    input3.type = "hidden";
                    input3.name = "website";
                    input3.value = site_url;
                    
                    const submitButton = document.createElement("input");
                    submitButton.type = "hidden";
                    submitButton.name = "changeToUserView";  // Name of the submit button
                    submitButton.value = "Send Data";    // Button text (not necessary)
                
                    form.appendChild(input1);
                    form.appendChild(input2);
                    form.appendChild(input3);
                    form.appendChild(submitButton);
                    document.body.appendChild(form);
                
                    form.submit(); // Submit the form to the same page
                }
            </script>
        ';
    }

    public function listUnverifiedForLeader(): void
    {
        $teams = $this->user->getAllGroups();
        foreach ($teams as $team_name => $team) {
            foreach ($team as $member) {
                $this->listUnverified($member);
            }
        }
    }

    public function listUnverifiedOfMyTeam($leader_id): void
    {
        $team = $this->user->getGroup($leader_id);
        foreach ($team as $member) {
            $this->listUnverified($member);
        }
    }

    private function listUnverified($member): void
    {
        $scout_paths = $this->completedTasks->getUnverifiedScoutPaths($member['member_id'], $_SESSION['position_id']);
        $merit_badges = $this->completedTasks->getUnverifiedMeritBadges($member['member_id'], $_SESSION['position_id']);

        if (!empty($scout_paths) || !empty($merit_badges)) {

            $this->printMemberContainerStart($member['member_name']);

            foreach ($scout_paths as $scout_path) {
                $this->printContainer($scout_path['id'], 'scoutPath',
                    $scout_path['name'], $scout_path['color'],
                    $scout_path['image'], $member['member_id'],
                    $member['member_name']);
            }

            foreach ($merit_badges as $merit_badge) {
                $this->printContainer($merit_badge['id'], 'meritBadges',
                    $merit_badge['name'], $merit_badge['color'],
                    $merit_badge['image'].$merit_badge['level_image'],
                    $member['member_id'], $member['member_name']);
            }

            $this->printMemberContainerEnd();
        }
    }

    private function printContainer($id, $siteType, $name, $color, $image, $user_id, $user_name): void
    {
        $script = 'linkToSideAndChangeUser('.$id.', \''.$siteType.'\', '.$user_id.', \''.$user_name.'\')';
        echo '
            <a onclick="'.$script.'" class="taskContainer">
                <span class="taskContainerHeading" style="background-color: '.$color.'">'.$name.'</span>
                <img class="taskContainerImage" src="../images/'.$image.'.png" alt="'.$name.'">
            </a>
            ';
    }

    private function printMemberContainerStart($name): void
    {
        echo '
            <div class="taskApprovalUsersTasksContainer">
            
                <div class="taskApprovalUsersHeader">
                    <span class="taskApprovalUsersName">'.$name.'</span>
                    <img class="taskApprovalUsersIcon" onclick="foldUnfoldContainer('.$this->container_iter.')" src="../images/arrows.png" alt="rozbalit/zabalit">
                </div>
   
                <div class="taskApprovalUsersTasks" id="TaskContainer_id_'.$this->container_iter.'">
        ';
        $this->container_iter++;
    }

    private function printMemberContainerEnd(): void
    {
        echo '
                </div>
            </div>
        ';
    }

    public function printContainerStart(): void
    {
        echo '
            <div id="taskApprovalMainContainer">
        ';
    }

    public function printContainerEnd(): void
    {
        echo '
            </div>
        ';
    }

}

?>