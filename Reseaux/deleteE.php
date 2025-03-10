<?php
// delete_employee.php

// Inclure le fichier de connexion à la base de données
require 'db.php';

// Récupérer l'ID de l'employé à supprimer
$id = $_GET['id'];

// Supprimer l'employé de la base de données
$sql = "DELETE FROM employes WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: employees.php"); // Rediriger vers la page des employés
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>