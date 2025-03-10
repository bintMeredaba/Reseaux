<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accès Distant - Terminal SSH, VNC et noVNC</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      transition: all 0.3s;
    }
    .login-container {
      max-width: 900px;
      margin: 50px auto;
    }
    .card {
      border: none;
      border-radius: 0.5rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
      transition: transform 0.3s, box-shadow 0.3s;
      margin-bottom: 1.5rem;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
    }
    .card-body {
      text-align: center;
    }
    .card-title {
      font-size: 1.25rem;
      font-weight: 600;
    }
    .btn {
      margin-top: 1rem;
    }
    .form-group {
      margin-bottom: 1rem;
    }
    #terminal {
      display: none;
      background-color: #000;
      color: #fff;
      font-family: 'Courier New', monospace;
      padding: 10px;
      border-radius: 5px;
      height: 500px;
      overflow-y: auto;
      text-align: left;
      width: 100%;
    }
    .vnc-screen {
      display: none;
      background-color: #333;
      color: #fff;
      padding: 10px;
      border-radius: 5px;
      height: 500px;
      width: 100%;
      position: relative;
      overflow: hidden;
    }
    .novnc-screen {
      display: none;
      background-color: #222;
      color: #fff;
      padding: 10px;
      border-radius: 5px;
      height: 500px;
      width: 100%;
      position: relative;
      overflow: hidden;
    }
    .desktop-icon {
      display: inline-block;
      text-align: center;
      margin: 10px;
      cursor: pointer;
    }
    .desktop-icon i {
      font-size: 2rem;
      margin-bottom: 5px;
    }
    .desktop-icon p {
      font-size: 0.8rem;
      margin: 0;
    }
    .taskbar {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 40px;
      background-color: #1a1a1a;
      display: flex;
      align-items: center;
      padding: 0 10px;
    }
    .start-button {
      background-color: #0078d7;
      color: white;
      border: none;
      border-radius: 2px;
      padding: 5px 10px;
      margin-right: 10px;
    }
    .window {
      position: absolute;
      top: 50px;
      left: 50px;
      width: 400px;
      height: 300px;
      background-color: #f0f0f0;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      display: none;
    }
    .window-header {
      background-color: #0078d7;
      color: white;
      padding: 5px 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: move;
    }
    .window-content {
      padding: 10px;
      height: calc(100% - 30px);
      overflow: auto;
      color: #000;
    }
    .prompt {
      color: #4CAF50;
    }
    .dark-mode {
      background-color: #222;
      color: #f0f0f0;
    }
    .terminal-cursor {
      animation: blink 1s step-end infinite;
    }
    @keyframes blink {
      from, to { background-color: transparent; }
      50% { background-color: white; }
    }
    .toolbar {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
    }
    .toolbar button {
      background-color: #444;
      color: white;
      border: none;
      border-radius: 3px;
      padding: 3px 8px;
      font-size: 0.8rem;
    }
    .novnc-toolbar {
      background-color: #333;
      padding: 5px;
      display: flex;
      justify-content: space-between;
    }
  </style>
