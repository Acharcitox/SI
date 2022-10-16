<?php
    require_once "main.php";

    // Almacena los datos
    $nombre = limpiar_cadena( $_POST['usuario_nombre'] );

    $apellido = limpiar_cadena( $_POST['usuario_apellido'] );

    $usuario = limpiar_cadena( $_POST['usuario_usuario'] );

    $email = limpiar_cadena( $_POST['usuario_email'] );

    $clave_1 = limpiar_cadena( $_POST['usuario_clave_1'] );
    $clave_2 = limpiar_cadena( $_POST['usuario_clave_2'] );

    // Verifica los campos obligatorios
    if ( $nombre == "" || $apellido == "" || $usuario == "" || $clave_1 == "" || $clave_2 == "" ) {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No has llenado todos los campos que son requeridos.
              </div>';
        exit();
    }

    // Verifica integridad de datos
    if ( verificar_datos( "[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre ) ) {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado.
              </div>';
        exit();

    }

    if ( verificar_datos( "[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido ) ) {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El APELLIDO no coincide con el formato solicitado.
              </div>';
        exit();

    }

    if ( verificar_datos( "[a-zA-Z0-9]{4,20}", $usuario ) ) {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El USUARIO no coincide con el formato solicitado.
              </div>';
        exit();

    }

    if ( verificar_datos( "[a-zA-Z0-9$@.-]{7,100}", $clave_1 ) || 
         verificar_datos( "[a-zA-Z0-9$@.-]{7,100}", $clave_2 ) ) {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La CONTRASEÑA no coincide con el formato solicitado.
              </div>';
        exit();

    }

    // Verifica el email

    if ( $email != "" ) {
        if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            $check_email = conexion();
            $check_email = $check_email -> query("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");

            if ( $check_email -> rowCount() > 0 ) {
                
                echo '<div class="notification is-danger is-light">
                        <strong>¡Ocurrió un error inesperado!</strong><br>
                        El CORREO ELECTRÓNICO ingresado ya está registrado.
                      </div>';
                exit();

            }
            
            $check_email = null; // cierra la conexión
        } else {

            echo '<div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    El CORREO ELECTRÓNICO ingresado no es válido.
                 </div>';
            exit();

        }
    }

    // Verifica el usuario único
    
    $check_usuario = conexion();
    $check_usuario = $check_usuario -> query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");

    if ( $check_usuario -> rowCount() > 0 ) {
        
        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El USUARIO ya existe. Por favor, ingrese un usuario diferente.
                </div>';
        exit();

    }
    
    $check_usuario = null; // cierra la conexión

    // verifica las contraseñas
    if ( $clave_1 != $clave_2 ) {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                Las CONTRASEÑAS que ha ingresado no coinciden.
                </div>';
        exit();

    } else {

        $clave = password_hash( $clave_1, PASSWORD_BCRYPT, ["cost" => 10]); // enripta la contraseña

    }

    // Guarda los datos
    $guardar_usuario = conexion();
    $guardar_usuario = $guardar_usuario -> prepare( // método 'prepare' para evitar inyección sql
        "INSERT INTO usuario(usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,usuario_email) 
        VALUES(:nombre,:apellido,:usuario,:clave,:email)");  // marcadores :nombre 
    
    $marcadores = [
        ":nombre" => $nombre,
        ":apellido" => $apellido,
        ":usuario" => $usuario,
        ":clave" => $clave,
        ":email" => $email
    ];
    // ejecuta la consulta
    $guardar_usuario -> execute( $marcadores );
    
    // comprueba si los datos se han registrado en la BD
    if ( $guardar_usuario -> rowCount() == 1 ) {

        echo '<div class="notification is-info is-light">
                <strong>USUARIO REGISTRADO</strong><br>
                El USUARIO se registró con éxito.
                </div>';

    } else {

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No se pudo registrar el usuario. Por favor, intente nuevamente.
                </div>';

    }

    $guardar_usuario = null; // cierra la conexión a la BD
