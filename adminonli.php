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
  $sql = "CREATE TABLE admin (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        mot_de_passe VARCHAR(255) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

  // use exec() because no results are returned
  $conn->exec($sql);
  echo "Table admin created successfully<br>";

  // Inserting data into the table
  $insert_sql = "INSERT INTO admin (nom, mot_de_passe) VALUES ('AdminPatronOn', 'PatronOn135')";
  $conn->exec($insert_sql);
  echo "Data inserted successfully";
} catch (PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
