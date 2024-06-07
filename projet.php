<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit();
}

// Récupérer les informations de l'utilisateur connecté
$utilisateur = $_SESSION['utilisateur'];

// Connexion à la base de données
$host = 'localhost';
$dbname = 'gestion';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation des requêtes SQL
    $stmt_insert = $db->prepare("INSERT INTO projet (titre_projet, categorie_projet, contexte_projet, lien_demo, texte_supplementaire, photo_projet, id_user) 
                                 VALUES (:titre_projet, :categorie_projet, :contexte_projet, :lien_demo, :texte_supplementaire, :photo_projet, :id_user)");

    $stmt_select = $db->prepare("SELECT id, titre_projet, categorie_projet, contexte_projet, lien_demo, texte_supplementaire, photo_projet
                                 FROM projet
                                 WHERE id_user = :id_user");
    $stmt_select->bindParam(':id_user', $utilisateur['id']);

    $stmt_delete = $db->prepare("DELETE FROM projet WHERE id = :id");

    $stmt_update = $db->prepare("UPDATE projet
                                 SET titre_projet = :titre_projet,
                                     categorie_projet = :categorie_projet,
                                     contexte_projet = :contexte_projet,
                                     lien_demo = :lien_demo,
                                     texte_supplementaire = :texte_supplementaire,
                                     photo_projet = :photo_projet
                                 WHERE id = :id");

    // Vérification si un formulaire a été soumis (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $target_dir = "uploads/";
        $photo_projet = "";

        if (isset($_FILES['photo_projet']) && $_FILES['photo_projet']['error'] == 0) {
            $target_file = $target_dir . basename($_FILES['photo_projet']['name']);
            if (move_uploaded_file($_FILES['photo_projet']['tmp_name'], $target_file)) {
                $photo_projet = $target_file;
            }
        }


        // Vérification si c'est un formulaire d'insertion
        if (!empty($_POST['titre_projet'])) {
            $stmt_insert->bindParam(':titre_projet', $_POST['titre_projet']);
            $stmt_insert->bindParam(':categorie_projet', $_POST['categorie_projet']);
            $stmt_insert->bindParam(':contexte_projet', $_POST['contexte_projet']);
            $stmt_insert->bindParam(':lien_demo', $_POST['lien_demo']);
            $stmt_insert->bindParam(':texte_supplementaire', $_POST['texte_supplementaire']);
            $stmt_insert->bindParam(':photo_projet', $photo_projet);
            $stmt_insert->bindParam(':id_user', $utilisateur['id']);
            $stmt_insert->execute();
        }

        // Vérification si c'est un formulaire de suppression
        if (isset($_POST['supprimer_id'])) {
            $stmt_delete->bindParam(':id', $_POST['supprimer_id']);
            $stmt_delete->execute();
        }

        // Vérification si c'est un formulaire de modification
        if (isset($_POST['modifier_id'])) {
            $stmt_update->bindParam(':id', $_POST['modifier_id']);
            $stmt_update->bindParam(':titre_projet', $_POST['titre_projet']);
            $stmt_update->bindParam(':categorie_projet', $_POST['categorie_projet']);
            $stmt_update->bindParam(':contexte_projet', $_POST['contexte_projet']);
            $stmt_update->bindParam(':lien_demo', $_POST['lien_demo']);
            $stmt_update->bindParam(':texte_supplementaire', $_POST['texte_supplementaire']);
            $stmt_update->bindParam(':photo_projet', $photo_projet);
            $stmt_update->execute();
        }

        // Après le traitement du formulaire
        header("Location: projet.php");
        exit();
    }

    // Récupération des projets de l'utilisateur connecté
    $stmt_select->execute();
    $projets = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="style/projet.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        <!-- Formulaire de modification de projet (modal ou autre) -->
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

        <div class="card--container">
            <h3 class="main--title">MES PROJETS</h3>
            <div class="main--paragraphe">
                <P id="showFormButton">Ajouter un projet</P>
            </div>


            <div id="formContainer" class="form-containe" style="display: none;">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="monFormulaire" enctype="multipart/form-data">

                    <div class="nein">
                        <label for="titre_projet">Titre du Projet:</label>
                        <input type="text" id="titre_projet" name="titre_projet" required>
                    </div>

                    <div class="nein">
                        <label for="categorie_projet">Catégorie du Projet:</label>
                        <select id="categorie_projet" name="categorie_projet" required>
                            <option value="categorie1">Catégorie 1</option>
                            <option value="categorie2">Catégorie 2</option>

                        </select>
                    </div>
                    <div class="nein">
                        <label for="contexte_projet">Contexte du Projet:</label>
                        <textarea id="contexte_projet" name="contexte_projet" rows="4" required></textarea>
                    </div>
                    <div class="nein">
                        <label for="lien_demo">Lien Demo:</label>
                        <input type="url" id="lien_demo" name="lien_demo">
                    </div>
                    <div class="nein">
                        <label for="texte_supplementaire">Texte Supplémentaire:</label>
                        <textarea id="texte_supplementaire" name="texte_supplementaire" rows="6"></textarea>

                    </div>
                    <div>
                        <label for="photo_projet">Choisir une Photo:</label>
                        <input type="file" id="photo_projet" name="photo_projet">
                        <img id="photo_preview" src="" alt="Prévisualisation de la photo" style="display: none; max-width: 200px; margin-top: 10px;">
                    </div>


                    <button type="submit">Soumettre Projet</button>
                </form>
            </div>


            <div class="tableau">
                <table border="1">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Contexte</th>
                            <th>Lien demo</th>
                            <th>Texte supplémentaire</th>
                            <th>Photo du projet</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($projets)) : ?>
                            <?php foreach ($projets as $projet) : ?>
                                <tr>
                                    <td><?php echo $projet['titre_projet']; ?></td>
                                    <td><?php echo $projet['categorie_projet']; ?></td>
                                    <td><?php echo $projet['contexte_projet']; ?></td>
                                    <td><?php echo $projet['lien_demo']; ?></td>
                                    <td><?php echo $projet['texte_supplementaire']; ?></td>
                                    <td>
                                        <?php if (!empty($projet['photo_projet'])) : ?>
                                            <img src="<?php echo htmlspecialchars($projet['photo_projet']); ?>" alt="Photo du projet" style="max-width: 100px;">
                                        <?php else : ?>
                                            Aucune photo
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display: inline;">
                                            <input type="hidden" name="supprimer_id" value="<?php echo $projet['id']; ?>">
                                            <button type="submit">Supprimer</button>
                                        </form>
                                        <button class="modifier-btn" data-id="<?php echo $projet['id']; ?>">Modifier</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


        </div>

        <div id="modifier-modal" class="form-contain" style="display: none;">
            <form id="modifier-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <div>
                    <input type="hidden" name="modifier_id" id="modifier_id">
                    <label for="titre_projet">Titre du Projet:</label>
                    <input type="text" id="titre_projet" name="titre_projet" required>
                </div>


                <div> <label for="categorie_projet">Catégorie du Projet:</label>
                    <select id="categorie_projet" name="categorie_projet" required>
                        <option value="categorie1">Catégorie 1</option>
                        <option value="categorie2">Catégorie 2</option>
                        Ajoutez d'autres options pour les catégories
                    </select>
                </div>
                <div>
                    <label for="contexte_projet">Contexte du Projet:</label>
                    <textarea id="contexte_projet" name="contexte_projet" rows="4" required></textarea>
                </div>
                <div>
                    <label for="lien_demo">Lien Demo:</label>
                    <input type="url" id="lien_demo" name="lien_demo">
                </div>
                <div><label for="texte_supplementaire">Texte Supplémentaire:</label>
                    <textarea id="texte_supplementaire" name="texte_supplementaire" rows="6"></textarea>
                </div>
                <div>
                    <label for="photo_projet">Choisir une Photo:</label>
                    <input type="file" id="photo_projet" name="photo_projet">
                    <img id="photo_projet_preview" src="" alt="Photo actuelle du projet" style="max-width: 200px; display: none;">
                </div>


                <button type="submit">Enregistrer les modifications</button>
            </form>
        </div>

    </div>




    <script src="js/script.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        //placer le formulaire de projet au centre
        document.getElementById("showFormButton").addEventListener("click", function() {
            var formContainer = document.getElementById("formContainer");
            formContainer.style.display = "block";
            formContainer.style.top = "40%";
            formContainer.style.zIndex = "100"
            formContainer.style.transform = "translate(-50%, -50%)";
        });


        // suppression avec ajax

        $(document).ready(function() {
            $(".supprimer-btn").click(function() {
                var projet_id = $(this).data('id');
                if (confirm("Êtes-vous sûr de vouloir supprimer ce projet ?")) {
                    $.ajax({
                        url: 'supprimer_projet.php',
                        type: 'POST',
                        data: {
                            id: projet_id
                        },
                        success: function(response) {
                            if (response === 'success') {
                                alert("Projet supprimé avec succès.");
                                location.reload(); // Rafraîchir la page pour voir les changements
                            } else {
                                alert("Erreur lors de la suppression du projet.");
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Erreur :', textStatus, errorThrown);
                            alert("Une erreur s'est produite lors de la communication avec le serveur.");
                        }
                    });
                }
            });
        });


        // modification

        $(document).ready(function() {
            $(".modifier-btn").click(function() {
                var projetId = $(this).data("id");
                $("#modifier-modal").show();
                chargerDetailsProjet(projetId);
            });

            function chargerDetailsProjet(projetId) {
                $.ajax({
                    url: "get_projet_details.php",
                    type: "POST",
                    data: {
                        id: projetId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $("#modifier_id").val(response.id);
                            $("#titre_projet").val(response.titre_projet);
                            $("#categorie_projet").val(response.categorie_projet);
                            $("#contexte_projet").val(response.contexte_projet);
                            $("#lien_demo").val(response.lien_demo);
                            $("#texte_supplementaire").val(response.texte_supplementaire);
                            // Afficher l'image si elle existe
                            if (response.photo_projet) {
                                $("#photo_projet_preview").attr("src", "data:image/jpeg;base64," + response.photo_projet);
                                $("#photo_projet_preview").show();
                            } else {
                                $("#photo_projet_preview").hide();
                            }
                        } else {
                            alert("Erreur: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Status: " + status);
                        console.log("Error: " + error);
                        console.log("Response: " + xhr.responseText);
                        alert("Une erreur s'est produite lors de la communication avec le serveur.");
                    }
                });
            }
        });

        // photo 
        document.getElementById('photo_projet').onchange = function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('photo_preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        };

        document.getElementById('current_date').textContent = new Date().toLocaleDateString();

        if (response.photo_projet) {
            $("#photo_projet_preview").attr("src", response.photo_projet);
            $("#photo_projet_preview").show();
        } else {
            $("#photo_projet_preview").hide();
        }
    </script>
</body>

</html>