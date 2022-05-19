<?php


include 'global/confi.php';
include 'global/conexion.php';
include 'Carrito.php';
include 'templates/Cabesera.php';

?>
<script src="https://www.paypal.com/sdk/js?client-id=test&currency=USD"></script>
<?php
if ($_POST) {

    $total = 0;
    $SID = session_id();
    $Correo = $_POST['email'];

    foreach ($_SESSION['CARRITO'] as $indice => $producto) {

        $total = $total + ($producto['PRECIO'] * $producto['CANTIDAD']);
    }

    $sentencia = $pdo->prepare("INSERT INTO `tblventas` (`ID`, `ClaveTransaccion`, `PaypalDatos`, `Fecha`, `Correo`, `Total`, `status`) 
    VALUES (NULL,:ClaveTransaccion,'' , NOW(),:Correo ,:Total , 'pendiente');");

    $sentencia->bindParam(":ClaveTransaccion", $SID);
    $sentencia->bindParam(":Correo", $Correo);
    $sentencia->bindParam(":Total", $total);
    $sentencia->execute();
    $idVenta = $pdo->lastInsertId();


    foreach ($_SESSION['CARRITO'] as $indice => $producto) {

        $sentencia = $pdo->prepare("INSERT INTO `tbldetalleventa` (`ID`, `IDVENTA`, `IDPRODUCTO`, `PRECIOUNITARIO`, `CANTIDAD`, `DESCARGADO`) 
        VALUES (NULL,:IDVENTA , :IDPRODUCTO, :PRECIOUNITARIO, :CANTIDAD, '0');");

        $sentencia->bindParam(":IDVENTA", $idVenta);
        $sentencia->bindParam(":IDPRODUCTO", $producto['ID']);
        $sentencia->bindParam(":PRECIOUNITARIO", $producto['PRECIO']);
        $sentencia->bindParam(":CANTIDAD", $producto['CANTIDAD']);
        $sentencia->execute();
    }

    //echo "<h3>".$total."</h3?";
}

?>


<div class="jumbotron text-center">
    <h1 class="display-4">!Paso Final ¡</h1>
    <hr class="my-4">
    <p class="lead">Estas a punto de pagar con paypal la cantidad de :
    <h4>$<?php echo number_format($total, 2) ?></h4>
    <div id="paypal-button-container"></div>
    </p>

    <p>Los productos prodran ser descargado una vez que se procese el pago <br />
        <strong>(para aclaraciones : bedwing06@gmail.com)</strong>
    </p>
</div>



    <!-- Set up a container element for the button -->
    
    <script>
      paypal.Buttons({
        // Sets up the transaction when a payment button is clicked
        createOrder: (data, actions) => {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: '<?php echo $total;?>' // Can also reference a variable or function
              }
            }]
          });
        },
        env:'production', //sandbox:
     
        style: {
            label: 'checkout',
            size:  'small',    // small | medium | large | responsive
            shape: 'pill',     // pill | rect
            color: 'gold'      // gold | blue | silver | black
        },
       
  client:{
     sandbox: 'AQ9aHgZ06Nlmj-VMiTXI2kB6RaYdn60NjeR8A58BMGppPkVdOUTxvua9AD5Wbsgu5bUT-L9yBYIT6qZe',
      production: 'AVrCM6U76bDvrcIDuQCNaV-ELQQXie0oCn0U8sPpmwPH7kHmQMRbwI201FveSG79U6VwRoBz60bg_ga_'
  },



        // Finalize the transaction after payer approval
        onApprove: (data, actions) => {
          return actions.order.capture().then(function(orderData) {
            // Successful capture! For dev/demo purposes:
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            const transaction = orderData.purchase_units[0].payments.captures[0];
            alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
            // When ready to go live, remove the alert and show a success message within this page. For example:
            // const element = document.getElementById('paypal-button-container');
            // element.innerHTML = '<h3>Thank you for your payment!</h3>';
            // Or go to another URL:  actions.redirect('thank_you.html');
          });
        }
      }).render('#paypal-button-container');
    </script>

<?php include 'templates/pie.php'; ?>