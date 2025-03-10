<?php
// update_employee.php

// Inclure le fichier de connexion à la base de données
require 'db.php';

// Récupérer l'ID de l'employé à mettre à jour
$id = $_GET['id'];

// Récupérer les données de l'employé
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $poste = $_POST['poste'];
    $email = $_POST['email'];

    // Mettre à jour l'employé dans la base de données
    $sql = "UPDATE employes SET nom='$nom', poste='$poste', email='$email' , prenom='$prenom' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: employees.php"); // Rediriger vers la page des employés
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Récupérer les informations actuelles de l'employé
$sql = "SELECT * FROM employes WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier un employé</title>
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
          <h2 class="text-center mb-4">Modifier un employé</h2>
          <form method="POST">
            <div class="mb-3">
              <label for="employeeName" class="form-label">Nom</label>
              <input type="text" class="form-control" id="employeeName" name="nom" value="<?php echo $row['nom']; ?>" required>
            </div>
            <div class="mb-3">
              <label for="employeePosition" class="form-label">Poste</label>
              <input type="text" class="form-control" id="employeePosition" name="poste" value="<?php echo $row['poste']; ?>" required>
            </div>
            <div class="mb-3">
              <label for="employeeEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="employeeEmail" name="email" value="<?php echo $row['email']; ?>" required>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">Mettre à jour</button>
              <a href="employees.php" class="btn btn-secondary">Annuler</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>