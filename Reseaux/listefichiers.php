<?php
// liste_fichiers.php

// Inclure le fichier de connexion à la base de données
require 'db.php';

// Récupérer la liste des fichiers
$sql = "SELECT * FROM fichiers";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Fichiers</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        .table th {
            background-color: #f8f9fc;
            font-weight: 600;
        }
        .table td {
            vertical-align: middle;
        }
        .btn-retour {
            margin-bottom: 20px; /* Espacement sous le bouton */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="table-container">
            <!-- Bouton Retour -->
            <a href="files.php" class="btn btn-secondary btn-retour">
                <i class="fas fa-arrow-left"></i> Retour
            </a>

            <h2 class="text-center mb-4">Liste des Fichiers</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom du Fichier</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['nom_fichier']}</td>
                                    <td>
                                        <a href='{$row['chemin_fichier']}' class='btn btn-primary btn-sm' download>Télécharger</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>Aucun fichier trouvé.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>