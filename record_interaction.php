<?php
// Inclure la connexion à la base de données
include 'php/connection.php';

// Vérifier si les données POST sont reçues
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'], $_POST['cible_id'])) {
    $type = $_POST['type']; // 'animal' ou 'habitat'
    $cible_id = intval($_POST['cible_id']);

    try {
        // Insérer l'interaction dans la table
        $query = $pdo->prepare("INSERT INTO interactions (type, cible_id, date_interaction) VALUES (:type, :cible_id, NOW())");
        $query->execute([
            ':type' => $type,
            ':cible_id' => $cible_id
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Interaction enregistrée']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
}
