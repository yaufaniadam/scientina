if (isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

if ($_POST['submitted'] == 'daftar') {
   
    $email = $_POST['email'];

    // // this is required for username checks
    // require_once(ABSPATH . WPINC . '/registration.php');                           

    if (!is_email($email)) {
        //invalid email
        sctn_errors()->add('email_invalid', __('Email tidak valid'));
    }
    if (email_exists($email)) {
        //Email address already registered
        sctn_errors()->add('email_used', __('Email sudah terdaftar'));
    }
    

    $userdata = array(
        'user_login'        => sanitize_text_field( str_replace('@','-', $_POST['email'])),
        'user_email'        => sanitize_text_field($email),
        'user_pass'         => wp_generate_password( 8, false ),
        'user_registered'    => date('Y-m-d H:i:s'),
        'role'                => 'subscriber'
    );

    $errors = sctn_errors()->get_error_messages();

    // if no errors then cretate user
    if (empty($errors)) {

        $user_id = wp_insert_user($userdata);

        if ($user_id) {
            //add user meta telp
            add_user_meta($user_id, 'telp', sanitize_text_field($_POST['telp']));

            // send an email to the admin
            wp_new_user_notification($user_id);

            //ambil session untuk back to page bayar
            $_SESSION['training_referer'] = $_POST['training_id'];

            wp_redirect( wp_login_url() ); exit;

        }
    } /* empty error */

} /* isset daftar*/

} /* is post submit */

// show any error messages after form submission

if (sctn_errors()->get_error_codes()) {
echo '<pre>';
print_r(sctn_errors()->get_error_codes());
echo '</pre>';
}

$codes = sctn_errors()->get_error_codes();
if ($codes) {
$cart .= '<div class="sctn_errors">';
// Loop error codes and display errors
foreach ($codes as $code) {
    $message = sctn_errors()->get_error_message($code);
    $cart .= '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
}
$cart .= '</div>';
}


keranjang

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

$cart .= '<p>' . $halo . 'Anda memiliki ' . $totpeserta . ' order pada keranjang belanja. <a href="' . get_bloginfo('url') . '/kosongkan-keranjang" class="">Kosongkan Keranjang</a></p>';

$cart .= "<div class='container'>";
foreach ($_SESSION["cart_item"] as $key => $item) {
    $subtotal = $item["jml_peserta"] * $item["harga"];

    $cart .= "<div class='row' style='background:#0d7d76;border-bottom:1px solid #0b635d;'>
     <div class='col-8'><strong>" . $item["judul"] . "</strong><br>
     
     Rp</span> " . number_format($item["harga"], 2) . " x " . $item["jml_peserta"] . " = Rp " . number_format($subtotal, 2) . "
     </div>
    
    ";


    $cart .= "<div class='col-4' style='text-align:center'>";
    //   $cart .= "<form action='" . $url_checkout . "' method='post'>";
    $cart .=  wp_nonce_field('post_nonce', 'post_nonce_field');
    $cart .= "<input type='hidden' name='order_id' value='" . session_id() . "'>";
    $cart .= "<input type='hidden' name='harga' value='" . $item["harga"] . "'>";
    $cart .= "<input type='hidden' name='training_id' value='" . $key . "'>";
    $cart .= "<input type='hidden' name='total_harga' value='" . $subtotal . "'>";
    $cart .= "<input type='hidden' name='jml_peserta' value='" . $item["jml_peserta"] . "'>";
    $cart .= "<input type='hidden' name='submitted' value='bayar'>
    <a href='" . get_bloginfo('url') . "/lengkapi-data/?training_id=" . $key . "' class='button button_checkout'>Checkout</a></div></div>";         

}
$cart .='</div>';      

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