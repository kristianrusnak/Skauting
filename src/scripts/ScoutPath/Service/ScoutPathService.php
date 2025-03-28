<?php

namespace ScoutPath\Service;

require_once dirname(__DIR__) . '/Manager/AreaOfScoutPathManager.php';
require_once dirname(__DIR__) . '/Manager/ChaptersOfScoutPathManager.php';
require_once dirname(__DIR__) . '/Manager/RequiredPointsManager.php';
require_once dirname(__DIR__) . '/Manager/ScoutPathManager.php';
require_once dirname(__DIR__) . '/Manager/ScoutPathTaskManager.php';

use Error;
use Exception;

use ScoutPath\Manager\AreaOfScoutPathManager as Areas;
use ScoutPath\Manager\ChaptersOfScoutPathManager as Chapters;
use ScoutPath\Manager\RequiredPointsManager as Rp;
use ScoutPath\Manager\ScoutPathManager as Paths;
use ScoutPath\Manager\ScoutPathTaskManager as Tasks;

class ScoutPathService
{
    private Areas $areas;

    private Chapters $chapters;

    private Rp $rp;

    private Paths $paths;

    private Tasks $tasks;

    function __construct(){
        $this->areas = new Areas();
        $this->chapters = new Chapters();
        $this->rp = new Rp();
        $this->paths = new Paths();
        $this->tasks = new Tasks();
    }

    public function getScoutPaths(): array
    {
        return $this->paths->getAll();
    }

    public function getTask(int $task_id): null|object
    {
        return $this->tasks->getById($task_id);
    }

    public function getScoutPath(int $scout_path_id): object
    {
        return $this->paths->get($scout_path_id);
    }

    public function getScoutPathByChapterId(int $chapter_id): object
    {
        $chapter = $this->getChapterById($chapter_id);
        return $this->getScoutPath($chapter->scout_path_id);
    }

    public function getAreas(): array
    {
        return $this->areas->getAll();
    }

    public function getRequired(int $scout_path_id, int $area_id): object
    {
        return $this->rp->getAllByAreaAndScoutPathId($scout_path_id, $area_id);
    }

    public function getRequiredByChapterId($chapter_id): object
    {
        $chapter = $this->chapters->getById($chapter_id);
        return $this->getRequired($chapter->scout_path_id, $chapter->area_id);
    }

    public function getChaptersOfScoutPath(int $scout_path_id, int $area_id): array
    {
        return $this->chapters->getAllByAreaIdAndScoutPathId($scout_path_id, $area_id);
    }

    public function getChapterById(int $chapter_id): object
    {
        return $this->chapters->getById($chapter_id);
    }

    public function getScoutPathTask($scout_path_id): object
    {
        return $this->tasks->getById($scout_path_id);
    }

    public function getTasksFromChapter(int $chapter_id): array
    {
        $result = array();
        $tasks = $this->tasks->getAll();
        foreach ($tasks as $task) {
            if ($task->chapter_id == $chapter_id) {
                $result[] = $task;
            }
        }
        return $result;
    }

    public function getNameOfScoutPath($scout_path_id): string
    {
        $scoutPath = $this->paths->get($scout_path_id);
        if (isset($scoutPath->name)){
            return $scoutPath->name;
        }
        return '';
    }

    public function isValidScoutPathId($scout_path_id): bool
    {
        $scoutPath = $this->paths->get($scout_path_id);
        if (!empty($scoutPath)) {
            return true;
        }
        return false;
    }

    public function addNewChapter($data): bool
    {
        try {
            return $this->chapters->add($data) > 0;
        }
        catch (Exception|Error) {
            return false;
        }
    }

    public function updateChapter($chapter_id, $name): bool
    {
        try {
            return $this->chapters->update($chapter_id, "name", $name) > 0;
        }
        catch (Exception|Error) {
            return false;
        }
    }

    public function deleteChapter($chapter_id): bool
    {
        try {
            $tasks = $this->getTasksFromChapter($chapter_id);

            if ($this->chapters->remove($chapter_id)) {

                foreach ($tasks as $task) {

                    $this->tasks->remove($task->id);
                }

            }
            else {
                return false;
            }

            return true;
        }
        catch (Exception|Error) {
            return false;
        }
    }

    public function addNewTask(array $data): bool
    {
        try {

            if ($this->tasks->add($data) > 0) {
                return true;
            }

            return false;

        }
        catch (Exception|Error) {
            return false;
        }
    }

    public function updateTask(int $task_id, array $task): bool
    {
        try {

            foreach ($task as $row => $newValue) {

                $this->tasks->update($task_id, $row, $newValue);

            }

            return true;

        }
        catch (Exception|Error) {
            return false;
        }
    }

    public function deleteTask($task_id): bool
    {
        try {

            if ($this->tasks->remove($task_id) > 0) {
                return true;
            }

            return false;

        }
        catch (Exception|Error) {
            return false;
        }
    }

    public function createScoutPath(array $data):bool
    {
        try {
            if ($scout_path_id = $this->paths->add($data)) {

                $areas = $this->areas->getAll();

                foreach ($areas as $area) {

                    $data = [
                        'scout_path_id' => $scout_path_id,
                        'area_id' => $area->id,
                    ];

                    if (!$this->rp->add($data)) {
                        $this->paths->remove($scout_path_id);
                        return false;
                    }

                }

            }

            return true;
        }
        catch (Exception|Error) {
            return false;
        }
    }

    public function updateScoutPath($id, $row, $newValue): bool
    {
        try {
            if ($this->paths->update($id, $row, $newValue)) {
                return true;
            }

            return false;
        }
        catch (Exception|Error) {
            return false;
        }
    }

    public function deleteScoutPath(int $scout_path_id): void
    {
        try {
            $this->rp->remove($scout_path_id);

            $chapters = $this->chapters->getAllByScoutPathId($scout_path_id);

            foreach ($chapters as $chapter) {
                $tasks = $this->getTasksFromChapter($chapter->id);

                foreach ($tasks as $task) {
                    $this->tasks->remove($task->task_id);
                }

                $this->chapters->remove($chapter->id);
            }

            $this->paths->remove($scout_path_id);
        }
        catch (Exception|Error) {}
    }
}