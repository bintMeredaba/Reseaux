<?php
// Inclure le fichier de connexion à la base de données
require 'db.php';

// Vérifier si l'ID est fourni dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: files.php?error=ID manquant");
    exit();
}

$id = intval($_GET['id']); // Récupérer et valider l'ID

// Récupérer les informations actuelles du fichier
$sql = "SELECT nom_fichier FROM fichiers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: files.php?error=Fichier non trouvé");
    exit();
}

$file_data = $result->fetch_assoc();
$current_filename = $file_data['nom_fichier'];

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $new_filename = $_POST['new_filename']; // Nouveau nom de fichier (sans extension)
    $upload_dir = 'uploads/'; // Dossier où les fichiers sont stockés

    // Vérifier si un nouveau fichier a été uploadé
    if (!empty($_FILES['new_file']['name'])) {
        $file_tmp = $_FILES['new_file']['tmp_name']; // Chemin temporaire du fichier
        $file_name = $_FILES['new_file']['name']; // Nom du fichier uploadé
        $file_type = $_FILES['new_file']['type']; // Type MIME du fichier

        // Générer un nom de fichier unique pour éviter les conflits
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $final_filename = $new_filename . '.' . $file_extension;
        $destination = $upload_dir . $final_filename;

        // Déplacer le fichier uploadé vers le dossier uploads
        if (move_uploaded_file($file_tmp, $destination)) {
            // Mettre à jour la base de données avec le nouveau fichier
            $sql_update = "UPDATE fichiers SET nom_fichier = ?, type_fichier = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssi", $final_filename, $file_type, $id);

            if ($stmt_update->execute()) {
                header("Location: files.php?success=Fichier mis à jour avec succès");
                exit();
            } else {
                $error = "Erreur lors de la mise à jour de la base de données.";
            }
        } else {
            $error = "Erreur lors du téléchargement du fichier.";
        }
    } else {
        // Si aucun fichier n'est uploadé, mettre à jour uniquement le nom du fichier
        $sql_update = "UPDATE fichiers SET nom_fichier = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_filename, $id);

        if ($stmt_update->execute()) {
            header("Location: files.php?success=Nom du fichier mis à jour avec succès");
            exit();
        } else {
            $error = "Erreur lors de la mise à jour du nom du fichier.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mise à jour du fichier</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Mise à jour du fichier</h5>
          </div>
          <div class="card-body">
            <?php if (isset($error)): ?>
              <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $id; ?>">

              <div class="mb-3">
                <label for="new_filename" class="form-label">Nouveau nom (sans extension)</label>
                <input type="text" class="form-control" id="new_filename" name="new_filename" required 
                       value="<?php echo pathinfo($current_filename, PATHINFO_FILENAME); ?>">
              </div>

              <div class="mb-3">
                <label for="new_file" class="form-label">Remplacer le fichier (optionnel)</label>
                <input type="file" class="form-control" id="new_file" name="new_file">
                <div class="form-text">Laissez vide pour conserver le fichier actuel.</div>
              </div>

              <div class="d-flex justify-content-between">
                <a href="files.php" class="btn btn-secondary">Annuler</a>
                <button type="submit" name="update" class="btn btn-primary">Mettre à jour</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>