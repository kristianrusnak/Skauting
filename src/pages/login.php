<?php
include '../scripts/headerScript.php';
getLoginHeader();
include '../scripts/loginHandleScript.php';
?>
<form id="loginForm" method="post">
    <img class="loginFormLogo" src="../images/skauting_logo.png" alt="logo">
    <label class="loginFormTextInput" for="username">
        <input type="text" name="email" placeholder="email">
    </label>
    <label class="loginFormTextInput" for="password">
        <input type="password" name="password" placeholder="password">
    </label>
    <label class="loginFormCheckBox" for="remember">
        <input type="checkbox" id="remember" name="remember">
        <span>Zapametaj si ma</span>
    </label>
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
<?php include 'end.php'; ?>