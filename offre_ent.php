<?php
session_start();

// ⚠️ Remplacer par la session utilisateur quand le système de login entreprise sera prêt
// $_SESSION['id'] = ...;
// $id_uti = $_SESSION['id'];

// 🔧 Pour les tests, on simule un utilisateur entreprise avec un ID fixe
$id_uti = $_SESSION['id'];

// Connexion BDD
$host = "localhost";
$dbname = "stage";
$username = "root";
$password = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// ✅ Récupération des annonces postées par cette entreprise
$query = "SELECT a.Id_ann, a.titre, a.contenu
          FROM Annonce a
          JOIN Entreprise e ON a.Id_ent = e.Id_ent
          WHERE e.Id_uti = :id_uti";
$stmt = $pdo->prepare($query);
$stmt->execute(['id_uti' => $id_uti]);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!doctype html> 
<html lang="fr"> 
<head> 
  <meta charset="utf-8">
  <meta name="description" content="Postuler à une offre de stage">
  <title>Lebonplan</title>
  <link rel="stylesheet" href="offre_ent.css">
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
        <li class="menu-item"><a href="Offre_ent.php" class="top-level-entry active">Offre</a></li>
      </ul>

      <div class="auth-links">
        <a href="index.php" class="button">Déconnexion</a>
      </div>
    </nav>
  </div>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="Offre_ent.php">Offre</a></li>
    </ol>
  </nav>
  <br>
</header>

<a href="creer_offre.html" class="create-offer-btn">+ Créer une offre</a>

<main>
  <h2>Vos offres</h2>

  <?php if (count($annonces) > 0): ?>
    <?php foreach ($annonces as $annonce): ?>
      <div class="offer-container">
        <h3><?= htmlspecialchars($annonce['titre']) ?></h3>
        <div class="gender-radio-group">
          <p>Homme</p>
          <p>Femme</p>
        </div>
        <p><?= nl2br(htmlspecialchars($annonce['contenu'])) ?></p>
        <button class="btn-delete">
          <a href="supprimer_offre.php?id_ann=<?= $annonce['Id_ann'] ?>" style="color:#ffffff">Supprimer</a>
        </button>
        <button class="btn-edit">
          <a href="creer_offre.php?id_ann=<?= $annonce['Id_ann'] ?>" style="color:#ffffff">Modifier</a>
        </button>
        <button class="btn-stats">
          <a href="stat_ent.php?id_ann=<?= $annonce['Id_ann'] ?>" style="color:#2368e1">Stats</a>
        </button>
        <button class="btn-view">
          <a href="candidature_ent.php?id_ann=<?= $annonce['Id_ann'] ?>" style="color:#2368e1">Voir candidature</a>
        </button>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="text-align: center; font-size: 18px;">Aucune annonce trouvée pour cette entreprise.</p>
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

