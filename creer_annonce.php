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
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');

    if (empty($titre) || empty($contenu)) {
        echo "<p style='color:red;text-align:center;'>Veuillez remplir tous les champs.</p>";
    } else {
        // 🔍 Récupérer l'Id_ent de l'entreprise connectée
        $stmt = $pdo->prepare("SELECT Id_ent FROM Entreprise WHERE Id_uti = :id_uti");
        $stmt->execute(['id_uti' => $_SESSION['id']]);
        $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$entreprise) {
            echo "<p style='color:red;text-align:center;'>Entreprise introuvable.</p>";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO Annonce (titre, contenu, Id_ent) VALUES (:titre, :contenu, :id_ent)");
                $stmt->execute([
                    ':titre' => $titre,
                    ':contenu' => $contenu,
                    ':id_ent' => $entreprise['Id_ent']
                ]);
               echo "<p style='color:green;text-align:center;'>✅ Annonce publiée avec succès ! Redirection dans 2 secondes...</p>";
echo "<script>setTimeout(() => { window.location.href = 'entreprise.php'; }, 2000);</script>";
exit;

            } catch (PDOException $e) {
                echo "<p style='color:red;text-align:center;'>Erreur lors de la publication : " . $e->getMessage() . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une annonce</title>
    <link rel="stylesheet" href="creer_offre.css">
    <link rel="icon" href="logo_chap.png">
    <style>
        .form-annonce-container {
            max-width: 600px;
            margin: 0 auto;
            background:  url('fond_connexion.png') no-repeat center center;
    	    background-size: cover;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #2684ff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #186ddc;
        }
    </style>
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
            <li class="breadcrumb-item"><a href="entreprise.php">Entreprise</a></li>
            <li class="breadcrumb-item"><a href="creer_annonce.php">Créer une annonce</a></li>
        </ol>
    </nav>
    <br>
</header>

<main class="content">
    <h1 style="text-align: center;">Créer une nouvelle annonce</h1>

    <div class="form-annonce-container">
        <form method="POST" action="">
            <label for="titre">Titre de l'annonce :</label><br>
            <input type="text" id="titre" name="titre" required><br><br>

            <label for="contenu">Contenu / description :</label><br>
            <textarea id="contenu" name="contenu" rows="6" required></textarea><br><br>

            <button type="submit">Publier l'annonce</button>
        </form>
    </div>
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

