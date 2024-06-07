<?php // Informations de connexion à la base de données
$host = 'localhost';
$dbname = 'gestion';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
function get_all_users($conn)
{
    $sql = "SELECT nom_prenom FROM utilisateur";
    $result = $conn->query($sql);
    $users = [];

    // Récupérer les données des utilisateurs
    $sql = "SELECT * FROM utilisateur";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        // Traiter les données récupérées
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row;
        }
    } else {
        echo "Aucun résultat trouvé.";
    }

    return $users;
}
// Assurez-vous que la fonction get_all_users() est correctement définie et renvoie les utilisateurs
$users = get_all_users($conn);

// Vérifiez la méthode de la requête
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifiez si les données attendues existent dans $_POST avant de les récupérer
    if (isset($_POST["nom_prenom"]) && isset($_POST["travail"])) {
        $nom_prenom = $_POST["nom_prenom"];
        $travail = $_POST["travail"];

        // Assurez-vous que la fonction envoyer_travail() est correctement définie
        // et qu'elle accepte les paramètres nécessaires
        envoyer_travail($conn, $nom_prenom, $travail);
    } else {
        // Gérez le cas où les données attendues ne sont pas présentes dans $_POST
        echo "Les données envoyées sont incomplètes.";
    }
}




function get_user_tasks($conn, $nom_prenom)
{
    $sql = "SELECT t.travail
FROM utilisateur u
JOIN travaux t ON u.id = t.utilisateur_id
WHERE u.nom_prenom = :nom_prenom";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nom_prenom', $nom_prenom);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return $tasks;
}
function envoyer_travail($conn, $nom_prenom, $travail)
{
    $sql = "SELECT id FROM utilisateur WHERE nom_prenom = :nom_prenom";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nom_prenom', $nom_prenom);
    $stmt->execute();
    $utilisateur_id = $stmt->fetchColumn();

    if ($utilisateur_id) {
        $sql = "INSERT INTO travaux (utilisateur_id, travail) VALUES (:utilisateur_id, :travail)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
        $stmt->bindParam(':travail', $travail, PDO::PARAM_STR);
        $stmt->execute();
        // echo "Le travail '$travail' a été envoyé à $nom_prenom.";
    } else {
        echo "L'utilisateur $nom_prenom n'est pas inscrit.";
    }
}

function supprimer_utilisateur($conn, $id_utilisateur)
{
    try {
        $sql = "DELETE FROM utilisateur WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id_utilisateur, PDO::PARAM_INT);
        $stmt->execute();
        echo "L'utilisateur a été supprimé avec succès.";
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
    }
}

?>



<!DOCTYPE html>
<html>

<head>
    <title>Espace Administrateur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Traitement des données soumises ici
        // ...
    }
    ?>

    <div class="container">
        <h1 class="mt-4 text-center">Espace Administrateur</h1>
        <h2 class="mt-4">Liste des utilisateurs</h2>
        <?php if (!empty($users)) : ?>
            <ul class="list-group">
                <?php foreach ($users as $user) : ?>
                    <li class="list-group-item">
                        <?php echo $user['nom_prenom']; ?>
                        
                        <form method="post" action="supprimer_utilisateur.php">
                            <input type="hidden" name="id_utilisateur" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php endif; ?>



        <!-- Formulaire pour envoyer un travail à un utilisateur -->
        <h2 class="mt-4">Envoyer un travail</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="nom_prenom">Nom de l'utilisateur :</label>
                <input type="text" class="form-control" id="nom_prenom" name="nom_prenom" required>
            </div>
            <div class="form-group">
                <label for="travail">Travail :</label>
                <input type="text" class="form-control" id="travail" name="travail" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Envoyer</button>
        </form>

        <!-- Formulaire pour accéder à l'espace d'un utilisateur -->
        <h2 class="mt-4">Accéder à l'espace d'un utilisateur</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="nom_prenom">Nom de l'utilisateur :</label>
                <input type="text" class="form-control" id="nom_prenom" name="nom_prenom" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Afficher les travaux</button>
        </form>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom_prenom = $_POST["nom_prenom"];
            $tasks = get_user_tasks($conn, $nom_prenom);
        } ?>

        <?php if (isset($tasks) && !empty($tasks)) : ?>
            <h3 class="mt-4">Travaux de <?php echo $nom_prenom; ?> :</h3>
            <ul class="list-group">
                <?php foreach ($tasks as $task) : ?>
                    <li class="list-group-item"><?php echo $task; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($tasks) && empty($tasks)) : ?>
            <p><?php echo $nom_prenom; ?> n'a aucun travail pour le moment.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>