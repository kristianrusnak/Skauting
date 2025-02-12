<?php
include '../scripts/connector.php';
$body->printLogInHeader("registrácia");
include '../scripts/registrationHandleScript.php';
?>

    <form id="loginForm" method="post">
        <img class="loginFormLogo" src="../images/skauting_logo.png" alt="logo">
        <label class="loginFormTextInput" for="username">
            <input type="text" name="name" placeholder="krstné meno" required>
        </label>
        <label class="loginFormTextInput" for="username">
            <input type="text" name="email" placeholder="email" required>
        </label>
        <label class="loginFormTextInput" for="password">
            <input type="password" name="password1" placeholder="heslo" required>
        </label>
        <label class="loginFormTextInput" for="password">
            <input type="password" name="password2" placeholder="zopakuj heslo" required>
        </label>
        <span>Už si zaregistrovaný? <a href="login.php">prihlás sa!</a></span>

        <?php
        if (false){
            ?>

            <span class="loginFormError" id="errorWindow1">
            <img class="loginErrorImage" src="../images/error.png" alt="error">
            Zadané heslá sa nezhodujú
            </span>

            <?php
        }
        ?>

        <input class="loginFormSubmitButton" name="submit" type="submit" value="Zaregistruj sa">
    </form>

<?php
$body->printFooter();
?>