<?php
require_once("../PHPScripts/utils.php");
session_start();
checkAuth();
checkCart();

$error = null;
function validatePostData(string $name, string $errorMessage):mixed {
    $variable = $_POST[$name] ?? null;
    if($variable == null) showError($errorMessage);
    return $variable;
}

function showError(string $errorMessage):void {
    global $error;
    $error = $errorMessage;
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $address = validatePostData("address", "¡La dirección no debe estar vacía!");
    $billingMethod = validatePostData("billing-method", "¡El método de pago no puede estar vacío!");
    if(!$error) {
        $_SESSION["ADDRESS"] = $address;
        $_SESSION["BILLING-METHOD"] = $billingMethod;
        header("Location: ./finished.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/purchase.css">
    <title>MiTienda - Finalizar Compra</title>
</head>

<body>
    <div class="container">
        <h1>Finalizar Compra</h1>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
            <fieldset>
                <legend>Datos adicionales</legend>
                <p>
                    <input type="text" name="address" placeholder="Dirección de envío"/>
                </p>
                <p>
                    <label for="billing-method">Método de pago:</label>
                    <select name="billing-method" required>
                        <option value="mastercard">Mastercard</option>
                        <option value="paypal">Paypal</option>
                    </select>
                </p>
            </fieldset>
            <span id="error-message"><?php if($error) echo $error; ?></span>
            <div class="buttons">
                <a href="./cart.php">← Volver</a>
                <button type="submit" name="submit">Finalizar Compra ✓</button>
            </div>
        </form>
    </div>
</body>

</html>