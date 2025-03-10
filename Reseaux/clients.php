<?php
// clients.php

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reseaux";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer la liste des clients
$sql = "SELECT * FROM clients";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Clients</title>
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
      <div class="col-lg-2 sidebar">
        <div class="d-flex justify-content-center py-4">
          <h3 class="text-white fw-bold">Admin Panel</h3>
        </div>
        <hr class="sidebar-divider bg-white opacity-25 mx-3">
        <div class="nav flex-column px-3">
          <a href="interface.html" class="nav-link active" id="dashboard-link">
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
        
        <!-- Clients Content -->
        <div class="container-fluid" id="clients-content">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 page-title">Gestion des Clients</h1>
            <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addClientModal">
              <i class="fas fa-plus fa-sm text-white-50 me-2"></i>Ajouter un client
            </button>
          </div>
          
          <!-- Clients Table -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Liste des Clients</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Nom</th>
                      <th>prenom</th>
                      <th>Email</th>
                      <th>Téléphone</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['nom']}</td>
                                    <td>{$row['prenom']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['telephone']}</td>
                                    
                                    <td>
                                      <a href='updateC.php?id={$row['id']}' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>
                                      <a href='deleteC.php?id={$row['id']}' class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Aucun client trouvé</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Add Client Modal -->
  <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addClientModalLabel">Ajouter un client</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="addC.php" method="POST">
            <div class="mb-3">
              <label for="clientName" class="form-label">Nom</label>
              <input type="text" class="form-control" id="clientName" name="nom" placeholder="Entrez le nom" required>
            </div>
            <div class="mb-3">
              <label for="clientEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="clientEmail" name="email" placeholder="Entrez l'email" required>
            </div>
            <div class="mb-3">
              <label for="clientPhone" class="form-label">Téléphone</label>
              <input type="tel" class="form-control" id="clientPhone" name="telephone" placeholder="Entrez le téléphone" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>