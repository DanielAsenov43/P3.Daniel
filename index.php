<?php

// Comentario de prueba

require_once("./PHPScripts/connection.php");
session_start();
if(isset($_SESSION["USER"])) {
    //unset($_SESSION["USER"]); // Debug
    header("Location: ./Sites/home.php");
    exit();
}

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

function checkAuth(string $user, string $pass):bool {
    global $connection;
    $hashedPass = hash("md5", $pass); // Se puede encriptar en sha256, que es más seguro
    $query = "SELECT usuario, contrasena FROM usuarios WHERE usuario LIKE :user AND contrasena LIKE :pass";
    $statement = $connection->prepare($query);
    try {
        $success = $statement->execute([
            ":user" => $user,
            ":pass" => $hashedPass
        ]);
        if($success) {
            $results = $statement->fetchAll();
            return count($results) >= 1;
        }
    } catch(PDOException $exception) {
        echo "Ha surgido un error interno a la hora de verificar las credenciales";
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MitadMarkt - Iniciar Sesión</title>
    <link rel="stylesheet" href="./Styles/index.css">
    <script src="./Scripts/script.js"></script>
</head>
<body>
    <div class="container">
        <h1>MitadMarkt</h1>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
            <fieldset>
                <legend>Iniciar Sesión</legend>
                <p>
                    <input type="text" name="user" placeholder="Usuario"/>
                </p>
                <p>
                    <input type="password" name="pass" placeholder="Contraseña"/>
                </p>
                <p>
                    <button type="submit" name="login">Acceder</button>
                </p>
            </fieldset>
        </form>
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
            $errorMessage = "";
            $user = validatePostData("user", "¡El usuario no puede estar vacío!");
            $pass = validatePostData("pass", "¡La contraseña no puede estar vacía!");

            $user = strtolower($user);
            
            if(!$error) {
                if(checkAuth($user, $pass)) {
                    $_SESSION["USER"] = $user;
                    $_SESSION["CART"] = array();
                    echo "$user -> $pass";
                    // no necesitamos guardar la contraseña en la sesión
                    header("Location: ./Sites/home.php");
                } else {
                    // Podemos ser menos ambiguos pero sería un mayor riesgo de seguridad
                    showError("¡El usuario o la contraseña no son correctos!");
                }
            }
        }
        ?>
        <span id="error-message"><?php if($error) echo $error; ?></span>
    </div>
    
</body>
</html>