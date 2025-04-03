<?php
header('Content-Type: application/json');
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

// Récupérer l'action soit depuis GET soit depuis POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = isset($_GET['action']) ? $_GET['action'] : null;
} else {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = isset($data['action']) ? $data['action'] : null;
}

switch($action) {
    case 'lister':
        $req = $pdo->prepare("SELECT id_uti, nom, email, role FROM utilisateur");
        $req->execute();
        echo json_encode($req->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'supprimer':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id_uti'])) {
            echo json_encode(["success" => false, "message" => "ID utilisateur manquant"]);
            exit;
        }
        $req = $pdo->prepare("DELETE FROM utilisateur WHERE id_uti = ?");
        $success = $req->execute([$data['id_uti']]);
        echo json_encode(["success" => $success, "message" => $success ? "Utilisateur supprimé" : "Erreur lors de la suppression"]);
        break;

    default:
        echo json_encode(["success" => false, "message" => "Action inconnue"]);
        break;
}