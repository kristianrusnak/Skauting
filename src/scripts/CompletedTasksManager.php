<?php

class CompletedTasksManager
{
    /**
     * Database connection
     *
     * @var DatabaseService
     */
    private DatabaseService $database;

    function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * Returns a row from database about users information about the task
     *
     * @param $user_id
     * @param $task_id
     * @return array
     * @throws Exception
     */
    public function getUsersTask($task_id, $user_id): array
    {
        $this->database->setSql("
                SELECT * 
                FROM completed_tasks AS ct
                INNER JOIN tasks AS t ON ct.task_id = t.id
                WHERE task_id = '$task_id' AND user_id = '$user_id'
        ");
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0){
            return $result->fetch_assoc();
        }
        return array();
    }

    /**
     * Returns array all tasks from user from merit badge that are in progress
     *
     * @param $user_id
     * @return array
     * @throws Exception
     */
    public function getAllTasksInProgressFromMeritBadge($user_id): array
    {
        $array = array();
        $this->database->setSql(
            'SELECT 
                    t1.merit_badge_id, 
                    t1.level_id, 
                    t1.level_image,
                    t1.finished, 
                    t2.total,
                    mb.name,
                    mb.image,
                    mb.color
                FROM
                    (SELECT mbt.merit_badge_id, mbt.level_id, lmb.image AS level_image, count(*) AS finished
                    FROM completed_tasks AS ct
                    INNER JOIN merit_badge_tasks AS mbt ON mbt.task_id = ct.task_id AND ct.user_id = '.$user_id.' AND ct.verified = 1
                    INNER JOIN levels_of_merit_badge AS lmb ON lmb.id = mbt.level_id
                    GROUP BY mbt.merit_badge_id, mbt.level_id) AS t1
                INNER JOIN
                    (SELECT merit_badge_id, level_id, count(*) AS total
                    FROM merit_badge_tasks
                    GROUP BY merit_badge_id, level_id) AS t2
                ON t1.merit_badge_id = t2.merit_badge_id AND t1.level_id = t2.level_id
                INNER JOIN
                merit_badges AS mb ON t1.merit_badge_id = mb.id
                WHERE t1.finished < t2.total
                ORDER BY t1.merit_badge_id, t1.level_id;'
        );
        $this->database->execute();
        $result = $this->database->getResult();
        if ($result && $result->num_rows > 0){
            while ($row = $result->fetch_assoc()){
                $array[] = $row;
            }
        }
        return $array;
    }

    public function getAllUsersUnverifiedMeritBadge($user_id, $is_leader = false): array
    {
        $position = 't1.position_id < 4 AND t1.position_id > 1';
        if ($is_leader){
            $position = 'position_id > 3';
        }

        $this->database->setSql('
            WITH ct AS (
                SELECT
                    task_id
                FROM
                    completed_tasks AS c
                WHERE
                    c.user_id = '.$user_id.' AND
                    c.verified = 0
            ),
            t AS (
            SELECT
                    t1.id AS task_id
                FROM
                    tasks AS t1
                INNER JOIN
                    ct ON t1.id = ct.task_id
                WHERE
                    '.$position.'
            ),
            mbt AS (
            SELECT
                    mbt1.merit_badge_id,
                    lmb.image AS level_image
                FROM
                    merit_badge_tasks AS mbt1
                INNER JOIN
                    t on t.task_id = mbt1.task_id
                INNER JOIN
                    levels_of_merit_badge AS lmb ON lmb.id = mbt1.level_id
            )
            SELECT
                mb.*,
                mbt.level_image
            FROM
                merit_badges as mb
            INNER JOIN
                mbt ON mb.id = mbt.merit_badge_id
            GROUP BY
                mb.id
            ORDER BY
                mb.name;
        ');
        $this->database->execute();
        $result = $this->database->getResult();
        $array = array();
        if ($result && $result->num_rows > 0){
            while ($row = $result->fetch_assoc()){
                $array[] = $row;
            }
        }
        return $array;
    }

    /**
     * Returns array with all tasks from user from scout path that are in progress
     *
     * @param $user_id
     * @return array
     * @throws Exception
     */
    public function getAllTasksInProgressFromScoutPath($user_id): array
    {
        $array = array();
        $this->database->setSql(
            'WITH print_all_paths_table  AS (SELECT
                                                t1.scout_path_id,
                                                t1.area_id,
                                                t1.name,
                                                t1.image,
                                                t1.color,
                                                rp.type_of_points,
                                                rp.icon,
                                                rp.name as alt,
                                                CASE
                                                    WHEN t2.finished IS NULL THEN 0
                                                    ELSE t2.finished
                                                    END AS finished,
                                                t1.total
                                            FROM
                                                (SELECT
                                                    sp.id as scout_path_id,
                                                    CASE
                                                        WHEN rp.area_id IS NULL THEN 1
                                                        ELSE rp.area_id
                                                        END AS area_id,
                                                    COALESCE(sp.required_points, rp.required_points) AS total,
                                                    sp.name,
                                                    sp.image,
                                                    sp.color
                                                FROM scout_path AS sp
                                                LEFT JOIN required_points AS rp ON sp.required_points IS NULL
                                                WHERE sp.id = rp.scout_path_id OR rp.scout_path_id IS NULL) AS t1
                                            LEFT JOIN
                                                (SELECT csp.scout_path_id, csp.area_id, sum(ct.points) as `finished`
                                                FROM completed_tasks AS ct
                                                INNER JOIN scout_path_tasks AS spt ON ct.task_id = spt.task_id AND ct.user_id = '.$user_id.' AND ct.verified = 1
                                                INNER JOIN chapters_of_scout_path AS csp ON csp.id = spt.chapter_id
                                                INNER JOIN scout_path AS sp ON sp.id = csp.scout_path_id
                                                GROUP BY csp.scout_path_id,
                                                    CASE
                                                        WHEN sp.required_points IS NULL THEN csp.area_id
                                                        ELSE NULL
                                                    END) AS t2
                                            ON t1.scout_path_id = t2.scout_path_id AND t1.area_id = t2.area_id
                                            INNER JOIN required_points AS rp ON rp.scout_path_id = t1.scout_path_id AND rp.area_id = t1.area_id
                                            ORDER BY t1.scout_path_id, t1.area_id),
            help_table_1 AS (
                            SELECT scout_path_id
                            FROM print_all_paths_table
                            WHERE finished < total
                            GROUP BY scout_path_id),
            help_table_2 AS (
                            SELECT scout_path_id
                            FROM print_all_paths_table
                            WHERE finished > 0
                            GROUP BY scout_path_id),
            help_table_3 AS (
                            SELECT h1.scout_path_id
                            FROM help_table_1 AS h1
                            INNER JOIN help_table_2 AS h2 ON h1.scout_path_id = h2.scout_path_id)
            SELECT
                papt.scout_path_id,
                papt.area_id,
                papt.name,
                papt.image,
                papt.color,
                papt.type_of_points,
                papt.icon,
                papt.name as alt,
                papt.finished,
                papt.total
            FROM print_all_paths_table AS papt
            INNER JOIN help_table_3 AS h3 ON papt.scout_path_id = h3.scout_path_id
            ORDER BY papt.scout_path_id, papt.area_id;'
        );
        $this->database->execute();
        $result = $this->database->getResult();

        if ($result && $result->num_rows > 0){
            while ($row = $result->fetch_assoc()){
                $array[] = $row;
            }
        }
        return $array;
    }

