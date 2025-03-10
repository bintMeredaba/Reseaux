<?php
session_start();
require 'db.php';

// Vérifier si l'employé est connecté
if (!isset($_SESSION['employee_id'])) {
    header('Location: pageemploye.php');
    exit();
}

// Traitement de l'upload de fichier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['file']['name']);
        $file_tmp = $_FILES['file']['tmp_name'];
        $upload_dir = 'uploads/'; // Dossier où les fichiers seront stockés

        // Vérifier si le dossier existe, sinon le créer
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Déplacer le fichier vers le dossier d'upload
        if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
            // Enregistrer le fichier dans la base de données
            $employee_id = $_SESSION['employee_id'];
            $sql = "INSERT INTO fichiers (nom_fichier, employe_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $file_name, $employee_id);

            if ($stmt->execute()) {
                $success = "Fichier uploadé avec succès.";
            } else {
                $error = "Erreur lors de l'enregistrement du fichier dans la base de données.";
            }
        } else {
            $error = "Erreur lors du déplacement du fichier.";
        }
    } else {
        $error = "Veuillez sélectionner un fichier valide.";
    }
}

// Récupérer les fichiers uploadés par l'employé
$employee_id = $_SESSION['employee_id'];
$sql = "SELECT * FROM fichiers WHERE employe_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Fichiers</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
            :root {
      --primary-color: #7b2cbf; /* Nouvelle couleur principale (violet) */
      --primary-dark: #5a189a; /* Variante foncée */
      --primary-light: #9d4edd; /* Variante claire */
      --secondary-color: #858796;
      --success-color: #2ecc71;
      --info-color: #3498db;
      --warning-color: #f39c12;
      --danger-color: #e74c3c;
      --dark-color: #2c3e50;
      --light-color: #f8f9fa;
    }
    
    body {
      background-color: #f8f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .sidebar {
      min-height: 100vh;
      background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
      color: white;
    }
    
    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.8);
      margin-bottom: 0.5rem;
      padding: 0.75rem 1rem;
      border-radius: 0.35rem;
      transition: all 0.3s;
      font-weight: 500;
    }
    
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
      color: white;
      background-color: rgba(255, 255, 255, 0.15);
      transform: translateX(5px);
    }
    
    .sidebar .nav-link i {
      margin-right: 0.5rem;
      width: 20px;
      text-align: center;
    }
    
    .topbar {
      height: 4.375rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      background-color: white;
    }
    
    .card-dashboard {
      border: none;
      border-radius: 0.5rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
      transition: transform 0.3s, box-shadow 0.3s;
      margin-bottom: 1.5rem;
    }
    
    .card-dashboard:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
      background-color: white;
      border-bottom: 1px solid #e3e6f0;
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .icon-circle {
      height: 3rem;
      width: 3rem;
      border-radius: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .bg-gradient-primary {
      background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }
    
    .bg-gradient-success {
      background: linear-gradient(180deg, var(--success-color) 0%, #27ae60 100%);
    }
    
    .bg-gradient-info {
      background: linear-gradient(180deg, var(--info-color) 0%, #2980b9 100%);
    }
    
    .border-left-primary {
      border-left: 0.25rem solid var(--primary-color);
    }
    
    .border-left-success {
      border-left: 0.25rem solid var(--success-color);
    }
    
    .border-left-info {
      border-left: 0.25rem solid var(--info-color);
    }
    
    .table {
      border-radius: 0.5rem;
      overflow: hidden;
      margin-bottom: 0;
    }
    
    .table th {
      background-color: #f8f9fc;
      font-weight: 600;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      border-top: none;
    }
    
    .table td {
      vertical-align: middle;
    }
    
    .crud-controls a {
      cursor: pointer;
      margin: 0 0.4rem;
      transition: transform 0.2s;
      display: inline-block;
    }
    
    .crud-controls a:hover {
      transform: scale(1.2);
    }
    
    .modal-header {
      background-color: var(--primary-color);
      color: white;
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
      background-color: var(--primary-dark);
      border-color: var(--primary-dark);
    }
    
    .page-title {
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: var(--dark-color);
    }
    
    .search-bar {
      position: relative;
    }
    
    .search-bar input {
      padding-left: 40px;
      border-radius: 50px;
      border: 1px solid #e3e6f0;
    }
    
    .search-bar i {
      position: absolute;
      left: 15px;
      top: 10px;
      color: var(--secondary-color);
    }
    
    /* Custom styles for form controls */
    .form-control:focus, .form-select:focus {
      border-color: var(--primary-light);
      box-shadow: 0 0 0 0.25rem rgba(123, 44, 191, 0.25);
    }
    
    /* Améliorations pour le tableau de bord */
    .stat-card {
      padding: 1.5rem;
      border-radius: 0.5rem;
      height: 100%;
    }
    
    .stat-card .icon {
      font-size: 2rem;
      margin-bottom: 1rem;
    }
    
    .stat-card .value {
      font-size: 2rem;
      font-weight: 700;
    }
    
    .stat-card .label {
      text-transform: uppercase;
      font-size: 0.85rem;
      font-weight: 600;
      letter-spacing: 1px;
    }
    
    /* Animation pour les actions CRUD */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .fadeIn {
      animation: fadeIn 0.3s ease-in-out forwards;
    }
    
    /* Style pour les alertes Toast */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
    }
    
    .toast {
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
      overflow: hidden;
    }
    
    .toast-header {
      border-bottom: none;
    }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar">
                <div class="d-flex justify-content-center py-4">
                    <h3 class="text-white fw-bold">Espace Employé</h3>
                </div>
                <hr class="sidebar-divider bg-white opacity-25 mx-3">
                <div class="nav flex-column px-3">
                    <a href="pageemploye.php" class="nav-link active">
                        <i class="fas fa-fw fa-file"></i>
                        <span>Upload de Fichiers</span>
                    </a>
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-fw fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10">
                <!-- Topbar -->
                <nav class="navbar navbar-expand topbar mb-4 static-top shadow-sm">
                    <div class="container-fluid">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                        <div class="search-bar d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-md-0 mw-100">
                            <i class="fas fa-search fa-sm"></i>
                            <input type="text" class="form-control bg-light small" placeholder="Rechercher..." aria-label="Search">
                        </div>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="me-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['employee_email']; ?></span>
                                    <img class="img-profile rounded-circle" width="32" height="32" src="/api/placeholder/32/32" alt="Profile">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="logout.php">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                            Déconnexion
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Upload Section -->
                <div class="container-fluid" id="dashboard-content">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 page-title">Upload de Fichiers</h1>
                    </div>

                    <!-- Upload Form -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Téléverser un fichier</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Formulaire d'upload -->
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Uploader</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des fichiers uploadés -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Mes fichiers</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nom du fichier</
                                                
                                                </tr>
                                            </thead>
                                        
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo $row['nom_fichier']; ?></td>
                                                        <td>
                                                         <!-- Bouton Modifier -->
                                                             <a href="updatemploye.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                                                 <i class="fas fa-edit"></i> Modifier
                                                             </a>
                                                        <!-- Bouton Supprimer -->
                                                            <a href="deletemploye.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?');">
                                                                <i class="fas fa-trash"></i> Supprimer
                                                             </a>
                                                         </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>