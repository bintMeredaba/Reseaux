<?php
// Inclure le fichier de connexion à la base de données
require 'db.php';

// Vérifier si l'ID est fourni
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Récupérer les informations du fichier avant suppression
    $sql_select = "SELECT * FROM fichiers WHERE id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    
    if($result->num_rows > 0) {
        $file_data = $result->fetch_assoc();
        $file_path = 'uploads/' . $file_data['nom_fichier'];
        
        // Supprimer le fichier physique si existant
        if(file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Supprimer l'entrée de la base de données
        $sql_delete = "DELETE FROM fichiers WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);
        
        if($stmt_delete->execute()) {
            // Redirection avec message de succès
            header("Location: files.php?message=Le fichier a été supprimé avec succès.");
            exit();
        } else {
            // Redirection avec message d'erreur
            header("Location: files.php?error=Erreur lors de la suppression du fichier: " . $conn->error);
            exit();
        }
    } else {
        // Fichier non trouvé
        header("Location: files.php?error=Fichier non trouvé.");
        exit();
    }
} else {
    // ID non fourni
    header("Location: files.php?error=ID de fichier non spécifié.");
    exit();
}

// Fermer la connexion
$conn->close();
?>