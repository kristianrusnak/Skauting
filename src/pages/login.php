<?php

require_once '../scripts/connector.php';
require_once '../scripts/HtmlBuilder/HtmlBody.php';
require_once '../APIs/loginHandleScript.php';

use HtmlBuilder\HtmlBody as Body;

Body::printLogInHeader();
?>

<form id="loginForm" method="post">
    <img class="loginFormLogo" src="../images/skauting_logo.png" alt="logo">
    <label class="loginFormTextInput" for="username">
        <input type="text" name="email" placeholder="email" required>
    </label>
    <label class="loginFormTextInput" for="password">
        <input type="password" name="password" placeholder="heslo" required>
    </label>
    <label class="loginFormCheckBox" for="remember" style="display: none">
        <input type="checkbox" id="remember" name="remember">
        <span>Zapametaj si ma</span>
    </label>
    <a href="registration.php">Zaregistruj sa</a>
    <?php
    if ($error1){
    ?>

    <span class="loginFormError" id="errorWindow1">
        <img class="loginErrorImage" src="../images/error.png" alt="error">
        Zadaný email alebo heslo nie su správne
    </span>

        <?php
    }
        ?>

    <input class="loginFormSubmitButton" name="submit" type="submit" value="Prihlásiť sa">
</form>

<?php
Body::printFooter();
?>