<?php
session_start();
if (isset($_SESSION['dniadmin']) || isset($_SESSION["dniencargado"])){
} else{
 header("location:index.php");}


// Conexion a la Base de Datos Biblioteca 

 require_once "conexion.php";
 require_once "fpaginado_seguimientoL.php";

 //paginadolibro
 if(isset($_POST['clavebuscada'])){
    $clavebusqueda=$_POST['clavebuscada'];
 }
 if(isset($_GET['clav']) && ((($_GET['clav'])!="") || ($_GET['clav'])!=" ")){
    $clavebusqueda=$_GET['clav'];

}else {$clavebusqueda="";}
 $cantmax=contar_registros($conex,$clavebusqueda);

 if(isset($_GET['clav']) && ((($_GET['clav'])!="") || ($_GET['clav'])!=" ")){
    $clavebusqueda=$_GET['clav'];

}



 if (isset($_POST['btnbuscar']) && $_POST['clavebuscada']!=''){
    //si el boton buscar manda algo se ejecuta esto

$clavebusqueda=$_POST['clavebuscada'];
 }
$cantmax=contar_registros($conex,$clavebusqueda);

if (!isset($_GET['pg'])){
    $pag=0;
    $result=registros_porpagina($conex,$pag,$clavebusqueda); 
}else{
    $pag=$_GET['pg'];
    $result=registros_porpagina($conex,$pag,$clavebusqueda);
} 
 if (mysqli_num_rows($result)>0){

         
    include ("primero.php");
        
        include('header.php');

    ?>
      
    <section>
    
     
    <?php
    if((isset($_GET['clav']) && ($_GET['clav'])!="") || (isset($_POST['btnbuscar']) && $_POST['clavebuscada']!='')){
            echo '<a href="listado_seguimientoL.php"><i class="fa-sharp fa-solid fa-arrow-left fa-2x m-2"></i></a>';}?>
    
        

    <div class="container text-center">
        <div class="text-center mt-5 mb-3"><h3>Listado de seguimiento de Libros</h3></div>

        <?php 
            if(isset($_GET['mensaje'])){
                switch ($_GET['mensaje']) {
                    
                        case 'noencontrado':
                            echo "<div class='text-center mt-4 mb-5'><div class='alert alert-danger' role='alert'><strong>".'Libro no encontrado'."</strong></div></div>";
                       break;

                        }
             }
            ?>
        
       

        <table class="table table-striped table-hover">
            <div class="row">
            <div class="row">
                <div class="col-4">
                <form action="listado_seguimientoL.php" method="POST">	
                  	<div class="input-group mt-2">
          					<input type="text" name="clavebuscada" class="form-control" value="<?php if (!empty($_POST['clavebuscada'])){ echo $_POST['clavebuscada']; }?>">
          					<button class="btn btn-outline-secondary btn-sm" type="submit" name="btnbuscar" id="btnbuscar" value="Buscar">Buscar</button>
          			</div>
				</form>

                </div>
                <div class="col-5"></div>

           
                <thead>
                <tr>
                    <th scope="col">Cod</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Nombre del Usuario</th>
                    <th scope="col">Apellido del Usuario</th>
                    <th scope="col">DNI</th>
                    <th scope="col">Fecha de Ingreso</th>
                    <th scope="col">Fecha de Carga</th>
                    
                    <!-- <th scope="col">Dato Descriptivo</th>
                    <th scope="col">Procedencia</th>
                    <th scope="col">Estado de Consevacion</th>
                    <th scope="col">Categoria</th> -->
                    <?php 
            if (isset($_SESSION['dniadmin']) || isset($_SESSION['dniencargado'])){
            ?>
                    <th scope="col">Acciones</th>
            <?php } ?>
                    </tr>
                </thead>

            <tbody>

            <?php
while ($fila = mysqli_fetch_array($result)) {
    ?>
    <tr>
        <th scope="row"><?php echo $fila["codigo"]; ?></th>
        <td><?php echo isset($fila["nombre"]) ? $fila["nombre"] : ''; ?></td>
        <td><?php echo isset($fila["nombre_usuario"]) ? $fila["nombre_usuario"] : ''; ?></td>
        <td><?php echo isset($fila["apellido_usuario"]) ? $fila["apellido_usuario"] : ''; ?></td>
        <td><?php echo isset($fila["dni_usuario"]) ? $fila["dni_usuario"] : ''; ?></td>
        <td><?php echo date('d/m/Y', strtotime($fila["fechaingreso"])); ?></td>

        <td><?php echo date('d/m/Y H:i:s', strtotime($fila["fechaCarga"])); ?></td>


        <?php 
        if (isset($_SESSION['dniadmin']) || isset($_SESSION['dniencargado'])) {
            include("verLibros.php");
            ?>
            <td>
                <a class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#verinfo2<?php echo $fila['idlibro']; ?>"><i class="fa fa-eye fa-1x" aria-hidden="true"></i></a>
            </td>
        <?php } ?>
    </tr>
    <?php

?>
                
                
                    

</tr>
            <?php } ?>
                
                

                 
            
            </tbody>



    </table></div>



     
    <div>
    <ul class="pagination justify-content-center">

   <?php
    
    //paginado

$itemxpag=$cantmax/5;
for ($i = 0; $i < $itemxpag; $i++) { ?>
    <li class="page-item"><?php echo "<a class='page-link' href='listado_seguimientoL.php?clav=$clavebusqueda&pg=".$i."'>"; echo $i+1;}?></a></li>
 </ul> 
  </div>  

   <?php

     }else header("location:listado_seguimientoL.php?mensaje=noencontrado");
   ?>  
    
    </section>    

    <?php
  
    include('footer.php');

    ?>
    

   
   <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
 </body>
 </html>


