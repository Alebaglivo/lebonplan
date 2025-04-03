<?php
$host = "4.180.78.195";
$dbname = "stage";
$username = "webuser";
$password = "tresbonmdp";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["success" => false, "message" => "Erreur de connexion à la base de données."]));
}

$id = $_GET['id'];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['contenu'];

    $req = $pdo->prepare("UPDATE annonce SET titre=?, description=? WHERE id_annonce=?");
    $req->execute([$titre, $description, $id]);

    header('Location: gestion_annonces.html');
    exit();
}

$req = $pdo->prepare("SELECT titre, description FROM annonce WHERE id_annonce = ?");
$req->execute([$id]);
$annonce = $req->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Annonce</title>
</head>
<body>
<h2>Modifier l'annonce</h2>

<form method="POST">
    <label>Titre :</label>
    <input type="text" name="titre" value="<?= htmlspecialchars($annonce['titre']) ?>" required><br>

    <label>Description :</label>
    <textarea name="description" required><?= htmlspecialchars($annonce['description']) ?></textarea><br>

    <button type="submit">Enregistrer</button>
</form>
</body>
</html>
