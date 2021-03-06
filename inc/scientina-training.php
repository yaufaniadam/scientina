<?php

defined('ABSPATH') or die('No direct access!');

require_once('inc/cpt.php');
require_once('inc/midtrans-php/Midtrans.php');
require_once('inc/shortcode.php');
require_once('inc/coupon.php');

// training_running
add_action('elementor/query/training_running', function ($query) {
  // // Append our meta query
  $meta_query[] = [
    'key' => 'running',
    'value' => 'running',
    'compare' => 'like'
  ];
  $query->set('meta_query', $meta_query);
  $query->set('post_type', ['training']);
  $query->set('posts_per_page', 2);
});

// training_scheduled
add_action('elementor/query/training_scheduled', function ($query) {
  // // Append our meta query
  $meta_query[] = [
    'key' => 'running',
    'value' => 'scheduled',
    'compare' => 'like'
  ];
  $query->set('meta_query', $meta_query);
  $query->set('post_type', ['training']);
  $query->set('posts_per_page', 2);
});

add_action('wp_ajax_registrasi', 'registrasi');
add_action('wp_ajax_nopriv_registrasi', 'registrasi');
function registrasi()
{
  // Change the parameter of check_ajax_referer() to 'jsforwp_likes_nonce'
  check_ajax_referer('scajax_nonce');

  $isi = $_POST['isi'];
  $newarray = explode("&", $isi);

  foreach ($newarray as $key => $val) {
    $id_field = explode("=", $val);
    $field[$id_field[0]] = $id_field[1];
  }

  if ($field['submitted'] == 'daftar') {

    $email = urldecode($field['email']);
    $username = str_replace('@', '-', $email);
    $password = $field['password'];
    $telp = $field['telp'];

   
    if (($email) != '') {
      if (!is_email($email)) {
        //invalid email
        sctn_errors()->add('email_invalid', __('Email tidak valid'));
      } else {
        if (email_exists($email)) {
          //Email address already registered
          sctn_errors()->add('email_used', __('Email sudah terdaftar'));
        }
      }
    } else {
      sctn_errors()->add('email_empty', __('Email harus diisi'));
    }

    if (($password) != '') {
      if (strlen($password) < 8) {
        sctn_errors()->add('password_thooshort', __('Password minimal 8 karakter'));
      }
    } else {
      sctn_errors()->add('password_empty', __('Password harus diisi'));
    }

    if (strlen($telp) < 11) {
      sctn_errors()->add('telp_thooshort', __('Telepon minimal 10 angka'));
    } else {
      if (!is_numeric($telp)) {
        sctn_errors()->add('telp_notnumeric', __('Telepon harus berupa angka'));
      }
    }

    $errors = sctn_errors()->get_error_codes();
    $error_msgs = sctn_errors()->get_error_messages();

    $error_combine = array_combine($errors, $error_msgs);

    if (empty($errors)) {
      $userdata = array(
        'user_login'        => sanitize_text_field($username),
        'user_email'        => sanitize_text_field($email),
        'user_pass'         => $password,
        'user_registered'   => date('Y-m-d H:i:s'),
        'role'              => 'subscriber'
      );

      $user_id = wp_insert_user($userdata);

      if ($user_id) {
        // add user meta telp
        add_user_meta($user_id, 'telp', sanitize_text_field($field['telp']));

        // send an email to the admin
        wp_new_user_notification($user_id);

        //kirim email ke user yg register
        $subject = 'Pendaftaran di Scientina Skill';
        $body = 'Selamat, Anda telah terdaftar di situs Scientinaskill.com';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        wp_mail( sanitize_text_field($email), $subject, $body, $headers );

        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);      
      }       
      $error = 'noerror';      
        
    } else {
      $error = 'error';
      $response['error_code'] = $error_combine;
    }

  }  else {

    $response['button'] = $field['submitted'];
    $error = 'noerror';

  }

  if($error != 'error') {
    $wpdb = $GLOBALS['wpdb'];
        $query = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID =" . $field['post_id'], ARRAY_A);

        $itemArray = array(
          $query['ID'] => array(
            "judul" => $query["post_title"],
            "harga" => sanitize_text_field($field['harga']),
            "jml_peserta" => sanitize_text_field($field['jml_peserta']),
          )    
        );     
        
          //ambil nama users
      $userdata = get_userdata(get_current_user_id());

        $data_order = array(
          // 'post_title'    =>  $query["post_title"],
          'post_title'    =>  sanitize_text_field($userdata->user_login),
          'post_status'   => 'draft',
          'post_author'   => get_current_user_id(),
          'post_type'   => 'orders',
        );

        // Insert the post into the database.
        $result = wp_insert_post($data_order);

        if($result) {
          $total_harga = sanitize_text_field($field["harga"])*sanitize_text_field($field["jml_peserta"]);

          add_post_meta($result, 'jml_peserta', sanitize_text_field($field['jml_peserta']), true);
          add_post_meta($result, 'total_harga', $total_harga, true);
          add_post_meta($result, 'training', $query['ID'], true);
          add_post_meta($result, 'status_pendaftaran', 'awal', true);
        }
   
  }
  
  $response['error'] = $error;
  $response['type'] = 'success';
  $response['redir'] = urldecode($field['url']);
  $response = json_encode($response);
  echo $response;
  die();
}

