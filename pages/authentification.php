<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs saisies dans le formulaire
    $nom_admin = $_POST["nom_admin"];
    $mot_de_passe_admin = $_POST["mot_de_passe_admin"];

    // Définir les informations de connexion à la base de données
    $servername = "localhost"; // Ou l'adresse de votre serveur MySQL
    $username = "root"; // Nom d'utilisateur MySQL
    $password = ""; // Mot de passe MySQL
    $dbname = "gestion"; // Nom de la base de données

    try {
        // Créer une connexion PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Définir le mode d'erreur PDO sur exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête SQL pour récupérer l'administrateur
        $sql = "SELECT * FROM admin WHERE nom = :nom AND mot_de_passe = :mot_de_passe";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nom_admin, PDO::PARAM_STR);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe_admin, PDO::PARAM_STR); // Pas de hashage ici
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'administrateur existe
        if ($admin) {
            // Authentification réussie
            echo "Authentification réussie. Bienvenue, " . $admin['nom'] . "!";
            // Rediriger l'administrateur vers la page d'accueil de l'administration, par exemple
            header("Location: admin.php");
            // exit; // Arrêter l'exécution du script pour éviter toute sortie indésirable
        } else {
            // Authentification échouée
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        // En cas d'erreur de connexion à la base de données
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }

    // Fermer la connexion à la base de données
    $conn = null;
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="form-container">
                    <h2 class="text-center mb-4">Connexion Administrateur</h2>
                    <form action="authentification.php" method="POST">
                        <div class="form-group">
                            <label for="nom_admin">Nom d'utilisateur :</label>
                            <input type="text" class="form-control" id="nom_admin" name="nom_admin" required>
                        </div>
                        <div class="form-group">
                            <label for="mot_de_passe_admin">Mot de passe :</label>
                            <input type="password" class="form-control" id="mot_de_passe_admin" name="mot_de_passe_admin" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="Se connecter">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>