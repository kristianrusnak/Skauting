<?php
echo '
<div id="menuContainer">
    <div id="menuContainerLogo">
        <img src="../images/skauting_logo.png" alt="Skauting logo">
    </div>
    <div id="menuContainerLinks">
        <a class="menuContainerLink" href="home.php">
            <img src="../images/home.png" alt="Domov">
            <span>Domov</span>
        </a>
        <a class="menuContainerLink" href="meritBadges.php">
            <img src="../images/merit_badge.png" alt="Odborky">
            <span>Odborky</span>
        </a>
        <a class="menuContainerLink" href="../pages/scoutPath.php">
            <img src="../images/path.png" alt="Domov">
            <span>Skautský chodník</span>
        </a>';

if($_COOKIE['position_id'] == 3){
    echo '<a class="menuContainerLink">
            <img src="../images/druzina.png" alt="Druzina">
            <span>Družina</span>
        </a>';
}
else if($_COOKIE['position_id'] == 4){
    echo '<a class="menuContainerLink">
            <img src="../images/druzina.png" alt="Druzina">
            <span>Družiny</span>
        </a>';
}

echo    '<a class="menuContainerLink">
            <img src="../images/search.png" alt="Hladaj">
            <span>Hľadaj</span>
        </a>
        <a href="user.php" class="menuContainerLink" id="userLink">
            <img src="../images/user.png" alt="User">
            <span>'.$_COOKIE['name'].'</span>
        </a>
    </div>
    <div id="menuContainerUser">
        <a href="user.php">
            <img src="../images/user.png" alt="User">
            <span>'.$_COOKIE['name'].'</span>
        </a>
    </div>
</div>
';
?>