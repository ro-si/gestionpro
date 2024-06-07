<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit();
}

// Récupération des données de l'utilisateur depuis la session
$user_id = $_SESSION['utilisateur']['id'] ?? null;

if (!$user_id) {
    die("Aucun ID utilisateur trouvé dans la session.");
}

// Informations de connexion à la base de données
$host = 'localhost';
$dbname = 'gestion';
$username = 'root';
$password = '';

try {
    // Création d'une instance de PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations de l'utilisateur depuis la base de données
    $sql = "SELECT * FROM utilisateur WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $nom_prenom = $user['nom_prenom'];
        $email = $user['email'];
    } else {
        die("Aucune information d'utilisateur trouvée.");
    }

    // Vérifier si le formulaire de modification a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Valider les données entrées par l'utilisateur
        if (!empty($_POST['nouveau_nom_prenom']) && !empty($_POST['nouvelle_email'])) {
            $nouveau_nom_prenom = $_POST['nouveau_nom_prenom'];
            $nouvelle_email = $_POST['nouvelle_email'];

            // Mettre à jour les informations de l'utilisateur dans la base de données
            $sql = "UPDATE utilisateur SET nom_prenom = :nouveau_nom_prenom, email = :nouvelle_email WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nouveau_nom_prenom', $nouveau_nom_prenom);
            $stmt->bindParam(':nouvelle_email', $nouvelle_email);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            // Mettre à jour les informations dans la session
            $_SESSION['utilisateur']['nom_prenom'] = $nouveau_nom_prenom;
            $_SESSION['utilisateur']['email'] = $nouvelle_email;

            echo "Informations mises à jour avec succès.";
            // Rafraîchir la page pour afficher les mises à jour
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit();
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion</title>
    <link rel="stylesheet" href="style/compte.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li class="active">
                <a href="dashboard.php">
                    <i class="fa-solid fa-gauge"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="projet.php">
                    <i class="fa-solid fa-diagram-project"></i>
                    <span>Projet</span>
                </a>
            </li>
            <li>
                <a href="chat.php">
                    <i class="fa-regular fa-comment"></i>
                    <span>Chat</span>
                </a>
            </li>

            <li class="logout">
                <a href="#">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Déconnexion</span>
                </a>
            </li>

        </ul>
    </div>

    <div class="main--content">
        <div class="header--wrapper">
            <div class="hearder--title">
                <div class="search--box">

                    <form action="recherche.php" method="GET">
                        <input type="text" name="query" placeholder="Recherche...">

                    </form>
                </div>
            </div>


            <!-- Ajouter un élément pour afficher la date -->
            <div class="date--wrapper">
                <p id="currentDate"></p>
            </div>
            <div class="user--info">
                <img src="images/fil.jpeg" alt="img">
            </div>
        </div>



        <?php if (isset($user_id)) : ?>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card mt-5">
                            <div class="card-body">
                                <h1 class="card-title text-center mb-4">Bienvenue, <?php echo htmlspecialchars($nom_prenom); ?></h1>
                                <p class="card-text text-center mb-4">Email : <?php echo htmlspecialchars($email); ?></p>
                                <hr>
                                <h2 class="card-title text-center mb-4">Modifier vos informations</h2>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="form-group">
                                        <label for="nouveau_nom_prenom">Nouveau nom et prénom :</label>
                                        <input type="text" class="form-control" id="nouveau_nom_prenom" name="nouveau_nom_prenom" value="<?php echo htmlspecialchars($nom_prenom); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="nouvelle_email">Nouvelle adresse email :</label>
                                        <input type="email" class="form-control" id="nouvelle_email" name="nouvelle_email" value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                    <button type="submit" class="btn  btn-block">Mettre à jour</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>



    </div>



    <script src="js/script.js"></script>
</body>

</html>