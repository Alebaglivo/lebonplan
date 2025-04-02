<?php
session_start();

// Connexion BDD
$host = "4.180.78.195";
$dbname = "stage";
$username = "root";
$password = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'entreprise') {
    die("Accès refusé. Vous devez être connecté en tant qu'entreprise.");
}

$id_utilisateur = $_SESSION['id'];

// Récupération des données actuelles
$stmt = $pdo->prepare("SELECT e.*, u.nom, u.prenom, u.email FROM Entreprise e JOIN Utilisateur u ON u.Id_uti = e.Id_uti WHERE u.Id_uti = :id");
$stmt->execute(['id' => $id_utilisateur]);
$entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$entreprise) {
    die("Entreprise non trouvée.");
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_ent = $_POST['nom_ent'];
    $adresse = $_POST['adresse'];
    $siret = $_POST['siret'];
    $siren = $_POST['siren'];
    $domaine = $_POST['domaine'];
    $descriptif = $_POST['descriptif'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    try {
        // Mettre à jour la table Utilisateur
        $stmtUser = $pdo->prepare("UPDATE Utilisateur SET nom = ?, prenom = ?, email = ?, descriptif = ? WHERE Id_uti = ?");
        $stmtUser->execute([$nom, $prenom, $email, $descriptif, $id_utilisateur]);

        // Mettre à jour la table Entreprise
        $stmtEnt = $pdo->prepare("UPDATE Entreprise SET nom_ent = ?, adresse = ?, SIRET = ?, SIREN = ?, domaine_activite = ? WHERE Id_uti = ?");
        $stmtEnt->execute([$nom_ent, $adresse, $siret, $siren, $domaine, $id_utilisateur]);

        echo "<p style='color:green;'>Profil mis à jour avec succès.</p>";
        header("Refresh:2; url=entreprise.php");
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Entreprise</title>
    <link rel="stylesheet" href="inscription_etu.css">
    <link rel="icon" href="logo_chap.png">
</head>
<body>
<header style="text-align: center; padding: 20px;">
    <img src="logo.png" alt="Logo" style="width: 500px;">
</header>

<header>
        <div class="navbar">
            <!-- Bouton hamburger -->
            <button class="menu-toggle" id="menu-toggle" aria-label="Ouvrir le menu">&#9776;</button>
            <style>
        .center-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
    </style>
            
            <nav class="nav-items-container">
                <ul class="main-menu" id="main-menu">
                    <li class="menu-item"><a href="accueil_ent.php" class="top-level-entry ">Accueil</a></li>
                    <li class="menu-item"><a href="contact_ent.html" class="top-level-entry">Contact</a></li>
                    <li class="menu-item"><a href="entreprise.php" class="top-level-entry active">Entreprise</a></li>
                    <li class="menu-item"><a href="offre_ent.php" class="top-level-entry">Offre</a></li>
                </ul>

                <!-- Liens de Connexion et S'inscrire à droite -->
                <div class="auth-links">
                    <a href="index.php" class="button">Déconnexion</a>
                </div>
            </nav>
        </div>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="entreprise.php">Entreprise</a></li>
                <li class="breadcrumb-item"><a href="modifierprofil_ent.php">Modifier mon profil</a></li>
            </ol>
        </nav>
        <br>
     </header>
<body>
<section class="form-section">
<section class="form-section-flou">
    <h1>Modifier le profil de votre entreprise</h1>
    <form method="POST">
        <label>Nom de l'entreprise: <input type="text" name="nom_ent" value="<?= htmlspecialchars($entreprise['nom_ent']) ?>" required></label><br>
        <div class="center-buttons">
            <a href="mdp_ent.php" style="text-decoration: none;"><button type="button">Modifier le mot de passe</button></a>
        </div>
        <br>
        <label>Adresse: <input type="text" name="adresse" value="<?= htmlspecialchars($entreprise['adresse']) ?>" required></label><br><br>
        <label>SIRET: <input type="text" name="siret" value="<?= htmlspecialchars($entreprise['SIRET']) ?>" required></label><br><br>
        <label>SIREN: <input type="text" name="siren" value="<?= htmlspecialchars($entreprise['SIREN']) ?>" required></label><br><br>
        <label>Domaine d'activité: <input type="text" name="domaine" value="<?= htmlspecialchars($entreprise['domaine_activite']) ?>" required></label><br><br>
        <label>Nom du contact: <input type="text" name="nom" value="<?= htmlspecialchars($entreprise['nom']) ?>" required></label><br><br>
        <label>Prénom du contact: <input type="text" name="prenom" value="<?= htmlspecialchars($entreprise['prenom']) ?>" required></label><br><br>
        <label>Email de contact: <input type="email" name="email" value="<?= htmlspecialchars($entreprise['email']) ?>" required></label><br><br>
        <label>Descriptif de l'entreprise:<br>
            <textarea name="descriptif" rows="4" cols="50" placeholder="Parlez de votre entreprise..."><?= htmlspecialchars($entreprise['descriptif'] ?? '') ?></textarea>
        </label><br><br>

        <div class="center-buttons">
            <button type="submit">Enregistrer les modifications</button>
        </div>
    
    
    </section>
        </section>
        
        
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
