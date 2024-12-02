<?php
$host = 'patient-shape-9276.internal'; // Hôte Fly.io
$dbname = 'postgres'; // Nom de la base par défaut
$username = 'postgres'; // Utilisateur par défaut
$password = 'GUYZEwmAjJySZ1'; // Mot de passe généré
$port = '5432'; // Port de Postgres

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

