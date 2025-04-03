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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = isset($_GET['action']) ? $_GET['action'] : null;
} else {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = isset($data['action']) ? $data['action'] : null;
}

switch ($action) {
    case 'lister':
        $stmt = $pdo->prepare("SELECT a.id_ann AS id_ann, a.titre, a.contenu AS contenu, e.nom_ent AS nom_entreprise
                       FROM annonce a
                       LEFT JOIN entreprise e ON a.id_ent = e.id_ent");

        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'supprimer':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id_ann'])) {
            echo json_encode(["success" => false, "message" => "ID annonce manquant"]);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM annonce WHERE id_ann = ?");
        $success = $stmt->execute([$data['id_ann']]);
        echo json_encode(["success" => $success, "message" => $success ? "Annonce supprimée" : "Erreur lors de la suppression"]);
        break;

    default:
        echo json_encode(["success" => false, "message" => "Action inconnue"]);
        break;
}

