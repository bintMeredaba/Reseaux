<?php
// Inclure le fichier de connexion à la base de données
require 'db.php';

// Vérifier si l'ID est présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: files.php?error=ID manquant");
    exit();
}

// Récupérer et valider l'ID
$id = intval($_GET['id']);
if ($id <= 0) {
    header("Location: files.php?error=ID invalide");
    exit();
}

// Récupérer les informations du fichier
$sql = "SELECT nom_fichier FROM fichiers WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erreur de préparation de la requête : " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si le fichier existe dans la base de données
if ($result->num_rows === 0) {
    header("Location: files.php?error=Fichier non trouvé");
    exit();
}

// Récupérer le nom du fichier
$file = $result->fetch_assoc();
$fileName = $file['nom_fichier'];
$filePath = "uploads/" . $fileName;

// Supprimer le fichier physique s'il existe
if (file_exists($filePath)) {
    if (!unlink($filePath)) {
        header("Location: files.php?error=Impossible de supprimer le fichier physique");
        exit();
    }
} else {
    header("Location: files.php?error=Le fichier physique n'existe pas");
    exit();
}

// Supprimer l'entrée de la base de données
$delete_sql = "DELETE FROM fichiers WHERE id = ?";
$delete_stmt = $conn->prepare($delete_sql);
if (!$delete_stmt) {
    die("Erreur de préparation de la requête de suppression : " . $conn->error);
}
$delete_stmt->bind_param("i", $id);

if ($delete_stmt->execute()) {
    header("Location: files.php?success=Fichier supprimé avec succès");
    exit();
} else {
    header("Location: files.php?error=Erreur lors de la suppression du fichier dans la base de données");
    exit();
}

// Fermer les connexions
$stmt->close();
$delete_stmt->close();
$conn->close();
?>