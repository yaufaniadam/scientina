<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

function register_my_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

require 'update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://solusidesain-update-theme.netlify.app/scientina/theme.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'scientina'
);

/* redirect login */
function admin_login_redirect( $url, $request, $user ){
  //is there a user
  if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
      //is user admin
      if( $user->has_cap( 'administrator' ) ) {
          //go do admin stuff
          $url = admin_url();
          //but wait there's more
      }
  }
  return $url;
}
add_filter('login_redirect', 'admin_login_redirect', 10, 3 );

function staff_login_redirect( $url, $request, $user ){
  if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
      if( $user->has_cap('staff') && strpos($_REQUEST['redirect_to'], 'gf_entries') == false )  {
          //please god work
          $url = home_url() . '/resources';
          //but waittt there's more
      } else {
          //damnit all
          if( $user->has_cap('staff') && isset($_REQUEST['redirect_to']) && strpos($_REQUEST['redirect_to'], 'gf_entries') !== false) {

          $url = $_REQUEST['redirect_to'];

          }
      }
  }
  return $url;
}
add_filter('login_redirect', 'staff_login_redirect', 10, 3 );

function transient_login_redirect( $url, $request, $user ) {
  if ( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
      if (!$user->has_cap('administrator') && !$user->has_cap('staff') ) {
      //go away
      $url= home_url('/access-denied');
      }
  }
  return $url;
}
add_filter('login_redirect', 'transient_login_redirect', 10, 3);

// for thumbnail support 
add_theme_support( 'post-thumbnails' );

/* -- SET VARIABLE -- */
function add_custom_query_vars_filter($vars)
{
    $vars[] .= 'harga';
    $vars[] .= 'post_id';
    $vars[] .= 'training_id';
    $vars[] .= 'status';
    $vars[] .= 'id';
    $vars[] .= 'participant_id';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars_filter');

// Hook for adding admin menus
add_action('admin_menu', 'wpcp_coupon_add_pages');
 
 // action function for above hook

function wpcp_coupon_add_pages() {

 
  $args = array(
      'post_type'     => 'orders',
      'post_status'   => 'publish',
      'meta_query'    => array(
          'compare' => 'AND',
          array (
              'key' => 'status_pendaftaran',
              'value' => 'sudah_bayar',
              'compare' => '=',
          ),           
      ),
  );

  $query = new WP_Query( $args );

  $notification_count = $query->found_posts;
    $menu_slug = 'edit.php?post_type=training';
     add_menu_page(
        __( 'Training', 'textdomain' ),
        __( 'Training','textdomain' ),       
        'manage_options',
        $menu_slug,
        '',
        'dashicons-awards',25
    );
    add_submenu_page( 
        $menu_slug,  
        'Order',
        $notification_count ? sprintf('Order <span class="awaiting-mod">%d</span>', $notification_count) : 'Order',        
        'manage_options',
        'edit.php?post_type=orders',
        '',      
    );
    add_submenu_page( 
        $menu_slug, 
        'Participants',
        'Participants',
        'manage_options',
        'edit.php?post_type=participant',
        '',      
    );
    add_submenu_page( 
        $menu_slug, 
        'Trainer',
        'Trainer',
        'manage_options',
        'edit.php?post_type=trainer',
        '',      
    );
   

    add_menu_page(
      __( 'Coupon', 'textdomain' ),
      __( 'Coupon','textdomain' ),       
      'manage_options',
      'wpcp_coupon_page_callback',
      'wpcp_coupon_page_callback',
      'dashicons-awards',25
    );

    add_submenu_page( 
      'wpcp_coupon_page_callback',  
      'Add Coupon',
      'Add Coupon',
      'manage_options',
      'wpcp_coupon_add',
      'wpcp_coupon_add_callback',      
  );

    global $menu;

    $count = 5;
  
    $menu_item = wp_list_filter(
      $menu,
      array( 2 => 'edit.php?post_type=orders' ) // 2 is the position of an array item which contains URL, it will always be 2!
    );
  
    if ( ! empty( $menu_item )  ) {
      $menu_item_position = key( $menu_item ); // get the array key (position) of the element
      $menu[ $menu_item_position ][1] .= ' <span class="awaiting-mod">' . $count . '</span>';
    }
}

add_action('init', 'register_my_session');

function myEndSession()
{
    unset($_SESSION["cart_item"]);
}

require('inc/scientina-training.php');

require 'update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://solusidesain-update-theme.netlify.app/scientina/theme.json',
    __FILE__, //Full path to the main plugin file or functions.php.
    'scientina'
);

if (!defined('WP_DEBUG')) {
    die('Direct access forbidden.');
}

function remove_dashboard_meta()
{
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //since 3.8
    remove_meta_box('e-dashboard-overview', 'dashboard', 'normal');
}
add_action('admin_init', 'remove_dashboard_meta');

function add_dashboard_widget()
{
    wp_add_dashboard_widget("rss-feed", "Admin Scientina", "display_rss_dashboard_widget");
}

