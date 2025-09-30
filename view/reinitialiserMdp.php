<?php


if (!isset($_GET["token"])) {
    die("Token non valide !");
}
$token = $_GET["token"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plus 2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .container {
            max-width: 400px;
            margin-top: 50px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">Changement de Mot de passe</h2>
    <form method="POST" action="../src/treatment/traitementReinitialisation.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <div class="mb-3">
            <label for="mdp" class="form-label">Saisir le nouveau mot de passe</label>
            <input type="password" class="form-control" id="mdp" name="mdp" required>
        </div>
        <div class="mb-3">
            <label for="confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
            <input type="password" class="form-control" id="confirmation" name="confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Confirmer</button>
        <br><br>
    </form>
    <a href="../view/connexion.php">
        <button type="submit" class="btn btn-primary w-100">Acceuil</button>
    </a><br><br>

</div>
</body>
</html>
