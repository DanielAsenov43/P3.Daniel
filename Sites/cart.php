<?php
require_once("../PHPScripts/connection.php");
require_once("../PHPScripts/utils.php");
session_start();
checkAuth();
checkCart();

$error = null;

function getCategories():array {
    global $connection, $error;
    $categories = array();
    $query = "SELECT cod, nombre FROM familia";
    try {
        $result = $connection->query($query);
        while($category = $result->fetch(PDO::FETCH_OBJ)) {
            $categories[$category->cod] = $category->nombre;
        }
    } catch(PDOException $exception) {
        $error = "Se ha producido un error al obtener los productos";
    }
    return $categories;
}

$categories = getCategories();

function createRow(string $code, Object $product):void {
    global $categories;
    echo "<tr class=\"popup-trigger\" popup-id=\"$code\">
            <td>$code</td>
            <td>$product->nombre_corto</td>
            <td>".$categories[$product->familia]."</td>
            <td>".formatPrice($product->PVP)."</td>
        </tr>";
}

function createPopup(string $code, Object $product):void {
    global $categories;
    $description = str_replace("\n", "<br/>", $product->descripcion);
    echo "<div class=\"popup\" popup-id=\"$code\">
            <h1>$product->nombre_corto</h1>
            <h2>Código: <span>$code</span></h2>
            <p>Categoría: ".$categories[$product->familia]."</p>
            <div class=\"description-container\">
                <p class=\"description\">$description</p>
            </div>
            <p class=\"price-container\">
                <span class=\"label\">Total:</span>
                <span class=\"price\">".formatPrice($product->PVP)."</span>
            </p>
        </div>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Popup/style.css">
    <link rel="stylesheet" href="../Styles/cart.css">
    <script src="../Popup/script.js"></script>
    <meta name="popup-close" value="../Popup/Icons/close.png">
    <title>MitadMarkt - Carrito</title>
</head>
<body>
    <?php
    foreach($_SESSION["CART"] as $code => $product) createPopup($code, $product);
    ?>
    <div class="container">
        <div class="products-container">
            <h1>Carrito de la compra</h1>
            <h2>Resumen</h2>
            <p class="hint">Haz click sobre un producto para ver detalles</p>
            <div class="table-container">
                <table>
                    <tr class="title">
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                    </tr>
                    <?php
                    $total = 0;
                    foreach($_SESSION["CART"] as $code => $product) {
                        $total += $product->PVP;
                        createRow($code, $product);
                    }
                    ?>
                </table>
            </div>
            <div class="total">
                <span class="label">Total:</span>
                <span class="price"><?php echo formatPrice($total); ?></span>
            </div>
            <span id="error-message"><?php if($error) echo $error; ?></span>
        </div>
        <div class="buttons">
            <a href="./home.php">← Volver</a>
            <a href="./purchase.php">Continuar →</a>
        </div>
    </div>
</body>
</html>