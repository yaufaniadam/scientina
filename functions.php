<?php
add_action('init', 'myStartSession', 1);

function myStartSession()
{
    if (!session_id()) {
        session_start();
    }
}

function myEndSession()
{
    unset($_SESSION["cart_item"]);
}

require_once('inc/cpt.php');
require_once('inc/midtrans-php/Midtrans.php');

require 'update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://solusidesain-update-theme.netlify.app/labip/theme.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'lab-ip'
);

if (!defined('WP_DEBUG')) {
    die('Direct access forbidden.');
}

add_action('wp_enqueue_scripts', 'sctn_child_enqueue_styles', 99);
function sctn_child_enqueue_styles()
{
    // CSS
    wp_enqueue_style('parent-style', get_stylesheet_directory_uri() . '/style.css');

    // JS
    wp_enqueue_script('feather', 'https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js');
    wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js');
}


/* -- SET VARIABLE -- */
function add_custom_query_vars_filter($vars)
{
    $vars[] .= 'harga';
    $vars[] .= 'post_id';
    $vars[] .= 'training_id';
    $vars[] .= 'status';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars_filter');

/* -- SHORTCODE -- */

/*-------- Button Beli ----------*/
add_shortcode('button_beli', 'button_beli');
function button_beli()
{
    $status =  get_query_var('status');
    $user = wp_get_current_user();
    $url = get_bloginfo('url') . '/keranjang/';
    $form = '';
    $form .= '<form action="' . $url . '" method="POST" class="pendaftaran">';

    // if (is_user_logged_in()) {
    //     $form .= '<p style="text-align:center;">Selamat datang, ' . $user->user_login . ' <p>';
    // } else {
    //     $form .= '<p style="text-align:center;"><a href="' . esc_url(wp_login_url(get_permalink())) . '">Login disini </a>jika sudah terdaftar.</p>';
    // }

    $form .=  wp_nonce_field('post_nonce', 'post_nonce_field');

    // if (!is_user_logged_in()) {
    //     $form .= '<tr>       
    //     <td>
    //     <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap"  placeholder="Nama Lengkap *">
    //     </td>
    // </tr>
    // <tr>       
    //     <td>
    //     <input type="text" class="form-control"  name="handphone" id="handphone"  placeholder="Handphone *">
    //     </td>
    // </tr>
    // <tr>       
    //     <td>
    //     <input type="email" class="form-control"  name="email" id="email"  placeholder="Email *">
    //     </td>
    // </tr>';
    // // cek profil lembaga dari user ini, jika belum mengisi form lembaga maka diminta mengisi


    // }

    $form .= '<p style="margin-left:9px; color:#fff;">Jumlah Peserta (wajib) <input type="number"  class="form-controld" name="jml_peserta" id="jml_peserta" min="1" value="1" placeholder=""></p>';

    // $form .= '<tr>       
    // <td>
    // <input type="text" class="form-control"  name="pesan" id="pesan"  placeholder="Pesan tambahan (opsional)">
    // </td>
    // </tr>';


    $form .= '<input type="hidden" name="harga" id="harga" value="' . get_field('harga', get_the_ID()) . '" />';
    $form .= '<input type="hidden" name="post_id" id="post_id" value="' . get_the_ID() . '" />';
    $form .= '<input type="hidden" name="submitted" id="submitted" value="add" />';

    $form .= '<button type="submit" class="btn btn-warning btn-lg button button_beli"><span><i class="fas fa-edit"></i> Beli Program</span></a>';

    $form .= '<script type="text/javascript">
    var selectEl = document.getElementById("redirectSelect");
    
    selectEl.onchange = function(){
        var goto = this.value;
        window.location = goto;
        
    };
    </script>';

    return $form;
}

/*-------- Hapus Sesi ----------*/
add_shortcode('session_des', 'session_des');
function session_des()
{
    myEndSession();
    header('Location: ' . get_bloginfo('url') . '/keranjang');
}
/*-------- Harga ----------*/
add_shortcode('harga', 'harga');
function harga()
{
    $harga = get_field('harga', get_the_ID());

    return number_format($harga);
}
/*-------- Harga ----------*/
add_shortcode('cek_sesi', 'cek_sesi');
function cek_sesi()
{

    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
}

/*-------- Halaman Keranjang ----------*/
add_shortcode('keranjang', 'keranjang');
function keranjang()
{

    if (!session_id()) {
        session_start();
    }

    if (isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

        switch ($_POST['submitted']) {
            case "add":

                if (!empty($_POST['jml_peserta'])) {

                    $wpdb = $GLOBALS['wpdb'];
                    $subtotal = sanitize_text_field($_POST['harga']) * sanitize_text_field($_POST['jml_peserta']);
                    $query = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID =" . $_POST['post_id'], ARRAY_A);

                    $itemArray = array(
                        $query['ID'] => array(
                            "judul" => $query["post_title"],
                            "harga" => sanitize_text_field($_POST['harga']),
                            "jml_peserta" => sanitize_text_field($_POST['jml_peserta']),
                        )
                    );

                    if (!empty($_SESSION["cart_item"])) {

                        if (in_array($query["ID"], array_keys($_SESSION["cart_item"]))) {
                            foreach ($_SESSION["cart_item"] as $k => $v) {
                                if ($query["ID"] == $k) {
                                    if (empty($_SESSION["cart_item"][$k]["jml_peserta"])) {
                                        $_SESSION["cart_item"][$k]["jml_peserta"] = 0;
                                    }
                                    $_SESSION["cart_item"][$k]["jml_peserta"] += $_POST["jml_peserta"];
                                }
                            } //endforeach
                        } else {

                            $_SESSION["cart_item"] = $_SESSION["cart_item"] + $itemArray;
                        }
                    } else {
                        $_SESSION["cart_item"] = $itemArray;
                    }
                }


                break;
            case "hapus":
                echo "hapus";
                break;
        }
    }

    if (isset($_SESSION["cart_item"])) {
        $total_peserta = 0;
        $total_harga = 0;
        $url_checkout = get_bloginfo('url') . "/bayar";
        $user = wp_get_current_user();

        $totpeserta = count($_SESSION["cart_item"]);

        if (is_user_logged_in()) {
            $halo = 'Halo, ' .  $user->user_login . '. ';
        } else {
            $halo = '';
        }

        $cart = '';
        $cart .= '<table><tr><td style="width:80%;">' . $halo . 'Anda memiliki ' . $totpeserta . ' order pada keranjang belanja.</td><td><a href="' . get_bloginfo('url') . '/kosongkan-keranjang" class="button button_checkout">Kosongkan Keranjang</a></td></tr></table>';
        $cart .= "<table class='cart'><thead><tr><th>Training</th><th>Harga</th><th>Peserta</th><th>Sub Total</th><th>&nbsp;</th><tr></thead>";

        foreach ($_SESSION["cart_item"] as $key => $item) {
            $subtotal = $item["jml_peserta"] * $item["harga"];

            $cart .= "<tr><td>" . $item["judul"] . "</td><td style='text-align:right;'><span style='float:left;'>Rp</span> " . number_format($item["harga"], 2) . " </td>
            <td style='text-align:center;'>" . $item["jml_peserta"] . "</td><td style='text-align:right;'><span style='float:left;'>Rp </span>" . number_format($subtotal, 2) . " </td><td style='text-align:center;'>";

            //   $cart .= "<form action='" . $url_checkout . "' method='post'>";
            $cart .=  wp_nonce_field('post_nonce', 'post_nonce_field');
            $cart .= "<input type='hidden' name='order_id' value='" . session_id() . "'>";
            $cart .= "<input type='hidden' name='harga' value='" . $item["harga"] . "'>";
            $cart .= "<input type='hidden' name='training_id' value='" . $key . "'>";
            $cart .= "<input type='hidden' name='total_harga' value='" . $subtotal . "'>";
            $cart .= "<input type='hidden' name='jml_peserta' value='" . $item["jml_peserta"] . "'>";
            $cart .= "<input type='hidden' name='submitted' value='bayar'>
            <a href='" . get_bloginfo('url') . "/bayar/?training_id=" . $key . "' class='button button_checkout'>Lengkapi data</a>";
            //    $cart .= "</form>";
            $cart .= "</td></tr>";
        }

        $cart .= "</table>";

        echo $cart;
    } else {
        echo "<p>Keranjang belanja Anda kosong.</p>";
    }

    echo '<script>
    feather.replace();    
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>';
}



/*-------- Login ----------*/
add_shortcode('login', 'login');
function login()
{
    if (is_user_logged_in()) {  ?>
        <a class="loginheader" href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
    <?php } else { ?>
        <a class="loginheader" href="<?php echo esc_url(wp_login_url()); ?>" alt="Login">
            Login
        </a>
<?php }
}


/*-------- Keranjang Belanja ----------*/
add_shortcode('keranjang_belanja', 'keranjang_belanja');
function keranjang_belanja()
{

    if (isset($_SESSION["cart_item"])) {
        $total_training = count($_SESSION["cart_item"]);
        echo '<a href="' . get_bloginfo('url') . '/keranjang"><p class="keranjang_belanja" data-badge="' . $total_training . '"><i data-feather="shopping-cart"></i></p></a>';
    } else {
        $total_training = 0;
        echo '<a href="' . get_bloginfo('url') . '/keranjang"><p class="keranjang_belanja"><i data-feather="shopping-cart"></i></</p></a>';
    }


    echo '<script>
    feather.replace({width: "1em", height: "1em"});    
    </script>';
}


/*-------- bayar ----------*/
add_shortcode('bayar', 'bayar');
function bayar()
{
    $user = wp_get_current_user();

    $training_id = isset($_GET['training_id']) ? $_GET['training_id'] : '';

    


    if ($training_id != '') {

        //bikin session utk penamping training yg aktif utk proses pembayaran dan redirect after login
        $_SESSION["training_referer"] = $training_id;

        $url_checkout = get_bloginfo('url') . "/proses-bayar";

        $jml_peserta = $_SESSION["cart_item"][$training_id]["jml_peserta"];
        $harga = $_SESSION["cart_item"][$training_id]["harga"];
        $total_harga = $jml_peserta * $harga;
        $cart = '';

        $cart .= '<h5><strong>Pesanan Anda</strong></h5>';

        $cart .= "<table class='cart'><thead><tr><th>Training</th><th>Harga</th><th>Peserta</th><th>Total</th><tr></thead>";
        $cart .= "<tr>
            <td>" . $_SESSION["cart_item"][$training_id]["judul"] . "</td>
            <td style='text-align:right;'><span style='float:left;'>Rp</span> " . number_format($harga, 2) . "</td>
            <td style='text-align:center;'><input style='width:50px;padding:2px;' type='number' name='jml_peserta' value='".$jml_peserta."'></td>
            <td style='text-align:right;'><span style='float:left;'>Rp</span> " . number_format($total_harga, 2) . "</td>           
            <tr>
        ";

        $cart .= "</table>
            <table class='detail-order'>               
                <tr>
                    <td style='width:60%;'>";
        if (is_user_logged_in()) {

            $cart .= "<form action='" . $url_checkout . "' method='post'>";
            $cart .=  wp_nonce_field('post_nonce', 'post_nonce_field');

            $cart .= "<h5><strong>Peserta</strong></h5>";
            for ($i = 1; $i <= $jml_peserta; $i++) {
                $cart .= "<p><input class='form-control' style='width:100%;' type='text' name='peserta[]' placeholder='Nama Peserta " . $i . "'> </p>";
            }

            $cart .= "<input type='hidden' name='training_title' value='" . $_SESSION["cart_item"][$training_id]["judul"] . "'>";
            $cart .= "<input type='hidden' name='training_id' value='" . $training_id . "'>";
            $cart .= "<input type='hidden' name='total_harga' value='".$total_harga."'>";
            $cart .= "<input type='hidden' name='harga' value='".$harga."'>";
            $cart .= "<input type='hidden' name='jml_peserta' value='".$jml_peserta."'>";
      

            $cart .= "<input type='hidden' name='submitted' value='bayar'>
                                        <p><button type='submit' class='button button_checkout'>Lanjut</button></p>";
            $cart .= "</form>";
        }
        $cart .= '
                    </td>
                    <td style="width:5%;"></td>
                    <td>
                        <h5><strong>Detail Tagihan</strong></h5>';




        if (is_user_logged_in()) {
            $cart .= '<p style="text-align:left;">Username: ' . $user->user_login . ' <p>';
            $cart .= '<p style="text-align:left;">Email: ' . $user->user_email . ' <p>';
        } else {

            $cart .= '<p style="text-align:center;"><a href="' . esc_url(wp_login_url(get_permalink() . '/?training_id=' . $_SESSION["training_referer"])) . '">Login disini </a>jika sudah terdaftar.</p>';

            $cart .= "<form action='" . $url_checkout . "' method='post'>";
            $cart .=  wp_nonce_field('post_nonce', 'post_nonce_field');

            $cart .= ' <p><input type="text" class="form-control" name="username" id="username"  placeholder="Username *"></p>
                        <p><input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap"  placeholder="Nama Lengkap *"></p>                       
                        <p><input type="text" class="form-control" name="email" id="email"  placeholder="E-mail *"></p>
                        <p><input type="password" class="form-control" name="password" id="password"  placeholder="Password *"></p>
                        <p><input type="text" class="form-control" name="telp" id="telp"  placeholder="HP /WA *"></p> 
                        <p><input type="hidden" class="form-control" name="training_id" id="training_id"  value="' . $training_id . '"></p>';

            $cart .= "<input type='hidden' name='submitted' value='daftar'>
                    <p><button type='submit' class='button button_checkout'>Buat Akun</button></p>";
            $cart .= "</form>";
        }


        $cart .= "</td></tr></table>";
        echo $cart;
    } else {
        // header('Location: ' . get_bloginfo('url') . '/keranjang');
    }



    echo '<script>
    feather.replace();    
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>';
}

/*-------- Tempat ----------*/
add_shortcode('proses_bayar', 'proses_bayar');
function proses_bayar()
{
    $user = wp_get_current_user();

    if (isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

        if ($_POST['submitted'] == 'bayar') {
            $users = array();
            foreach ($_POST['peserta'] as $key => $peserta) {
                $data_peserta = array(
                    'post_title'    => $peserta,
                    'post_status'   => 'pending',
                    'post_author'   => $user->ID,
                    'post_type'   => 'participant',
                );

                // Insert the post into the database.
                $users[] .= wp_insert_post($data_peserta);
            }

            $data_order = array(
                'post_title'    =>  $_POST['training_title'],
                'post_status'   => 'pending',
                'post_author'   => $user->ID,
                'post_type'   => 'orders',
            );

            // Insert the post into the database.
            $result = wp_insert_post($data_order);

            if ($result && !is_wp_error($result)) {
                $post_id = $result;
                add_post_meta($post_id, 'training', $_POST['training_id'], true);
                add_post_meta($post_id, 'participant', $users, true);
                add_post_meta($post_id, 'total_harga', $_POST['total_harga'], true);
                add_post_meta($post_id, 'harga', $_POST['harga'], true);
                add_post_meta($post_id, 'jml_peserta', $_POST['jml_peserta'], true);
            }

            myEndSession();

            $wpdb = $GLOBALS['wpdb'];
            $order = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID =" . $result , ARRAY_A);
            $peserta = get_post_meta( $result, 'participant', false );

            $pst = '';
            foreach ($peserta[0] as $value) {
                $peserta = get_post( $value);
                $pst  .= '<li>'. $peserta->post_title . '</li> ';
            } 

            \Midtrans\Config::$serverKey = 'SB-Mid-server-nfg_ilmmRSPvnW3RSa_DymDW';
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;             

            $jml_peserta = get_post_meta( $result, 'jml_peserta', true );
            $harga = get_post_meta( $result, 'harga', true );

            $transaction_details = array(
                'order_id' => rand(),
                'gross_amount' => $harga,
            );
            
            $item_details = array(
                'id' => $post_id,
                'price' => $harga,
                'quantity' => $jml_peserta,
                'name'=> $order["post_title"]
            );
            
            $item_details = array($item_details);           
            
            $customer_details = array(
                'first_name' => $user->display_name,
                'last_name' =>'',
                'email' => $user->user_email,
                'phone' => '08562563456',
                'billing_address' => '',
                'shipping_address' => ''
            );
            
            $enable_payments = array('mandiri_clickpay');
            
            $transaction = array(
                'enabled_payments' => $enable_payments,
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
                'item_details' => $item_details
            );
            
            $snapToken = \Midtrans\Snap::getSnapToken($transaction);          
           
            
            
            $cart = '';

            $cart .= '<h5><strong>Pesanan Anda</strong></h5>';
    
            $cart .= "<table class='cart'><thead><tr><th>Training</th><th>Nama Peserta</th><th>Total</th><th>&nbsp;</th><tr></thead>";
            $cart .= "<tr>       
                <td>". $order["post_title"]. "</td>
                <td><ol>" . $pst ."</ol></td>
                <td style='text-align:right;'><span style='float:left;'>Rp</span> " . number_format(get_post_meta( $result, 'total_harga', true ),2) . " </td>     
                <td style='text-align:center;'><button id='pay-button' style='background:#f4511e; border-radius:5px;'>Bayar</button></td>      
                <tr>
            ";

            $cart .= "</table>";


            $cart .= '
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-NUHDTW6uipcvE7sz"> </script>
            <script
  src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
  integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
  crossorigin="anonymous"></script>
            <script type="text/javascript"> 
                $("#pay-button").on("click", function() {                 
                    snap.pay("' . $snapToken . '", {
                        onSuccess: function(result){
                        document.getElementById("result-json").innerHTML += JSON.stringify(result, null, 2)
                        },
                        onPending: function(result) {
                        document.getElementById("result-json").innerHTML += JSON.stringify(result, null, 2)
                        },
                        onError: function(result) {
                        document.getElementById("result-json").innerHTML += JSON.stringify(result, null, 2)
                        }
                    });
                });            
            </script>';

            echo $cart;
            
        } elseif ($_POST['submitted'] == 'daftar') {

            $userdata = array(
                'user_login' =>  sanitize_text_field($_POST['username']),
                'user_email'   =>  sanitize_text_field($_POST['email']),
                'user_pass'   =>  sanitize_text_field($_POST['password']),
                'display_name'   =>  sanitize_text_field($_POST['nama_lengkap']),
                'user_firstname'   =>  sanitize_text_field($_POST['nama_lengkap']),
            );

            $user_id = wp_insert_user($userdata);

            automatically_log_me_in($user_id);
        } else {
            echo "balikin ke keranjang";
        }


    }
}

function automatically_log_me_in($user_id)
{

    $training_id = $_SESSION['training_id'];

    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    wp_redirect(home_url('/bayar/?training_id=' . $training_id));
    exit();
}
add_action('user_register', 'automatically_log_me_in');

/*-------- Tempat ----------*/
add_shortcode('tempat', 'tempat');
function tempat($atts)
{
    $a = shortcode_atts(array(
        'lokasi' => '',
    ), $atts);

    $online = get_field('online', get_the_ID());
    if ($online && in_array('Offline', $online)) {
        if ($a['lokasi'] == 'loop') {
            $tempat = get_field('kota', get_the_ID());
        } else {
            $tempat = get_field('tempat', get_the_ID()) . ' ' . get_field('kota', get_the_ID());
        }
    } else {
        // $tempat = get_field('kota', get_the_ID());
        $tempat = get_field('kota', get_the_ID());
    }

    return $tempat;
}

/*-------- Online ----------*/
add_shortcode('online_button', 'online_button');
function online_button($atts)
{
    $a = shortcode_atts(array(
        'lokasi' => 'single',
    ), $atts);

    $online = get_field('online', get_the_ID());
    if ($online && in_array('Offline', $online)) {
        $online =  "Offline";
    } else {
        // $tempat = get_field('kota', get_the_ID());
        $online = "Online";
    }
    return "<p class='online_button " . $a['lokasi'] . " " . $online . "'>" . $online . "</p>";
}

/*-------- Tanggal ----------*/
add_shortcode('tanggal', 'tanggal');
function tanggal($atts)
{
    $a = shortcode_atts(array(
        'lokasi' => '',
    ), $atts);

    // $date = new DateTime(get_field('tanggal_mulai'));
    $mulai = get_field('tanggal_mulai');
    $selesai = get_field('tanggal_selesai');

    $e_mulai = explode(' ', $mulai);
    $e_selesai = explode(' ', $selesai);

    if ($a['lokasi'] == 'loop') {
        $tanggal = date('d F Y', strtotime($e_mulai[0]));
    } else {
        if ($e_mulai[0] === $e_selesai[0]) {
            $tanggal = date('d M Y', strtotime($e_mulai[0])) . ' &raquo; ' . date('H:i', strtotime($e_mulai[1])) . '-' . date('H:i', strtotime($e_selesai[1])) . ' WIB';
        } else {
            //jika tanggal beda, cek apakah tahunnya sama;
            if (date('Y', strtotime($e_mulai[0])) === date('y', strtotime($e_selesai[0]))) {

                //jika tanggal beda, cek apakah bulannya sama;
                if (date('M', strtotime($e_mulai[0])) === date('M', strtotime($e_selesai[0]))) {
                    $tanggal = date('d', strtotime($e_mulai[0])) . '-' . date('d M Y', strtotime($e_selesai[0])) . ' &raquo; ' . date('H:i', strtotime($e_mulai[1])) . '-' . date('H:i', strtotime($e_selesai[1])) . ' WIB';
                } else {
                    $tanggal = date('d M Y', strtotime($e_mulai[0])) . '-' . date('d M Y', strtotime($e_selesai[0])) . ' &raquo; ' . date('H:i', strtotime($e_mulai[1])) . '-' . date('H:i', strtotime($e_selesai[1])) . ' WIB';
                }
            } else {
                $tanggal = date('d M Y', strtotime($e_mulai[0])) . '-' . date('d M Y', strtotime($e_selesai[0])) . ' &raquo; ' . date('H:i', strtotime($e_mulai[1])) . '-' . date('H:i', strtotime($e_selesai[1])) . ' WIB';
            }
        }
    }
    return $tanggal;
}

/*-------- Tempat ----------*/
add_shortcode('cek_running', 'cek_running');
function cek_running($atts)
{
    global $post;
    $a = shortcode_atts(array(
        'lokasi' => 'single',
    ), $atts);

    $mulai = get_field('tanggal_mulai', $post->ID);
    $selesai = get_field('tanggal_selesai', $post->ID);

    date_default_timezone_set('Asia/Jakarta');
    $today = date('d-m-Y H:i:s');

    if ($mulai <= $today && $selesai >= $today) {
        $running = "Running";
    }

    if ($mulai > $today) {
        $running = "Scheduled";
    }
    if ($selesai < $today) {
        $running = "Finished";
    }



    return "<p class='cek_running " . $a['lokasi'] . " " . $running . "'>" . $running . "</p>";
    wp_reset_postdata();
}

/*-------- Trainer ----------*/
add_shortcode('trainers', 'trainers');
function trainers()
{
    $trainer = get_field('trainer', get_the_ID());
    if ($trainer) {
        echo "<ol class='trainer'>";
        foreach ($trainer as $trainer) {

            echo '<li>' . $trainer->post_title . '</li>';
        }
        echo "</ol>";
    }
}

/*-------- add to Cart ----------*/
add_shortcode('add_to_cart', 'trainers');
function add_to_cart()
{
    $trainer = get_field('trainer', get_the_ID());
    if ($trainer) {
        echo "<ol class='trainer'>";
        foreach ($trainer as $trainer) {

            echo '<li>' . $trainer->post_title . '</li>';
        }
        echo "</ol>";
    }
}
