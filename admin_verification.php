<?php
session_start();

// Vérifie si l'utilisateur est bien un administrateur
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false]);
    exit();
}

echo json_encode(['success' => true]);
?>

