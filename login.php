<?php
session_start();
include 'php/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        try {
            $query = $pdo->prepare("SELECT * FROM utilisateur WHERE username = :username");
            $query->execute([':username' => $username]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            // Vérification du mot de passe en clair
            if ($user && $password === $user['password']) {
                // Connexion réussie
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];

                // Redirection en fonction du rôle
                if ($user['role'] === 'administrateur') {
                    header('Location: admin/admin_dashboard.php');
                } elseif ($user['role'] === 'employe') {
                    header('Location: employe/employe_dashboard.php');
                } elseif ($user['role'] === 'veterinaire') {
                    header('Location: vet/veterinaire_dashboard.php');
                }
                exit;
            } else {
                // Erreur de connexion
                $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error_message = "Erreur serveur : " . $e->getMessage();
        }
    } else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Connexion</h1>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


