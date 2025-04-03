<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    die("Accès non autorisé.");
}

$host = "4.180.78.195";
$dbname = "stage";
$username = "webuser";
$password = "tresbonmdp";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_utilisateur = $_SESSION['id'];
    $role = $_SESSION['role'];

    if ($role === 'etudiant') {
        // Récupérer l'ID de l'étudiant
        $stmt = $pdo->prepare("SELECT Id_etu FROM Etudiant WHERE Id_uti = :id");
        $stmt->execute(['id' => $id_utilisateur]);
        $etu = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($etu) {
            $id_etu = $etu['Id_etu'];

            // Supprimer les dépendances
            $pdo->prepare("DELETE FROM Wishlist WHERE Id_etu = :id")->execute(['id' => $id_etu]);
            $pdo->prepare("DELETE FROM Visiter WHERE Id_etu = :id")->execute(['id' => $id_etu]);
            $pdo->prepare("DELETE FROM Postuler WHERE Id_etu = :id")->execute(['id' => $id_etu]);

            // Supprimer le lien pilote si existant
            $pdo->prepare("DELETE FROM Pilote WHERE Id_etu = :id")->execute(['id' => $id_etu]);

            // Supprimer l'étudiant
            $pdo->prepare("DELETE FROM Etudiant WHERE Id_etu = :id")->execute(['id' => $id_etu]);
        }

        // Supprimer le compte utilisateur
        $pdo->prepare("DELETE FROM Utilisateur WHERE Id_uti = :id")->execute(['id' => $id_utilisateur]);
    }

    elseif ($role === 'pilote') {
        // Supprimer le pilote
        $pdo->prepare("DELETE FROM Pilote WHERE Id_uti = :id")->execute(['id' => $id_utilisateur]);

        // Supprimer le compte utilisateur
        $pdo->prepare("DELETE FROM Utilisateur WHERE Id_uti = :id")->execute(['id' => $id_utilisateur]);
    }

    elseif ($role === 'entreprise') {
        // Récupérer l'ID entreprise
        $stmt = $pdo->prepare("SELECT Id_ent FROM Entreprise WHERE Id_uti = :id");
        $stmt->execute(['id' => $id_utilisateur]);
        $ent = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ent) {
            $id_ent = $ent['Id_ent'];

            // Supprimer les annonces liées
            $pdo->prepare("DELETE FROM Annonce WHERE Id_ent = :id")->execute(['id' => $id_ent]);

            // Supprimer l’entreprise
            $pdo->prepare("DELETE FROM Entreprise WHERE Id_ent = :id")->execute(['id' => $id_ent]);
        }

        // Supprimer le compte utilisateur
        $pdo->prepare("DELETE FROM Utilisateur WHERE Id_uti = :id")->execute(['id' => $id_utilisateur]);
    }

    // Déconnexion
    session_destroy();
    header("Location: index.php?compte_supprime=1");
    exit();

} catch (PDOException $e) {
    die("Erreur lors de la suppression du compte : " . $e->getMessage());
}
?>

