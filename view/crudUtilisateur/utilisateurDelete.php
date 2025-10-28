<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACCUEIL • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
</head>
<body>
<header
        class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS • ADMIN</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <li class="nav-item"><a href="../forum.php" class="btn btn-outline-light me-2">Forum</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="../administration.php" class="btn btn-outline-warning active me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="col-2 btn-group md-3 me-3 text-end" role="group" aria-label="Boutons utilisateur">
        <?php if (isset($_SESSION['utilisateur'])): ?>
            <a href="../account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
            <a href="../../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
        <?php else: ?>
            <a href="../connexion.php" class="btn btn-outline-success">Connexion</a>
            <a href="../inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>
<nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom text-white bg-dark">
    <div class="nav col mb-2 justify-content-center mb-md-0">
        <div class="btn-group mx-1" role="group" aria-label="Basic example">
            <a href="utilisateurRead.php" class="btn btn-outline-info active">Utilisateur</a>
            <a href="#" class="btn btn-outline-info">Alumni</a>
            <a href="#" class="btn btn-outline-info">Professeur</a>
            <a href="#" class="btn btn-outline-info">Partenaire</a>
            <a href="#" class="btn btn-outline-info">Étudiant</a>
            <a href="../crudFormation/formationRead.php" class="btn btn-outline-info">Formation</a>
            <a href="#" class="btn btn-outline-info">Offre</a>
            <a href="#" class="btn btn-outline-info">Évènement</a>
            <a href="#" class="btn btn-outline-info">Fiche entreprise</a>
            <a href="#" class="btn btn-outline-info">Postuler</a>
            <a href="#" class="btn btn-outline-info">Post</a>
            <a href="#" class="btn btn-outline-info">Réponse</a>
        </div>
    </div>
</nav>







<?php
declare(strict_types=1);
session_start();

