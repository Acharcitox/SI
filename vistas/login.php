<div class="main-container">

    <form class="box login" style="align-content: center; margin-top: 75px;" action="" method="POST" autocomplete="off">
        <h5 class="title is-5 has-text-centered is-uppercase">
            Sistema de Inventario
        </h5>

        <div class="field">
            <label class="label">Usuario</label>
            <div class="control">
                <input class="input" type="text" name="login_usuario"
                pattern="[a-zA-Z0-9]{4, 20}" max_length="20" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Contraseña</label>
            <div class="control">
                <input class="input" type="password" name="login_clave"
                pattern="[a-zA-Z0-9$@.-]{7,100}" max_length="100" required>
            </div>
        </div>

        <p class="has-text-centered mb-4 mt-3">
            <button type="submit" class="button is-info is-rounded">
                Iniciar Sesión
            </button>
        </p>

        <?php 
            // Verifica que las variables login y clave estén definidas y se isncluye los archivos de conexion y funciones
            if ( isset($_POST['login_usuario']) && isset($_POST['login_clave']) ) {

                require_once "./php/main.php"; // conexión bd y funciones contra inyección sql
                require_once "./php/iniciar_sesion.php";
                
            }
        ?>

    </form>

</div>