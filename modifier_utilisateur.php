<?php
$host = "4.180.78.195";
$dbname = "stage";
$username = "webuser";
$password = "tresbonmdp";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT nom, email, role FROM utilisateur WHERE id_uti = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update = $pdo->prepare("UPDATE utilisateur SET nom=?, email=?, role=? WHERE id_uti=?");
    $update->execute([$nom, $email, $role, $id]);

    header('Location: gestion_utilisateurs.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
</head>
<body>
<h2>Modifier l'utilisateur</h2>

<form method="post">
    <label>Nom :</label>
    <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required><br>

    <label>Email :</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

    <label>Rôle :</label>
    <select name="role" required>
        <option <?= $user['role']=='etudiant'?'selected':'' ?> value="etudiant">Étudiant</option>
        <option <?= $user['role']=='pilote'?'selected':'' ?> value="pilote">Pilote</option>
        <option <?= $user['role']=='entreprise'?'selected':'' ?> value="entreprise">Entreprise</option>
        <option <?= $user['role']=='admin'?'selected':'' ?> value="admin">Admin</option>
    </select><br>

    <button type="submit">Enregistrer</button>
</form>

</body>
</html>

