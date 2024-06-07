<?php
session_start(); // Démarrage de la session


// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'gestion';
$username = 'root';
$password = '';

try {
    // Connexion à la base de données
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer les noms des utilisateurs
    $stmt = $db->query("SELECT id, nom_prenom FROM utilisateur");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérifier si les données du formulaire sont présentes
        if (isset($_POST['message'], $_POST['id_destinataire']) && isset($_SESSION['utilisateur'])) {
            // Récupérer les données du formulaire
            $message = $_POST['message'];
            $id_destinataire = $_POST['id_destinataire'];
            $id_auteur = $_SESSION['utilisateur']['id'];

            // Préparer et exécuter la requête SQL pour insérer le message
            $sql = "INSERT INTO message (message, id_destinataire, id_auteur) VALUES (:message, :id_destinataire, :id_auteur)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':id_destinataire', $id_destinataire, PDO::PARAM_INT);
            $stmt->bindParam(':id_auteur', $id_auteur, PDO::PARAM_INT);

            if ($stmt->execute()) {
            } else {
                echo "Erreur lors de l'insertion du message.";
            }
        } else {
            echo "Erreur : données du formulaire ou utilisateur non connecté.";
        }
    }

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['utilisateur'])) {
        $id_utilisateur = $_SESSION['utilisateur'];

        // Requête pour récupérer les messages où l'utilisateur est l'auteur ou le destinataire
        $sql = "SELECT m.message, m.id_auteur, m.id_destinataire, u.nom_prenom as auteur_nom
                FROM message m
                JOIN utilisateur u ON m.id_auteur = u.id
                WHERE m.id_auteur = :id_utilisateur OR m.id_destinataire = :id_utilisateur";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $messages = [];
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style/chat.css">

    <style>
        body {
            display: flex;
            margin: 0;
        }

        #chat-container {
            width: 400px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

        #chat-messages {
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
        }

        #chat-form {
            display: flex;
            margin-top: 10px;
        }

        #message-input {
            flex-grow: 1;
            padding: 5px;
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
                <img src="images/fil.jpeg" alt="img">
            </div>
        </div>

        <!-- body  -->
        <div id="chat-container">
            <div id="user-list">
                <h1>Liste des utilisateurs</h1>
                <form action="" method="post">
                    <label for="id_destinataire">Choisir un utilisateur :</label>
                    <select name="id_destinataire" id="id_destinataire">
                        <?php foreach ($users as $user) : ?>
                            <option value="<?php echo $user['id']; ?>"><?php echo $user['nom_prenom']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="message" id="message-input" placeholder="Tapez votre message..." required>
                    <button type="submit">Envoyer</button>
                </form>
            </div>

            <div id="chat">
                <h2 id="selected-user">Chat</h2>
                <div id="chat-messages">
                    <?php if (!empty($messages)) : ?>
                        <?php foreach ($messages as $msg) : ?>
                            <div class="chat-message">
                                <p>
                                    <strong><?php echo htmlspecialchars($msg['auteur_nom']); ?>:</strong>
                                    <?php echo htmlspecialchars($msg['message']); ?>
                                    (<?php echo $msg['id_auteur'] == $id_utilisateur ? 'Vous' : 'Destinataire'; ?>)
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>Aucun message disponible.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>




        <script>
            var selectedUser = ""; // Variable pour stocker le destinataire sélectionné

            function selectUser(username) {
                selectedUser = username;
                // Mettre en évidence visuellement le destinataire sélectionné, par exemple en changeant la couleur du texte ou en ajoutant une classe CSS
                console.log("Destinataire sélectionné : " + selectedUser);

                // Mettre à jour la partie du chat avec le nom de l'utilisateur sélectionné
                document.getElementById("selected-user").innerText = "Chat avec " + selectedUser;
            }

            function sendMessage() {
                var message = $("#message-input").val();
                if (selectedUser !== "" && message !== "") {
                    // Envoyer le message au destinataire sélectionné
                    console.log("Envoyer le message à " + selectedUser + " : " + message);
                    // Réinitialiser le champ de saisie après l'envoi
                    $("#message-input").val("");
                } else {
                    alert("Veuillez sélectionner un destinataire et saisir un message.");
                }
            }
        </script>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    </div>

    <script src="js/script.js"></script>


</body>

</html>