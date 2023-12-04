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
  

 

        
		$designacion = $_POST['designacion'];
		$modoadquisicion = $_POST['modoadquisicion'];
		$nomdonante = $_POST['nomdonante'];
		$fechaing = $_POST['fechaing'];
		$datodescr = $_POST['datodescr'];
		//Campos agregados
		$procedencia =$_POST['procedencia'];
        $estadoconserv =$_POST['estadoconserv'];
        $categoria=$_POST['categoriaboss'];
		$idusuario=$_SESSION['id'];

        // recibimos la categoria vieja en categoria2
        $categoria2=$_POST['categoria2'];
        //agregamos el codigo propio

        $cod="SELECT concat(idcategoriaboss,'-',contador,'-',iniciales) as codigo FROM categoria WHERE idcategoriaboss=$categoria";

		$result=mysqli_query($conex,$cod); $fila=mysqli_fetch_array($result);
		$codigo=$fila['codigo'];

        // Se arma la sentencia SQL de Actualización

        $sql="UPDATE inventariomuebles SET designacion='$designacion',modoadquisicion='$modoadquisicion',nomdonante='$nomdonante',fechaing='$fechaing',datodescr='$datodescr',procedencia='$procedencia',estadoconserv='$estadoconserv',codigo='$codigo',categoria_idcategoriaboss='$categoria',usuarios_idusuario='$idusuario', nomImg='$nomImg' WHERE idmuebles=$id";    
        
        // Ejecuta la sentencia

        mysqli_query($conex,$sql);

        //die($sql);

        // Evalúa si se realizó la actualización de algun dato

        if (mysqli_affected_rows($conex)==1){
            if($categoria2!=$categoria){
                //sumamos 1 al contador de la categoria nueva seleccionada
                $cont="UPDATE categoria SET contador=contador+1 WHERE idcategoriaboss=$categoria";
                mysqli_query($conex,$cont);
                //restamos 1 al contador de la categoria vieja
                $cont2="UPDATE categoria SET contador=contador-1 WHERE idcategoriaboss=$categoria2";
                mysqli_query($conex,$cont2);
            }

            header("Location:inventariomuebles.php?mensaje=edit");

        }
	
	
 

?>