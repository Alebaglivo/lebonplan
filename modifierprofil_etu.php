<?php
session_start();

// Connexion BDD
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

if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], ['etudiant', 'pilote'])) {
    die("Accès refusé. Vous devez être connecté en tant qu'étudiant ou pilote.");
}

$id_utilisateur = $_SESSION['id'];

// Récupération des données actuelles
$stmt = $pdo->prepare("SELECT u.nom, u.prenom, u.email, u.descriptif FROM Utilisateur u WHERE u.Id_uti = :id");
$stmt->execute(['id' => $id_utilisateur]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Utilisateur non trouvé.");
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $descriptif = $_POST['descriptif'];

    try {
        // Mettre à jour la table Utilisateur
        $stmtUser = $pdo->prepare("UPDATE Utilisateur SET nom = ?, prenom = ?, email = ?, descriptif = ? WHERE Id_uti = ?");
        $stmtUser->execute([$nom, $prenom, $email, $descriptif, $id_utilisateur]);

        echo "<p style='color:green;'>Profil mis à jour avec succès.</p>";
        header("Refresh:2; url=profil.php");
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le profil</title>
    <link rel="stylesheet" href="inscription_etu.css">
    <link rel="icon" href="logo_chap.png">
</head>
<body>
<header style="text-align: center; padding: 20px;">
    <img src="logo.png" alt="Logo" style="width: 500px;">
    <style>
        .center-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
    </style>
</header>

<header>
    <div class="navbar">
        <button class="menu-toggle" id="menu-toggle" aria-label="Ouvrir le menu">&#9776;</button>

        <nav class="nav-items-container">
            <ul class="main-menu" id="main-menu">
                <li class="menu-item"><a href="accueil_etu.php" class="top-level-entry ">Accueil</a></li>
                <li class="menu-item"><a href="contact_etu.html" class="top-level-entry">Contact</a></li>
                <li class="menu-item"><a href="profil.php" class="top-level-entry active">Profil</a></li>
                <li class="menu-item"><a href="recherche_etu.php" class="top-level-entry">Offre</a></li>
            </ul>

            <div class="auth-links">
                <a href="index.php" class="button">Déconnexion</a>
            </div>
        </nav>
    </div>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="profil.php">Profil</a></li>
            <li class="breadcrumb-item"><a href="modifierprofil_etu.php">Modifier mon profil</a></li>
        </ol>
    </nav>
    <br>
</header>
<body>
<section class="form-section">
<section class="form-section-flou">
    <h1>Modifier votre profil</h1>
    <form method="POST">
        <label>Nom: <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required></label><br>
        <div class="center-buttons">
            <a href="mdp_etu.php" style="text-decoration: none;"><button type="button">Modifier le mot de passe</button></a>
        </div>
        <br>
        <label>Prénom: <input type="text" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required></label><br><br>
        <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required></label><br><br>
        <label>Descriptif:<br>
            <textarea name="descriptif" rows="4" cols="50" placeholder="Parlez de vous..."><?= htmlspecialchars($utilisateur['descriptif'] ?? '') ?></textarea>
        </label><br><br>

        <div class="center-buttons">
            <button type="submit">Enregistrer les modifications</button>
        </div>
    </form>
</section>
</section>
<?php include 'footer.php'; ?>
<script src="menu.js"></script> 
</body>
</html>

