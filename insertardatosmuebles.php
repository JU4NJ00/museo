<?php

session_start();


// Conexion a la Base de Datos Biblioteca 

 require_once "conexion.php";

 require_once "funcionesval2.php";

 




$error2 = "";

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

if(!empty(trim($_POST['designacion'])) && !empty(trim($_POST['modoadquisicion'])) && 
   !empty(trim($_POST['nomdonante'])) && !empty(trim($_POST['fechaing'])) && !empty(trim($_POST['datodescr'])) && !empty(trim($_POST['procedencia'])) && !empty(trim($_POST['estadoconserv']))){
	
         
		$designacion = $_POST['designacion'];
		$modoadquisicion = $_POST['modoadquisicion'];
		$nomdonante = $_POST['nomdonante'];
		$fechaing = $_POST['fechaing'];
		$datodescr =$_POST['datodescr'];
		//Campos agregados
		$procedencia=$_POST['procedencia'];
        $estadoconserv=$_POST['estadoconserv'];
		$categoria=$_POST['categoriaboss'];
		$idusuario=$_SESSION['id'];
		$cont2="UPDATE categoria SET contador=contador+1 WHERE idcategoriaboss=$categoria";
		mysqli_query($conex,$cont2);
		
        //die($sql);
		//agregado
		$cod="SELECT concat(idcategoriaboss,'-',contador,'-',iniciales) as codigo FROM categoria WHERE idcategoriaboss=$categoria";

		$result1=mysqli_query($conex,$cod); $fila=mysqli_fetch_array($result1);
		$codigo=$fila['codigo'];

		//die($cod);
            
        $sql="INSERT INTO inventariomuebles(designacion,modoadquisicion,nomdonante,fechaing,datodescr,procedencia,estadoconserv,categoria_idcategoriaboss,usuarios_idusuario,codigo,nomImg) VALUES('$designacion','$modoadquisicion','$nomdonante','$fechaing','$datodescr','$procedencia','$estadoconserv','$categoria','$idusuario','$codigo','$nomImg')";

        $result=mysqli_query($conex,$sql);
		//die($sql);



        if ($result){
			
		
             header("Location:inventariomuebles.php?mensaje=agregado");

        }
	
	else{
		header("Location:inventariomuebles.php?mensaje=".$error2);
	}

}
?>
