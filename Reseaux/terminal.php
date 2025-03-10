<?php
session_start();

// Vérification que les informations de connexion sont disponibles
if (!isset($_SESSION['ssh_connection'])) {
    header("Location: terminal.php");
    exit;
}

$connection = $_SESSION['ssh_connection'];
$ip = $connection['ip'];
$username = $connection['username'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal SSH - <?php echo htmlspecialchars($ip); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/xterm/3.14.5/xterm.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e1e;
            color: #f0f0f0;
            font-family: 'Courier New', monospace;
            padding: 20px;
        }
        #terminal {
            height: 500px;
            background-color: #000;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .navbar {
            margin-bottom: 20px;
            background-color: #333;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-terminal"></i> SSH: <?php echo htmlspecialchars($username); ?>@<?php echo htmlspecialchars($ip); ?>
            </a>
            <button class="btn btn-danger ms-auto" onclick="window.location.href='logout.php'">Déconnexion</button>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div id="terminal"></div>
        
        <div class="alert alert-info">
            <strong>Note:</strong> Ceci est une simulation d'interface terminal. Dans un environnement de production, 
            vous devriez intégrer une solution comme WebSSH2, ttyd, ou shellinabox pour une véritable connexion SSH.
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xterm/3.14.5/xterm.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        // Initialisation du terminal
        const term = new Terminal({
            cursorBlink: true,
            theme: {
                background: '#000000',
                foreground: '#f0f0f0'
            }
        });
        term.open(document.getElementById('terminal'));
        
        // Simulation d'une connexion SSH
        term.writeln('Connexion à <?php echo htmlspecialchars($ip); ?>...');
        setTimeout(() => {
            term.writeln('Connecté à <?php echo htmlspecialchars($username); ?>@<?php echo htmlspecialchars($ip); ?>');
            term.writeln('');
            term.write('<?php echo htmlspecialchars($username); ?>@<?php echo htmlspecialchars($ip); ?>:~$ ');
        }, 1000);
        
        // Gestion des entrées utilisateur (simulation)
        term.onKey(e => {
            const printable = !e.domEvent.altKey && !e.domEvent.ctrlKey && !e.domEvent.metaKey;
            
            if (e.domEvent.keyCode === 13) { // Enter
                term.writeln('');
                term.write('<?php echo htmlspecialchars($username); ?>@<?php echo htmlspecialchars($ip); ?>:~$ ');
            } else if (e.domEvent.keyCode === 8) { // Backspace
                if (term._core.buffer.x > 2) {
                    term.write('\b \b');
                }
            } else if (printable) {
                term.write(e.key);
            }
        });
    </script>
</body>
</html>