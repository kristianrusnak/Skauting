<?php
date_default_timezone_set('Europe/Bratislava');
include('db.php');
include('sanitizeScript.php');
include('tasksContainerScript.php');
include('tasksListerScript.php');
include('groupApprovalListerScript.php');

function verifyUserAndSetCookies($mysqli, $email, $password){
    if (!$mysqli->connect_errno) {
        $sql = "SELECT * FROM `users` WHERE email = '$email'";
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];
            if (password_verify($password, $stored_password)) {
                setcookie("user_id", $row['id'], time() + (86400 * 30), "/");
                setcookie("name", $row['name'], time() + (86400 * 30), "/");
                setcookie("position_id", $row['position_id'], time() + (86400 * 30), "/");
                return true;
            }
        }
    }
    return false;
}

function check_get_for_merit_badge($mysqli, $id){
    $id = sanitizeInput($id);
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM merit_badges WHERE id = ".$id;
        try{
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
                return true;
            }
        }
        catch (Exception $e){
            return false;
        }
    }
    return false;
}

function get_name_of_merit_badge($mysqli, $id){
    $id = sanitizeInput($id);
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM merit_badges WHERE id = ".$id;
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            $row = $result->fetch_assoc();
            return $row['name'];
        }
    }
    return '';
}

function check_get_for_scout_path($mysqli, $id){
    $id = sanitizeInput($id);
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM scout_path WHERE id = ".$id;
        try{
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
                return true;
            }
        }
        catch (Exception $e){
            return false;
        }
    }
    return false;
}

function get_name_of_scout_path($mysqli, $id){
    $id = sanitizeInput($id);
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM scout_path WHERE id = ".$id;
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            $row = $result->fetch_assoc();
            return $row['name'];
        }
    }
    return '';
}

function get_points_for_task($mysqli, $task_id){
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM scout_path_tasks WHERE task_id = ".$task_id;
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            $row = $result->fetch_assoc();
            return $row['points'];
        }
    }
    return null;
}

function get_position_from_task($mysqli, $task_id){
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM tasks WHERE id = ".$task_id;
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            $row = $result->fetch_assoc();
            return $row['position_id'];
        }
    }
}

function is_task_merit_badge($mysqli, $task_id){
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM merit_badge_tasks WHERE task_id = ".$task_id;
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            return true;
        }
    }
    return false;
}

function is_task_scout_path($mysqli, $task_id){
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM scout_path_tasks WHERE task_id = ".$task_id;
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            return true;
        }
    }
    return false;
}

function is_task_verified($mysqli, $task_id){
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM complited_tasks WHERE task_id = ".$task_id." AND user_id = ".$_COOKIE['user_id']." AND verified = 1";
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            return true;
        }
    }
    return false;
}

function get_sql_for_gained_points_for_scout_path(){
    return 'SELECT csp.scout_path_id, csp.area_id, sum(ct.points) as `finished`
                    FROM complited_tasks AS ct
                    INNER JOIN scout_path_tasks AS spt ON ct.task_id = spt.task_id AND ct.user_id = '.$_COOKIE['user_id'].' AND ct.verified = 1
                    INNER JOIN chapters_of_scout_path AS csp ON csp.id = spt.chapter_id
                    INNER JOIN scout_path AS sp ON sp.id = csp.scout_path_id
                    GROUP BY csp.scout_path_id,
                        CASE
                            WHEN sp.required_points IS NULL THEN csp.area_id
                            ELSE NULL
                        END';
}

function get_sql_for_required_points_for_scout_path(){
    return "SELECT
                sp.id as scout_path_id,
                CASE
                    WHEN rp.area_id IS NULL THEN 1
                    ELSE rp.area_id
                END AS area_id,
                COALESCE(sp.required_points, rp.required_points) AS total
            FROM scout_path AS sp
            LEFT JOIN required_points AS rp ON sp.required_points IS NULL
            WHERE sp.id = rp.scout_path_id OR rp.scout_path_id IS NULL";
}

function get_full_sql_for_scout_path(){
    return 'SELECT 
                    t1.scout_path_id,
                    t1.area_id,
                    t1.name,
                    t1.image,
                    t1.color,
                    rp.type_of_points,
                    rp.icon
                    CASE
                    	WHEN t2.finished IS NULL THEN 0
                        ELSE t2.finished
                    END AS finished,
                    t1.total
                FROM
                    ('.get_sql_for_required_points_for_scout_path().') AS t1
                LEFT JOIN
                    ('.get_sql_for_gained_points_for_scout_path().') AS t2
                ON t1.scout_path_id = t2.scout_path_id AND t1.area_id = t2.area_id
                INNER JOIN required_points AS rp ON rp.scout_path_id = t1.scout_path_id AND rp.area_id = t1.area_id
                ORDER BY t1.scout_path_id, t1.area_id';
}

function entire_sql_scout_path(){
    return 'WITH print_all_paths_table  AS (SELECT
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
                                                FROM complited_tasks AS ct
                                                INNER JOIN scout_path_tasks AS spt ON ct.task_id = spt.task_id AND ct.user_id = 2 AND ct.verified = 1
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
ORDER BY papt.scout_path_id, papt.area_id;';
}

function can_be_task_unchecked($mysqli, $task_id){
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM complited_tasks WHERE task_id = ".$task_id." AND user_id = ".$_COOKIE['user_id']." AND verified = 1";
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            echo '<script>console.log("ok")</script>';
            $sql1 = "SELECT * FROM tasks WHERE id = ".$task_id;
            if (($result1 = $mysqli->query($sql1)) && ($result1->num_rows > 0)) {
                $row = $result1->fetch_assoc();
                echo '<script>console.log('.$_COOKIE['position_id'].')</script>';
                if ((int)$row['position_id'] > (int)$_COOKIE['position_id']){
                    return "disabled";
                }
            }
        }
    }
    return "";
}
?>