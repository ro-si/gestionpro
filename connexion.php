<?php
session_start(); // Démarre la session

// Informations de connexion à la base de données
$host = 'localhost'; // Remplacez localhost par l'adresse de votre serveur MySQL si nécessaire
$dbname = 'gestion'; // Nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$password = ''; // Remplacez par votre mot de passe MySQL

try {
    // Création d'une instance de PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Configuration pour générer des exceptions en cas d'erreur
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des valeurs du formulaire
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        // Requête SQL pour vérifier l'authentification de l'utilisateur
        $sql = "SELECT * FROM utilisateur WHERE email = :email AND mot_de_passe = :mot_de_passe";
        $stmt = $db->prepare($sql);

        // Liaison des valeurs avec les paramètres de la requête
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe);

        // Exécution de la requête
        $stmt->execute();

        // Vérification du nombre de lignes retournées
        $row_count = $stmt->rowCount();

        if ($row_count == 1) {
            // Récupération des informations de l'utilisateur
            $utilisateur = $stmt->fetch();

            // Stocke les informations de l'utilisateur dans la session
            $_SESSION['utilisateur'] = $utilisateur;

            // Authentification réussie, rediriger vers la page d'accueil par exemple
            header("Location: dashboard.php");
            exit();
        } else {
            // Authentification échouée, afficher un message d'erreur par exemple
            echo '<div class="error-message">Email ou mot de passe incorrect.</div>';
        }
    }
} catch (PDOException $e) {
    // En cas d'erreur de connexion à la base de données
    echo '<div class="error-message">Erreur de connexion à la base de données : ' . $e->getMessage() . '</div>';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style1.css">


</head>

<body>


    <main>
        <div class="container">
            <img src="images/Forms-amico 1.png" class="img" height="250" alt="">

            <div class="nain">
                <div class="hh">
                    <h3>CONNEXION</h3>
                </div>
                <div class="pi">
                    <p>Veuillez entrer vos identifications et mots de passe</p>
                </div>

                <div class="form">
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Veuillez entrer votre email">
                        </div>
                        <div class="form-group">
                            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Votre mot de passe">
                        </div>
                        <input class="button" type="submit" value="Se connecter">
                    </form>

                    <p>Pas de compte?<a href="inscrire.php" class="mot-de-passe-oublie">Inscrivez vous!</a></p>
                    <div id="e"></div>
                </div>
            </div>
        </div>
    </main>


    <script>

    </script>
</body>

</html>