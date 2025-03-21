<?php

class ScoutPathService
{
    private ScoutPathTaskManager $scoutPathTasks;

    private ChaptersOfScoutPathManager $chapters;

    private AreaOfScoutPathManager $areas;

    /**
     * @var ScoutPathManager
     */
    private ScoutPathManager $scoutPath;

    private RequiredPointsManager $requiredPoints;

    private TaskManager $tasks;

    /**
     * @var MatchTaskManager
     */
    private MatchTaskManager $match;

    /**
     * @throws Exception
     */
    function __construct($database){
        $this->scoutPathTasks = new ScoutPathTaskManager($database);
        $this->chapters = new ChaptersOfScoutPathManager($database);
        $this->areas = new AreaOfScoutPathManager($database);
        $this->scoutPath = new ScoutPathManager($database);
        $this->requiredPoints = new RequiredPointsManager($database);
        $this->tasks = new TaskManager($database);
        $this->match = new MatchTaskManager($database);
    }

    /**
     * @return array
     */
    public function getScoutPaths(): array
    {
        return $this->scoutPath->getAllPaths();
    }

    public function getScoutPath($scout_path_id): array
    {
        return $this->scoutPath->getPath($scout_path_id);
    }

    public function getAreasOfScoutPath(): array
    {
        return $this->areas->getAllAreas();
    }

    public function getChaptersOfScoutPath($scout_path_id, $area_id): array
    {
        return $this->chapters->getChaptersWhere($scout_path_id, $area_id);
    }

    public function getChapterByScoutPath($scout_path_id): array
    {
        $chapters = $this->chapters->getAllChapters();
        $result = array();
        foreach ($chapters as $chapter) {
            if ($chapter['scout_path_id'] == $scout_path_id) {
                $result[] = $chapter;
            }
        }
        return $result;
    }

    public function getScoutPathTask($scout_path_id): array
    {
        return $this->scoutPathTasks->getTask($scout_path_id);
    }

    public function getTasksFromChapter($chapter_id): array
    {
        $result = array();
        $tasks = $this->scoutPathTasks->getAllTasks();
        foreach ($tasks as $task) {
            if ($task['chapter_id'] == $chapter_id) {
                $result[] = $task;
            }
        }
        return $result;
    }

    public function getNameOfScoutPath($scout_path_id): string
    {
        $scoutPath = $this->scoutPath->getPath($scout_path_id);
        if (isset($scoutPath['name'])){
            return $scoutPath['name'];
        }
        return '';
    }

    public function isValidScoutPathId($scout_path_id): bool
    {
        $scoutPath = $this->scoutPath->getPath($scout_path_id);
        if (!empty($scoutPath)) {
            return true;
        }
        return false;
    }

    public function addNewChapter($scout_path_id, $area_id, $name, $mandatory = 1): bool
    {
        return $this->chapters->addChapter($name, $mandatory, $area_id, $scout_path_id);
    }

    public function updateChapter($chapter_id, $name): bool
    {
        return $this->chapters->updateChapter($chapter_id, "name", $name);
    }

    public function deleteChapter($chapter_id): bool
    {
        $tasks = $this->getTasksFromChapter($chapter_id);

        if ($this->chapters->deleteChapter($chapter_id)) {
            foreach ($tasks as $task) {
                if (!$this->scoutPathTasks->deleteTask($task['task_id'])) {
                    return false;
                }
                if (!$this->tasks->deleteTask($task['task_id'])){
                    return false;
                }
            }
        }
        return true;
    }

    public function addNewTask($chapter_id, $mandatory, $name, $task, $points, $position_id): bool
    {
        if ($task_id = $this->tasks->addTask("1", $task, $position_id, $name)) {
            if ($points == "") {
                $points = "null";
            }
            if ($this->scoutPathTasks->addTask($task_id, $chapter_id, $points, $mandatory)) {
                $this->match->embed($task_id, $task);
                return true;
            }
        }
        return false;
    }

    public function updateTask($task_id, $name, $task, $points, $position_id): bool
    {
        if (!$this->tasks->updateTask($task_id, "name", $name)){
            return false;
        }
        if (!$this->tasks->updateTask($task_id, "task", $task)){
            return false;
        }
        if (!$this->tasks->updateTask($task_id, "position_id", $position_id)){
            return false;
        }

        if ($points == "") {
            $points = "null";
        }
        if (!$this->scoutPathTasks->updateTask($task_id, "points", $points)){
            return false;
        }

        return true;
    }

    public function deleteTask($task_id): bool
    {
        if (!$this->scoutPathTasks->deleteTask($task_id)) {
            return false;
        }
        if (!$this->tasks->deleteTask($task_id)){
            return false;
        }
        return true;
    }

    public function createScoutPath($name, $image, $color, $points):bool
    {
        if ($scout_path_id = $this->scoutPath->addPath($name, $image, $color, $points)) {
            $areas = $this->areas->getAllAreas();

            foreach ($areas as $area) {
                if (!$this->requiredPoints->addRP($scout_path_id, $area['id'])) {
                    return false;
                }
            }

            return true;
        }
        return false;
    }

    public function updateScoutPath($id, $row, $newValue): bool
    {
        if ($this->scoutPath->updatePath($id, $row, $newValue)) {
            return true;
        }
        return false;
    }

    public function deleteScoutPath($id): bool
    {
        if (!$this->requiredPoints->deleteRP($id)) {
            return false;
        }

        $chapters = $this->getChapterByScoutPath($id);

        foreach ($chapters as $chapter) {
            $tasks = $this->getTasksFromChapter($chapter['id']);

            foreach ($tasks as $task) {
                if (!$this->tasks->deleteTask($task['task_id'])) {
                    return false;
                }
            }

            if (!$this->chapters->deleteChapter($chapter['id'])) {
                return false;
            }
        }

        if (!$this->scoutPath->deletePath($id)) {
            return false;
        }

        return true;
    }
}

?>