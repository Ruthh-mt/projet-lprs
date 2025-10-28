<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Créer une formation</title>
</head>
<body>
<h1>Créer une formation</h1>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $k => $v): ?>
            <li><?= htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <label>Nom (max 50) :
        <input type="text" name="nom" maxlength="50" required value="<?= htmlspecialchars($old['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <br><br>
    <button type="submit">Créer</button>
    <a href="FormationRead.php">Annuler</a>
</form>
</body>
</html>
