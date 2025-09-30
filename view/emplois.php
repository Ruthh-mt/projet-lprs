<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once ('../src/bdd/config.php');
$pdo  = (new Config())->connexion();

$sql =$pdo->query("SELECT titre,description,mission,salaire,nom_entreprise,type,etat FROM offre o inner join fiche_entreprise f on o.ref_fiche = f.id_fiche_entreprise");
$sql -> execute();
$offres = $sql -> fetchAll(PDO::FETCH_ASSOC);
var_dump($offres);

?>

}

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

<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light active dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="" class="btn btn-outline-light me-2">Emplois</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="administration.php" class="btn btn-outline-warning me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="col-2 btn-group md-3 me-3 text-end" role="group" aria-label="Boutons utilisateur">
        <?php if (isset($_SESSION['utilisateur'])): ?>
            <a href="account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
            <a href="../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php" class="btn btn-outline-success">Connexion</a>
            <a href="inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>

<body>

<section class="creation-offre">
<div class="card">
    <div class="card-head"><h2>Offres d'emploi</h2></div>
    <div class="table-wrap">
        <table class="table">
            <thead>
            <tr><th>Titre</th><th>Description</th><th>Mission</th><th>Salaire</th><th>Entreprise</th><th>Type d'offre</th><th>Etat</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($offres as $offre): ?>
                    <td><strong><?= htmlspecialchars($offre['titre']) ?></strong></td>
                    <td><?= htmlspecialchars($offre['description']) ?></td>
                    <td><?= htmlspecialchars($offre['mission']) ?></td>
                    <td><?= htmlspecialchars($offre['salaire']) ?></td>
                    <td><?= htmlspecialchars($offre['nom_entreprise']) ?></td>
                    <td><?= htmlspecialchars($offre['type']) ?></td>
                    <td><?= htmlspecialchars($offre['etat']) ?></td>
                <td><a href="">Postuler</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</section>


<br>
<br>
<br>
<br>

<section class="creation-offre">
<?php
$pdo  = (new Config())->connexion();
$sql = $pdo->prepare("Insert INTO offre (titre,description,mission,salaire,type,etat,ref_fiche) VALUES(?,?,?,?,?,?,?)");
$sql->execute([]);
$sqlFicheEntreprises = $pdo->prepare("Select * from fiche_entreprise ");
$sqlFicheEntreprises->execute();
$resultatFiches = $sqlFicheEntreprises->fetchAll();

?>

<!-- Panneau Offre -->
<aside class="sidepanel card">
    <div class="card-head"><h2>Créer une offre d'emploi</h2></div>
    <form class="form" action="" method="post">
     <table>
         <div class="grid two">
             <!-- Titre du poste -->
             <label>
                     <span>Titre du poste</span>
                     <input type="text" name="titre_poste" placeholder="Ex : Caissier(ère)" required>
                 </label>


        <!-- Mission -->
        <label>
            <span>Mission</span>
            <input type="text" name="mission" placeholder="Ex : Faire ... , Réaliser.." required>
            </label>
         </div>


         <div class="">

         <!-- Type de contrat -->
        <label>
            <span>Type de contrat</span>
            <select name="type_contrat" required>
                <option value="CDD">CDD</option>
                <option value="CDI">CDI</option>
                <option value="Stage">Stage</option>
                <option value="Alternance">Alternance</option>
            </select>
            </label>
         </div>


         <div class="grid two">

         <!-- Détails du poste -->
        <label>
            <span>Détails du poste</span>
            <textarea name="detail_poste" rows="4" placeholder="Dé du poste..." required></textarea>
            </label>
         </div>

         <div class="grid two">
             <label>
                 <span>Salaire</span>
                 <textarea name="salaire" rows="4" placeholder="1900..." required></textarea>
             </label>

         <label>
             <select name="entreprises" required>
               <?php foreach ($resultatFiches as $fiche) :?>
                 <option value="<?php echo $fiche['nom_entreprise']; ?>">

                 <?php endforeach ?>

             </select>
         </label>
        <div class="form-actions">
            <button type="reset" class="btn ghost">Annuler</button>
            <button type="submit" class="btn"><i class="bi bi-send"></i> Enregistrer</button>
            </div>
    </form>
</aside>
</section>
