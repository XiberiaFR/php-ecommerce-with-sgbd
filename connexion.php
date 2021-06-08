<?php
include('functions.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['auth'])) {
    header('location: compte?=OK.php');
};





if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $query = $pdo->prepare('SELECT * FROM clients WHERE username = :username OR email = :username');
    $query->execute(['username' => $_POST['username']]);
    $user = $query->fetch();
    if (password_verify($_POST['password'], $user['mot_de_passe'])) {
        $_SESSION['auth'] = $user;
        if ($_POST['remember']) {
            $remember_token = random_string(250);
            $pdo->prepare('UPDATE clients SET remember_token = ? WHERE id = ?')->execute([$remember_token, $user['id']]);
            setcookie('remember', $user['id'] . '==' . $remember_token . sha1($user['id'] . 'arinfo'), time() + 60 * 60 * 24 * 31);
        }
        header('Location: compte?=OK.php');
        exit();
    } else { ?>
        <button class="btn btn-warning">Identifiant ou mot de passe incorrect</button>
<?php }
}


if (isset($_COOKIE['remember']) && !isset($_SESSION['auth'])) {
    $remember_token = $_COOKIE['remember'];
    $parts = explode('==', $remember_token);
    $user_id = $parts[0];
    $query = $pdo->prepare('SELECT * FROM clients WHERE id = ?');
    $query->execute([$user_id]);
    $user = $query->fetch();

    if ($user) {
        $expected = $user_id . '==' . $user['remember_token'] . sha1($user_id . 'arinfo');
        if ($expected == $remember_token) {
            $_SESSION['auth'] = $user;
            setcookie('remember', $remember_token, time() +  60 * 60 * 24 * 31);
            header('Location: compte?=OK.php');
        } else {
            setcookie('remember', null, -1);
        }
    } else {
        setcookie('remember', null, -1);
    }
}


if (strpos($_SERVER['REQUEST_URI'], 'NOTOK') !== false) {
    echo '<div class="mt-5 alert alert-success">Vous devez vous connecter pour accéder à cette page</div>';
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

<body class="mt-5 pt-5">
    <h1>Se connecter</h1>
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
        <form action="" method="POST">
            <div class="form-group">
                <label for="">Pseudo ou email</label>
                <input class="form-control" type="text" name="username" required>
            </div>

            <div class="form-group">
                <label for="">Mot de passe</label>
                <input class="form-control" type="password" name="password" required>
            </div>

            <div class="form-group">
                <label class="p-2">
                    <input type="checkbox" name="remember" value="1">Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Se connecter</button>
            <a class="btn btn-warning" href="inscription.php">Je n'ai pas de compte</a>
        </form>
</body>