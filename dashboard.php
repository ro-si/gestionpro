<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit();
}

// Récupérer les informations de l'utilisateur connecté
$utilisateur = $_SESSION['utilisateur'];

// Connexion à la base de données (à remplacer avec vos propres informations de connexion)
$host = 'localhost';
$dbname = 'gestion';
$username = 'root';
$password = '';

// Création de la connexion PDO
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
// Configuration pour afficher les erreurs PDO
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/dashboard.css">
    <style>
        body {

            margin: 0;

        }

        h1 {
            color: #333;
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {

            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }



        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid white;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>

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
                <a href="logout.php">
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
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="search" />
                </div>
            </div>

            <!-- Ajouter un élément pour afficher la date -->
            <div class="date--wrapper">
                <p id="currentDate"></p>
            </div>
            <div class="user--info">
                <a href="compte.php"><img src="images/Vector.png" width="250" alt="img"></a>
            </div>

        </div>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Traitement des données soumises ici
            // ...
        }
        ?>


        <h1>Bienvenue</h1>

        <h2>Vos travaux</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Travail</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    // Placez ici votre code de connexion à la base de données

                    // ID de l'utilisateur pour lequel vous souhaitez récupérer les travaux (à remplacer par l'ID de l'utilisateur)
                    $utilisateur_id = 1; // Par exemple, 1 pour l'utilisateur avec l'ID 1

                    // Requête SQL pour récupérer les travaux de l'utilisateur spécifique
                    $sql = "SELECT id, travail FROM travaux WHERE utilisateur_id = :utilisateur_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':utilisateur_id', $utilisateur_id);
                    $stmt->execute();

                    // Vérification si des résultats ont été trouvés
                    if ($stmt->rowCount() > 0) {
                        // Affichage des travaux de l'utilisateur
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["travail"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>Vous n'avez aucun travail actuellement</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "Erreur de connexion : " . $e->getMessage();
                }
                ?>
            </tbody>
        </table>


    </div>
    </div>



    <script src="js/script.js"></script>
</body>

</html>