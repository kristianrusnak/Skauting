<?php
include '../scripts/connector.php';
$cookies->KickIfCookiesNotSet();
$body->printMainHeader( "domov");
include 'menu.php';
?>

    <div id="tasksContainer1" >
        <h1>Kontrola úloh</h1>
        <?php



        ?>
    </div>

<?php
$body->printFooter();
?>