function display_rss_dashboard_widget()
{ ?>

    <h1>Selamat Datang di Scientinaskill.com</h1>
    <p>Halaman ini merupakan halaman admin</p>

<?php
}
add_action("wp_dashboard_setup", "add_dashboard_widget");


add_action('wp_enqueue_scripts', 'sctn_child_enqueue_styles', 99);
function sctn_child_enqueue_styles()
{
    // CSS
    wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('bs-css', get_template_directory_uri() . '/css/bootstrap.css');
    //JS
    wp_enqueue_script('bs-js', get_template_directory_uri() . '/js/bootstrap.min.js');     
}

function jsforwp_frontend_scripts()
{

  wp_enqueue_script(
    'scajax-js',
    get_template_directory_uri() . '/js/scajax.js',  
    ['jquery'],
    time(),
    true);

  wp_localize_script(
    'scajax-js',
    'scajax_globals',
    [
      'ajax_url'    => admin_url('admin-ajax.php'),
      'nonce'       => wp_create_nonce('scajax_nonce')
    ]
  );
}
add_action('wp_enqueue_scripts', 'jsforwp_frontend_scripts');

function jsforwp_backend_scripts()
{
  wp_enqueue_script(
    'scajax-backend-js',
    get_template_directory_uri() . '/js/scajax-backend.js',  
    ['jquery'],
    time(),
    true);

  wp_localize_script(
    'scajax-backend-js',
    'scajax_backend_globals',
    [
      'ajax_url'    => admin_url('admin-ajax.php'),
      'nonce'       => wp_create_nonce('scajax_backend_nonce')
    ]
  );
}
add_action('admin_enqueue_scripts', 'jsforwp_backend_scripts');

add_action( 'wp_ajax_cektransaksi', 'cektransaksi' );
add_action( 'wp_ajax_nopriv_cektransaksi', 'cektransaksi' );
function cektransaksi() {

    $orderid = $_POST['orderid'];
 
    check_ajax_referer( 'scajax_backend_nonce' );  

    $status = get_status_midtrans($orderid, 'transaction_status');
    $gross_amount = get_status_midtrans($orderid, 'gross_amount');
    
    $response['orderid'] = $orderid;   
    $response['status'] = $status;   
    $response['gross_amount'] = "Rp".number_format($gross_amount);   
    $response['type'] = 'success';   
  
    $response = json_encode( $response );
    echo $response;
    die();  
}

add_action( 'wp_ajax_cektransaksi_front', 'cektransaksi_front' );
add_action( 'wp_ajax_nopriv_cektransaksi_front', 'cektransaksi_front' );
function cektransaksi_front() {

    $orderid = $_POST['orderid'];
 
    check_ajax_referer( 'scajax_nonce' );  

    $status = get_status_midtrans($orderid, 'transaction_status');
    $gross_amount = get_status_midtrans($orderid, 'gross_amount');
    
    $response['orderid'] = $orderid;   
    $response['status'] = $status;   
    $response['gross_amount'] = "Rp".number_format($gross_amount);   
    $response['type'] = 'success';   
  
    $response = json_encode( $response );
    echo $response;
    die();  
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}


//panggil midtrans

\Midtrans\Config::$serverKey = 'SB-Mid-server-xBzp5AlUuPmVau-HsWkVjNLS';

function get_status_midtrans($id, $key ) {
 

  $data = \Midtrans\Transaction::status($id);
  if($key=='') {
    return $data;
  } else {
    return $data->$key;
  }
  
}

add_action("manage_orders_posts_custom_column",  "orders_custom_columns");
add_filter("manage_edit-orders_columns", "orders_edit_columns");

function orders_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Order",
    "training" => "Training",
    "peserta" => "Peserta",    
    "kupon" => "Kupon",
    "jumlah_bayar" => "Gross Amount",
    "status_daftar" => "Status Pendaftaran",
    "status" => "Status Pembayaran",
  );

  return $columns;
}

function orders_custom_columns($column){

  global $post;
  $transaction_id = get_post_meta($post->ID, 'transaction_id', true);
  $kode_kupon = get_post_meta($post->ID, 'kode_kupon', true);
  $total_harga = get_post_meta($post->ID, 'total_harga', true);
  $status_daftar = get_post_meta($post->ID, 'status_pendaftaran', true);

  switch ($column) {
    case "training":
        //Judul training
        $training = get_post(get_field('training', $post->ID)); 
        echo $training->post_title;

      break;
    case "peserta":

        $peserta = get_post_meta($post->ID, 'participant', true);

        if($peserta) {
          foreach ($peserta as $peserta) {
            echo "<a href='" . get_edit_post_link(get_post($peserta)->ID) . "'>" . get_post($peserta)->post_title ."</a>, ";
           
          }
        }

      break;
   
    case "kupon":
      if($kode_kupon) {
        echo $kode_kupon;
      }     
      break;
    case "jumlah_bayar":
      
      if($kode_kupon ) {
        echo "<strike>Rp" . number_format($total_harga)."</strike><br>";

        if ($transaction_id !='')
         {
          echo '<span class="gross_amount '.$transaction_id.'"></span>';
         } 
       
      } else {
        if ($transaction_id !='')
        {
         echo '<span class="gross_amount '.$transaction_id.'"></span>';
        } 
      }       
      
      break;
      case "status_daftar":   
               
       echo $status_daftar;

      break;
      case "status":   
               
        // //tampilkan data status midtrans, jika key kosong maka data berupa object;
        // //jika key ada, maka bisa langsung di echo;
        if($transaction_id !='') {
         echo '<a class="cektransaksi" id="' .$transaction_id.'" target="_blank" href="https://dashboard.sandbox.midtrans.com/transactions/'.$transaction_id.'"><span class="loader"><img width="50" src="' . get_template_directory_uri() . '/img/loader.gif"></span></a>';
        }
      break;
  }
}


