<?php

function checkAuth():void {
    if(!isset($_SESSION["USER"])) {
        header("Location: ../index.php");
        exit();
    }
}

function checkCart():void {
    if(count($_SESSION["CART"]) <= 0) {
        header("Location: ./home.php");
        exit();
    }
}
function formatPrice(float $price):string {
    return number_format($price, 2) . "€";
}

?>