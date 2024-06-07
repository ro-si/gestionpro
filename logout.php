<?php
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Si vous voulez détruire complètement la session, effacez également le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Finalement, détruisez la session.
session_destroy();

// Redirection vers la page de connexion
header("Location: connexion.php");
exit;
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déconnexion</title>
</head>

<body>
    <h1>Bienvenue sur notre site!</h1>
    <form action="logout.php" method="post">
        <button type="submit">Déconnexion</button>
    </form>
</body>

</html>