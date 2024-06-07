<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion";

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur PDO sur Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifiez si l'ID du projet est envoyé
    if (!empty($_POST['id'])) {
        $projet_id = $_POST['id'];

        // Préparez la requête SQL pour supprimer le projet correspondant
        $sql = "DELETE FROM projet WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        // Liez les valeurs des paramètres
        $stmt->bindParam(':id', $projet_id, PDO::PARAM_INT);
        // Exécutez la requête
        if ($stmt->execute()) {
            echo 'success'; // Renvoyer 'success' si la suppression réussit
        } else {
            echo 'error'; // Renvoyer 'error' si la suppression échoue
        }
    } else {
        // Retournez une erreur si l'ID du projet n'est pas envoyé
        echo 'error';
    }
} catch (PDOException $e) {
    // Gérez les erreurs PDO
    echo 'Erreur : ' . $e->getMessage();
}
?>
