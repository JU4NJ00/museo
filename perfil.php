<?php
session_start();
if (isset($_SESSION['dniadmin']) || isset($_SESSION["dniencargado"])) {
    require_once "conexion.php";

    // Obtener el ID del usuario actualmente autenticado
    $idUsuario = $_SESSION['id'];  // Asegúrate de que el nombre de la clave de sesión sea correcto

    // Realizar la consulta SQL para obtener los datos del usuario
    $sql = "SELECT * FROM usuarios WHERE idusuario = $idUsuario";
    $result = mysqli_query($conex, $sql);

    if ($result) {
        // Obtener la fila de resultados como un array asociativo
        $usuario = mysqli_fetch_assoc($result);

        // Puedes acceder a los datos del usuario usando $usuario['nombre'], $usuario['apellido'], etc.
    } else {
        // Manejar el error en caso de que la consulta no sea exitosa
        echo "Error al obtener los datos del usuario: " . mysqli_error($conex);
    }

} else {
    header("location:index.php");
}

// Verificar si el formulario está en modo de edición
$modoEdicion = isset($_GET['editar']);

// Verificar si se ha enviado el formulario de edición
if ($modoEdicion && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Realizar las actualizaciones en la base de datos
    $nombre = mysqli_real_escape_string($conex, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conex, $_POST['apellido']);
    $dni = mysqli_real_escape_string($conex, $_POST['dni']);
// Cifrar la nueva contraseña solo si se proporciona una nueva contraseña
$clave = isset($_POST['clave']) ? password_hash($_POST['clave'], PASSWORD_DEFAULT) : $usuario['clave'];

    $updateSql = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', dni = '$dni', clave = '$clave' WHERE idusuario = $idUsuario";
    $updateResult = mysqli_query($conex, $updateSql);

    if (!$updateResult) {
        echo "Error al actualizar los datos del usuario: " . mysqli_error($conex);
    } else {
        // Redirige al usuario a la página de perfil con un mensaje de éxito
        header("Location: perfil.php?mensaje=Los datos se actualizaron correctamente");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Agrega tus etiquetas meta, título y enlaces a las hojas de estilo aquí -->
</head>

<body>
    <?php
    include("primero.php");
    include('header.php');
    ?>

    <section class="container mt-5">
        <h2>Perfil del Usuario</h2>
        <!-- Muestra el mensaje de éxito si está presente en la URL -->
        <?php if (isset($_GET['mensaje'])) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['mensaje']); ?>
            </div>
        <?php endif; ?>
        <form action="?editar" method="post" enctype="multipart/form-data">
            <!-- Muestra la información del usuario en los campos del formulario -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" <?php echo $modoEdicion ? '' : 'readonly'; ?>>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $usuario['apellido']; ?>" <?php echo $modoEdicion ? '' : 'readonly'; ?>>
            </div>

            <div class="mb-3">
                <label for="dni" class="form-label">DNI:</label>
                <input type="text" class="form-control" id="dni" name="dni" value="<?php echo $usuario['dni']; ?>" <?php echo $modoEdicion ? '' : 'readonly'; ?>>
            </div>

            <div class="mb-3">
                <label for="clave" class="form-label">Clave:</label>
                <input type="text" class="form-control" id="clave" name="clave" value="<?php echo $usuario['clave']; ?>" <?php echo $modoEdicion ? '' : 'readonly'; ?>>
            </div>

            <?php if (!$modoEdicion) : ?>
                <a href="?editar" class="btn btn-primary">Editar</a>
            <?php else : ?>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <?php endif; ?>
        </form>
    </section>

    <?php
    include('footer.php');
    ?>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
