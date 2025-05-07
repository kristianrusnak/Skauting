<?php

namespace HtmlBuilder;

class HtmlBody
{
    public static function printMainHeader(string $title): void
    {
        echo '
            <!DOCTYPE html>
            <lang="sk">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Skauting-'.$title.'</title>
                <link rel="stylesheet" href="../styles/menuStyle.css">
                <link rel="stylesheet" href="../styles/tasksContainerStyle.css">
                <link rel="stylesheet" href="../styles/mainStyle.css">
                <link rel="stylesheet" href="../styles/userStyle.css">
                <link rel="stylesheet" href="../styles/tasksListerStyle.css">
                <link rel="stylesheet" href="../styles/groupContainerStyle.css">
                <link rel="stylesheet" href="../styles/differentTasksStyle.css">
                <link rel="stylesheet" href="../styles/taskApprovalStyle.css">
            </head>
            <body>
                <div id="tasksContainer1" >
        ';
    }

    public static function printLogInHeader(string $title = 'Skauting-login'): void
    {
        echo '
            <!DOCTYPE html>
            <lang="sk">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>'.$title.'</title>
                <link rel="stylesheet" href="../styles/loginStyle.css">
            </head>
            <body>
        ';
    }

    public static function printFooter(): void
    {
        echo '
            </div>
        <script src="../scripts/TasksLister.js"></script>
        </body>
        </html>
        ';
    }

    public static function printMenu(): void
    {
        echo '
            <div id="menuContainer">
                <div id="menuContainerLogo">
                    <img class="menuContainerLogoImg" src="../images/skauting_logo.png" alt="Skauting logo">
                </div>
                <div id="menuContainerLinks">
                    <a class="menuContainerLink" href="home.php">
                        <img class="menuContainerLinkImg" src="../images/home.png" alt="Domov">
                        <span class="menuContainerLinkSpan">Domov</span>
                    </a>
                    <a class="menuContainerLink" href="meritBadges.php">
                        <img class="menuContainerLinkImg" src="../images/merit_badge.png" alt="Odborky">
                        <span class="menuContainerLinkSpan">Odborky</span>
                    </a>
                    <a class="menuContainerLink" href="../pages/scoutPath.php">
                        <img class="menuContainerLinkImg" src="../images/path.png" alt="Domov">
                        <span class="menuContainerLinkSpan">Skautský chodník</span>
                    </a>
        ';

        if($_SESSION['position_id'] == 2){
            echo '
                    <a href="groups.php" class="menuContainerLink">
                        <img class="menuContainerLinkImg" src="../images/druzina.png" alt="Moje Deti">
                        <span class="menuContainerLinkSpan">Moje Deti</span>
                    </a>
            ';
        }

        else if($_SESSION['position_id'] >= 3){
            echo '
                    <a href="groups.php" class="menuContainerLink">
                        <img class="menuContainerLinkImg" src="../images/druzina.png" alt="Druzina">
                        <span class="menuContainerLinkSpan">Družina</span>
                    </a>'
            ;
        }

        else if($_SESSION['position_id'] >= 3){
            echo '
                    <a href="groups.php" class="menuContainerLink">
                        <img class="menuContainerLinkImg" src="../images/druzina.png" alt="Druzina">
                        <span class="menuContainerLinkSpan">Správa</span>
                    </a>'
            ;
        }

        echo    '
                    <a href="user.php" class="menuContainerLink" id="userLink">
                        <img class="menuContainerLinkImg" src="../images/user.png" alt="User">
                        <span class="menuContainerLinkSpan">'.$_SESSION['name'].'</span>
                    </a>
                </div>
                <div id="menuContainerUser">
                    <a href="user.php">
                        <img src="../images/user.png" alt="User">
                        <span>'.$_SESSION['name'].'</span>
                    </a>
                </div>
            </div>
';
    }
}

?>