</head>
<body>
  <div class="container login-container" id="login-view">
    <h1 class="text-center mb-4">Accès distant</h1>
    <div class="row">
      <!-- SSH -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <i class="fas fa-terminal fa-3x mb-3 text-primary"></i>
            <h5 class="card-title">SSH</h5>
            <p class="card-text">Connexion sécurisée via SSH.</p>
            <form id="ssh-form">
              <div class="form-group">
                <input type="text" class="form-control" id="ssh-ip" placeholder="Adresse IP" required value="192.168.1.110">
              </div>
              <div class="form-group">
                <input type="text" class="form-control" id="ssh-username" placeholder="Nom d'utilisateur" required value="oumou">
              </div>
              <div class="form-group">
                <input type="password" class="form-control" id="ssh-password" placeholder="Mot de passe" required>
              </div>
              <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
          </div>
        </div>
      </div>
      <!-- VNC -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <i class="fas fa-desktop fa-3x mb-3 text-success"></i>
            <h5 class="card-title">VNC</h5>
            <p class="card-text">Connexion via VNC.</p>
            <form id="vnc-form">
              <div class="form-group">
                <input type="text" class="form-control" id="vnc-ip" placeholder="Adresse IP" required value="192.168.1.110">
              </div>
              <div class="form-group">
                <input type="number" class="form-control" id="vnc-port" placeholder="Port" required value="5900">
              </div>
              <div class="form-group">
                <input type="password" class="form-control" id="vnc-password" placeholder="Mot de passe VNC" required>
              </div>
              <button type="submit" class="btn btn-success">Se connecter</button>
            </form>
          </div>
        </div>
      </div>
      <!-- noVNC -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <i class="fas fa-laptop fa-3x mb-3 text-info"></i>
            <h5 class="card-title">noVNC</h5>
            <p class="card-text">Connexion via noVNC.</p>
            <form id="novnc-form">
              <div class="form-group">
                <input type="text" class="form-control" id="novnc-ip" placeholder="Adresse IP" required value="192.168.1.110">
              </div>
              <div class="form-group">
                <input type="number" class="form-control" id="novnc-port" placeholder="Port" required value="6080">
              </div>
              <div class="form-group">
                <input type="password" class="form-control" id="novnc-password" placeholder="Mot de passe noVNC" required>
              </div>
              <button type="submit" class="btn btn-info">Se connecter</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Conteneur SSH Terminal -->
  <div class="container mt-3" id="terminal-container" style="display:none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 id="ssh-connection-info"></h3>
      <button id="ssh-disconnect-btn" class="btn btn-danger">Déconnexion</button>
    </div>
    <pre id="terminal">Microsoft Windows [version 10.0.19045.5487]
(c) Microsoft Corporation. Tous droits réservés.

C:\Users\hp>ssh -L 6080:localhost:6080 <span id="ssh-conn-details"></span>
<span id="ssh-password-prompt"></span>Welcome to Ubuntu 24.04.1 LTS (GNU/Linux 6.11.0-19-generic x86_64)

 * Documentation:  https://help.ubuntu.com
 * Management:     https://landscape.canonical.com
 * Support:        https://ubuntu.com/pro

La maintenance de sécurité étendue pour Applications n'est pas activée.

141 mises à jour peuvent être appliquées immédiatement.
Pour afficher ces mises à jour supplémentaires, exécuter : apt list --upgradable

3 mises à jour de sécurité supplémentaires peuvent être appliquées avec ESM Apps.
En savoir plus sur l'activation du service ESM Apps at https://ubuntu.com/esm

