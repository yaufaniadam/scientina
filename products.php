<?php require_once dirname(__FILE__) . '/vendor/midtrans-php/Midtrans.php';

\Midtrans\Config::$serverKey = 'SB-Mid-server-nfg_ilmmRSPvnW3RSa_DymDW';
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$transaction_details = array(
  'order_id' => rand(),
  'gross_amout' => 40000,
);

$item_details = array(
  'id' => 'a1',
  'price' => 20000,
  'quantity' => 2,
  'name'=> 'Denim shirt'
);

$item_details = array($item_details);

$billing_address = array(
  'first_name' => 'Kiostr',
  'last_name' => '',
  'address' => 'Mataram',
  'city' => 'Mataram',
  'postal_code' => '83112',
  'phone' => '08562563456',
  'country_code'=> 'IND'
);
$shipping_address = array(
  'first_name' => 'yaufani',
  'last_name' => 'Adam',
  'address' => 'Yogyakarta',
  'city' => 'bantul',
  'postal_code' => '55183',
  'phone' => '08562563456',
  'country_code'=> 'IND'
);

$customer_details = array(
  'first_name' => 'Kiostr',
  'last_name' => '',
  'email' => 'yaufaniadam@gmail.com',
  'phone' => '08562563456',
  'billing_address' => $billing_address,
  'shipping_address' => $shipping_address
);

$enable_payments = array('mandiri_clickpay');

$transaction = array(
  'enabled_payments' => $enable_payments,
  'transaction_details' => $transaction_details,
  'customer_details' => $customer_details,
  'item_details' => $item_details
);

$snapToken = \Midtrans\Snap::getSnapToken($transaction);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>KIOSTR</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="css/mdb.min.css" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link href="css/style.min.css" rel="stylesheet">
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
    <div class="container">

      <!-- Brand -->
      <a class="navbar-brand waves-effect" href="https://www.kodetr.com" target="_blank">
        <strong class="blue-text">KIOSTR</strong>
      </a>

      <!-- Collapse -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Links -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <!-- Left -->
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link waves-effect" href="#">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link waves-effect" href="ttps://www.kodetr.com" target="_blank">Brands</a>
          </li>
          <li class="nav-item">
            <a class="nav-link waves-effect" href="ttps://www.kodetr.com"
              target="_blank">Sale</a>
          </li>
        </ul>

         <!-- Right -->
        <ul class="navbar-nav nav-flex-icons">
          <li class="nav-item">
            <a class="nav-link waves-effect">
              <i class="fas fa-bell"></i>
              <span class="badge red z-depth-1 mr-1"> 2 </span>
            </a>
          </li>
        </ul>

      </div>

    </div>
  </nav>
  <!-- Navbar -->

  <!--Main layout-->
  <main class="mt-5 pt-4">
    <div class="container dark-grey-text mt-5">

      <!--Grid row-->
      <div class="row wow fadeIn">

        <!--Grid column-->
        <div class="col-md-6 mb-4">

          <img src="https://mdbootstrap.com/img/Photos/Horizontal/E-commerce/Vertical/12.jpg" class="img-fluid" alt="">

        </div>
        <!--Grid column-->

        <!--Grid column-->
        <div class="col-md-6 mb-4">

          <!--Content-->
          <div class="p-4">
            <div class="mb-3">
              <a href="">
                <span class="badge blue mr-1">New</span>
              </a>
              <a href="">
                <span class="badge red mr-1">Bestseller</span>
              </a>
            </div>

            <p class="lead">
              <span>Rp 120.000</span>
            </p>

            <p class="lead font-weight-bold">Deskripsi</p>

            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et dolor suscipit libero eos atque quia ipsa
              sint voluptatibus!
              Beatae sit assumenda asperiores iure at maxime atque repellendus maiores quia sapiente.</p>
            
              <button id="pay-button" class="btn btn-primary btn-md my-0 p" type="submit">Buy
                  <i class="fas fa-shopping-Buy ml-1"></i>
              </button>
              <p>
              <?php echo "Snap Token " . $snapToken ?>
              </p>
              <p>
              <pre>
                <div id="result-json"> Json payment :</div>
              </pre>
              </p>

          </div>
          <!--Content-->
        </div>
        <!--Grid column-->
      </div>
    </div>
  </main>
  <!--Main layout-->

  <!--Footer-->
  <footer class="page-footer text-center font-small mt-4 wow fadeIn">

    <!--Copyright-->
    <div class="footer-copyright py-3">
      <a href="https://kodetr.com/" target="_blank"> KIOSTR </a>
       © 2019
    </div>
    <!--/.Copyright-->

  </footer>
  <!--/.Footer-->

  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-NUHDTW6uipcvE7sz"> </script>
  <script
  src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
  integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
  crossorigin="anonymous"></script>

  <script type="text/javascript"> 

    $("#pay-button").on('click', function() {  
      
      snap.pay('<?= $snapToken; ?>', {
        onSuccess: function(result){
          document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2)
        },
        onPending: function(result) {
          document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2)
        },
        onError: function(result) {
          document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2)
        }
      });

    });

 
  </script>

  <!-- SCRIPTS -->
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Initializations -->
  <script type="text/javascript">
    // Animations initialization
    new WOW().init();

  </script>
</body>

</html>
