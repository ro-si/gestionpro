<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'gestion';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification si l'ID du projet est passé en tant que paramètre
    if (isset($_POST['id'])) {
        $projet_id = $_POST['id'];

        // Préparation de la requête SQL pour récupérer les détails du projet
        $stmt_select = $db->prepare("SELECT id, titre_projet, categorie_projet, contexte_projet, lien_demo, texte_supplementaire, photo_projet
                                     FROM projet
                                     WHERE id = :id");
        $stmt_select->bindParam(':id', $projet_id, PDO::PARAM_INT);
        $stmt_select->execute();

        // Récupération des détails du projet
        $projet = $stmt_select->fetch(PDO::FETCH_ASSOC);

        // Vérification si le projet a été trouvé
        if ($projet) {
            // Vérification si le fichier de la photo existe
            if (file_exists($projet['photo_projet'])) {
                // Conversion de l'image en base64
                $image_data = file_get_contents($projet['photo_projet']);
                $base64_image = base64_encode($image_data);
            } else {
                $base64_image = null;
            }

            // Construction de la réponse JSON
            $response = array(
                'success' => true,
                'id' => $projet['id'],
                'titre_projet' => $projet['titre_projet'],
                'categorie_projet' => $projet['categorie_projet'],
                'contexte_projet' => $projet['contexte_projet'],
                'lien_demo' => $projet['lien_demo'],
                'texte_supplementaire' => $projet['texte_supplementaire'],
                'photo_projet' => $base64_image // Image en base64
            );
        } else {
            // Projet non trouvé
            $response = array('success' => false, 'message' => 'Projet non trouvé');
        }
    } else {
        // ID de projet manquant
        $response = array('success' => false, 'message' => 'ID de projet manquant');
    }
} catch (PDOException $e) {
    // Erreur de connexion à la base de données
    $response = array('success' => false, 'message' => 'Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Envoi de la réponse JSON
header('Content-Type: application/json');
echo json_encode($response);
