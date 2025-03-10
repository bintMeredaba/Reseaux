<?php
// add_client.php

// Inclure le fichier de connexion à la base de données
require 'db.php';

// Ajouter un client
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    $sql = "INSERT INTO clients (nom, email, telephone) VALUES ('$nom', '$email', '$telephone')";

    if ($conn->query($sql) === TRUE) {
        header("Location: clients.php"); // Rediriger vers la page des clients
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>