<?php

session_start();
if(isset($_SESSION["dniadmin"])){
	header("location:inicio_admin.php");
}else{
 if(isset($_SESSION["dniencargado"])){
	header("location:inicio_encargado.php");
 }else {header("location:index.php");}}
// Conexion a la BD
require_once "conexion.php";

//Funcion de Validacion de Datos




$error = "";
if (!empty(basename($_FILES['archivo']['name']))) {
    //tratamos la imagen aquí
    require_once('validargpt.php');
    
    // Ruta temporal del archivo subido
    $temporal = $_FILES['archivo']['tmp_name'];

    // Verifica si el archivo subido es una imagen
    if (getimagesize($temporal) !== false) {
        // Si el archivo es una imagen, continúa con el proceso
        $ruta = './imagenes2/';
        $nombrearchivo = basename($_FILES['archivo']['name']);
        $destino = $ruta . $nombrearchivo;

        // Verifica si el archivo ya existe en el directorio de destino
        if (file_exists($destino)) {
            $mens = 0; //el archivo ya existe
        } else {
            // Si el archivo no existe, mueve el archivo de la carpeta temporal a la carpeta de destino
            if (move_uploaded_file($temporal, $destino)) {
                $nomImg = proceseimg($ruta, $nombrearchivo);
                unlink($ruta.$_FILES['archivo']['name']);
                $mens = 1; //se ha subido correctamente
            } else {
                $mens = 2; //Hubo un error al subir el archivo
            }
        }
    } else {
        // Si el archivo no es una imagen, muestra un mensaje de error
        $mens = 3; //el archivo no es una imagen
    }
}
 // Recibe el id oculto desde el form_editar

 $id=$_SESSION['ids'];
  

 

        if($_GET['msjp'] == 'error'){
            header("Location:inventariolibros.php?mensaje=editError");
        }else{ 




        //die($imagen);

		$autor = $_POST['autor'];
		$nombre = $_POST['nombre'];
		$editorial = $_POST['editorial'];
		$fechaedicion= $_POST['fechaedicion'];
		$lugar = $_POST['lugar'];
		//Campos agregados
		$paginas =$_POST['paginas'];
        $modoadquisicion =$_POST['modoadquisicion'];
        $nomdonante =$_POST['nomdonante'];
        $fechaingreso =$_POST['fechaingreso'];
        $descripcion = $_POST['descripcion'];
        $procedencia = $_POST['procedencia'];
        $estado = $_POST['estado'];
        $categoria =$_POST['categoria'];
        $usuario=$_SESSION['id'];
       
        //agarramos la categoria anterior mandada por hidden desde el form editar
     
        $categoria2=$_POST['categoria2'];

        // Se arma la sentencia SQL de Actualización
            
        $sql="UPDATE inventariolibros SET autor='$autor',nombre='$nombre',editorial='$editorial',fechaedicion='$fechaedicion',lugar='$lugar',paginas='$paginas',modoadquisicion='$modoadquisicion',nomdonante='$nomdonante',fechaingreso='$fechaingreso',descripcion='$descripcion',procedencia='$procedencia',estado='$estado',categoria_idcategoriaboss=2,categorialibro_idcategorias='$categoria', usuarios_idusuario='$usuario', nomImg='$nomImg' WHERE idlibro=$id";    
        
        // Ejecuta la sentencia

        mysqli_query($conex,$sql);
            
        //die($sql);

        // Evalúa si se realizó la actualización de algun dato

        if (mysqli_affected_rows($conex)==1){
            if($categoria2!=$categoria){
                //sumamos 1 al contador de la categoria nueva seleccionada
                $cont="UPDATE categorialibro SET contador=contador+1 WHERE idcategorias=$categoria";
                mysqli_query($conex,$cont);
                //restamos 1 al contador de la categoria vieja
                $cont2="UPDATE categorialibro SET contador=contador-1 WHERE idcategorias=$categoria2";
                mysqli_query($conex,$cont2);
            }

            header("Location:inventariolibros.php?mensaje=edit");

        }
        }
	
 

?>