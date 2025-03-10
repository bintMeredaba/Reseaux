<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier les identifiants dans la base de données
    $sql = "SELECT * FROM employes WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        if (password_verify($password, $employee['password'])) {
            // Connexion réussie
            $_SESSION['employee_id'] = $employee['id'];
            $_SESSION['employee_email'] = $employee['email'];
            header('Location: pageemploye.php'); // Rediriger vers la page d'upload
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Aucun employé trouvé avec cet e-mail.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Employé - Smarttech</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .welcome-banner {
            text-align: center;
            color: white;
            margin-bottom: 2rem;
        }

        .welcome-banner h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .welcome-banner p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .form-control {
            border-radius: 5px;
            padding: 0.75rem;
            border: 1px solid #ddd;
            margin-bottom: 1rem;
        }

        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.5);
        }

        .btn-primary {
            background-color: #6a11cb;
            border: none;
            padding: 0.75rem;
            border-radius: 5px;
            width: 100%;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2575fc;
        }

        .alert {
            margin-bottom: 1rem;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div>
        <!-- Bannière de bienvenue -->
        <div class="welcome-banner">
            <h1>Bienvenue sur Smarttech</h1>
            <p>Votre espace de travail sécurisé</p>
        </div>

        <!-- Formulaire de connexion -->
        <div class="login-container">
            <h2>Connexion Employé</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>