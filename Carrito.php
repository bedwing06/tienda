<?php
session_start();
$mensaje = "";

if (isset($_POST['btnAccion'])){

    switch($_POST['btnAccion']){


        case 'Agregar':

            if(is_numeric(openssl_decrypt($_POST['id'],COD,KEY))){
                 $ID= openssl_decrypt($_POST['id'],COD,KEY);
                 $mensaje.= "OK ID correcto ".$ID."<br/>";
            }else {$mensaje.= "upsss.. ID incorrecto".$ID."<br/>";
                


            }
            if(is_string(openssl_decrypt($_POST['nombre'],COD,KEY))){
                $NOMBRE = openssl_decrypt($_POST['nombre'],COD,KEY);
                $mensaje.= "OK Nombre correcto ".$NOMBRE."<br/>";
            } else{ $mensaje.="UPs... algo pasa con el nombre"."<br/>";     break;}

            if(is_numeric(openssl_decrypt($_POST['cantidad'],COD,KEY))){
                $CANTIDAD = openssl_decrypt($_POST['cantidad'],COD,KEY);
                $mensaje.= "OK cantidad correcto ".$CANTIDAD."<br/>";
            } else { $mensaje.="UPs... algo pasa con la cantidad"."<br/>";     break;}

            
            if(is_numeric(openssl_decrypt($_POST['Precio'],COD,KEY))){
                $PRECIO= openssl_decrypt($_POST['Precio'],COD,KEY);
                $mensaje.= "OK Precio ".$PRECIO."<br/>";
            } else { $mensaje.="UPs... algo pasa con el Precio";     break;}

           


        if(!isset($_SESSION['CARRITO'])){

            $producto=array(
                'ID'=>$ID,
                'NOMBRE'=>$NOMBRE,
                'CANTIDAD'=>$CANTIDAD,
                'PRECIO'=>$PRECIO
            );
            $_SESSION['CARRITO'][0]=$producto;
            $mensaje= "Producto agregado al carrito";

        }  else{

            $idProductos=array_column($_SESSION['CARRITO'],"ID");
            
            if(in_array($ID,$idProductos)){
                echo "<script>alert('El Producto ya ha sido seleccionado...')</script>";
                $mensaje= "";

            } else{

            $NemeroProductos=count($_SESSION['CARRITO']);
         

            $producto=array(
                'ID'=>$ID,
                'NOMBRE'=>$NOMBRE,
                'CANTIDAD'=>$CANTIDAD,
                'PRECIO'=>$PRECIO
            );

            $_SESSION['CARRITO'][$NemeroProductos]=$producto;
            $mensaje= "Producto agregado al carrito";
        
            }
        } 
        //$mensaje= print_r($_SESSION, true); 
       


            break;
            case "Eliminar":
                if(is_numeric( openssl_decrypt($_POST['id'],COD,KEY))){
                        $ID= openssl_decrypt($_POST['id'],COD,KEY);
                       

                        foreach($_SESSION['CARRITO'] as $indice=> $producto){
                            if($producto['ID']==$ID){
                                unset($_SESSION['CARRITO'][$indice]);
                                echo "<script>alert('Elemento Borrodo....');</script>";
                                break;                           
          
                            }


                        }
               }else {
                   $mensaje.= "upsss.. ID incorrecto".$ID."<br/>";
              }

                break;



    }
}

?>