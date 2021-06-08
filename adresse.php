<?php
include('functions.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
logged_only();

$user_id = $_SESSION['auth']['id'];
$query = $pdo->prepare('SELECT * FROM adresses WHERE id_client = ?');
$query->execute([$user_id]);
$queryResult = $query->fetch();

if (isset($_POST['check'])) {
    $adresse = $_POST['number'] . " " . $_POST['street'];
    $query = $pdo->prepare('UPDATE adresses SET adresse = ?, code_postal = ?, ville = ? WHERE id_client = ?');
    $query->execute([$adresse, $_POST['zipcode'], $_POST['city'], $user_id]);
    echo "<script> alert(\"Votre adresse a bien été mise à jour\");</script>";
}



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Hold my bear - La boutique des ours en peluche</title>
</head>

<body>

    <header class="container-fluid header">

        <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
            <div class="container collapse navbar-collapse">

                <div class="col-md-3 d-flex justify-content-center">
                    <a class="navbar-brand" href="/">Hold my bear</a>
                </div>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#monMenu" aria-controls="monMenu" aria-label="Menu pour mobile">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="col-md-9 collapse navbar-collapse d-flex justify-content-end" id="monMenu">
                    <ul class="navbar-nav d-flex justify-content-center align-items-center flex-row">
                        <?php if (isset($_SESSION['auth'])) : ?>
                            <li class="p-2 nav-item"><a class="nav-link active" href="compte.php">Votre compte(<?= $_SESSION['auth']['prenom'] . " " . $_SESSION['auth']['nom']; ?>)</a></li>
                            <li class="p-2 nav-item"><a class="nav-link" href="deconnexion.php">Se déconnecter</a></li>
                        <?php else : ?>
                            <li class="p-2 nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
                            <li class="p-2 nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                        <?php endif; ?>
                        <li class="p-2 nav-item">
                            <a class="nav-link" href="cart.php"><i class="navigation__icon fas fa-shopping-basket"></i>Panier</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="mt-5 pt-5">
        <h1>Bonjour <?= $_SESSION['auth']['prenom']; ?></h1>

        <div class="text-center basketamount__userinformations col-md-12 bg-warning pt-2 pb-2 me-2 mb-3">
            <p class="basketamount__userinformationstitle basketpage__title">Votre adresse actuelle</p>
            <p><?= $queryResult['adresse'] . " - " . $queryResult['code_postal'] . " " . $queryResult['ville'] ?></p>
        </div>

        <form action="" method="POST">

            <div class="text-center basketamount__userinformations col-md-12 bg-warning pt-2 pb-2 me-2 mb-3">
                <p class="basketamount__userinformationstitle basketpage__title">Modification de votre adresse</p>
                <input type="text" class="input-text" name="street" placeholder="Nom de rue" minlength="2" maxlength="30" pattern="[A-Za-z -éàâêèç][^0-9]{2,30}" required>
                <input type="text" class="input-text" name="number" placeholder="Numéro de rue" minlength="1" maxlength="4" pattern="[0-9]{1,4}" required>
                <input type="text" class="input-text" name="zipcode" placeholder="Code postal" minlength="5" maxlength="5" pattern="[0-9]{5}" required>
                <input type="text" class="input-text" name="city" placeholder="Ville" minlength="2" maxlength="30" pattern="[A-Za-z -éàâêèç][^0-9]{2,30}" required>
            </div>

            <input type="hidden" name="check" value="true">
            <div class="d-flex justify-content-center">
                <button class="btn btn-primary">Valider ma nouvelle adresse</button>
            </div>

        </form>
    </main>

    <footer class="container-fluid footer p-4 bg-dark text-center text-white mt-5">
        <h3 class="footer__h3">Hold my bear, la référence des ours en peluche made in France.</h3>
    </footer>
    <!-- Script pour chargement de fontawesome-->
    <script src="https://kit.fontawesome.com/a4bf076c8c.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>