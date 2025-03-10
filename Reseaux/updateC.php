<?php
// update_client.php

// Inclure le fichier de connexion à la base de données
require 'db.php';

// Récupérer l'ID du client à mettre à jour
$id = $_GET['id'];

// Récupérer les données du client
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    // Mettre à jour le client dans la base de données
    $sql = "UPDATE clients SET nom='$nom', email='$email', telephone='$telephone' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: clients.php"); // Rediriger vers la page des clients
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Récupérer les informations actuelles du client
$sql = "SELECT * FROM clients WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier un client</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .container {
      margin-top: 50px;
    }
    .form-container {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="form-container">
          <h2 class="text-center mb-4">Modifier un client</h2>
          <form method="POST">
            <div class="mb-3">
              <label for="clientName" class="form-label">Nom</label>
              <input type="text" class="form-control" id="clientName" name="nom" value="<?php echo $row['nom']; ?>" required>
            </div>
            <div class="mb-3">
              <label for="clientEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="clientEmail" name="email" value="<?php echo $row['email']; ?>" required>
            </div>
            <div class="mb-3">
              <label for="clientPhone" class="form-label">Téléphone</label>
              <input type="tel" class="form-control" id="clientPhone" name="telephone" value="<?php echo $row['telephone']; ?>" required>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">Mettre à jour</button>
              <a href="clients.php" class="btn btn-secondary">Annuler</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>