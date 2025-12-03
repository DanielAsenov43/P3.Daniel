<?php
//define("DB_HOST", "localhost");
if(basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) die("Acceso denegado");

const DB_HOST = "localhost";
const DB_NAME = "dwes";
const DB_USER = "dwes";
const DB_PASS = "abc123.";

try {
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    $connection = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, $options);
} catch(PDOException $exception) {
    echo $exception->getCode();
    echo "Error en la conexión" . $exception->getMessage();
}
function select(string $query, callable $function, int $mode=PDO::FETCH_OBJ):void {
    global $connection;
    try {
        $result = $connection->query($query);
        while($row = $result->fetch($mode)) $function($row);
    } catch(PDOException $exception) {
        echo "Error al realizar la consulta";
    }
}
function update(string $query):int {
    global $connection;
    try { $affectedRows = $connection->exec($query); }
    catch(PDOException $exception) {
        echo "Error al realizar la actualización";
        $affectedRows = 0;
    }
    return $affectedRows;
}
?>