if (!function_exists('e')) {
    function e(?string $v): string { return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
}
if (!function_exists('value')) {
    function value($maybeObj, string $prop, $default = null) {
        if (is_array($maybeObj))   return $maybeObj[$prop] ?? $default;
        if (is_object($maybeObj))  return $maybeObj->{$prop} ?? $default;
        return $default;
    }
}

$user       = $user       ?? null;
$csrfToken  = $csrfToken  ?? ($_SESSION['csrf_token'] ?? bin2hex(random_bytes(32)));
$action     = $action     ?? (isset($_SERVER['PHP_SELF']) ? (string)$_SERVER['PHP_SELF'] : '/uilisateur/delete');
$redirect   = $redirect   ?? '/utilisateurs';
$dependencies = $dependencies ?? [];
$isDeletable = $isDeletable ?? true;
$_SESSION['csrf_token'] = $csrfToken;

if (!$user) {
    $user = [
        'id'         => (int)($_GET['id'] ?? 0),
        'email'      => 'inconnu@example.org',
        'first_name' => 'Utilisateur',
        'last_name'  => 'Inconnu',
        'roles'      => ['utilisateur']
    ];
}

$uid        = (int) value($user, 'id', 0);
$email      = (string) value($user, 'email', '');
$firstName  = (string) value($user, 'first_name', '');
$lastName   = (string) value($user, 'last_name', '');
$roles      = value($user, 'roles', []);
$rolesText  = is_array($roles) ? implode(', ', $roles) : (string)$roles;
$isSelf     = isset($_SESSION['user_id']) ? ((int)$_SESSION['user_id'] === $uid) : false;

if ($uid <= 0) { $isDeletable = false; }
if ($isSelf)   { $isDeletable = false; } ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Supprimer un utilisateur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root { --bg:#0f172a; --panel:#111827; --text:#e5e7eb; --muted:#9ca3af; --danger:#ef4444; --warn:#f59e0b; --ok:#22c55e; --border:#334155; }
        * { box-sizing:border-box }
        body { margin:0; font-family:system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; background:var(--bg); color:var(--text); }
        .wrap { max-width:880px; margin:48px auto; padding:0 16px; }
        .card { background:var(--panel); border:1px solid var(--border); border-radius:16px; padding:24px; box-shadow:0 10px 25px rgba(0,0,0,.35); }
        h1 { margin:0 0 12px; font-size:24px; }
        .meta { display:grid; grid-template-columns:180px 1fr; gap:8px 16px; margin:18px 0 6px; }
        .meta div:nth-child(odd){ color:var(--muted) }
        .alert { border:1px solid; border-radius:12px; padding:14px 16px; margin:16px 0; }
        .alert-danger { border-color:var(--danger); background:rgba(239,68,68,.08); }
        .alert-warn   { border-color:var(--warn);   background:rgba(245,158,11,.08); }
        .deps { margin:8px 0 0 0; padding-left:18px; }
        .row { display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
        .grow { flex:1 }
        label { display:block; font-size:14px; color:var(--muted); margin:10px 0 6px; }
        input[type="text"] { width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--border); background:#0b1220; color:var(--text); }
        .seg { display:flex; gap:8px; margin:8px 0 12px; }
        .seg label { border:1px solid var(--border); border-radius:10px; padding:10px 12px; color:var(--text); cursor:pointer; }
        .seg input { margin-right:8px; transform:translateY(1px); }
        .muted { color:var(--muted); font-size:13px; }
        .btns { display:flex; gap:12px; margin-top:18px; }
        .btn { padding:10px 14px; border-radius:10px; border:1px solid var(--border); background:#0b1220; color:var(--text); text-decoration:none; cursor:pointer; font-weight:600; }
        .btn-danger { background:var(--danger); border-color:#b91c1c; color:white; }
        .btn[disabled] { opacity:.5; cursor:not-allowed; }
        .checkbox { display:flex; gap:10px; align-items:flex-start; margin-top:8px; }
        .footnote { margin-top:12px; font-size:12px; color:var(--muted); }
        code { background:#0b1220; border:1px solid var(--border); padding:2px 6px; border-radius:6px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Confirmer la suppression de l’utilisateur</h1>

        <?php if ($isSelf): ?>
            <div class="alert alert-danger"><strong>Action interdite :</strong> vous ne pouvez pas supprimer votre propre compte.</div>
        <?php endif; ?>

        <?php if (!$isDeletable): ?>
            <div class="alert alert-danger">
                <strong>Suppression impossible.</strong> Vérifiez les droits, l’identifiant utilisateur ou les contraintes métiers.
            </div>
        <?php endif; ?>

        <div class="meta">
            <div>Identifiant</div><div>#<?= e((string)$uid) ?></div>
            <div>Nom</div><div><?= e(trim($firstName.' '.$lastName)) ?></div>
            <div>Email</div><div><?= e($email) ?></div>
            <?php if ($rolesText): ?>
                <div>Rôles</div><div><?= e($rolesText) ?></div>
            <?php endif; ?>
        </div>

        <?php if (!empty($dependencies)): ?>
            <div class="alert alert-warn">
                <strong>Dépendances détectées :</strong> cet utilisateur est lié aux éléments suivants :
                <ul class="deps">
                    <?php foreach ($dependencies as $d): ?>
                        <li><?= e((string)$d) ?></li>
                    <?php endforeach; ?>
                </ul>
                <div class="muted">Assurez-vous de transférer/archiver ces éléments ou choisissez la suppression logique.</div>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e($action) ?>" onsubmit="return validateDeleteForm();">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <input type="hidden" name="user_id" value="<?= e((string)$uid) ?>">

            <label>Mode de suppression</label>
            <div class="seg" role="group" aria-label="Mode de suppression">
                <label><input type="radio" name="mode" value="soft" checked> Suppression logique (recommandé)</label>
                <label><input type="radio" name="mode" value="hard"> Suppression définitive</label>
            </div>
            <div class="muted">La suppression logique désactive le compte (conseillée pour conserver l’historique et respecter la traçabilité).</div>

            <div id="hardConfirm" style="display:none;">
                <label>Confirmez l’email pour supprimer définitivement</label>
                <input type="text" name="confirm_email" placeholder="<?= e($email) ?>" autocomplete="off" inputmode="email">
                <div class="footnote">Tapez exactement l’email <code><?= e($email) ?></code> pour activer la suppression définitive.</div>
            </div>

            <div class="checkbox">
                <input id="ack" type="checkbox" name="ack" value="1" required>
                <label for="ack">Je comprends que cette action est irréversible et peut impacter des données liées.</label>
            </div>

            <div class="btns">
                <button class="btn btn-danger" type="submit" name="confirm" value="1" <?= (!$isDeletable ? 'disabled' : '') ?>>Supprimer l’utilisateur</button>
                <a class="btn" href="<?= e($redirect) ?>">Annuler</a>
                <div class="grow"></div>
                <span class="muted">CSRF: <?= e(substr($csrfToken, 0, 8)) ?>…</span>
            </div>

            <!-- Conseils de sécurité -->
            <div class="footnote">
                Astuce : journalisez cette action (table <code>audit_logs</code>) et empêchez la suppression d’un rôle critique (ex. <code>admin</code>) côté contrôleur.
            </div>
        </form>
    </div>
</div>

<script>
    (function(){
        const radios = document.querySelectorAll('input[name="mode"]');
        const hardBox = document.getElementById('hardConfirm');
        function toggle() {
            const val = document.querySelector('input[name="mode"]:checked')?.value;
            hardBox.style.display = (val === 'hard') ? 'block' : 'none';
        }
        radios.forEach(r => r.addEventListener('change', toggle));
        toggle();
    })();

    function validateDeleteForm() {
        const mode = document.querySelector('input[name="mode"]:checked')?.value || 'soft';
        if (mode === 'hard') {
            const expected = "<?= e($email) ?>";
            const entered = document.querySelector('input[name="confirm_email"]')?.value?.trim() || '';
            if (entered !== expected) {
                alert("Pour la suppression définitive, vous devez saisir exactement l'email : " + expected);
                return false;
            }
        }
        return true;
    }
</script>
</body>
</html>
