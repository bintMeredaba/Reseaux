<?php
// add_employee.php

// Inclure le fichier de connexion à la base de données
require 'db.php';

// Ajouter un employé
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $poste = $_POST['poste'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hasher le mot de passe

    // Requête préparée pour insérer l'employé
    $sql = "INSERT INTO employes (nom, prenom, poste, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nom, $prenom, $poste, $email, $password);

    if ($stmt->execute()) {
        // Envoyer un e-mail de confirmation
        $to = $email;
        $subject = "Bienvenue chez SmartTech";
        $message = "
            <h1>Bienvenue chez SmartTech, $prenom !</h1>
            <p>Votre compte a été créé avec succès.</p>
            <p>Voici vos informations de connexion :</p>
            <ul>
                <li><strong>E-mail :</strong> $email</li>
                <li><strong>Mot de passe :</strong> {$_POST['password']}</li>
            </ul>
            <p>Vous pouvez vous connecter à votre espace employé en cliquant <a href='http://votre-site.com/login_employee.php'>ici</a>.</p>
            <p>Cordialement,<br>L'équipe SmartTech</p>
        ";

        // En-têtes de l'e-mail
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: SmartTech <no-reply@smattech.sn>" . "\r\n";

        // Envoyer l'e-mail
        if (mail($to, $subject, $message, $headers)) {
            echo "E-mail de confirmation envoyé avec succès.";
        } else {
            echo "Erreur lors de l'envoi de l'e-mail.";
        }

        // Rediriger vers la page des employés
        header("Location: employees.php");
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>