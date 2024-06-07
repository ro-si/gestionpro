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
  $sql = "CREATE TABLE utilisateur (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom_prenom VARCHAR(255) NOT NULL,
  email VARCHAR(30) NOT NULL,
  mot_de_passe VARCHAR(50),
   reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  )";

  // use exec() because no results are returned
  $conn->exec($sql);
  echo "Table utilisateur created successfully";
} catch (PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
