<?php
// db.php

// Paramètres de connexion à la base de données
$servername = "localhost"; // Adresse du serveur MySQL
$username = "root";        // Nom d'utilisateur MySQL
$password = "";            // Mot de passe MySQL
$dbname = "reseaux"; // Nom de la base de données

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Définir l'encodage des caractères
$conn->set_charset("utf8");
?>