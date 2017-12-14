<form action="/Access/login" method="post">

    <input type="text" name="username" placeholder="Benutzername" />
    <input type="password" name="password" placeholder="Passwort" />
    <button type="submit" name="login">Login</button>
</form>

<?php if(isset($_SESSION['userDoesntExist']) && !empty($_SESSION['userDoesntExist'])) { echo $_SESSION['userDoesntExist'];}; ?>
<?php if(isset($_SESSION['differentPassword']) && !empty($_SESSION['differentPassword'])) { echo $_SESSION['differentPassword'];}; ?>


<form action="/Access/register" method="post">

    <input type="text" name="username" placeholder="Benutzername" />
    <input type="text" name="email" placeholder="Email" />
    <input type="text" name="role" placeholder="Rolle" />
    <input type="password" name="password" placeholder="Passwort" />
    <input type="password" name="reppassword" placeholder="Passwort wiederholen" />
    <button type="submit" name="register">Registrieren</button>
</form>
