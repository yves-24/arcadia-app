<?php
// Démarrer la session et inclure la connexion à la base de données
session_start();
include '../php/connection.php';

// Vérifier si l'utilisateur est un vétérinaire
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'veterinaire') {
    header('Location: ../login.php');
    exit;
}

// Initialisation des messages
$message = '';
$error = '';

// Récupérer les animaux pour le menu déroulant
try {
    $query_animals = $pdo->query("SELECT id, prenom FROM animal");
    $animals = $query_animals->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des animaux : " . $e->getMessage();
}

// Récupérer les habitats pour les commentaires
try {
    $query_habitats = $pdo->query("SELECT id, nom FROM habitat");
    $habitats = $query_habitats->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des habitats : " . $e->getMessage();
}

// Ajouter un rapport vétérinaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_report'])) {
    if (!empty($_POST['animal_id']) && !empty($_POST['etat'])) {
        $animal_id = $_POST['animal_id'];
        $etat = $_POST['etat'];
        $nourriture = $_POST['nourriture'] ?? '';
        $detail = $_POST['detail'] ?? '';

        try {
            $query = $pdo->prepare("INSERT INTO rapport_veterinaire (animal_id, date, etat, nourriture, detail) 
                                    VALUES (:animal_id, CURDATE(), :etat, :nourriture, :detail)");
            $query->execute([
                ':animal_id' => $animal_id,
                ':etat' => $etat,
                ':nourriture' => $nourriture,
                ':detail' => $detail
            ]);
            $message = "Rapport ajouté avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout du rapport : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir les champs obligatoires pour le rapport.";
    }
}

// Ajouter un commentaire sur un habitat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    if (!empty($_POST['habitat_id']) && !empty($_POST['commentaire_habitat'])) {
        $habitat_id = $_POST['habitat_id'];
        $commentaire_habitat = $_POST['commentaire_habitat'];

        try {
            $query = $pdo->prepare("INSERT INTO habitat_comments (habitat_id, commentaire_habitat, date) 
                                    VALUES (:habitat_id, :commentaire_habitat, CURDATE())");
            $query->execute([
                ':habitat_id' => $habitat_id,
                ':commentaire_habitat' => $commentaire_habitat
            ]);
            $message = "Commentaire ajouté avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout du commentaire : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir les champs obligatoires pour le commentaire.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Vétérinaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Vétérinaire - Zoo Arcadia</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="../logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Espace Vétérinaire</h1>

        <!-- Affichage des messages -->
        <?php if ($message): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Formulaire pour ajouter un rapport vétérinaire -->
        <div class="card mb-4">
            <div class="card-header">Ajouter un rapport vétérinaire</div>
            <div class="card-body">
                <form action="veterinaire_dashboard.php" method="POST">
                    <div class="mb-3">
                        <label for="animal_id" class="form-label">Animal</label>
                        <select class="form-select" id="animal_id" name="animal_id" required>
                            <option value="">-- Choisir un animal --</option>
                            <?php foreach ($animals as $animal): ?>
                                <option value="<?= $animal['id'] ?>"><?= htmlspecialchars($animal['prenom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="etat" class="form-label">État de santé</label>
                        <input type="text" class="form-control" id="etat" name="etat" required>
                    </div>
                    <div class="mb-3">
                        <label for="nourriture" class="form-label">Nourriture donnée</label>
                        <input type="text" class="form-control" id="nourriture" name="nourriture">
                    </div>
                    <div class="mb-3">
                        <label for="detail" class="form-label">Détails</label>
                        <textarea class="form-control" id="detail" name="detail" rows="3"></textarea>
                    </div>
                    <button type="submit" name="add_report" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>

        <!-- Formulaire pour ajouter un commentaire sur un habitat -->
        <div class="card mb-4">
            <div class="card-header">Ajouter un commentaire sur un habitat</div>
            <div class="card-body">
                <form action="veterinaire_dashboard.php" method="POST">
                    <div class="mb-3">
                        <label for="habitat_id" class="form-label">Habitat</label>
                        <select class="form-select" id="habitat_id" name="habitat_id" required>
                            <option value="">-- Choisir un habitat --</option>
                            <?php foreach ($habitats as $habitat): ?>
                                <option value="<?= $habitat['id'] ?>"><?= htmlspecialchars($habitat['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="commentaire_habitat" class="form-label">Commentaire</label>
                        <textarea class="form-control" id="commentaire_habitat" name="commentaire_habitat" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="add_comment" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-center text-white py-3 mt-4">
        <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
