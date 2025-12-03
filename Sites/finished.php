<?php
require_once("../PHPScripts/utils.php");
session_start();
$_SESSION["CART"] = array();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/finished.css">
    <title>MiTienda - Compra Finalizada</title>
</head>
<body>
    <div class="container">
        <h1>Gracias por su compra</h1>
        <p>Su compra será enviada a <?php echo $_SESSION["ADDRESS"]; ?></p>
        <p>El pago se realizará a traves de <?php echo $_SESSION["BILLING-METHOD"]; ?></p>
        <br/>
        <a href="../index.php">Volver</a>
    </div>
</body>
</html>