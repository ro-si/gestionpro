<?php
$servername = "localhost";
$username = "spcom1_rosine";
$password = "YPNnM=vO5kD{";
$dbname = "spcom1_rosinedb";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // définir le mode d'erreur PDO sur exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // SQL pour supprimer la table
  $sql = "DROP TABLE utilisateur";

  // utiliser exec() car aucun résultat n'est retourné
  $conn->exec($sql);
  echo "La table utilisateur a été supprimée avec succès";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>



