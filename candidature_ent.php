<?php
session_start();

// Connexion BDD
$host = "localhost";
$dbname = "stage";
$username = "root";
$password = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$id_ann = isset($_GET['id_ann']) ? (int)$_GET['id_ann'] : 0;

// Récupération des candidatures pour cette annonce
$stmt = $pdo->prepare("SELECT u.nom, u.prenom, e.etablissement, u.descriptif FROM Postuler p JOIN Etudiant e ON p.Id_etu = e.Id_etu JOIN Utilisateur u ON e.Id_uti = u.Id_uti WHERE p.Id_ann = :id_ann");
$stmt->execute(['id_ann' => $id_ann]);
$candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html> 
<html lang="fr"> 
<head> 
  <meta charset="utf-8">
  <meta name="description" content="Statistiques d'une offre">
  <title>Statistiques - Lebonplan</title>
  <link rel="stylesheet" href="stat_ent.css">
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
        <li class="menu-item"><a href="accueil_ent.php" class="top-level-entry">Accueil</a></li>
        <li class="menu-item"><a href="contact_ent.html" class="top-level-entry">Contact</a></li>
        <li class="menu-item"><a href="entreprise.php" class="top-level-entry">Entreprise</a></li>
        <li class="menu-item"><a href="offre_ent.php" class="top-level-entry active">Offre</a></li>
      </ul>
      <div class="auth-links">
        <a href="accueil.html" class="button">Déconnexion</a>
      </div>
    </nav>
  </div>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="offre_ent.php">Offre</a></li>
      <li class="breadcrumb-item"><a href="candidature_ent.php">Candidatures</a></li>
    </ol>
  </nav>
  <br>
</header>
<main>
  <h1 style="text-align:center;">Candidatures pour l'annonce</h1>

  <?php if (count($candidatures) > 0): ?>
    <?php foreach ($candidatures as $c): ?>
      <div class="container">
        <div class="box">
          <h2 class="title">
            <?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?>
          </h2>
          <img src="anakin.png" width="100" height="100" /> <!-- Statique pour l'instant -->
          <p><?= htmlspecialchars($c['etablissement']) ?></p>
          <p><strong>Message au recruteur :</strong></p>
          <p><?= nl2br(htmlspecialchars($c['descriptif'])) ?></p>
          <button class="button"><a href="#">Accepter ></a></button>
          <button class="button"><a href="#">Refuser ></a></button>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="text-align:center;font-size:18px;">Aucune candidature pour cette annonce.</p>
  <?php endif; ?>
</main>
<footer class="footer">
  <div class="footer-container">
    <div class="footer-column">
      <img src="logo_chap.png" alt="Logo principal" class="footer-logo">
    </div>
    <div class="footer-column">
      <h3>Coordonnées</h3>
      <a style='color:#ffffff' href="https://www.google.fr/maps/place/Campus+CESI/">Immeuble Le Quatrième Zone Aéroportuaire, 34130 Mauguio</a>
      <p>contact@cesi.fr</p>
      <p>+33 6 12 34 56 78</p>
    </div>
    <div class="footer-column">
      <h3>Navigation</h3>
      <ul class="footer-links">
        <li><a href="coockies_ent.html">Cookies</a></li>
        <li><a href="faq_ent.html">F.A.Q</a></li>
        <li><a href="cgu_ent.html">Conditions générales</a></li>
        <li><a href="protection_ent.html">Protection des données</a></li>
        <li><a href="mentions_legales_ent.html">Mentions légales</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h3>Suivez-nous</h3>
      <div class="social-buttons">
        <a href="https://x.com/cesi_officiel" target="_blank"><img src="Twitter.png"></a>
        <a href="https://www.tiktok.com/@bde_cesi_mtp" target="_blank"><img src="tiktok.png"></a>
        <a href="https://www.instagram.com/bde.cesi.montpellier" target="_blank"><img src="instagram.png"></a>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 - Tous droits réservés. <a href="mentions_legales.html">Mentions légales</a></p>
  </div>
</footer>
<script src="menu.js"></script> 
</body>
</html>


