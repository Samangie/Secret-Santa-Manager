<form action="/Access/login" method="post">

    <input type="text" name="username" placeholder="Benutzername" />
    <input type="password" name="password" placeholder="Passwort" />
    <button type="submit" name="login">Button</button>
</form>

<?php if(isset($_SESSION['userNotExists']) && !empty($_SESSION['userNotExists'])) { echo $_SESSION['userNotExists'];}; ?>


<form action="/Access/register" method="post">

    <input type="text" name="username" placeholder="Benutzername" />
    <input type="text" name="email" placeholder="Email" />
    <input type="password" name="password" placeholder="Passwort" />
    <input type="password" name="reppassword" placeholder="Passwort wiederholen" />
    <button type="submit" name="register">Button</button>
</form>
