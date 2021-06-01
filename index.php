<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}include('functions.php');
if (!empty($_POST['reset'])) {
    deleteCart();
}
$queryproductlist1 = "SELECT * FROM articles WHERE id_gamme = 1 ORDER BY RAND() limit 1";
$queryproductlist2 = "SELECT * FROM articles WHERE id_gamme = 2 ORDER BY RAND() limit 1";
$queryproductlist3 = "SELECT * FROM articles WHERE id_gamme = 3 ORDER BY RAND() limit 1";

try {
    $result1 = $pdo->query($queryproductlist1);
    $result2 = $pdo->query($queryproductlist2);
    $result3 = $pdo->query($queryproductlist3);


    if ($result1 === false) {
        die("Erreur");
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}

$tableauarticles = array();

while ($resultatsarticles = $result1->fetch(PDO::FETCH_ASSOC)) {
    $tableauarticles[] = $resultatsarticles;
}

while ($resultatsarticles = $result2->fetch(PDO::FETCH_ASSOC)) {
    $tableauarticles[] = $resultatsarticles;
}

while ($resultatsarticles = $result3->fetch(PDO::FETCH_ASSOC)) {
    $tableauarticles[] = $resultatsarticles;
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
                    <a class="navbar-brand" href="">Hold my bear</a>
                </div>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#monMenu" aria-controls="monMenu" aria-label="Menu pour mobile">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="col-md-9 collapse navbar-collapse d-flex justify-content-end" id="monMenu">
                <ul class="navbar-nav d-flex justify-content-center align-items-center flex-row">
                        <?php if (isset($_SESSION['auth'])) : ?>
                            <li class="p-2 nav-item"><a class="nav-link" href="compte.php">Votre compte(<?= $_SESSION['auth']['prenom']." ".$_SESSION['auth']['nom']; ?>)</a></li>
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

        <section class="headersection vh-100 d-flex justify-content-evenly align-items-center flex-column">
            <h1 class="headersection__title">La boutique de l'ours en peluche</h1>
            <a href="#main" class="btn btn-warning">J'achète mon ours</a>
        </section>

    </header>

    <main class="container-fluid homepage mt-5 vh-100 d-flex align-items-center" id="main">
        <div class="row d-flex justify-content-around align-items-center">
            <?php
            while (count($tableauarticles) > 0) {
                $product = array_shift($tableauarticles); ?>
                <section class="product col-md-3 text-center shadow p-3 mb-5 bg-white rounded">

                    <article class="product__nameandprice">
                        <h2 class="product__title">Ours <?= $product['nom'] ?></h2>
                        <p><?= $product['prix'] ?>€</p>
                        <img src="images/<?= $product['image'] ?>" alt="Ours en peluche en coton">
                        <p><?= $product['description'] ?></p>
                        <div class="form__container row d-flex justify-content-center">
                            <form class="col-md-7 product__cta" action="product.php" method="POST">
                                <input type="hidden" name="productId" value="<?= $product['id'] ?>">
                                <input class="mt-3 btn btn-warning" type="submit" value="Je le découvre">
                            </form>
                            <form class="col-md-5 product__cta" action="cart.php" method="POST">
                                <input type="hidden" name="productId" value="<?= $product['id'] ?>">
                                <input class="mt-3 btn btn-warning" type="submit" value="Je l'adopte">
                            </form>
                        </div>
                    </article>

                </section>
            <?php } ?>

        </div>
    </main>

    <footer class="footer row d-flex justify-content-center text-center bg-dark mt-5 pt-3 pb-3 text-white">
        <h3 class="footer__h3">Hold my bear, la référence des ours en peluche made in France.</h3>
    </footer>
    <!-- script pour le menu de bootstrap -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script>
        $(function() {
            var navbarNav = $("#navbarNav");
            navbarNav.on("click", "a", null, function() {
                navbarNav.collapse('hide');
            });
        });
    </script>

    <!-- Script pour chargement de fontawesome-->
    <script src="https://kit.fontawesome.com/a4bf076c8c.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>