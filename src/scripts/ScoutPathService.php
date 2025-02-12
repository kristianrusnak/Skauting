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

    /**
     * @throws Exception
     */
    function __construct($database){
        $this->scoutPathTasks = new ScoutPathTaskManager($database);
        $this->chapters = new ChaptersOfScoutPathManager($database);
        $this->areas = new AreaOfScoutPathManager($database);
        $this->scoutPath = new ScoutPathManager($database);
        $this->requiredPoints = new RequiredPointsManager($database);
    }

    /**
     * @return array
     */
    public function getScoutPaths(): array
    {
        return $this->scoutPath->getAllPaths();
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

    public function getChapter($chapter_id): array
    {
        return $this->chapters->getChapter($chapter_id);
    }

    public function getArea($area_id): array
    {
        return $this->areas->getArea($area_id);
    }

    public function getStructuredScoutPaths(): array
    {
        $tasks = $this->scoutPathTasks->getAllTasks();
        $tasks = $this->getStructuredScoutPathById('scout_path_id', $tasks);

        foreach ($tasks as $scout_path_id => $array1) {
            $tasks[$scout_path_id] = $this->getStructuredScoutPathById('area_name', $array1);

            foreach ($tasks[$scout_path_id] as $area_id => $array2) {
                $tasks[$scout_path_id][$area_id] = $this->getStructuredScoutPathById('chapter_name', $array2);

                foreach ($tasks[$scout_path_id][$area_id] as $chapter_id => $array3) {
                    $tasks[$scout_path_id][$area_id][$chapter_id] = $this->getStructuredScoutPathById('mandatory', $array3);
                }

            }

        }

        return $tasks;
    }

    private function getStructuredScoutPathById($id, $tasks): array
    {
        $result = array();

        $last_id = '';
        $array = array();
        foreach ($tasks as $task) {
            if ($last_id == ''){
                $last_id = $task[$id];
            }
            else if ($last_id != $task[$id]){
                $result += [$last_id => $array];
                $array = array();
                $last_id = $task[$id];

            }
            $array[] = $task;
        }

        $result += [$last_id => $array];
        return $result;

    }

    public function getScoutPathTasks($task_id): null|array
    {
        $task = $this->scoutPathTasks->getTask($task_id);
        if (empty($task)) {
            return null;
        }
        return $task;
    }
}

?>