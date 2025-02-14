<?php

class DifferentTasksManager
{
    private CookieManager $cookies;

    private string $user_name = "";

    function __construct($cookies)
    {
        $this->cookies = $cookies;
    }

    public function alertHeader(): void
    {
        if ($this->cookies->doISeeDifferentTaskThanMine())
        {
            echo '
                <div id="differentTaskViewContainer">
                    <form>
                        <span>Vydíš úlohy používateľa: '.$this->user_name.'</span>
                        <input type="submit" value="koniec" name="endDifferentTaskView">
                    </form>
                </div>
            ';
        }
    }

    public function setDifferentTaskView($user_id, $name): void
    {
        $this->user_name = $name;
        $this->cookies->setToDifferentTasks($user_id);
        header('Location: ../pages/home.php');
        exit;
    }

    public function endDifferentTaskView(): void
    {
        $this->cookies->setToMyOwnTasks();
        $this->user_name = "";
        header('Location: ../pages/home.php');
        exit;
    }
}