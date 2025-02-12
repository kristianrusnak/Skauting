<?php

class MeritBadgeService
{
    /**
     * Connection to the class taskManager
     *
     * @var TaskManager
     */
    private TaskManager $tasks;

    /**
     * Connection to the class meritBadgeTaskManager
     *
     * @var MeritBadgeTaskManager
     */
    private MeritBadgeTaskManager $meritBadgeTasks;

    /**
     * Connection to the class meritBadgeLevelManager
     *
     * @var MeritBadgeLevelManager
     */
    private MeritBadgeLevelManager $levels;

    /**
     * connection to the class meritBadgeManager
     *
     * @var MeritBadgeManager
     */
    private MeritBadgeManager $meritBadge;

    /**
     * connection to the class categoriesOfMeritBadgeManager
     *
     * @var CategoriesOfMeritBadgeManager
     */
    private CategoriesOfMeritBadgeManager $categories;

    /**
     * connection to the class additionalInformationAboutMeritBadgeManager
     *
     * @var AdditionalInformationAboutMeritBadgeManager
     */
    private AdditionalInformationAboutMeritBadgeManager $information;

    function __construct($database)
    {
        $this->tasks = new TaskManager($database);
        $this->meritBadgeTasks = new MeritBadgeTaskManager($database);
        $this->levels = new MeritBadgeLevelManager($database);
        $this->meritBadge = new MeritBadgeManager($database);
        $this->categories = new CategoriesOfMeritBadgeManager($database);
        $this->information = new AdditionalInformationAboutMeritBadgeManager($database);
    }

    /**
     * Returns merit badges according to the input filter
     * If 'all' function will return array of all merit badges
     * If number function will return merit badge with matching category_id
     *
     * @return array
     * @throws Exception
     */
    public function getAllMeritBadges(): array
    {
        $meritBadges = $this->meritBadge->getAllMeritBadges();
        $result = array();

        $last_category_id = '';
        $array = array();
        foreach ($meritBadges as $meritBadge) {
            if ($last_category_id == ''){
                $last_category_id = $meritBadge['category_id'];
            }
            else if ($last_category_id != $meritBadge['category_id']){
                $result += [$last_category_id => $array];
                $array = array();
                $last_category_id = $meritBadge['category_id'];
            }
            $array[] = $meritBadge;
        }

        $result += [$last_category_id => $array];
        return $result;
    }

    public function getMeritBadgeTask($task_id): null|array
    {
        $task = $this->meritBadgeTasks->getMeritBadgeTask($task_id);
        if (empty($task)) {
            return null;
        }
        return $task;
    }

    /**
     * Returns all tasks from merit badge ordered by level_id
     *
     * @param $merit_badge_id
     * @return array
     * @throws Exception
     */
    public function getTasksFromMeritBadge($merit_badge_id):array
    {
         $tasks = $this->meritBadgeTasks->getAllTasksWithMeritBadge($merit_badge_id);
         $result = array();

         $last_level_id = '';
         $array = array();
         foreach ($tasks as $task) {
             if ($last_level_id == ''){
                 $last_level_id = $task['level_id'];
             }
             else if ($last_level_id != $task['level_id']){
                 $result += [$last_level_id => $array];
                 $array = array();
                 $last_level_id = $task['level_id'];

             }
             $array[] = $task;
         }

         $result += [$last_level_id => $array];
         return $result;
    }

    /**
     * Returns image of the lowest possible level of given merit badge
     *
     * @param $merit_badge_id
     * @return string
     * @throws Exception
     */
    public function getMeritBadgeImage($merit_badge_id): string
    {
        return $this->meritBadgeTasks->getLowestTaskLevelInMeritBadgeImage($merit_badge_id);
    }

    public function isMeritBadgeIdValid($merit_badge_id): bool
    {
        $meritBadge = $this->meritBadge->getMeritBadge($merit_badge_id);
        if (!empty($meritBadge)) {
            return true;
        }
        return false;
    }

    public function getMeritBadgeName($merit_badge_id): string
    {
         $task = $this->meritBadge->getMeritBadge($merit_badge_id);
         if (isset($task['name'])){
             return $task['name'];
         }
         return '';
    }

    /**
     * Creates new merit badge level
     *
     * @param $name
     * @return int
     * @throws Exception
     */
    public function createNewLevel($name): int
    {
        if ($result = $this->levels->addLevel($name)){
            return $result;
        }
        throw new Exception("Error creating new level");
    }

    /**
     * Creates new category of merit badge
     *
     * @param $name
     * @return int
     * @throws Exception
     */
    public function createNewCategory($name): int
    {
        if ($result = $this->categories->addCategory($name)){
            return $result;
        }
        throw new Exception("Error creating new category");
    }

    /**
     * Creates new additional information about merit badge
     *
     * @param $before
     * @param $between
     * @param $after
     * @return int
     * @throws Exception
     */
    public function createNewInformation($before, $between, $after): int
    {
        if ($result = $this->information->addInformation($before, $between, $after)){
            return $result;
        }
        throw new Exception("Error creating new information");
    }

    /**
     * Creates new merit badge and adds new tasks to the merit badge
     *
     * @param $tasks
     * @param $name
     * @param $image
     * @param $category_id
     * @param $additional_information_id
     * @param $color
     * @return void
     * @throws Exception
     */
    public function createNewMeritBadge($tasks, $name, $image, $category_id, $additional_information_id = null, $color = null): void
    {
        if (!$merit_badge_id = $this->meritBadge->addMeritBadge($name, $image, $color, $category_id, $additional_information_id)){
            throw new Exception("Error creating new merit badge");
        }

        foreach ($tasks as $task){
            if ($task_id = $this->tasks->addTask($task['order'], $task['task'], $task['position_id'])){
                if(!$this->meritBadgeTasks->addMeritBadgeTask($task_id, $merit_badge_id, $task['level_id']))
                    throw new Exception("Error creating new merit badge task");
            }
            else{
                throw new Exception("Error creating new task");
            }
        }
    }

    /**
     * Deletes merit badge and all task in the merit badge
     *
     * @param $merit_badge_id
     * @return void
     * @throws Exception
     */
    public function deleteMeritBadge($merit_badge_id): void
    {
        $tasks = $this->meritBadgeTasks->getAllTasksWithMeritBadge($merit_badge_id);

        if ($this->meritBadge->deleteMeritBadge($merit_badge_id)){
            foreach ($tasks as $task){
                $this->tasks->deleteTask($task['task_id']);
            }
        }
        else{
            throw new Exception("Error deleting merit badge");
        }
    }
}

?>