<?php
session_start();
require 'db.php';

// Vérifier si l'employé est connecté
if (!isset($_SESSION['employee_id'])) {
    header('Location: login_employee.php');
    exit();
}

// Récupérer l'ID du fichier à supprimer
if (!isset($_GET['id'])) {
    header('Location: pageemploye.php');
    exit();
}
$file_id = $_GET['id'];

// Vérifier que le fichier appartient à l'employé connecté
$employee_id = $_SESSION['employee_id'];
$sql = "SELECT * FROM fichiers WHERE id = ? AND employe_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $file_id, $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Le fichier n'existe pas ou n'appartient pas à l'employé
    header('Location: pageemploye.php');
    exit();
}

// Supprimer le fichier du serveur
$file = $result->fetch_assoc();
$upload_dir = 'uploads/';
if (file_exists($upload_dir . $file['nom_fichier'])) {
    unlink($upload_dir . $file['nom_fichier']);
}

// Supprimer le fichier de la base de données
$sql = "DELETE FROM fichiers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $file_id);

if ($stmt->execute()) {
    header('Location: pageemploye.php?success=1');
} else {
    header('Location: pageemploye.php?error=1');
}
exit();
?>