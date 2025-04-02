<?php
header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uploadDir = 'uploads/'; // Assure-toi que ce dossier existe et est accessible en écriture

    $maxFileSize = 2 * 1024 * 1024; // 2 Mo
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['error'] = 'Erreur lors du téléversement.';
        echo json_encode($response);
        exit;
    }

    if ($file['size'] > $maxFileSize) {
        $response['error'] = 'Le fichier est trop volumineux (max 2 Mo).';
        echo json_encode($response);
        exit;
    }

    $fileType = mime_content_type($file['tmp_name']);
    if ($fileType !== 'application/pdf') {
        $response['error'] = 'Le fichier doit être au format PDF.';
        echo json_encode($response);
        exit;
    }

    $fileName = uniqid('cv_', true) . '.pdf';
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
        $response['success'] = 'Fichier téléversé avec succès ! <a href="' . $uploadDir . $fileName . '" target="_blank">Voir le fichier</a>';
    } else {
        $response['error'] = 'Erreur lors de l\'enregistrement du fichier.';
    }
} else {
    $response['error'] = 'Aucun fichier téléversé.';
}

echo json_encode($response);
?>

