
<?php // sesión
    require "./inc/session_start.php"; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "./inc/head.php"; ?>
</head>
<body>
    <?php
        // Verificamos si GET no viene definida o vacía, y por defecto lo enviamos a la vista login
        if (!isset($_GET['vista']) || $_GET['vista'] == "") {
            $_GET['vista'] = "login";
        }
        // Verificamos si existen las vistas que vienen en GET
        if (is_file("./vistas/".$_GET['vista'].".php") 
                && $_GET['vista'] != "login"
                && $_GET['vista'] != "404") {
                    
                    // Cerrar sesión forzada
                    if ( (!isset($_SESSION['id']) || $_SESSION['id'] == "") 
                    || (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == "") ) {

                        include "./vistas/logout.php";
                        exit();

                    }

                    include "./inc/navbar.php";

                    include "./vistas/".$_GET['vista'].".php"; // si existe el archivo cargamos 

                    include "./inc/script.php";

        } else { // si la vista no existe

            if ($_GET['vista'] == "login") {
                include "./vistas/login.php";
            } else {
                include "./vistas/404.php";
            }

        }
        
    ?>
</body>
</html>