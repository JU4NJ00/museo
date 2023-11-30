<?php
require_once "conexion.php";

function contar_registros($conex, $clavebusqueda)
{
    if ($clavebusqueda == '' || $clavebusqueda == " ") {
        $sql = "SELECT
            COUNT(inventariomuebles.idmuebles) AS cantidad_total
        FROM inventariomuebles
        JOIN usuarios ON inventariomuebles.usuarios_idusuario = usuarios.idusuario
        WHERE inventariomuebles.activo = 1;";
    } else {
        $sql = "SELECT
            COUNT(inventariomuebles.idmuebles) AS cantidad_total
        FROM inventariomuebles
        JOIN usuarios ON inventariomuebles.usuarios_idusuario = usuarios.idusuario
        WHERE
            (inventariomuebles.designacion LIKE '%$clavebusqueda%'
            OR inventariomuebles.nomdonante LIKE '%$clavebusqueda%'
            OR inventariomuebles.estadoconserv LIKE '%$clavebusqueda%'
            OR inventariomuebles.modoadquisicion LIKE '%$clavebusqueda%'
            OR inventariomuebles.fechaCarga LIKE '%$clavebusqueda%'
            OR inventariomuebles.codigo LIKE '%$clavebusqueda%'
            OR inventariomuebles.fechaing LIKE '%$clavebusqueda%'
            OR usuarios.dni LIKE '%$clavebusqueda%'
            OR usuarios.nombre LIKE '%$clavebusqueda%'
            OR usuarios.apellido LIKE '%$clavebusqueda%')
            AND inventariomuebles.activo = 1;";
    }

    $result = mysqli_query($conex, $sql);
    $fila = mysqli_fetch_assoc($result);
    return $fila['cantidad_total'];
}

function registros_porpagina($conex, $pag, $clavebusqueda)
{
    if ($clavebusqueda == '' || $clavebusqueda == " ") {
        $sql = "SELECT
            inventariomuebles.*,
            usuarios.nombre AS nombre_usuario,
            usuarios.apellido AS apellido_usuario,
            usuarios.dni AS dni_usuario
        FROM inventariomuebles
        JOIN usuarios ON inventariomuebles.usuarios_idusuario = usuarios.idusuario
        WHERE inventariomuebles.activo = 1
        ORDER BY inventariomuebles.fechaCarga DESC
        LIMIT " . ($pag * 5) . ",5;";
    } else {
        $sql = "SELECT
            inventariomuebles.*,
            usuarios.nombre AS nombre_usuario,
            usuarios.apellido AS apellido_usuario,
            usuarios.dni AS dni_usuario
        FROM inventariomuebles
        JOIN usuarios ON inventariomuebles.usuarios_idusuario = usuarios.idusuario
        WHERE
            (inventariomuebles.designacion LIKE '%$clavebusqueda%'
            OR inventariomuebles.nomdonante LIKE '%$clavebusqueda%'
            OR inventariomuebles.estadoconserv LIKE '%$clavebusqueda%'
            OR inventariomuebles.modoadquisicion LIKE '%$clavebusqueda%'
            OR inventariomuebles.fechaCarga LIKE '%$clavebusqueda%'
            OR inventariomuebles.codigo LIKE '%$clavebusqueda%'
            OR inventariomuebles.fechaing LIKE '%$clavebusqueda%'
            OR usuarios.dni LIKE '%$clavebusqueda%'
            OR usuarios.nombre LIKE '%$clavebusqueda%'
            OR usuarios.apellido LIKE '%$clavebusqueda%')
            AND inventariomuebles.activo = 1
        ORDER BY inventariomuebles.fechaCarga DESC
        LIMIT " . ($pag * 5) . ",5;";
    }

    $result = mysqli_query($conex, $sql);
    return $result;
}
?>
