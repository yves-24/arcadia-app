<?php
// Inclure le fichier de connexion à la base de données
include 'php/connection.php';

// Vérifier si un ID d'habitat est passé dans l'URL
if (!isset($_GET['id'])) {
    die("Erreur : aucun habitat spécifié.");
}

$habitat_id = (int) $_GET['id'];

// Récupérer les informations de l'habitat
$query_habitat = $pdo->prepare("SELECT * FROM habitat WHERE id = :id");
$query_habitat->execute(['id' => $habitat_id]);
$habitat = $query_habitat->fetch(PDO::FETCH_ASSOC);

if (!$habitat) {
    die("Erreur : habitat non trouvé.");
}

// Récupérer les animaux associés à cet habitat
$query_animaux = $pdo->prepare("SELECT * FROM animal WHERE habitat_id = :habitat_id");
$query_animaux->execute(['habitat_id' => $habitat_id]);
$animaux = $query_animaux->fetchAll(PDO::FETCH_ASSOC);

// Vérifiez que l'ID de l'animal est passé en paramètre
if (isset($_GET['animal_id'])) {
    $animal_id = intval($_GET['animal_id']);

    // Enregistrer l'interaction
    $query = $pdo->prepare("INSERT INTO interactions (type, cible_id) VALUES ('animal', :cible_id)");
    $query->execute([':cible_id' => $animal_id]);
}




?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Habitat : <?= htmlspecialchars($habitat['nom']) ?> - Zoo Arcadia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php">Zoo Arcadia</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="habitats.php">Habitats</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Section Habitat -->
  <section class="py-5">
    <div class="container">
      <h1><?= htmlspecialchars($habitat['nom']) ?></h1>
      <p><?= htmlspecialchars($habitat['description']) ?></p>
      <img src="<?= htmlspecialchars($habitat['image_url']) ?>" alt="<?= htmlspecialchars($habitat['nom']) ?>" class="img-fluid rounded">
    </div>
  </section>

  <!-- Section Animaux -->
  <section class="py-5 bg-light">
    <div class="container">
      <h2>Animaux dans cet habitat</h2>
      <div class="row">
        <?php foreach ($animaux as $animal): ?>
        <div class="col-md-4">
          <div class="card mb-3">
            <img src="<?= htmlspecialchars($animal['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($animal['prenom']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($animal['prenom']) ?> (<?= htmlspecialchars($animal['race']) ?>)</h5>
              <p class="card-text">État : <?= htmlspecialchars($animal['etat']) ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-center text-white py-3">
    <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
