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

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $civilite = $_POST['title'];
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'pilote';

    try {
        // Vérification de l'unicité de l'email
        $stmt = $pdo->prepare("SELECT Id_uti FROM Utilisateur WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo "<p style='color:red;text-align:center;'>Erreur : cet email est déjà utilisé.</p>";
            exit;
        }

        // Insertion dans la table Utilisateur
        $stmt = $pdo->prepare("INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, role) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $role]);

        // Connexion de session
        $_SESSION['id'] = $pdo->lastInsertId();
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;

        echo "<p style='color:green;text-align:center;'>Inscription réussie ! Redirection...</p>";
        header("Refresh:2; url=accueil_etu.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur lors de l'inscription : " . $e->getMessage() . "</p>";
    }
}
?>

