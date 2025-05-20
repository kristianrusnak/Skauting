<?php

namespace HtmlBuilder;

class DifferentTasksManager
{
    public static function alertHeader(): void
    {
        if ($_SESSION['view_users_task_id'] != $_SESSION['user_id'])
        {
            echo '
                <div id="differentTaskViewContainer">
                    <form method="post">
                        <span>Vidíš úlohy používateľa: '.$_SESSION['view_users_name'].'</span>
                        <input type="submit" value="koniec" name="endDifferentTaskView">
                    </form>
                </div>
            ';
        }
    }

    public static function setDifferentTaskView(int $user_id, string $name): void
    {
        $_SESSION['view_users_name'] = $name;
        $_SESSION['view_users_task_id'] = $user_id;
    }

    public static function endDifferentTaskView(): void
    {
        $_SESSION['view_users_task_id'] = $_SESSION['user_id'];
        $_SESSION['view_users_name'] = $_SESSION['name'];
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}