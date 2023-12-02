<?php
require_once "conexion.php";

function contar_registros($conex, $clavebusqueda)
{
    if ($clavebusqueda == '' || $clavebusqueda == " ") {
        $sql = "SELECT
            COUNT(inventariolibros.idlibro) AS cantidad_total
        FROM inventariolibros
        JOIN usuarios ON inventariolibros.usuarios_idusuario = usuarios.idusuario
        WHERE inventariolibros.activo = 0;";
    } else {
        $sql = "SELECT
            COUNT(inventariolibros.idlibro) AS cantidad_total
        FROM inventariolibros
        JOIN usuarios ON inventariolibros.usuarios_idusuario = usuarios.idusuario
        WHERE
            (inventariolibros.nombre LIKE '%$clavebusqueda%'
            OR inventariolibros.nomdonante LIKE '%$clavebusqueda%'
            OR inventariolibros.estado LIKE '%$clavebusqueda%'
            OR inventariolibros.modoadquisicion LIKE '%$clavebusqueda%'
            OR inventariolibros.fechaCarga LIKE '%$clavebusqueda%'
            OR inventariolibros.codigo LIKE '%$clavebusqueda%'
            OR inventariolibros.fechaingreso LIKE '%$clavebusqueda%'
            OR usuarios.dni LIKE '%$clavebusqueda%'
            OR usuarios.nombre LIKE '%$clavebusqueda%'
            OR usuarios.apellido LIKE '%$clavebusqueda%')
            AND inventariolibros.activo = 0;";
    }

    $result = mysqli_query($conex, $sql);
    $fila = mysqli_fetch_assoc($result);
    return $fila['cantidad_total'];
}

function registros_porpagina($conex, $pag, $clavebusqueda)
{
    if ($clavebusqueda == '' || $clavebusqueda == " ") {
        $sql = "SELECT
            inventariolibros.*,
            usuarios.nombre AS nombre_usuario,
            usuarios.apellido AS apellido_usuario,
            usuarios.dni AS dni_usuario
        FROM inventariolibros
        JOIN usuarios ON inventariolibros.usuarios_idusuario = usuarios.idusuario
        WHERE inventariolibros.activo = 0
        ORDER BY inventariolibros.fechaCarga DESC
        LIMIT ".($pag*5).",5;";
        $result = mysqli_query($conex, $sql);
        return $result;
    } else {
        $sql = "SELECT
            inventariolibros.*,
            usuarios.nombre AS nombre_usuario,
            usuarios.apellido AS apellido_usuario,
            usuarios.dni AS dni_usuario
        FROM inventariolibros
        JOIN usuarios ON inventariolibros.usuarios_idusuario = usuarios.idusuario
        WHERE
            (inventariolibros.nombre LIKE '%$clavebusqueda%'
            OR inventariolibros.nomdonante LIKE '%$clavebusqueda%'
            OR inventariolibros.estado LIKE '%$clavebusqueda%'
            OR inventariolibros.modoadquisicion LIKE '%$clavebusqueda%'
            OR inventariolibros.fechaCarga LIKE '%$clavebusqueda%'
            OR inventariolibros.codigo LIKE '%$clavebusqueda%'
            OR inventariolibros.fechaingreso LIKE '%$clavebusqueda%'
            OR usuarios.dni LIKE '%$clavebusqueda%'
            OR usuarios.nombre LIKE '%$clavebusqueda%'
            OR usuarios.apellido LIKE '%$clavebusqueda%')
            AND inventariolibros.activo = 0
        ORDER BY inventariolibros.fechaCarga DESC
        LIMIT " . ($pag * 5) . ",5;";
        $result = mysqli_query($conex, $sql);
        return $result;
    }
}
?>