    public function getAllUsersUnverifiedScoutPath($user_id, $is_leader = false): array
    {
        $position = 't1.position_id < 4 AND t1.position_id > 1';
        if ($is_leader){
            $position = 'position_id > 3';
        }

        $this->database->setSql('
            WITH ct AS (
                SELECT
                    task_id
                FROM
                    completed_tasks AS c
                WHERE
                    c.user_id = '.$user_id.' AND
                    c.verified = 0
            ),
            t AS (
                SELECT
                    t1.id AS task_id
                FROM
                    tasks AS t1
                INNER JOIN
                    ct ON t1.id = ct.task_id
                WHERE
                    '.$position.'
            ),
            spt AS (
                SELECT
                    spt1.chapter_id
                FROM
                    scout_path_tasks AS spt1
                INNER JOIN
                    t ON t.task_id = spt1.task_id
            ),
            csp AS (
                SELECT
                    csp1.scout_path_id
                FROM
                    chapters_of_scout_path AS csp1
                INNER JOIN
                    spt ON spt.chapter_id = csp1.id
            )
            SELECT
                *
            FROM
                scout_path AS sp
            INNER JOIN
                csp ON csp.scout_path_id = sp.id
            GROUP BY
                sp.id
            ORDER BY
                sp.name;
        ');
        $this->database->execute();
        $result = $this->database->getResult();
        $array = array();
        if ($result && $result->num_rows > 0){
            while ($row = $result->fetch_assoc()){
                $array[] = $row;
            }
        }
        return $array;
    }

    /**
     * Adds task to completed tasks
     *
     * @param $task_id
     * @param $user_id
     * @param $points
     * @param $verified
     * @return bool
     * @throws Exception
     */
    public function addTask($task_id, $user_id, $points, $verified): bool
    {
        if ($points == null){
            $this->database->setSql("INSERT INTO completed_tasks (task_id, user_id, points, verified) VALUES ('$task_id', '$user_id', null, '$verified')");
        }
        else{
            $this->database->setSql("INSERT INTO completed_tasks (task_id, user_id, points, verified) VALUES ('$task_id', '$user_id', '$points', '$verified')");
        }
        $this->database->execute();
        if ($this->database->getResult()){
            return true;
        }
        return false;
    }

    /**
     * Deletes task from completed tasks
     *
     * @param $task_id
     * @param $user_id
     * @return bool
     * @throws Exception
     */
    public function deleteTask($task_id, $user_id): bool
    {
        $this->database->setSql("DELETE FROM completed_tasks WHERE task_id = '$task_id' AND user_id = '$user_id'");
        $this->database->execute();
        if ($this->database->getResult()){
            return true;
        }
        return false;
    }

    /**
     * Updates task in completed tasks
     *
     * @param $task_id
     * @param $user_id
     * @param $row
     * @param $value
     * @return bool
     * @throws Exception
     */
    public function updateTask($task_id, $user_id, $row, $value): bool
    {
        $this->database->setSql("
                UPDATE completed_tasks 
                SET $row = $value
                WHERE 
                    task_id = $task_id AND 
                    user_id = $user_id
        ");
        $this->database->execute();
        if ($this->database->getResult()){
            return true;
        }
        return false;
    }
}