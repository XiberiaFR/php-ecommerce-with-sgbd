<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('functions.php');


if (!empty($_POST)) {

    $errors = array();

    if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
        $errors['username'] = "Pseudo invalide, il ne peut être vide et ne peut pas contenir de caractères spéciaux";
    } else {
        $query = $pdo->prepare('SELECT id FROM clients WHERE username = ?');
        $query->execute([$_POST['username']]);
        $user = $query->fetch();
        if ($user) {
            $errors['username'] = 'Ce pseudo est déjà utilisé';
        }
    }

    if (empty($_POST['street']) || !preg_match('/^[A-Za-z -éàâêèç][^0-9]{2,30}+$/', $_POST['street'])) {
        $errors['street'] = "Nom de rue invalide";
    }

    if (empty($_POST['number']) || !preg_match('/^[0-9]{1,4}+$/', $_POST['number'])) {
        $errors['number'] = "Numéro de rue invalide";
    }

    if (empty($_POST['zipcode']) || !preg_match('/^[0-9]{5}+$/', $_POST['zipcode'])) {
        $errors['zipcode'] = "Code postal invalide";
    }

    if (empty($_POST['city']) || !preg_match('/^[A-Za-z -éàâêèç][^0-9]{2,30}+$/', $_POST['city'])) {
        $errors['city'] = "La ville est incorrecte";
    }

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'adresse email est incorrecte";
    } else {
        $query = $pdo->prepare('SELECT id FROM clients WHERE email = ?');
        $query->execute([$_POST['email']]);
        $user = $query->fetch();
        if ($user) {
            $errors['email'] = 'Cet email est déjà associé à un autre compte';
        }
    }

    if (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']) {
        $errors['password'] = "Le mot de passe de confirmation ne correspond pas au mot de passe initial";
    }

    if (empty($errors)) {
        $query = $pdo->prepare("INSERT INTO clients SET username = ?, nom = ?, prenom = ?, email = ?, mot_de_passe = ?");
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $query->execute([$_POST['username'], $_POST['family_name'], $_POST['first_name'], $_POST['email'], $password]);
        $clientId = $pdo->lastInsertId();
        $query = $pdo->prepare("INSERT INTO adresses SET id_client = ?, adresse = ?, code_postal = ?, ville = ?");
        $adresse = $_POST['number'] . " " . $_POST['street'];
        $query->execute([$clientId, $adresse, $_POST['zipcode'], $_POST['city']]);
        die('Compte créé avec succès');
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
                            <li class="p-2 nav-item"><a class="nav-link" href="compte.php">Votre compte(<?= $_SESSION['auth']['prenom'] . " " . $_SESSION['auth']['nom']; ?>)</a></li>
                            <li class="p-2 nav-item"><a class="nav-link" href="deconnexion.php">Se déconnecter</a></li>
                        <?php else : ?>
                            <li class="p-2 nav-item"><a class="nav-link active" href="inscription.php">Inscription</a></li>
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

    <main class="mt-5 pt-5 container d-flex flex-column align-items-center justify-content-center">
        <h1>S'inscrire</h1>
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <p>Le formulaire contient des erreurs.</p>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            </div>
            <form class="d-flex flex-wrap justify-content-center align-items-center" action="" method="POST">

                <div class="form-group col-md-5 m-3">
                    <label for="">Pseudo</label>
                    <input class="form-control" type="text" name="username" required>
                </div>

                <div class="form-group col-md-5 m-3">
                    <label for="">Prénom</label>
                    <input class="form-control" type="text" name="first_name" required>
                </div>

                <div class="form-group col-md-5 m-3">
                    <label for="">Nom</label>
                    <input class="form-control" type="text" name="family_name" required>
                </div>

                <div class="form-group col-md-5 m-3">
                    <label for="">Nom de rue</label>
                    <input type="text" class="form-control" name="street" minlength="2" maxlength="30" pattern="[A-Za-z -éàâêèç][^0-9]{2,30}" required>
                </div>

                <div class="form-group col-md-5 m-3">
                    <label for="">Numéro de rue</label>
                    <input type="text" class="form-control" name="number" minlength="1" maxlength="4" pattern="[0-9]{1,4}" required>
                </div>

                <div class="form-group col-md-5 m-3">
                    <label for="">Code postal</label>
                    <input type="text" class="form-control" name="zipcode" minlength="5" maxlength="5" pattern="[0-9]{5}" required>
                </div>

                <div class="form-group col-md-5 m-3">
                    <label for="">Ville</label>
                    <input type="text" class="form-control" name="city" minlength="2" maxlength="30" pattern="[A-Za-z -éàâêèç][^0-9]{2,30}" required>
                </div>

                <div class="form-group col-md-5 m-3">
                    <label for="">Email</label>
                    <input class="form-control" type="email" name="email" required>
                </div>

                <div class="form-group col-md-5 m-3">
                    <label for="">Mot de passe</label>
                    <input class="form-control" type="password" name="password" required>
                </div>

                <div class="form-group col-md-5 m-3">
                    <label for="">Confirmez votre mot de passe</label>
                    <input class="form-control" type="password" name="password_confirm" required>
                </div>

                <button type="submit" class="col-md-3 btn btn-primary mt-3">M'inscrire</button>

            </form>
    </main>

    <footer class="footer row d-flex justify-content-center text-center bg-dark mt-5 pt-3 pb-3 text-white">
        <h3 class="footer__h3">Hold my bear, la référence des ours en peluche made in France.</h3>
    </footer>