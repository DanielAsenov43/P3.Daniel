<?php
require_once("../PHPScripts/connection.php");
require_once("../PHPScripts/utils.php");
session_start();
//session_destroy();
checkAuth();
error_reporting(E_ERROR);


$products = array();
function addProduct($product) {
    global $products;
    $products[$product->cod] = $product;
}
select("SELECT * FROM producto", fn(object $product) => addProduct($product));

function createProductHTML(Object $product):void {
    $price = formatPrice($product->PVP);
    $cartCodes = array_map(fn($product) => $product->cod, array_values($_SESSION["CART"]));
    $available = !in_array($product->cod, $cartCodes);

    echo "<form class=\"product\" action=".$_SERVER["PHP_SELF"]." method=\"post\">
            <div class=\"top\">
                <div class=\"product-name\">$product->nombre_corto</div>
            </div>
            <div class=\"bottom\">
                <input type=\"hidden\" name=\"code\" value=\"$product->cod\"/>
                <button type=\"submit\" name=\"product-add\"".($available ? "" : "disabled").">Añadir</button>
                <div class=\"product-price\">$price</div>
            </div>
        </form>";
}

function createCartProductHTML(Object $product):void {
    $price = number_format($product->PVP, 2) . "€";
    echo "<tr>
            <form class=\"product\" action=".$_SERVER["PHP_SELF"]." method=\"post\">
                <td class=\"product-name\">$product->nombre_corto</td>
                <td class=\"product-price\">$price</td>
                <input type=\"hidden\" name=\"code\" value=\"$product->cod\"/>
                <td><button type=\"submit\" name=\"product-delete\">Eliminar</td>
            </form>
        </tr>";
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"] ?? null;

    if($code != null && array_key_exists($code, $products)) {
        if(isset($_POST["product-add"])) {
            $_SESSION["CART"][$code] = $products[$code];
        }
        if(isset($_POST["product-delete"])) {
            unset($_SESSION["CART"][$code]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/home.css">
    <title>MitadMarkt</title>
</head>
<body>
    <div class="title">
        <h1>Hola, <?php echo ucfirst($_SESSION["USER"]); ?></h1>
        <a href="../PHPScripts/logout.php">Cerrar sesión</a>
    </div>
    <div class="container">
        <div class="shop-container">
            <h2>Tienda</h2>
            <div class="products">
                <?php
                foreach(array_values($products) as $product) {
                    createProductHTML($product);
                }
                ?>
            </div>
        </div>
        <div class="cart-container">
            <h2>Carrito</h2>
            <?php
            $cart = $_SESSION["CART"];
            if(count($cart) > 0) {
            ?>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th></th>
                </tr>
                <?php
                    $total = 0;
                    foreach($_SESSION["CART"] as $product) {
                        $total += $product->PVP;
                        createCartProductHTML($product);
                    }
                ?>
                <tr>
                    <td class="total-label">Total:</td>
                    <td class="total"><?php echo formatPrice($total); ?></td>
                </tr>
            </table>
            <p>
                <a href="./cart.php" class="continue">Finalizar compra →</a>
            </p>
            <?php
            } else echo "No has elegido ningún producto";
            ?>
        </div>
    </div>

</body>
</html>