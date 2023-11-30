<?php
require_once "conexion.php";

function contar_registros($conex, $clavebusqueda){
    if ($clavebusqueda == '' || $clavebusqueda == " ") {
        $sql = "SELECT COUNT(idmuebles) AS cantidad_total FROM inventariomuebles WHERE activo = 1 ORDER BY idmuebles;";
        // die($sql);
        $result = mysqli_query($conex, $sql);
        $fila = mysqli_fetch_assoc($result);
        return $fila['cantidad_total'];
    } else {
        $sql = "SELECT COUNT(idmuebles) AS cantidad_total FROM inventariomuebles WHERE (designacion LIKE '%$clavebusqueda%' OR nomdonante LIKE '%$clavebusqueda%' OR estadoconserv LIKE '%$clavebusqueda%' OR modoadquisicion LIKE '%$clavebusqueda%') AND activo = 1 ORDER BY idmuebles;";
        // die($sql);
        $result = mysqli_query($conex, $sql);
        $fila = mysqli_fetch_assoc($result);
        return $fila['cantidad_total'];
    }
}

function registros_porpagina($conex, $pag, $clavebusqueda){
    if ($clavebusqueda == '' || $clavebusqueda == " ") {
        $sql = "SELECT * FROM inventariomuebles WHERE activo = 1 LIMIT " . ($pag * 5) . ",5";
        $result = mysqli_query($conex, $sql);
        return $result;
    } else {
        $sql = "SELECT * FROM inventariomuebles WHERE (designacion LIKE '%$clavebusqueda%' OR nomdonante LIKE '%$clavebusqueda%' OR estadoconserv LIKE '%$clavebusqueda%' OR modoadquisicion LIKE '%$clavebusqueda%') AND activo = 1 LIMIT " . ($pag * 5) . ",5";
        $result = mysqli_query($conex, $sql);
        return $result;
    }
}
?>
