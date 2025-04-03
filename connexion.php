 <?php
session_start();

$host = "4.180.78.195";
$dbname = "stage";
$username = "webuser";
$password = "tresbonmdp";

echo "bonjour";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}
echo "c'est ici";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
echo "c'est la";
        $email = $_POST["email"];
        $password = $_POST["password"];

        $sql = "SELECT id_uti, nom, prenom, role, mot_de_passe FROM Utilisateur WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
echo "plutot ici";
        if ($utilisateur && password_verify($password, $utilisateur['mot_de_passe'])) {
            // On stocke les infos utiles en session
            $_SESSION['id'] = $utilisateur['id_uti'];
            $_SESSION['email'] = $email;
            $_SESSION['nom'] = $utilisateur['nom'];
            $_SESSION['prenom'] = $utilisateur['prenom'];
            $_SESSION['role'] = $utilisateur['role'];

// Redirection selon le rôle
            switch ($utilisateur['role']) {
                case 'etudiant':
             echo "aucune idee frero";
                    header("Location: accueil_etu.php");
                    break;
                case 'entreprise':
             echo "moi non plus";
                    header("Location: accueil_ent.php");
                    break;
                case 'pilote':
             echo "non";
                    header("Location: accueil_etu.php");
                    break;
                case 'admin':
                    header("Location: accueil_admin.php");
                    break;
                default:
                    echo "Rôle inconnu.";
            }

            exit();
        } else {
            echo "Échec de la connexion : email ou mot de passe incorrect.";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}
?>

