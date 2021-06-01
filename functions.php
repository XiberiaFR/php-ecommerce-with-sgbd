<?php

/* Connexion à la BDD */
$dsn = 'mysql:host=localhost;dbname=boutique_en_ligne';
$user = 'jason';
$password = 'testtest';

$pdo = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$queryproductlist = "SELECT * FROM articles";
$productresult = $pdo->query($queryproductlist);
$allarticles = array();
while ($resultatstousarticles = $productresult->fetch()) {
    $allarticles[] = $resultatstousarticles;
}

/* récuperation article selon son ID */

function getArticleFromID($id)
{
    global $allarticles;
    $products = $allarticles;
    foreach ($products as $product) {
        if ($product['id'] == $id) {
            $selectedProduct = $product;
            break;
        }
    }
    return $selectedProduct;
}


/* on génère notre div spécifique à un produit sur la page du produit */
function singleProductPage($product)
{ ?>

    <section class=" mb-5 vh-100 headersection d-flex justify-content-evenly align-items-center flex-column">
        <h1 class="headersection__title"><?= $product['nom'] ?></h1>
        <a href="#theproduct" class="btn btn-warning">Je le personnalise</a>
    </section>

    </header>
    <main class="productpage row mt-3" id="theproduct">
        <section class="product productpage__photo col-md-6">
            <img class="" src="images/<?= $product['image'] ?>" alt="Ours en peluche">
        </section>

        <section class="productpage__details col-md-6">
            <h2 class="productpage__subtitle"><?= $product['nom'] ?><br></h2>
            <p class="productpage__price"><span>Prix</span><br><?= $product['prix'] ?>€</p>

            <p class="productpage__description"><span>Description</span><br><?= $product['description_detaillee'] ?></p>
            <form class="col-md-5 product__cta" action="cart.php" method="POST">
                <input type="hidden" name="productId" value="<?= $product['id'] ?>">
                <input class="mt-3 btn btn-warning" type="submit" value="Je l'adopte">
            </form>
        </section>
    </main>
    <?php }



/* création du panier dans la session, si le panier n'existe pas car $_SESSION['cart'] retourne NULL alors on créé le panier */

function createCart()
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
}

/* on ajoute au panier en vérifiant 
ue le panier existe, s'il existe puis si le produit est déjà présent dans le panier on augmente la quantité, sinon on ajoute le produit*/

function addToCart($product)
{
    createCart();

    $productAlreadyAdded = false;

    for ($i = 0; $i < count($_SESSION['cart']); $i++) {

        if ($_SESSION['cart'][$i]['id'] == $product['id']) {
            echo '<script>alert("Cet ours est déjà présent dans votre panier")</script>';
            $productAlreadyAdded = true;
        }
    }
    if ($productAlreadyAdded == false) {
        $product['quantity'] = 1;
        array_push($_SESSION['cart'], $product);
        echo '<script>alert("Cet ours a bien été ajouté à votre panier")</script>';
    }
}

/* affichage des éléments de la page panier */

function cartPage($pageName)
{
    $itemInCart = $_SESSION['cart'];
    foreach ($itemInCart as $itemToDisplay) { ?>
        <div class="col-md-12 product itemsinbasket__details d-flex align-items-center mb-3 text-center shadow p-3 mb-5 bg-white rounded">
            <img class="col-md-2" src="images/<?= $itemToDisplay['image'] ?>" alt="Ceci est un ours en peluche">
            <p class="itemsinbasket__productname col-md-4"><?= $itemToDisplay['nom'] ?></p>
            <p class="itemsinbasket__price col-md-2"><?= $itemToDisplay['prix'] ?>€ par ours</p>
            <div class="editProduct col-md-4">
                <form class="row" action="<?= $pageName ?>" method="POST">
                    <input type="hidden" name="productIdUpdated" value="<?= $itemToDisplay['id'] ?>">
                    <input class="text-center" type="number" name="newQuantityValue" value="<?= $itemToDisplay['quantity'] ?>">
                    <button type="submit" class="btn btn-warning mt-2">
                        Mettre à jour la quantité
                    </button>
                </form>
                <form class="row mt-2" action="<?= $pageName ?>" method="POST">
                    <input type="hidden" name="productIdUpdated" value="<?= $itemToDisplay['id'] ?>">
                    <input class="text-center btn btn-danger" type="submit" name="deleteProduct" value="Retirer cet ours">
                </form>
            </div>
        </div>
<?php }
}


/* modification de la quantité de l'article présent dans le panier */

function updateQuantity($productId, $newQuantity)
{
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {

        if ($_SESSION['cart'][$i]['id'] == $productId) {
            if ($newQuantity <= $_SESSION['cart'][$i]['stock']) {
                $_SESSION['cart'][$i]['quantity'] = $newQuantity;
                echo "<script> alert(\"Quantité mise à jour avec succès\");</script>";
            } else {
                echo "<script> alert(\"Vous ne pouvez pas commander plus de " . $_SESSION['cart'][$i]['stock'] . " ours\");</script>";
            }
        }
    }
}

/* suppression d'un article dans le panier */

function deleteProduct($productId)
{
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {

        if ($_SESSION['cart'][$i]['id'] == $productId) {
            array_splice($_SESSION['cart'], $i, 1);
        }
    }
}

/* calcul des frais de port */

function deliveryPrice()
{
    $totalQuantity = 0;
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {

        foreach ($_SESSION['cart'] as $product) {
            $totalQuantity += $product['quantity'];
        }
    }
    if ($totalQuantity > 2) {
        return 0;
    } else {
        return 7;
    }
}

/* affichage des frais de port */

function deliveryPriceDisplay()
{
    if (deliveryPrice() == 0) {
        echo "Gratuit";
    } else {
        echo deliveryPrice() . "€";
    }
}

/* calcul du prix total du panier */

function totalAmount()
{
    $total = 0;
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {

        foreach ($_SESSION['cart'] as $product) {
            $total += $product['quantity'] * $product['prix'];
        }
        $total += deliveryPrice();
        echo $total . '€ TTC';
    } else {
        echo "Aucun ours n'est présent dans votre panier";
    }
}

function deleteCart()
{
    $_SESSION['cart'] = array();
}

/* fonction permettant d'afficher les erreurs avec un formatage */

function debug($variable){
    echo '<pre>' . var_dump($variable) . '</pre>';
}

function logged_only() {
    if(!isset($_SESSION['auth'])) {
        header('Location: connexion?=NOTOK.php');
        exit();
    }
}

function random_string($length) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($chars), 0, $length);
}

?>