Last login: Sun Mar 9 17:18:55 2025 from 192.168.1.199
<span class="prompt" id="ssh-prompt">oumou@192.168.1.110:~$ </span><span class="terminal-cursor">&nbsp;</span></pre>
  </div>

  <!-- Conteneur VNC -->
  <div class="container mt-3" id="vnc-container" style="display:none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 id="vnc-connection-info"></h3>
      <button id="vnc-disconnect-btn" class="btn btn-danger">Déconnexion</button>
    </div>
    <div class="toolbar">
      <button title="Envoyer Ctrl+Alt+Del"><i class="fas fa-sync-alt"></i> Ctrl+Alt+Del</button>
      <button title="Plein écran"><i class="fas fa-expand"></i> Plein écran</button>
      <button title="Paramètres"><i class="fas fa-cog"></i> Paramètres</button>
      <select class="form-select form-select-sm" style="width: 150px;">
        <option>Qualité: Élevée</option>
        <option>Qualité: Moyenne</option>
        <option>Qualité: Basse</option>
      </select>
    </div>
    <div class="vnc-screen" id="vnc-screen">
      <!-- Simulated Linux desktop -->
      <div class="desktop-icon">
        <i class="fas fa-folder text-warning"></i>
        <p>Documents</p>
      </div>
      <div class="desktop-icon">
        <i class="fas fa-folder text-primary"></i>
        <p>Téléchargements</p>
      </div>
      <div class="desktop-icon">
        <i class="fas fa-folder text-success"></i>
        <p>Images</p>
      </div>
      <div class="desktop-icon">
        <i class="fas fa-firefox-browser text-danger"></i>
        <p>Firefox</p>
      </div>
      <div class="desktop-icon">
        <i class="fas fa-terminal"></i>
        <p>Terminal</p>
      </div>
      
      <!-- Simulated taskbar -->
      <div class="taskbar">
        <button class="start-button"><i class="fab fa-linux me-1"></i> Menu</button>
        <div class="ms-2 text-white-50">10:24 AM</div>
      </div>
      
      <!-- Simulated window -->
      <div class="window" id="terminal-window">
        <div class="window-header">
          <div>Terminal</div>
          <div>
            <span class="me-2">_</span>
            <span class="me-2">□</span>
            <span>×</span>
          </div>
        </div>
        <div class="window-content">
          <div style="color: white; background: black; padding: 5px; height: 100%; font-family: monospace;">
            <div>Ubuntu 24.04.1 LTS</div>
            <div>oumou@ubuntu:~$ <span class="terminal-cursor">_</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Conteneur noVNC -->
  <div class="container mt-3" id="novnc-container" style="display:none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 id="novnc-connection-info"></h3>
      <button id="novnc-disconnect-btn" class="btn btn-danger">Déconnexion</button>
    </div>
    <div class="novnc-toolbar">
      <div>
        <button class="btn btn-sm btn-dark me-1"><i class="fas fa-power-off"></i></button>
        <button class="btn btn-sm btn-dark me-1"><i class="fas fa-keyboard"></i></button>
        <button class="btn btn-sm btn-dark me-1"><i class="fas fa-clipboard"></i></button>
        <button class="btn btn-sm btn-dark me-1"><i class="fas fa-cog"></i></button>
      </div>
      <div>
        <button class="btn btn-sm btn-dark"><i class="fas fa-expand"></i></button>
      </div>
    </div>
    <div class="novnc-screen" id="novnc-screen">
      <!-- Simulated Windows desktop -->
      <div class="desktop-icon">
        <i class="fas fa-desktop text-primary"></i>
        <p>Ce PC</p>
      </div>
      <div class="desktop-icon">
        <i class="fas fa-trash"></i>
        <p>Corbeille</p>
      </div>
      <div class="desktop-icon">
        <i class="fas fa-globe text-info"></i>
        <p>Microsoft Edge</p>
      </div>
      <div class="desktop-icon">
        <i class="fas fa-file-word text-primary"></i>
        <p>Word</p>
      </div>
      <div class="desktop-icon">
        <i class="fas fa-file-excel text-success"></i>
        <p>Excel</p>
      </div>
      
      <!-- Simulated Windows taskbar -->
      <div class="taskbar">
        <button class="start-button"><i class="fab fa-windows me-1"></i></button>
        <div class="d-flex align-items-center ms-2">
          <i class="fas fa-search me-3"></i>
          <i class="fas fa-globe me-3 text-info"></i>
          <i class="fas fa-file-word me-3 text-primary"></i>
          <i class="fas fa-folder-open me-3 text-warning"></i>
        </div>
        <div class="ms-auto text-white-50">10:24 AM</div>
      </div>
      
      <!-- Simulated window -->
      <div class="window" id="explorer-window">
        <div class="window-header">
          <div>Explorateur de fichiers</div>
          <div>
            <span class="me-2">_</span>
            <span class="me-2">□</span>
            <span>×</span>
          </div>
        </div>
        <div class="window-content">
          <div style="display: flex; align-items: center; gap: 5px; padding-bottom: 5px; border-bottom: 1px solid #ccc;">
            <i class="fas fa-arrow-left"></i>
            <i class="fas fa-arrow-right"></i>
            <i class="fas fa-arrow-up"></i>
            <span style="margin-left: 10px;">Ce PC > Documents</span>
          </div>
          <div style="padding: 10px;">
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
              <i class="fas fa-file-pdf text-danger me-2"></i>
              <span>Rapport_Février.pdf</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
              <i class="fas fa-file-word text-primary me-2"></i>
              <span>Présentation_Projet.docx</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
              <i class="fas fa-file-excel text-success me-2"></i>
              <span>Budget_2025.xlsx</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
              <i class="fas fa-file-image text-info me-2"></i>
              <span>Organigramme.png</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Fonction pour simuler l'interface SSH
    document.getElementById('ssh-form').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const ip = document.getElementById('ssh-ip').value;
      const username = document.getElementById('ssh-username').value;
      
      // Simuler la connexion
      document.body.classList.add('dark-mode');
      document.getElementById('login-view').style.display = 'none';
      document.getElementById('terminal-container').style.display = 'block';
      document.getElementById('terminal').style.display = 'block';
      
      document.getElementById('ssh-conn-details').textContent = username + '@' + ip;
      document.getElementById('ssh-password-prompt').textContent = username + '@' + ip + "'s password: \n";
      document.getElementById('ssh-connection-info').textContent = 'Connecté à ' + username + '@' + ip;
      document.getElementById('ssh-prompt').textContent = username + '@' + ip + ':~$ ';
      
      // Ajouter la possibilité de taper dans le terminal
      let currentInput = '';
      
      document.addEventListener('keydown', function(event) {
        const terminal = document.getElementById('terminal');
        
        if (event.key === 'Enter') {
          // Simuler l'exécution d'une commande
          const lastPrompt = document.getElementById('ssh-prompt').textContent;
          terminal.innerHTML += currentInput + '\n';
          
          // Réponse simulée pour certaines commandes
          if (currentInput === 'ls') {
            terminal.innerHTML += 'Documents  Images  Téléchargements  Vidéos\n';
          } else if (currentInput === 'pwd') {
            terminal.innerHTML += '/home/' + username + '\n';
          } else if (currentInput === 'whoami') {
            terminal.innerHTML += username + '\n';
          } else if (currentInput === 'date') {
            terminal.innerHTML += new Date().toString() + '\n';
          } else if (currentInput.startsWith('echo ')) {
            terminal.innerHTML += currentInput.substring(5) + '\n';
          } else if (currentInput) {
            terminal.innerHTML += '-bash: ' + currentInput.split(' ')[0] + ': commande introuvable\n';
          }
          
          // Nouvelle ligne avec prompt
          terminal.innerHTML += '<span class="prompt" id="ssh-prompt">' + lastPrompt + '</span><span class="terminal-cursor">&nbsp;</span>';
          currentInput = '';
          terminal.scrollTop = terminal.scrollHeight;
        } else if (event.key === 'Backspace') {
          if (currentInput.length > 0) {
            currentInput = currentInput.slice(0, -1);
            const promptText = document.getElementById('ssh-prompt').textContent;
            terminal.innerHTML = terminal.innerHTML.substring(0, terminal.innerHTML.length - currentInput.length - '</span><span class="terminal-cursor">&nbsp;</span>'.length - 1);
            terminal.innerHTML += currentInput + '<span class="terminal-cursor">&nbsp;</span>';
          }
        } else if (!event.ctrlKey && !event.altKey && event.key.length === 1) {
          currentInput += event.key;
          const promptSpan = document.getElementById('ssh-prompt');
          promptSpan.insertAdjacentText('afterend', event.key);
          terminal.scrollTop = terminal.scrollHeight;
        }
      });
      
      // Déconnexion SSH
      document.getElementById('ssh-disconnect-btn').addEventListener('click', function() {
        document.body.classList.remove('dark-mode');
        document.getElementById('login-view').style.display = 'block';
        document.getElementById('terminal-container').style.display = 'none';
        document.getElementById('terminal').style.display = 'none';
      });
    });
    
    // Fonction pour simuler l'interface VNC
    document.getElementById('vnc-form').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const ip = document.getElementById('vnc-ip').value;
      const port = document.getElementById('vnc-port').value;
      
      // Simuler la connexion
      document.body.classList.add('dark-mode');
      document.getElementById('login-view').style.display = 'none';
      document.getElementById('vnc-container').style.display = 'block';
      document.getElementById('vnc-screen').style.display = 'block';
      
      document.getElementById('vnc-connection-info').textContent = 'Connecté à ' + ip + ':' + port + ' (VNC)';
      
      // Simuler des interactions de base
      const desktopIcons = document.querySelectorAll('#vnc-screen .desktop-icon');
      desktopIcons.forEach(icon => {
        icon.addEventListener('click', function() {
          if (icon.querySelector('p').textContent === 'Terminal') {
            document.getElementById('terminal-window').style.display = 'block';
          }
        });
      });
      
      // Déplacer la fenêtre
      const windowHeader = document.querySelector('#terminal-window .window-header');
      let isDragging = false;
      let offsetX, offsetY;
      
      windowHeader.addEventListener('mousedown', function(e) {
        isDragging = true;
        const window = document.getElementById('terminal-window');
        const rect = window.getBoundingClientRect();
        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;
      });
      
      document.addEventListener('mousemove', function(e) {
        if (isDragging) {
          const window = document.getElementById('terminal-window');
          window.style.left = (e.clientX - offsetX) + 'px';
          window.style.top = (e.clientY - offsetY) + 'px';
        }
      });
      
      document.addEventListener('mouseup', function() {
        isDragging = false;
      });
      
      // Fermer la fenêtre
      const closeButton = document.querySelector('#terminal-window .window-header span:last-child');
      closeButton.addEventListener('click', function() {
        document.getElementById('terminal-window').style.display = 'none';
      });
      
      // Déconnexion VNC
      document.getElementById('vnc-disconnect-btn').addEventListener('click', function() {
        document.body.classList.remove('dark-mode');
        document.getElementById('login-view').style.display = 'block';
        document.getElementById('vnc-container').style.display = 'none';
        document.getElementById('vnc-screen').style.display = 'none';
        document.getElementById('terminal-window').style.display = 'none';
      });
    });
    
    // Fonction pour simuler l'interface noVNC
    document.getElementById('novnc-form').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const ip = document.getElementById('novnc-ip').value;
      const port = document.getElementById('novnc-port').value;
      
      // Simuler la connexion
      document.body.classList.add('dark-mode');
      document.getElementById('login-view').style.display = 'none';
      document.getElementById('novnc-container').style.display = 'block';
      document.getElementById('novnc-screen').style.display = 'block';
      
      document.getElementById('novnc-connection-info').textContent = 'Connecté à ' + ip + ':' + port + ' (noVNC)';
      
      // Simuler des interactions de base
      const desktopIcons = document.querySelectorAll('#novnc-screen .desktop-icon');
      desktopIcons.forEach(icon => {
        icon.addEventListener('click', function() {
          if (icon.querySelector('p').textContent === 'Ce PC') {
            document.getElementById('explorer-window').style.display = 'block';
          }
        });
      });
      
      // Déplacer la fenêtre
      const windowHeader = document.querySelector('#explorer-window .window-header');
      let isDragging = false;
      let offsetX, offsetY;
      
      windowHeader.addEventListener('mousedown', function(e) {
        isDragging = true;
        const window = document.getElementById('explorer-window');
        const rect = window.getBoundingClientRect();
        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;
      });
      
      document.addEventListener('mousemove', function(e) {
        if (isDragging) {
          const window = document.getElementById('explorer-window');
          window.style.left = (e.clientX - offsetX) + 'px';
          window.style.top = (e.clientY - offsetY) + 'px';
        }
      });
      
      document.addEventListener('mouseup', function() {
        isDragging = false;
      });
      
      // Fermer la fenêtre
      const closeButton = document.querySelector('#explorer-window .window-header span:last-child');
      closeButton.addEventListener('click', function() {
        document.getElementById('explorer-window').style.display = 'none';
      });
      
      // Déconnexion noVNC
      document.getElementById('novnc-disconnect-btn').addEventListener('click', function() {
        document.body.classList.remove('dark-mode');
        document.getElementById('login-view').style.display = 'block';
        document.getElementById('novnc-container').style.display = 'none';
        document.getElementById('novnc-screen').style.display = 'none';
        document.getElementById('explorer-window').style.display = 'none';
      });
    });
  </script>
</body>
</html>