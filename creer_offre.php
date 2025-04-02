<?php
session_start();
$_SESSION['id_uti'] = 1; // À remplacer par la session réelle plus tard
$id_uti = $_SESSION['id_uti'];

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

$titre = "Nom de l'offre";
$contenu = "Description de l'offre...";
$success = "";
$error = "";

$id_ann = isset($_GET['id_ann']) ? (int)$_GET['id_ann'] : null;

if ($id_ann !== null) {
    // Mise à jour si formulaire envoyé
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');

        if (!empty($titre) && !empty($contenu)) {
            $stmt = $pdo->prepare("UPDATE Annonce SET titre = :titre, contenu = :contenu WHERE Id_ann = :id_ann");
            $stmt->execute([
                'titre' => $titre,
                'contenu' => $contenu,
                'id_ann' => $id_ann
            ]);
            $success = "Offre modifiée avec succès !";
        } else {
            $error = "Tous les champs doivent être remplis.";
        }
    }

    // Récupération des valeurs actuelles
    $stmt = $pdo->prepare("SELECT titre, contenu FROM Annonce WHERE Id_ann = :id_ann");
    $stmt->execute(['id_ann' => $id_ann]);
    $annonce = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($annonce) {
        $titre = $annonce['titre'];
        $contenu = $annonce['contenu'];
    } else {
        $error = "Offre introuvable.";
    }
}
?>
<!doctype html> 
<html lang="fr"> 
<head> 
  <meta charset="utf-8">
  <meta name="description" content="Postuler à une offre de stage">
  <title>Lebonplan</title>
  <link rel="stylesheet" href="creer_offre.css">
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
        <li class="menu-item"><a href="entreprise.php" class="top-level-entry">Entreprise</a></li>
        <li class="menu-item"><a href="offre_ent.php" class="top-level-entry active">Offre</a></li>
      </ul>
      <div class="auth-links">
        <a href="index.php" class="button">Déconnexion</a>
      </div>
    </nav>
  </div>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="offre_ent.php">Offre</a></li>
      <li class="breadcrumb-item"><a href="creer_offre.php">Modifier une offre</a></li>
    </ol>
  </nav>
  <br>
</header>

<main>
  <h2><?= $id_ann ? 'Modifier votre offre de stage' : 'Créer votre offre de stage' ?></h2>

  <?php if ($success): ?>
    <p style="color: green; text-align: center;"><?= $success ?></p>
  <?php elseif ($error): ?>
    <p style="color: red; text-align: center;"><?= $error ?></p>
  <?php endif; ?>

  <div class="offer-container">
    <form method="POST">
      <p id="Offre" contenteditable="true"
         style="border: 1px dashed #ccc; padding: 10px;font-size: 1.5em;"
         onfocus="if(this.textContent.trim() === 'Nom de l\'offre') this.textContent = '';"
         onblur="if(this.textContent.trim() === '') this.textContent = 'Nom de l\'offre';">
        <?= htmlspecialchars($titre) ?>
      </p>

      <p id="description" contenteditable="true"
         style="border: 1px dashed #ccc; padding: 10px;"
         onfocus="if(this.textContent.trim() === 'Description de l\'offre...') this.textContent = '';"
         onblur="if(this.textContent.trim() === '') this.textContent = 'Description de l\'offre...';">
        <?= htmlspecialchars($contenu) ?>
      </p>

      <!-- Champs cachés pour synchroniser avec le formulaire -->
      <input type="hidden" name="titre" id="hidden-titre" value="">
      <input type="hidden" name="contenu" id="hidden-description" value="">

      <button type="submit" class="btn-delete" onclick="beforeSubmit()">Publier</button>
    </form>
  </div>
</main>

<script>
  function beforeSubmit() {
    document.getElementById('hidden-titre').value = document.getElementById('Offre').innerText.trim();
    document.getElementById('hidden-description').value = document.getElementById('description').innerText.trim();
  }
</script>

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

