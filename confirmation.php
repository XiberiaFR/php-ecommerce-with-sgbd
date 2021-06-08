<?php

session_start();
include('functions.php');
logged_only();

if (!empty($_POST)) {

    $errors = array();

    saveAddress();
    saveOrder();
    saveOrderProduct();
    if(isset($_POST['reset'])) {
        deleteCart();
    }    
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

<body class="">

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
                            <li class="p-2 nav-item"><a class="nav-link" href="compte.php">Votre compte(<?= $_SESSION['auth']['prenom'] . " " . $_SESSION['auth']['nom']; ?>)</a></li>
                            <li class="p-2 nav-item"><a class="nav-link" href="deconnexion.php">Se déconnecter</a></li>
                        <?php else : ?>
                            <li class="p-2 nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
                            <li class="p-2 nav-item"><a class="nav-link active" href="connexion.php">Connexion</a></li>
                        <?php endif; ?>
                        <li class="p-2 nav-item">
                            <a class="nav-link" href="cart.php"><i class="navigation__icon fas fa-shopping-basket"></i>Panier</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-5 pt-5 row">

        <div class="row">
            <form action="" method="POST">

                <div class="basketamount__userinformations col-md-12 bg-warning pt-2 pb-2 me-2">
                    <p class="basketamount__userinformationstitle basketpage__title border border-warning">Vos informations</p>
                    <input type="text" class="input-text" name="street" placeholder="Nom de rue" minlength="2" maxlength="30" pattern="[A-Za-z -éàâêèç][^0-9]{2,30}" required>
                    <input type="text" class="input-text" name="number" placeholder="Numéro de rue" minlength="1" maxlength="4" pattern="[0-9]{1,4}" required>
                    <input type="text" class="input-text" name="zipcode" placeholder="Code postal" minlength="5" maxlength="5" pattern="[0-9]{5}" required>
                    <input type="text" class="input-text" name="city" placeholder="Ville" minlength="2" maxlength="30" pattern="[A-Za-z -éàâêèç][^0-9]{2,30}" required>
                </div>

                <!-- Button trigger modal -->
                <div class="basketamount__total col text-center border border-success d-flex flex-column justify-content-center">
                    <p class="basketamount__totalprice">Montant des frais de port : <?php deliveryPriceDisplay(); ?></p>
                    <p class="basketamount__totalprice">Total de votre commande : <?php displayTotalAmount(); ?></p>

                    <button type="button" class="basketamount__totalsubmit btn btn-success col-md-12" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Je valide ma commande
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Confirmation de commande</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Félicitations, votre commande a été validée. Elle vous sera livrée d'ici le <?php echo date('d-m-Y', strtotime("+3 days"));; ?>
                                </div>
                                <div class="modal-footer">
                                    <input type="submit" id="reset" name="reset" class="btn btn-primary btn-success" value="C'est compris">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


        </div>
    </main>

    <footer class="container-fluid footer p-4 bg-dark text-center text-white mt-5">
        <h3 class="footer__h3">Hold my bear, la référence des ours en peluche made in France.</h3>
    </footer>
    <!-- Script pour chargement de fontawesome-->
    <script src="https://kit.fontawesome.com/a4bf076c8c.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>