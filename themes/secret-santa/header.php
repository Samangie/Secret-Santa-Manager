<?php if(!isset($_SESSION['loggedin']) && empty($_SESSION['loggedin'])) {
    echo "<a href='/Access/'>Login/ Registrieren</a><br/>";
}
?>
<a href='/Campaign/'>Campaign</a>