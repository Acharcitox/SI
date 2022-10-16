<?php

    //Almacena los datos del input cuando inicia sesión un usuario
    $usuario = limpiar_cadena( $_POST['login_usuario'] );
    $clave = limpiar_cadena( $_POST['login_clave'] );

    // Verifica los campos obligatorios
    if ( $usuario == "" || $clave == "" ) {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No has llenado todos los campos que son requeridos.
              </div>';
        exit();

    }

    // Verifica integridad de los datos
    if ( verificar_datos( "[a-zA-Z0-9]{4,20}", $usuario ) ) {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El USUARIO no coincide con el formato solicitado.
              </div>';
        exit();

    }

    if ( verificar_datos( "[a-zA-Z0-9$@.-]{7,100}", $clave ) ) {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La CONTRASEÑA no coincide con el formato solicitado.
              </div>';
        exit();

    }

    // Verifica con la BD que los datos son correctos para iniciar la sesión
    $check_user = conexion();
    $check_user = $check_user -> query("SELECT * FROM usuario WHERE usuario_usuario='$usuario'");

    if ( $check_user -> rowCount() == 1 ) {

        $check_user = $check_user -> fetch(); //fetch permite hacer una array de datos de la bd mediante la consulta anterior

        if ( $check_user['usuario_usuario'] == $usuario && 
        password_verify( $clave, $check_user['usuario_clave'] ) ) { // Con password_verify comparamos la clave del input con la clave en la bd
            
            $_SESSION['id'] = $check_user['usuario_id'];
            $_SESSION['nombre'] = $check_user['usuario_nombre'];
            $_SESSION['apellido'] = $check_user['usuario_apellido'];
            $_SESSION['usuario'] = $check_user['usuario_usuario'];

            if ( headers_sent() ) {

                echo "<script> window.location.href='index.php?vista=home'</script>";

            } else {

                header("Location: index.php?vista=home");
                
            }

        } else {

            echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                Usuario o contraseña incorrectos.
              </div>';

        }

    } else {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                Usuario o contraseña incorrectos.
              </div>';

    }
    
    $check_user = null;
