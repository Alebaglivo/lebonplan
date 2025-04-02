<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'entreprise') {
    die("Accès refusé.");
}

$id_uti = $_SESSION['id'];

if (!isset($_GET['id_ann'])) {
    die("ID d'annonce non spécifié.");
}

$id_ann = (int)$_GET['id_ann'];

$host = "localhost";
$dbname = "stage";
$username = "root";
$password = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 🔒 Vérifier que l’annonce appartient bien à l’entreprise connectée
    $stmt = $pdo->prepare("SELECT a.Id_ann
                           FROM Annonce a
                           JOIN Entreprise e ON a.Id_ent = e.Id_ent
                           WHERE a.Id_ann = :id_ann AND e.Id_uti = :id_uti");
    $stmt->execute([
        'id_ann' => $id_ann,
        'id_uti' => $id_uti
    ]);

    if ($stmt->rowCount() === 0) {
        die("Annonce non trouvée ou vous n'êtes pas autorisé à la supprimer.");
    }

    // ✅ Supprimer l’annonce
    $delete = $pdo->prepare("DELETE FROM Annonce WHERE Id_ann = :id_ann");
    $delete->execute(['id_ann' => $id_ann]);

    // Redirection
    header("Location: Offre_ent.php");
    exit();

} catch (PDOException $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
?>

