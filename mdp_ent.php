<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'entreprise') {
    die("Accès refusé.");
}

$host = "4.180.78.195";
$dbname = "stage";
$username = "webuser";
$password = "tresbonmdp";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ancien_mdp = $_POST['ancien'] ?? '';
    $nouveau_mdp = $_POST['nouveau'] ?? '';

    if (empty($ancien_mdp) || empty($nouveau_mdp)) {
        echo "<p style='color:red;text-align:center;'>Veuillez remplir les deux champs.</p>";
    } else {
        $stmt = $pdo->prepare("SELECT mot_de_passe FROM Utilisateur WHERE Id_uti = :id");
        $stmt->execute(['id' => $_SESSION['id']]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($ancien_mdp, $utilisateur['mot_de_passe'])) {
            $nouveau_hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE Utilisateur SET mot_de_passe = :newpass WHERE Id_uti = :id");
            $update->execute([
                'newpass' => $nouveau_hash,
                'id' => $_SESSION['id']
            ]);
            echo "<p style='color:green;text-align:center;'>Mot de passe modifié avec succès.</p>";
        } else {
            echo "<p style='color:red;text-align:center;'>Mot de passe actuel incorrect.</p>";
        }
    }
}
?>

<!doctype html> 
<html lang="fr"> 
   <head> 
      <meta charset="utf-8">
      <meta name="description" content="Postuler à une offre de stage">
      <title>Lebonplan</title>
      <link rel="stylesheet" href="mdp.css">
      <link rel="icon" href="logo_chap.png">
    </head> 
    <body>
    <header style="text-align: center; padding: 20px;">
        <img src="logo.png" alt="Logo" style="width: 500px;"> 
    </header>
    <header>
        <div class="navbar">
            <button class="menu-toggle" id="menu-toggle" aria-label="Ouvrir le menu">&#9776;</button>

            <nav class="nav-items-container">
                <ul class="main-menu" id="main-menu">
                    <li class="menu-item"><a href="accueil_ent.php" class="top-level-entry ">Accueil</a></li>
                    <li class="menu-item"><a href="contact_ent.html" class="top-level-entry">Contact</a></li>
                    <li class="menu-item"><a href="entreprise.php" class="top-level-entry active">Entreprise</a></li>
                    <li class="menu-item"><a href="offre_ent.php" class="top-level-entry">Offre</a></li>
                </ul>

                <div class="auth-links">
                    <a href="index.php" class="button">Déconnexion</a>
                </div>
            </nav>
        </div>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="entreprise.php">Entreprise </a></li>
                <li class="breadcrumb-item"><a href="modifierprofil_ent.php">modifier_mon_profil </a></li>
                <li class="breadcrumb-item"><a href="mdp_ent.php">mot_de_passe </a></li>
            </ol>
        </nav>
     </header>

     <main>
        <form class="password-form" method="POST" action="">
          <input type="password" name="ancien" placeholder="Mot de passe Actuel" required />
          <input type="password" name="nouveau" placeholder="Nouveau mot de passe" required />
          <button type="submit">Valider le mot de passe</button>
        </form>
      </main>

    </body> 
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-column">
          <img src="logo_chap.png" alt="Logo principal" class="footer-logo">
        </div>

        <div class="footer-column">
          <h3>Coordonnées</h3>
          <a  style='color:#ffffff'href="https://www.google.fr/maps/place/Campus+CESI">Immeuble Le Quatrième Zone Aéroportuaire, 34130 Mauguio</a>
          <p><i class="fa-solid fa-envelope"></i> contact@cesi.fr</p>
          <p><i class="fa-solid fa-phone"></i> +33 6 12 34 56 78</p>
        </div>

        <div class="footer-column">
          <h3>Navigation</h3>
          <ul class="footer-links">
            <li><a href="coockies_ent.html">Cookies</a></li>
            <li><a href="faq_ent.html">F.A.Q</a></li>
            <li><a href="cgu_ent.html">Conditions générales</a></li>
            <li><a href="protection_ent.html">Politique de protection des données</a></li>
            <li><a href="mentions_legales_ent.html">Mentions légales</a></li>
          </ul>
        </div>

        <div class="footer-column">
          <h3>Suivez-nous</h3>
          <div class="social-buttons">
            <a class="social-button twitter" href="https://x.com/cesi_officiel" target="_blank"><img class="twitter" src="Twitter.png"></a>
            <a class="social-button tiktok" href="https://www.tiktok.com/@bde_cesi_mtp" target="_blank"><img class="TikTok" src="tiktok.png"></a>
            <a class="social-button instagram" href="https://www.instagram.com/bde.cesi.montpellier" target="_blank"><img class="instagram" src="instagram.png"></a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>Copyright © 2025 - Tous droits réservés. <a href="mentions_legales.html">Mentions légales</a></p>
      </div>
    </footer>
    <script src="menu.js"></script> 
    </html>

