<?php
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
        </a>';

if($_COOKIE['position_id'] == 2){
    echo '<a href="groups.php" class="menuContainerLink">
            <img class="menuContainerLinkImg" src="../images/druzina.png" alt="Moje Deti">
            <span class="menuContainerLinkSpan">Moje Deti</span>
        </a>';
}
else if($_COOKIE['position_id'] == 3){
    echo '<a href="groups.php" class="menuContainerLink">
            <img class="menuContainerLinkImg" src="../images/druzina.png" alt="Druzina">
            <span class="menuContainerLinkSpan">Družina</span>
        </a>';
}
else if($_COOKIE['position_id'] >= 4){
    echo '<div id="menuContainerLinkAdditional4" class="menuContainerLink">
            <img class="menuContainerLinkImg" src="../images/druzina.png" alt="Druziny">
            <span class="menuContainerLinkSpan">Správa</span>
            <div id="additionalMenu4">
                <a href="groups.php" class="additionalMenu4Linker">
                    <img class="menuContainerLinkImg" src="../images/group_check.png" alt="Kontrola úloh">
                    <span class="menuContainerLinkSpan">Kontrola úloh</span>
                </a>
                <a class="additionalMenu4Linker">
                    <img class="menuContainerLinkImg" src="../images/group_manage.png" alt="Správca družín">
                    <span class="menuContainerLinkSpan">Správca družín</span>
                </a>
            </div>
        </div>';
}

echo    '<a class="menuContainerLink">
            <img class="menuContainerLinkImg" src="../images/search.png" alt="Hladaj">
            <span class="menuContainerLinkSpan">Hľadaj</span>
        </a>
        <a href="user.php" class="menuContainerLink" id="userLink">
            <img class="menuContainerLinkImg" src="../images/user.png" alt="User">
            <span class="menuContainerLinkSpan">'.$_COOKIE['name'].'</span>
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