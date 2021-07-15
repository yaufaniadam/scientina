
// add_shortcode('proses_bayar', 'proses_bayar');
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
            $order = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID =" . $result, ARRAY_A);
            $peserta = get_post_meta($result, 'participant', false);

            $pst = '';
            foreach ($peserta[0] as $value) {
                $peserta = get_post($value);
                $pst  .= '<li>' . $peserta->post_title . '</li> ';
            }

            \Midtrans\Config::$serverKey = 'SB-Mid-server-nfg_ilmmRSPvnW3RSa_DymDW';
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $jml_peserta = get_post_meta($result, 'jml_peserta', true);
            $harga = get_post_meta($result, 'harga', true);

            $transaction_details = array(
                'order_id' => rand(),
                'gross_amount' => $harga,
            );

            $item_details = array(
                'id' => $post_id,
                'price' => $harga,
                'quantity' => $jml_peserta,
                'name' => $order["post_title"]
            );

            $item_details = array($item_details);

            $customer_details = array(
                'first_name' => $user->display_name,
                'last_name' => '',
                'email' => $user->user_email,
                'phone' => '08562563456',
                'billing_address' => '',
                'shipping_address' => ''
            );

            $enable_payments = array('mandiri_clickpay', 'credit_card');

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
                <td>" . $order["post_title"] . "</td>
                <td><ol>" . $pst . "</ol></td>
                <td style='text-align:right;'><span style='float:left;'>Rp</span> " . number_format(get_post_meta($result, 'total_harga', true), 2) . " </td>     
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

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $nama_lengkap = $_POST['nama_lengkap'];

            // // this is required for username checks
            // require_once(ABSPATH . WPINC . '/registration.php');

            if (username_exists($username)) {
                // Username already registered
                sctn_errors()->add('username_unavailable', __('Username sudah terdaftar'));
            }

            if (!validate_username($username)) {
                // invalid username
                sctn_errors()->add('username_invalid', __('Username tidak valid'));
            }

            if ($username == '') {
                // empty username
                sctn_errors()->add('username_empty', __('Masukkan username'));
            }

            if ($nama_lengkap == '') {
                // empty username
                sctn_errors()->add('nama_lengkap_empty', __('Nama lengkap harus diisi'));
            }

            if (!is_email($email)) {
                //invalid email
                sctn_errors()->add('email_invalid', __('Email tidak valid'));
            }
            if (email_exists($email)) {
                //Email address already registered
                sctn_errors()->add('email_used', __('Email sudah terdaftar'));
            }
            if ($password == '') {
                // passwords do not match
                sctn_errors()->add('password_empty', __('Masukkan password'));
            }

            $userdata = array(
                'user_login'        => sanitize_text_field($username),
                'user_email'        => sanitize_text_field($email),
                'user_pass'         => $password,
                'display_name'      => sanitize_text_field($nama_lengkap),
                'user_firstname'    => sanitize_text_field($nama_lengkap),
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
                    $training_ref = $_SESSION['training_referer'];

                    wp_set_current_user($user_id);
                    wp_set_auth_cookie($user_id);

                    //redirect ke page bayar
                    wp_redirect(home_url('/bayar/?training_id=' . $training_ref));
                    exit();
                }
            }
        } else {
            echo "balikin ke keranjang";
        }
    }
}