add_action("manage_training_posts_custom_column",  "training_custom_columns");
add_filter("manage_edit-training_columns", "training_edit_columns");

function training_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Training",
    "trainer" => "Trainer",
    "tanggal" => "Tanggal",
    "tempat" => "Tempat",
    "harga" => "Harga",
    "running" => "Running",
    "online" => "Online",
  );

  return $columns;
}
function training_custom_columns($column){
  global $post;

  switch ($column) {
    case "trainer":
      $trainer = get_post_meta($post->ID, 'trainer', true);

      if($trainer) {
        foreach ($trainer as $trainer) {
          echo "<a href='" . get_edit_post_link(get_post($trainer)->ID) . "'>" . get_post($trainer)->post_title ."</a>, ";
         
        }
      }
      break;
      case "tanggal":
        echo get_post_meta($post->ID, 'tanggal_mulai', true);
        echo " - ";
        echo get_post_meta($post->ID, 'tanggal_selesai', true);
      break;
      case "tempat":

        echo get_post_meta($post->ID, 'tempat', true) . " ";
        echo get_post_meta($post->ID, 'kota', true);
      break;
      case "harga":
        echo "Rp" . number_format(get_post_meta($post->ID, 'harga', true));
      break;
      case "running":
        $running = get_post_meta($post->ID, 'running', true);     
        echo ($running) ? "Running" : "";

      break;
      case "online":
        $online = get_post_meta($post->ID, 'online', true);     
        
        echo $online[0];
     

      break;
   
  }
}

add_action("manage_participant_posts_custom_column",  "participant_custom_columns");
add_filter("manage_edit-participant_columns", "participant_edit_columns");

function participant_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Nama",
    "training" => "Training",
    "status_peserta" => "Status Peserta",
    "presensi" => "Presensi",
  );

  return $columns;
}

function participant_custom_columns($column){
  global $post;
  switch ($column) {
    case "training":
      
        $training = get_post_meta($post->ID, 'training', true);

        if($training) {
          echo get_the_title($training);
        }
      break;
    case "status_peserta":
        $status_peserta = get_post_meta($post->ID, 'status_peserta', true);
        if($status_peserta =='') {
          echo "Belum diperiksa";
        } else {
          echo $status_peserta;
        }
        break;  
      
    case "presensi":
        echo get_post_meta($post->ID, 'presensi', true);
        break;  
      
  }
}


/**
 * Add a sidebar.
 */
function wpdocs_theme_slug_widgets_init() {
  register_sidebar( array(
      'name'          => __( 'Main Sidebar', 'textdomain' ),
      'id'            => 'sidebar-1',
      'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'textdomain' ),
      'before_widget' => '',
      'after_widget'  => '',
      'before_title'  => '',
      'after_title'   => '',
  ) );
}
add_action( 'widgets_init', 'wpdocs_theme_slug_widgets_init' );


/**
 * Remove the 'all', 'publish', 'future', 'sticky', 'draft', 'pending', 'trash' 
 * views for non-admins
 */

function remove_views(  $views )
{
   
    $remove_views = [ 'all','publish','future','sticky','draft','pending','trash' ];

    foreach( (array) $remove_views as $view )
    {
        if( isset( $views[$view] ) )
            unset( $views[$view] );
    }
    return $views;
} 
add_action( 'views_edit-post', 'remove_views' );
add_action( 'views_edit-orders', 'remove_views' );
add_action( 'views_edit-participant', 'remove_views' );

function update_cpt_post_terms($post_id)
{
	if (get_post_type($post_id) != 'orders') {
		return;
	}
  //ambil status daftar
  $stat_daft = get_field('status_pendaftaran', $post_id);
  $training = get_field('training', $post_id);

  if($stat_daft == 'selesai') {

  $participant = get_field( 'participant', $post_id);
    foreach ($participant as $participant) {
      update_field('training', $training, $participant);
      update_field('status_peserta', 'disetujui', $participant);
    }

  } else  if($stat_daft == 'ditolak') {
    $participant = get_field( 'participant', $post_id);
    foreach ($participant as $participant) {
      update_field('training', $training, $participant);
      update_field('status_peserta', 'ditolak', $participant);
    }
  }


  update_field( 'order_id', $stat_daft, $post_id);

}

add_action('save_post', 'update_cpt_post_terms'); // Fixes issue with taxonomies not saving in some CPTs