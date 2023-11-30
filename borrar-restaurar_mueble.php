<!-- ssssssssssssssssssssssssssssssssssss -->
<?php
session_start();

if (isset($_SESSION["dniadmin"])) {
    header("location:inicio_admin.php");
} elseif (isset($_SESSION["dniencargado"])) {
    header("location:inicio_encargado.php");
} else {
    header("location:index.php");
}

require_once "conexion.php";

$id = $_POST['idmuebles'];
$_SESSION['ids'] = $id;

// Obtener el nombre del archivo de imagen antes de realizar la operación de borrado
$sql_select = "SELECT nomImg FROM inventariomuebles WHERE idmuebles=$id";
$result_select = mysqli_query($conex, $sql_select);

if ($row = mysqli_fetch_assoc($result_select)) {
    $nomImg = $row['nomImg'];

    // Verifica si se ha enviado el formulario para borrar o restaurar
    if (isset($_POST['restaurar'])) {
        // Actualiza el estado del mueble
        $sql = "UPDATE inventariomuebles SET activo=1 WHERE idmuebles=$id";
        $mensaje = "restaurado"; // Cambiado de "borrado" a "restaurado"
    } elseif (isset($_POST['borrar'])) {
        // Elimina la fila correspondiente de la base de datos
        $sql = "DELETE FROM inventariomuebles WHERE idmuebles=$id";
        $mensaje = "eliminado";

        // Elimina el archivo de imagen de la carpeta
        if (!empty($nomImg) && file_exists("./imagenes2/$nomImg")) {
            unlink("./imagenes2/$nomImg");
        }
    } else {
        // En caso de que no se haya enviado ninguna acción válida
        header("location:lista_muebles_borrados.php");
        exit();
    }

    $result = mysqli_query($conex, $sql);

    //die($sql);

    header("location:lista_muebles_borrados.php?mensaje=$mensaje");
} else {
    // En caso de que no se encuentre el registro con el ID proporcionado
    header("location:lista_muebles_borrados.php");
    exit();
}
?>
