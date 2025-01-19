<?php
$mysqli = new mysqli('localhost', 'root', '', 'skaut', null, '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');
if ($mysqli->connect_errno) {
    echo '<p class="chyba">Nepodarilo sa pripojiť!</p>';
//	echo '<p class="chyba">Nepodarilo sa pripojiť! (' . $mysqli->connect_errno . ' - ' . $mysqli->connect_error . ')</p>';
} else {
    $mysqli->query("SET CHARACTER SET 'utf8'");
}
?>