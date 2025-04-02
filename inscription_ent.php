<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'stage';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Champs pour la table Utilisateur
    $nom_contact = htmlspecialchars($_POST['nom']);
    $prenom_contact = htmlspecialchars($_POST['prenom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'entreprise';

    // Champs pour la table Entreprise
    $nom_ent = htmlspecialchars($_POST['nom_ent']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $siret = (int)$_POST['siret'];
    $siren = (int)$_POST['siren'];
    $domaine = htmlspecialchars($_POST['domaine']);

    try {
        // Vérifier si l'email est déjà utilisé
        $stmt = $pdo->prepare("SELECT Id_uti FROM Utilisateur WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo "Erreur : cet email est déjà utilisé.";
            exit;
        }

        // Insertion dans Utilisateur
        $stmt = $pdo->prepare("INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, role) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom_contact, $prenom_contact, $email, $mot_de_passe, $role]);

        $id_utilisateur = $pdo->lastInsertId();

        // Insertion dans Entreprise
        $stmt = $pdo->prepare("INSERT INTO Entreprise (nom_ent, adresse, SIRET, SIREN, domaine_activite, Id_uti)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom_ent, $adresse, $siret, $siren, $domaine, $id_utilisateur]);

        // Création de la session
        $_SESSION['id'] = $id_utilisateur;
        $_SESSION['nom'] = $nom_contact;
        $_SESSION['prenom'] = $prenom_contact;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;

        echo "<script>console.log('Inscription entreprise réussie : " . $_SESSION['id'] . "');</script>";
        echo "Inscription réussie !";
        header("refresh:2;url=accueil_ent.php");
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur lors de l'inscription : " . $e->getMessage();
        exit;
    }
}
?>

