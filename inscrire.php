<?php
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
        $nom_prenom = $_POST['nom_prenom'];
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        // Requête SQL pour insérer les données dans la table "user"
        $sql = "INSERT INTO utilisateur (nom_prenom, email, mot_de_passe) VALUES (:nom_prenom, :email, :mot_de_passe)";
        $stmt = $db->prepare($sql);

        // Liaison des valeurs avec les paramètres de la requête
        $stmt->bindParam(':nom_prenom', $nom_prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe);

        // Exécution de la requête
        if ($stmt->execute()) {
            // Redirection vers la page de connexion après inscription réussie
            header("Location: connexion.php");
        } else {
            echo "Erreur lors de l'enregistrement.";
        }
    }
} catch (PDOException $e) {
    // En cas d'erreur de connexion à la base de données
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
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
                    <h3>INSCRIPTION</h3>
                </div>

                <div class="form">
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" name="nom_prenom" id="nom_prenom" placeholder="Nom prenom" required>
                        </div>

                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Veuillez entrer votre email" required>
                        </div>
                        <div class="form-group">
                            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder=" mot de passe" required>
                        </div>


                        <input class="button" type="submit" value="S'inscrire">

                    </form>
                    <p>Vous avez un compte?<a href="connexion.php">Connectez vous!</a></p>
                </div>
            </div>
        </div>
    </main>
</body>

</html>