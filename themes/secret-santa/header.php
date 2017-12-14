<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Santa Manager</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav id="navigation">
    <ul>
        <li>
            <?php if(!isset($_SESSION['loggedin']) && empty($_SESSION['loggedin'])) {
                echo "<a href='/Access/'>Login/ Registrieren</a><br/>";
            }
            ?>
        </li>
        <li>
            <a href='/Campaign/'>Campaign</a>
        </li>
    </ul>
</nav>
