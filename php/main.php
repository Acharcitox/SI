<?php

// Conexión a la bd
function conexion () {
    $pdo = new PDO('mysql:host=localhost;dbname=inventario', 'root', '');
    return $pdo;
}


// Verfica los datos
function verificar_datos( $filtro, $cadena ) {
    if ( preg_match("/^".$filtro."$/", $cadena) ) {
        return false;
    } else {
        return true;
    }
}

// Limpia cadenas de texto
function limpiar_cadena( $cadena ) {
    $cadena = trim( $cadena );
    $cadena = stripcslashes( $cadena );
    // para evitar inyección sql y ataques xss
    $cadena = str_ireplace( "<script>", "", $cadena );
    $cadena = str_ireplace( "</script>", "", $cadena );
    $cadena = str_ireplace( "<script src>", "", $cadena );
    $cadena = str_ireplace( "<script type>", "", $cadena );
    $cadena = str_ireplace( "SELECT * FROM", "", $cadena );
    $cadena = str_ireplace( "DELETE FROM", "", $cadena );
    $cadena = str_ireplace( "INSERT INTO", "", $cadena );
    $cadena = str_ireplace( "DROP TABLE", "", $cadena );
    $cadena = str_ireplace( "DROP DATABASE", "", $cadena );
    $cadena = str_ireplace( "TRUNCATE TABLE", "", $cadena );
    $cadena = str_ireplace( "SHOW TABLES", "", $cadena );
    $cadena = str_ireplace( "SHOW DATABASES", "", $cadena );
    $cadena = str_ireplace( "<?php", "", $cadena );
    $cadena = str_ireplace( "?>", "", $cadena );
    $cadena = str_ireplace( "--", "", $cadena );
    $cadena = str_ireplace( "^", "", $cadena );
    $cadena = str_ireplace( "<", "", $cadena );
    $cadena = str_ireplace( "[", "", $cadena );
    $cadena = str_ireplace( "]", "", $cadena );
    $cadena = str_ireplace( "==", "", $cadena );
    $cadena = str_ireplace( ";", "", $cadena );
    $cadena = str_ireplace( "::", "", $cadena );
    $cadena = trim( $cadena );
    $cadena = stripcslashes( $cadena );
    return $cadena;
}

// renombrar imagen
function renombrar_imagen( $nombre ) {
    $nombre = str_ireplace( "", "_", $nombre );
    $nombre = str_ireplace( "/", "_", $nombre );
    $nombre = str_ireplace( "#", "_", $nombre );
    $nombre = str_ireplace( "-", "_", $nombre );
    $nombre = str_ireplace( "$", "_", $nombre );
    $nombre = str_ireplace( ".", "_", $nombre );
    $nombre = str_ireplace( ",", "_", $nombre );
    $nombre = $nombre."_".rand(0, 100);
    return $nombre;
}

//Función paginador 
function paginador_tablas( $pagina, $Npaginas, $url, $botones) {
    $tabla = '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagnation">';

    if ( $pagina <= 1 ) {
        $tabla.='<a class="pagination-previous is-disabled" disabled >Anterior</a>
        <ul class="pagination-list">';
    } else {
        $tabla.='<a class="pagination-previous" href="'.$url.($pagina -1).'">Anterior</a>
        <ul class="pagination-list">
            <li><a class="pagination-link" href="'.$url.'1">1</a></li>
            <li><span class="pagination-ellipsis">&hellip</span></li>';
    }

    $c = 0;
    for ( $i = $pagina; $i <= $Npaginas; $i++ ) {

        if ( $c >= $botones ) {
            break;
        }
        if ( $pagina == $i ) {
            $tabla.='<li><a class="pagination-link is-current" href="'.$url.$i.'">'.$i.'</a></li>';
        } else {
            $tabla.='<li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>';
        }

        $c++;
    }

    if ( $pagina == $Npaginas ) {
        $tabla.='<li><span class="pagination-ellipsis">&hellip</span></li>
                 <li><a class="pagination-link" href="'.$url.$Npaginas.'">'.$Npaginas.'</a></li>
            </ul>
            <a class="pagination-next is-disabled" disabled >Siguiente</a>';
    } else {
        $tabla.='</ul>
                <a class="pagination-next" href="'.$url.($pagina +1).'">Siguiente</a>';
    }

    $tabla.= '</nav>';

    return $tabla;
}