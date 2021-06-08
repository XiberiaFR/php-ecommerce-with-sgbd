<?php
include('functions.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
logged_only();


if (isset($_POST['check'])) {
    if (!empty($_POST['password_old']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])) {

        if ($_POST['password'] != $_POST['password_confirm']) {
            echo "<script> alert(\"Les mots de passe ne correspondent pas\");</script>";
        } else {
            $query = $pdo->prepare('SELECT * FROM clients WHERE username = :username');
            $query->execute(['username' => $_SESSION['auth']['username']]);
            $user = $query->fetch();
            $user_id = $_SESSION['auth']['id'];

            if (password_verify($_POST['password_old'], $user['mot_de_passe'])) {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $pdo->prepare('UPDATE clients SET mot_de_passe = ? WHERE id = ?')->execute([$password, $user_id]);
                echo "<script> alert(\"Votre mot de passe a bien été mis à jour\");</script>";
            } else {
                echo "<script> alert(\"Le mot de passe que vous venez de taper comme étant le mot de passe actuel ne correspond pas au mot de passe actuellement enregistré sur votre compte\");</script>";
            }
        }
    } else {
        echo "<script> alert(\"Un ou plusieurs champs vides\");</script>";
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

        <form action="" method="POST">

            <div class="from-group">
                <input class="form-control" type="password" name="password_old" placeholder="Inscrivez votre mot de passe actuel">
            </div>

            <div class="from-group">
                <input class="form-control" type="password" name="password" placeholder="Inscrivez votre nouveau mot de passe">
            </div>

            <div class="from-group">
                <input class="form-control" type="password" name="password_confirm" placeholder="Confirmez votre nouveau mot de passe">
            </div>

            <input type="hidden" name="check" value="true">
            <button class="btn btn-primary">Valider mon nouveau mot de passe</button>


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