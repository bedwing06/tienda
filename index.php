
<?php


include 'global/confi.php';
include 'global/conexion.php';
include 'Carrito.php';
include 'templates/Cabesera.php';

?>

    <br>
    <?php if($mensaje!=""){?>

    <div class="alert alert-success">
     <?php  echo $mensaje; ?>
      
       <a href="mostrarCarrito.php" class="badge badge-success">Ver carrito</a>
    </div>

    <?php }?>

   <div class="row">
       <?php
            $sentencia = $pdo->prepare("SELECT * FROM `tblproductos`");
            $sentencia->execute();
            $listaProductos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
            //print_r($listaProductos);
        ?>

       <?php foreach($listaProductos as $producto){ ?>
        <div class="col-3">
            <div class="card">
                <img 

                title="<?php echo  $producto['Nombre'];?>"
                alt="<?php echo  $producto['Nombre'];?>"
                class="card-img-top" 
                src="<?php echo  $producto['imagen'];?>"
                data-toggle="popover"
                data-trigger="hover"
                data-content="<?php echo  $producto['Descripcion'];?>"
              height="317px";  
                 >
                <div class="card-body">
                    <span><?php echo  $producto['Nombre'];?></span>
                    <h5 class="card-title"><?php echo  $producto['Precio'];?></h5>
                    <p class="card-text">Descripcion</p>

                    <form action="" method="post">

                    <input type="hidden"name="id" id="id" value="<?php echo openssl_encrypt($producto['ID'],COD,KEY);?>">
                    <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($producto['Nombre'],COD,KEY);?>">
                    <input type="hidden" name="Precio" id="Precio" value="<?php echo openssl_encrypt($producto['Precio'],COD,KEY);?>">
                    <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1,COD,KEY);?>">

                    <button class="btn btn-primary" 
                    name="btnAccion" 
                    value="Agregar"  
                    type="submit">
                    Agregar al carrito


                    </form>

                    

                    </button>
                </div>
            </div>
       </div>
        <?php } ?>
       
   </div>

    

    </div>

    



<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})

</script>
<?php include 'templates/pie.php';?>
