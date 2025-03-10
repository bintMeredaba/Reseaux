<?php
// Inclure le fichier de connexion à la base de données
require 'db.php';

// Récupérer le nombre total de fichiers
$sql_count = "SELECT COUNT(*) AS total_files FROM fichiers";
$result_count = $conn->query($sql_count);

if ($result_count) {
    $row = $result_count->fetch_assoc();
    $total_files = $row['total_files']; // Nombre total de fichiers
} else {
    $total_files = 0; // En cas d'erreur, afficher 0
}
$sql_count  = "SELECT COUNT(*) AS total_employees FROM employes";
$result_count = $conn->query($sql_count);

if ($result_count) {
    $row = $result_count->fetch_assoc();
    $total_employees = $row['total_employees']; // Nombre total d'employés
} else {
    $total_employees = 0; // En cas d'erreur, afficher 0
}
$sql_count = "SELECT COUNT(*) AS total_clients FROM clients";
$result_count = $conn->query($sql_count);

if ($result_count) {
    $row = $result_count->fetch_assoc();
    $total_clients = $row['total_clients']; // Nombre total de clients
} else {
    $total_clients = 0; // En cas d'erreur, afficher 0
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord Admin</title>
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
          <h3 class="text-white fw-bold">Admin Panel</h3>
        </div>
        <hr class="sidebar-divider bg-white opacity-25 mx-3">
        <div class="nav flex-column px-3">
          <a href="interface.php" class="nav-link active" id="dashboard-link">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Tableau de bord</span>
          </a>
          <a href="files.php" class="nav-link" id="files-link">
            <i class="fas fa-fw fa-file"></i>
            <span>Gestion des Fichiers</span>
          </a>
          <a href="employees.php" class="nav-link" id="employees-link">
            <i class="fas fa-fw fa-users"></i>
            <span>Gestion des Employés</span>
          </a>
          <a href="clients.php" class="nav-link" id="clients-link">
            <i class="fas fa-fw fa-handshake"></i>
            <span>Gestion des Clients</span>
          </a>
          <a href="#" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>Paramètres</span>
          </a>
          <a href="#" class="nav-link">
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
              <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-bell fa-fw"></i>
                  <span class="badge bg-danger badge-counter">3+</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown">
                  <li>
                    <h6 class="dropdown-header">Centre de notifications</h6>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                      <div class="me-3">
                        <div class="icon-circle bg-primary text-white">
                          <i class="fas fa-file-alt"></i>
                        </div>
                      </div>
                      <div>
                        <div class="small text-gray-500">7 Mars, 2025</div>
                        <span>Un nouveau rapport mensuel est disponible</span>
                      </div>
                    </a>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <a class="dropdown-item text-center small text-gray-500" href="#">Afficher toutes les notifications</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-envelope fa-fw"></i>
                  <span class="badge bg-danger badge-counter">7</span>
                </a>
              </li>
              <div class="topbar-divider d-none d-sm-block"></div>
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="me-2 d-none d-lg-inline text-gray-600 small">Admin User</span>
                  <img class="img-profile rounded-circle" width="32" height="32" src="/api/placeholder/32/32" alt="Profile">
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                      Profil
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>
                      Paramètres
                    </a>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                      <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                      Déconnexion
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
        
        <!-- Dashboard Content -->
        <div class="container-fluid" id="dashboard-content">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 page-title">Tableau de bord</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
              <i class="fas fa-download fa-sm text-white-50 me-2"></i>Générer un rapport
            </a>
          </div>
          
          <!-- Stats Cards -->
          <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card card-dashboard shadow h-100">
                <div class="card-body">
                  <div class="stat-card bg-light">
                    <div class="row">
                      <div class="col-auto">
                        <div class="icon text-primary">
                          <i class="fas fa-file"></i>
                        </div>
                      </div>
                      <div class="col text-end">
                        <a href="listefichiers.php" class="text-decoration-none">
                          <div class="value text-primary"><?php echo $total_files; ?></div>
                          <div class="label text-primary">Fichiers</div>
                      </a>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card card-dashboard shadow h-100">
                <div class="card-body">
                  <div class="stat-card bg-light">
                    <div class="row">
                      <div class="col-auto">
                        <div class="icon text-success">
                          <i class="fas fa-users"></i>
                        </div>
                      </div>
                      <div class="col text-end">
                      <div class="value text-primary"><?php echo $total_employees; ?></div>
                        <div class="label text-success">Employés</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card card-dashboard shadow h-100">
                <div class="card-body">
                  <div class="stat-card bg-light">
                    <div class="row">
                      <div class="col-auto">
                        <div class="icon text-info">
                          <i class="fas fa-handshake"></i>
                        </div>
                      </div>
                      <div class="col text-end">
                      <div class="value text-primary"><?php echo $total_clients; ?></div>
                        <div class="label text-info">Clients</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Recent Activity -->
          <div class="row">
            <div class="col-12 col-lg-8">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Activités Récentes</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Options:</div>
                      <a class="dropdown-item" href="#">Toutes les activités</a>
                      <a class="dropdown-item" href="#">Fichiers uniquement</a>
                      <a class="dropdown-item" href="#">Employés uniquement</a>
                      <a class="dropdown-item" href="#">Clients uniquement</a>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="activity-item d-flex mb-3 pb-3 border-bottom fadeIn">
                    <div class="me-3">
                      <div class="icon-circle bg-gradient-primary text-white">
                        <i class="fas fa-file"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">12 Mars, 2025</div>
                      <span>Nouveau fichier "Rapport Q1 2025.pdf" a été uploadé</span>
                    </div>
                  </div>
                  <div class="activity-item d-flex mb-3 pb-3 border-bottom fadeIn" style="animation-delay: 0.1s;">
                    <div class="me-3">
                      <div class="icon-circle bg-gradient-success text-white">
                        <i class="fas fa-user"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">10 Mars, 2025</div>
                      <span>Nouvel employé "Sophie Martin" a été ajouté</span>
                    </div>
                  </div>
                  <div class="activity-item d-flex mb-3 pb-3 border-bottom fadeIn" style="animation-delay: 0.2s;">
                    <div class="me-3">
                      <div class="icon-circle bg-gradient-info text-white">
                        <i class="fas fa-handshake"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">8 Mars, 2025</div>
                      <span>Nouveau client "Entreprise ABC" a été ajouté</span>
                    </div>
                  </div>
                  <div class="activity-item d-flex fadeIn" style="animation-delay: 0.3s;">
                    <div class="me-3">
                      <div class="icon-circle bg-gradient-primary text-white">
                        <i class="fas fa-file"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">5 Mars, 2025</div>
                      <span>Le fichier "Contrat Client XYZ.docx" a été mis à jour</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Tâches à faire</h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="task1">
                      <label class="form-check-label" for="task1">
                        Finaliser le rapport trimestriel
                      </label>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="task2">
                      <label class="form-check-label" for="task2">
                        Rencontrer le nouveau client
                      </label>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="task3">
                      <label class="form-check-label" for="task3">
                        Mettre à jour les contrats d'employés
                      </label>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="task4">
                      <label class="form-check-label" for="task4">
                        Préparer la réunion mensuelle
                      </label>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="task5">
                      <label class="form-check-label" for="task5">
                        Examiner les demandes de congés
                      </label>
                    </div>
                  </div>
                  <div class="text-center mt-4">
                    <a href="#" class="btn btn-sm btn-primary">Voir toutes les tâches</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Toast Notification Container -->
  <div class="toast-container"></div>
  
  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Prêt à partir?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Sélectionnez "Déconnexion" ci-dessous si vous êtes prêt à mettre fin à votre session actuelle.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Annuler</button>
          <a class="btn btn-primary" href="login.html">Déconnexion</a>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    
    // Function to show toast notifications
    function showToast(message, type = 'success') {
      const toastContainer = document.querySelector('.toast-container');
      const toast = document.createElement('div');
      toast.classList.add('toast', 'show', 'mb-3');
      toast.setAttribute('role', 'alert');
      toast.setAttribute('aria-live', 'assertive');
      toast.setAttribute('aria-atomic', 'true');
      
      let bgColor = 'bg-success';
      let iconClass = 'fas fa-check-circle';
      
      if (type === 'error') {
        bgColor = 'bg-danger';
        iconClass = 'fas fa-exclamation-circle';
      } else if (type === 'warning') {
        bgColor = 'bg-warning';
        iconClass = 'fas fa-exclamation-triangle';
      } else if (type === 'info') {
        bgColor = 'bg-info';
        iconClass = 'fas fa-info-circle';
      }
      
      toast.innerHTML = `
        <div class="toast-header">
          <i class="${iconClass} me-2 text-${type}"></i>
          <strong class="me-auto">Notification</strong>
          <small>À l'instant</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          ${message}
        </div>
      `;
      
      toastContainer.appendChild(toast);
      
      // Auto-dismiss after 3 seconds
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
          toast.remove();
        }, 300);
      }, 3000);
    }
  </script>
</body>
</html>