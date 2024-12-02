<?php
// Démarrer la session et inclure la connexion à la base de données
session_start();
include '../php/connection.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: ../login.php');
    exit;
}

try {
    // Récupération des statistiques principales
    $total_habitats = $pdo->query("SELECT COUNT(*) AS total FROM habitat")->fetch(PDO::FETCH_ASSOC)['total'];
    $total_animals = $pdo->query("SELECT COUNT(*) AS total FROM animal")->fetch(PDO::FETCH_ASSOC)['total'];
    $total_users = $pdo->query("SELECT COUNT(*) AS total FROM utilisateur WHERE role IN ('employe', 'veterinaire')")->fetch(PDO::FETCH_ASSOC)['total'];
    $total_services = $pdo->query("SELECT COUNT(*) AS total FROM service")->fetch(PDO::FETCH_ASSOC)['total'];

    // Top 5 des animaux les plus consultés
    $query_stats_animals = $pdo->query("
        SELECT a.prenom AS animal_name, COUNT(i.id) AS interactions 
        FROM interactions i 
        JOIN animal a ON i.cible_id = a.id 
        WHERE i.type = 'animal' 
        GROUP BY i.cible_id 
        ORDER BY interactions DESC 
        LIMIT 5
    ");
    $most_consulted_animals = $query_stats_animals->fetchAll(PDO::FETCH_ASSOC);

    // Top 5 des habitats les plus consultés
    $query_stats_habitats = $pdo->query("
        SELECT h.nom AS habitat_name, COUNT(i.id) AS interactions 
        FROM interactions i 
        JOIN habitat h ON i.cible_id = h.id 
        WHERE i.type = 'habitat' 
        GROUP BY i.cible_id 
        ORDER BY interactions DESC 
        LIMIT 5
    ");
    $most_consulted_habitats = $query_stats_habitats->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Erreur : " . $e->getMessage();
}

$query_stats_animals = $pdo->query("
    SELECT a.prenom AS animal_name, COUNT(i.id) AS interactions
    FROM interactions i
    JOIN animal a ON i.cible_id = a.id
    WHERE i.type = 'animal'
    GROUP BY i.cible_id
    ORDER BY interactions DESC
    LIMIT 5
");
$most_consulted_animals = $query_stats_animals->fetchAll(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Admin - Zoo Arcadia</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="admin_services.php">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_habitats.php">Habitats</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_animals.php">Animaux</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_users.php">Utilisateurs</a></li>
					<li class="nav-item"><a class="nav-link" href="admin_reports.php">Rapports Vétérinaires</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="../logout.php">Déconnexion</a></li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-4">
        <h1>Tableau de bord - Zoo Arcadia</h1>

        <!-- Affichage des erreurs -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Statistiques principales -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Habitats</h5>
                        <p class="card-text"><?= $total_habitats ?> Habitats</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Animaux</h5>
                        <p class="card-text"><?= $total_animals ?> Animaux</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Utilisateurs</h5>
                        <p class="card-text"><?= $total_users ?> Utilisateurs</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Services</h5>
                        <p class="card-text"><?= $total_services ?> Services</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des graphiques -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Top 5 des Animaux les Plus Consultés</div>
                    <div class="card-body">
                        <canvas id="chartAnimals"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Top 5 des Habitats les Plus Consultés</div>
                    <div class="card-body">
                        <canvas id="chartHabitats"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-center text-white py-3 mt-4">
        <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>

    <script>
        // Données pour les graphiques
        const animalLabels = <?= json_encode(array_column($most_consulted_animals, 'animal_name')) ?>;
        const animalData = <?= json_encode(array_column($most_consulted_animals, 'interactions')) ?>;
        const habitatLabels = <?= json_encode(array_column($most_consulted_habitats, 'habitat_name')) ?>;
        const habitatData = <?= json_encode(array_column($most_consulted_habitats, 'interactions')) ?>;

        // Configuration du graphique des animaux
        new Chart(document.getElementById('chartAnimals'), {
            type: 'bar',
            data: {
                labels: animalLabels,
                datasets: [{
                    label: 'Interactions',
                    data: animalData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Configuration du graphique des habitats
        new Chart(document.getElementById('chartHabitats'), {
            type: 'pie',
            data: {
                labels: habitatLabels,
                datasets: [{
                    data: habitatData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ]
                }]
            }
        });
    </script>
</body>
</html>
