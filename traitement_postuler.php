<?php
session_start();

if (!isset($_SESSION['id'])) {
    die("Accès interdit : vous devez être connecté.");
}

$host = 'localhost';
$dbname = 'stage';
$username = 'root';
$password = 'root';

// Connexion à la BDD
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur BDD : " . $e->getMessage());
}

// Récupération des données
$id_uti = $_SESSION['id'];
$id_ann = isset($_POST['id_ann']) ? $_POST['id_ann'] : null;
$lettre = isset($_POST['message']) ? substr($_POST['message'], 0, 800) : '';
$cvPath = null;

// Récupération de l'ID étudiant lié à l'utilisateur
$stmt = $pdo->prepare("SELECT Id_etu FROM Etudiant WHERE Id_uti = :id_uti");
$stmt->execute(['id_uti' => $id_uti]);
$etu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$etu) {
    die("Erreur : vous devez être inscrit en tant qu'étudiant pour postuler.");
}

$id_etu = $etu['Id_etu'];

// Upload du CV
if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    $fichierTmp = $_FILES['cv']['tmp_name'];
    $nomFichier = basename($_FILES['cv']['name']);
    $extension = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));
    $tailleMax = 2 * 1024 * 1024; // 2 Mo
    $extAutorisees = ['pdf', 'doc', 'docx', 'odt', 'rtf', 'jpg', 'png'];

    if (!in_array($extension, $extAutorisees)) {
        die("Format de fichier non autorisé.");
    }

    if ($_FILES['cv']['size'] > $tailleMax) {
        die("Fichier trop volumineux.");
    }

    $dossier = "uploads/";
    if (!is_dir($dossier)) {
        mkdir($dossier, 0777, true);
    }

    $nomUnique = uniqid("cv_") . "." . $extension;
    $cheminFinal = $dossier . $nomUnique;

    if (!move_uploaded_file($fichierTmp, $cheminFinal)) {
        die("Erreur lors du téléchargement du fichier.");
    }

    $cvPath = $cheminFinal;
}

// Insertion dans la table Postuler
try {
    $stmt = $pdo->prepare("INSERT INTO Postuler (Id_etu, Id_ann) VALUES (:id_etu, :id_ann)");
    $stmt->execute([
        ':id_etu' => $id_etu,
        ':id_ann' => $id_ann
    ]);

    echo "Votre candidature a été envoyée avec succès.";
    header("refresh:2;url=accueil_etu.html");
    exit();
} catch (PDOException $e) {
    die("Erreur lors de la soumission : " . $e->getMessage());
}
?>

