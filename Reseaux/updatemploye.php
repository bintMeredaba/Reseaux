<?php
session_start();
require 'db.php';

// Vérifier si l'employé est connecté
if (!isset($_SESSION['employee_id'])) {
    header('Location: login_employee.php');
    exit();
}

// Récupérer l'ID du fichier à modifier
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

// Traitement de la modification du fichier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['file']['name']);
        $file_tmp = $_FILES['file']['tmp_name'];
        $upload_dir = 'uploads/';

        // Supprimer l'ancien fichier
        $old_file = $result->fetch_assoc()['nom_fichier'];
        if (file_exists($upload_dir . $old_file)) {
            unlink($upload_dir . $old_file);
        }

        // Déplacer le nouveau fichier
        if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
            // Mettre à jour le nom du fichier dans la base de données
            $sql = "UPDATE fichiers SET nom_fichier = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $file_name, $file_id);

            if ($stmt->execute()) {
                $success = "Fichier modifié avec succès.";
            } else {
                $error = "Erreur lors de la mise à jour du fichier dans la base de données.";
            }
        } else {
            $error = "Erreur lors du déplacement du fichier.";
        }
    } else {
        $error = "Veuillez sélectionner un fichier valide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un fichier</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Modifier un fichier</h2>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
            <a href="pageemploye.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>