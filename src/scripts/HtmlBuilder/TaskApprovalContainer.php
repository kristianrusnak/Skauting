<?php

namespace HtmlBuilder;

require_once dirname(__DIR__) . '/Users/Service/UserService.php';
require_once dirname(__DIR__) . '/Tasks/Service/CompletedTasksService.php';
require_once dirname(__DIR__) . '/MeritBadge/Service/MeritBadgeService.php';
require_once dirname(__DIR__) . '/ScoutPath/Service/ScoutPathService.php';

use User\Service\UserService as User;
use Task\Service\CompletedTasksService as CompletedTasks;
use MeritBadge\Service\MeritBadgeService as MeritBadge;
use ScoutPath\Service\ScoutPathService as ScoutPath;

class TaskApprovalContainer
{
    private int $container_iter;

    private User $user;

    private CompletedTasks $completedTasks;

    private MeritBadge $badges;

    private ScoutPath $paths;

    function __Construct()
    {
        $this->container_iter = 0;
        $this->user = new User();
        $this->completedTasks = new CompletedTasks();
        $this->badges = new MeritBadge();
        $this->paths = new ScoutPath();
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
                    const site_url = "../../pages/" + siteType + ".php?id=" + id + "&filter=unverified";
                    
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
        $members = $this->user->getAllUsers();
        foreach ($members as $member) {
            $this->listUnverified($member, 4);
        }
    }

    public function listUnverifiedForPatrolLeaders(int $leader_id): void
    {
        $members = $this->user->getAllMembers($leader_id);
        foreach ($members as $member) {
            $this->listUnverified($member, 3);
        }
    }

    private function listUnverified(object $member, int $position_id): void
    {
        $scout_paths = $this->paths->getScoutPaths();
        $has_header = false;


        foreach ($scout_paths as $scout_path) {

            if ($position_id == 4 && !$this->completedTasks->hasUserUnverifiedScoutPathTasksForLeader($member->id, $scout_path->id)) {
                continue;
            }
            else if ($position_id == 3 && !$this->completedTasks->hasUserUnverifiedScoutPathTasksForPatrolLeader($member->id, $scout_path->id)) {
                continue;
            }

            if (!$has_header) {
                $this->printMemberContainerStart($member->name);
                $has_header = true;
            }

            $data = [
                'id' => $scout_path->id,
                'name' => $scout_path->name,
                'image' => $scout_path->image,
                'color' => $scout_path->color,
                'siteType' => 'scoutPath',
                'user_id' => $member->id,
                'user_name' => $member->name
            ];

            $this->printContainer($data);
        }

        $merit_badges = $this->badges->getMeritBadges();

        foreach ($merit_badges as $merit_badge) {

            $levels = $this->badges->getLevels();

            foreach ($levels as $level) {

                if ($position_id == 4 || !$this->completedTasks->hasUserUnverifiedMaritBadgeTasks($member->id, $merit_badge->id, $level->id)) {
                    continue;
                }

                if (!$has_header) {
                    $this->printMemberContainerStart($member->name);
                    $has_header = true;
                }

                $image = $merit_badge->image . $level->image;

                $data = [
                    'id' => $merit_badge->id,
                    'name' => $merit_badge->name,
                    'image' => $image,
                    'color' => $merit_badge->color,
                    'siteType' => 'meritBadges',
                    'user_id' => $member->id,
                    'user_name' => $member->name
                ];

                $this->printContainer($data);
            }
        }

        if ($has_header) {
            $this->printMemberContainerEnd();
        }
    }

    private function printContainer(array $data): void
    {
        $script = 'linkToSideAndChangeUser('.$data['id'].', \''.$data['siteType'].'\', '.$data['user_id'].', \''.$data['user_name'].'\')';

        echo '
            <a onclick="'.$script.'" class="taskContainer">
                <span class="taskContainerHeading" style="background-color: '.$data['color'].'">'.$data['name'].'</span>
                <img class="taskContainerImage" src="../images/'.$data['image'].'.png" alt="'.$data['name'].'">
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