// used for tracking error messages on registration
function sctn_errors()
{
  static $wp_error; // global variable handle
  return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

//////////////////////
///    CHECKOUT   ////
//////////////////////

add_action('wp_ajax_checkout', 'checkout');
add_action('wp_ajax_nopriv_checkout', 'checkout');
function checkout()
{
  // Change the parameter of check_ajax_referer() to 'jsforwp_likes_nonce'
  check_ajax_referer('scajax_nonce');

  $isi = $_POST['isi'];

  $newarray = explode("&", $isi);

  foreach ($newarray as $key => $val) {
    $id_field = explode("=", $val);
    $field[urldecode($id_field[0])] = urldecode($id_field[1]);
  }

  if ($field['submitted'] == 'checkout') {

    for ($i = 1; $i <= $field['jml_peserta']; $i++) {

      ${'peserta' . $i} = $field['peserta' . $i];

      if ((${'peserta' . $i}) == '') {
        sctn_errors()->add('peserta' . $i . '_empty', __("Nama peserta $i harus diisi"));
      }
    }

    $errors = sctn_errors()->get_error_codes();
    $error_msgs = sctn_errors()->get_error_messages();

    $errors = array_combine($errors, $error_msgs);

    if (empty($errors)) {
      $users = array();
      for ($i = 1; $i <= $field['jml_peserta']; $i++) {
        $data_peserta = array(
            'post_title'    => $field['peserta' . $i],
            'post_status'   => 'draft',
            'post_author'   => get_current_user_id(),
            'post_type'   => 'participant',
        );

        // Insert the post into the database.
        $users[] .= wp_insert_post($data_peserta);
        add_user_meta( $users, 'order', $field['training_id']);

      }

      //ambil nama users
      $userdata = get_userdata(get_current_user_id());

      $data_order = array(
        'ID'    =>  sanitize_text_field($field['training_id']),
        'post_title'    =>  sanitize_text_field($userdata->user_login),
        'post_status'   => 'draft',
        'post_author'   => get_current_user_id(),
        'post_type'   => 'orders',
      );

      // Insert the post into the database.
      $result = wp_update_post($data_order);

      if ($result && !is_wp_error($result)) {

        $cekkupon = detailkupon(sanitize_text_field($field['coupon']));
        $subtotal = sanitize_text_field($field['total_harga']);
       
        if($cekkupon) {
            date_default_timezone_set('Asia/Jakarta');
            $today = date('Y-m-d H:i:s');

            if (($today >= $cekkupon['start_date']) && ($today <= $cekkupon['end_date']) && ($cekkupon['quota'] > 0) ){
             
                $response['discount'] = $cekkupon['discount'];

                $mindiskon = $subtotal*($cekkupon['discount']/100);
                $diskon = $subtotal - $mindiskon;
                
            } else {
                 $diskon = $field['total_harga'];
            }       

        } else {
           $diskon = $field['total_harga'];
        }  
     
        $post_id = $field['training_id'];
        add_post_meta($post_id, 'participant', $users, true);
        add_post_meta($post_id, 'total_harga', sanitize_text_field($field['total_harga']), true);
        add_post_meta($post_id, 'total_bayar', sanitize_text_field($diskon), true);
        add_post_meta($post_id, 'kode_kupon', sanitize_text_field($field['coupon']), true);
        update_field( 'status_pendaftaran', 'tambah_peserta', $post_id);
      }

      $error = 'noerror';
    } else {
      $response['error'] = 'error';
      $response['error_code'] = $errors;
    } // empty error 
  }

  $response['redir'] = urldecode($field['url']);
  $response['type'] = 'success';
  $response = json_encode($response);
  echo $response;
  die();
}

//////////////////////
///    TRANSAKSI   ////
//////////////////////

add_action('wp_ajax_transaksi', 'transaksi');
add_action('wp_ajax_nopriv_transaksi', 'transaksi');
function transaksi()
{
  check_ajax_referer('scajax_nonce');
  $order_id = sanitize_text_field($_POST['order_id']); //order id di WP
  $result = $_POST['result'];

  update_field( 'total_bayar',  $result['gross_amount'], $order_id);
  update_field( 'transaction_time', $result['transaction_time'], $order_id);
  update_field( 'transaction_id', $result['transaction_id'], $order_id);
  update_field( 'order_id', $result['order_id'], $order_id);
  update_field( 'status_pendaftaran', 'sudah_bayar', $order_id);

  $response['order_id'] = $order_id;
  $response['result'] = $result;
  $response['type'] = 'success';
  $response = json_encode($response);
  echo $response;
  die();
}

///////////////////////
///    TRANSAKSI   ////
//////////////////////

add_action('wp_ajax_verifikasi', 'verifikasi');
add_action('wp_ajax_nopriv_verifikasi', 'verifikasi');
function verifikasi()
{
  check_ajax_referer('scajax_nonce');
  $order_id = sanitize_text_field($_POST['order_id']); //order id di WP
  $training = sanitize_text_field($_POST['training']); //order id di WP

  $participant = get_field( 'participant', $order_id);
  foreach ($participant as $participant) {
    update_field('training', $training, $participant);
    update_field('status_peserta', 'disetujui', $participant);
  }

  update_field( 'status_pendaftaran', 'selesai', $order_id);

  $response['type'] = 'success';
  $response = json_encode($response);
  echo $response;
  die();
}

function get_midtrans($transaksi) {

  $user = wp_get_current_user();

  \Midtrans\Config::$serverKey = 'SB-Mid-server-xBzp5AlUuPmVau-HsWkVjNLS';
  \Midtrans\Config::$isSanitized = true;
  \Midtrans\Config::$is3ds = true;

  $jml_peserta = $transaksi['jml_peserta'];
  $harga =  $transaksi['total_harga'];
  $post_id =  $transaksi['post_id'];

  $transaction_details = array(
    'order_id' => rand(),
    'gross_amount' => $harga,
  );

  $item_details = array(
   'id' => $post_id,
    'price' => $harga,
    'quantity' => 1,
    'name' => $transaksi['post_title']
  );

  $item_details = array($item_details);

  $customer_details = array(
    'first_name' => $user->display_name,
    'last_name' => '',
    'email' => $user->user_email,
    'phone' => get_user_meta($user->ID,'telp',true),
    'billing_address' => '',
    'shipping_address' => ''
  );

  $enable_payments = array(
    "credit_card",
    "gopay",
    "permata_va",
    "bca_va",
    "bni_va",
    "echannel",
    "other_va",
    "danamon_online",
    "mandiri_clickpay",
    "cimb_clicks",
    "bca_klikbca",
    "bca_klikpay",
    "bri_epay",
    "xl_tunai",
    "indosat_dompetku",
    "kioson",
    "Indomaret",
    "alfamart",
    "akulaku"

  );

  $transaction = array(
    'enabled_payments' => $enable_payments,
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details
  );

  return $snapToken = \Midtrans\Snap::getSnapToken($transaction);

}

