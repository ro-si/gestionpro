<?php
$servername = "localhost";
$username = "spcom1_rosine";
$password = "YPNnM=vO5kD{";
$dbname = "spcom1_rosinedb";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // sql to create table
  $sql = "CREATE TABLE projet (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre_projet VARCHAR(255) NOT NULL,
  categorie_projet VARCHAR(255) NOT NULL,
  contexte_projet text NOT NULL,
  lien_demo VARCHAR(255) DEFAULT NULL,
  texte_supplementaire text DEFAULT NULL,
  photo_projet varchar(255) DEFAULT NULL,
  id_user int(11) NOT NULL,
  FOREIGN KEY (id_user) REFERENCES utilisateur(id)
   reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  )";



  // use exec() because no results are returned
  $conn->exec($sql);
  echo "Table projet created successfully";
} catch (PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
