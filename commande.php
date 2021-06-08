<?php
include('functions.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
logged_only();

$queryOrderDetails = $pdo->prepare("SELECT * FROM commande_articles as ca INNER JOIN articles as ar ON ar.id = ca.id_article WHERE id_commande = ?");
$queryOrderDetails->execute([($_POST['id'])]);
$orderDetails = $queryOrderDetails->fetchAll();

$queryOrder = $pdo->prepare("SELECT * FROM commandes WHERE numero = ?");
$queryOrder->execute([$_POST['number']]);
$orderResult = $queryOrder->fetch();
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

    <header class="container header">

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
    <main class=" container mt-5 pt-5">
        <h1>Bonjour <?= $_SESSION['auth']['prenom']; ?></h1>
        <h2 class="mt-5 text-center">Détails de votre commande numéro <?= $_POST['number'] ?></h2>

        <section class="mt-5 container d-flex align-items-center flex-column">
            <table class="col-md-12 table table-hover">

                <thead>
                    <tr>
                        <th scope="col">Article</th>
                        <th scope="col">Prix unitaire</th>
                        <th scope="col">Quantité</th>
                        <th scope="col">Montant</th>
                    </tr>
                </thead>
                <?php foreach ($orderDetails as $order) { ?>

                    <tbody>
                        <tr>
                            <td><?= $order['nom'] ?></td>
                            <td><?= $order['prix'] ?>€</td>
                            <td><?= $order['quantite'] ?></td>
                            <td><?php $amount = $order['prix'] * $order['quantite'];
                                echo $amount . "€" ?></td>

                        </tr>
                    </tbody>


                <?php } ?>
            </table>

            <p class="mt-5 mb-3 text-center h5">Montant total de <?= $orderResult['prix'] ?>€ (<?php displayDeliveryPriceOnOrderDetails($orderDetails)?>)</p>
            <a href="compte.php" class="text-center btn btn-warning">Retour à la liste des commandes</a>

        </section>


    </main>

</body>