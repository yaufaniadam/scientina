<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

function register_my_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
add_action('init', 'register_my_session');

function myEndSession()
{
    unset($_SESSION["cart_item"]);
}

require_once('inc/cpt.php');
require_once('inc/midtrans-php/Midtrans.php');

require 'update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://solusidesain-update-theme.netlify.app/scientina/theme.json',
    __FILE__, //Full path to the main plugin file or functions.php.
    'scientina'
);

if (!defined('WP_DEBUG')) {
    die('Direct access forbidden.');
}


add_action('admin_init', 'my_remove_menu_pages');
function my_remove_menu_pages()
{
    global $user_ID;

    if (!current_user_can('activate_plugins')) {
        remove_menu_page('themes.php');                 //Appearance  
        remove_menu_page('plugins.php');                //Plugins  
        remove_menu_page('users.php');                  //Users  
        remove_menu_page('tools.php');                  //Tools  
        remove_menu_page('options-general.php');        //Settings  
        remove_menu_page('upload.php');
        remove_menu_page('edit.php?post_type=elementor_library');
        remove_menu_page('edit-comments.php');
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('profile.php');
    }
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

// training_running
add_action( 'elementor/query/training_running', function( $query ) {
	// // Append our meta query
	$meta_query[] = [
		'key' => 'running',
		'value' =>'"yes"',
		'compare' => 'like'
	];
	$query->set( 'meta_query', $meta_query );
    $query->set( 'post_type', [ 'training' ] );
    $query->set( 'posts_per_page', 2 ); 
} );

// training_scheduled
add_action( 'elementor/query/training_scheduled', function( $query ) {
	// // Append our meta query
	$meta_query[] = [
		'key' => 'running',
		'value' =>'"yes"',
		'compare' => 'not like'
	];
	$query->set( 'meta_query', $meta_query );
    $query->set( 'post_type', [ 'training' ] );
    $query->set( 'posts_per_page', 2 ); 
} );


add_action('wp_enqueue_scripts', 'sctn_child_enqueue_styles', 99);
function sctn_child_enqueue_styles()
{
    // CSS
    wp_enqueue_style('parent-style', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('simple-grid', get_stylesheet_directory_uri() . '/css/grid/simple-grid.min.css');

    // JS
    wp_enqueue_script('feather', 'https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js');
    wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js');  
   
}

add_action( 'admin_enqueue_scripts', 'my_admin_style');
function my_admin_style() {
    wp_enqueue_style('simple-grid', get_stylesheet_directory_uri() . '/css/grid/simple-grid.min.css');
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}


/* -- SET VARIABLE -- */
function add_custom_query_vars_filter($vars)
{
    $vars[] .= 'harga';
    $vars[] .= 'post_id';
    $vars[] .= 'training_id';
    $vars[] .= 'status';
    $vars[] .= 'id';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars_filter');

/* -- SHORTCODE -- */

/*-------- Button Beli ----------*/
add_shortcode('button_beli', 'button_beli');
function button_beli()
{
    $url = '';
    $form = '';  
    $user = wp_get_current_user();

    $form .= '<form action="' . $url . '" method="POST" class="pendaftaran">
                <table>';
        $form .=  wp_nonce_field('post_nonce', 'post_nonce_field'); 

    if (is_user_logged_in()) {         

        $form .= '
            <tr>                
                <td width="30%;">Email</td>
                <td>
                    <input type="text" class="form-control disabled" value="' . $user->user_email . '" disabled="disabled">
                </td>
            </tr>';
        $form .= '<tr>
                <td>Telp/WA</td>
                <td>
                    <input type="text" class="form-control disabled" value="' . get_user_meta($user->ID, "telp", TRUE) . '" disabled="disabled">
                </td>
            </tr>
        ';
        
    } else {
        echo '<p style="text-align:center;"><a href="' . esc_url(wp_login_url(get_permalink() . '/?training_id='.  get_the_ID())) . '">Login disini </a>jika sudah terdaftar.</p>';

        $form .= ' 
        <tr>                
            <td  width="30%;">Email* </td>
            <td><input type="text" class="form-control" name="email" id="email"  ></td>
        </tr>
        <tr>                
            <td  width="30%;">Password* </td>
            <td><input type="password" class="form-control" name="password" id="password" ></td>
        </tr>
        <tr>                
            <td>Telp/WA* </td>
            <td><input type="text" class="form-control" name="telp" id="telp"  placeholder="Cth : 085612344567">
        </tr> 
       ';
    }

    $form .= '<tr>                
                    <td>Jumlah Peserta*</td>
                    <td><input type="number"  class="form-control" name="jml_peserta" id="jml_peserta" min="1" value="1" placeholder="">
                    </td>
                </tr>';
   
    $form .= '<tr> 
                    <td>&nbsp;</td>               
                    <td><button type="submit" class="btn btn-warning btn-lg button button_beli" id="daftar"><span><i class="fas fa-edit"></i> Beli Program</span></button>
                    </td>
                </tr>';

    $submit = (is_user_logged_in())  ? 'add' : 'daftar';
    $form .= '<input type="hidden" name="harga" id="harga" value="' . get_field('harga', get_the_ID()) . '" />';
    $form .= '<input type="hidden" name="post_id" id="post_id" value="' . get_the_ID() . '" />';
    $form .= '<input type="hidden" name="submitted" id="submitted" value="' . $submit . '" />
        </table>
    </form>';   
    $form .= "<p class='tampil-data' style='margin-left:15px;'>* Wajib diisi</p>";

    return $form;
}



/*-------- Login ----------*/
add_shortcode('login', 'login');
function login()
{
    if (is_user_logged_in()) {  ?>
        <a class="loginheader" href="<?php echo wp_logout_url(home_url()); ?>">Logout</a><?php } else { ?>
            <a class="loginheader" href="<?php echo esc_url(wp_login_url()); ?>" alt="Login">
                Login
            </a><?php }
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

/*-------- Hapus Sesi ----------*/
add_shortcode('session_des', 'session_des');
function session_des()
{
    myEndSession();
    header('Location: ' . get_bloginfo('url') . '/keranjang');
}

/*-------- Halaman Keranjang ----------*/
add_shortcode('keranjang', 'keranjang');
function keranjang()
{

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
        
        $cart .= "<div class='container c-60'>";
        foreach ($_SESSION["cart_item"] as $key => $item) {
            $subtotal = $item["jml_peserta"] * $item["harga"];

            $cart .= "<div class='row' style='background:rgba(0,0,0,0.1);border-bottom:1px solid #0b635d; padding:20px;'>
             <div class='col-12'><strong>" . $item["judul"] . "</strong><br>
             
             Rp</span> " . number_format($item["harga"], 2) . " x " . $item["jml_peserta"] . " = Rp " . number_format($subtotal, 2) . "                      
            ";
            
            $cart .=  '<form action="" method="POST" class="form_checkout">';
            $cart .=  wp_nonce_field('post_nonce', 'post_nonce_field');

            $cart .= "<br>Masukkan Nama Peserta";
                        for ($i = 1; $i <= $item["jml_peserta"]; $i++) {
                            $cart .= "<input class='form-control' style='width:100%;' type='text' name='peserta[]' placeholder='Nama Peserta " . $i . "' required> ";
                        }
            $cart .= "Masukkan kode promo<br> <input class='form-control' style='width:20%;' type='text' name='coupon' id='kode_kupon'> <a class='button' style='width:30%;background:green;color:white;' id='cek_kupon'>Cek Kupon</a> 
            <p class='message_kupon' style='padding:5px 0;color:#fbff00; text-align:left;'></p>";            
            $cart .= "<input type='hidden' name='order_id' value='" . session_id() . "'>";
            $cart .= "<input type='hidden' name='harga' value='" . $item["harga"] . "'>";
            $cart .= "<input type='hidden' name='training_id' value='" . $key . "'>";
            $cart .= "<input type='hidden' name='total_harga' value='" . $subtotal . "'>";
            $cart .= "<input type='hidden' name='jml_peserta' value='" . $item["jml_peserta"] . "'>";

            $cart .= "<input type='hidden' name='submitted' value='checkout'>
            <button type='submit' class='button button_beli' id='button_checkout'>Konfirmasi Transaksi</a>";  
            $cart .= "</form>"; 

            $cart .= "</div>";         
            
            $cart .= "</div>";      
        
        }
        $cart .='</div>';      

        echo $cart;
    } else {
        echo "<p>Keranjang belanja Anda kosong.</p>";
    }

}

/*-------- bayar ----------*/
add_shortcode('bayar', 'bayar');
function bayar()
{
    
    // $user = wp_get_current_user();

    // echo '<pre>'; print_r($user); echo '</pre>';

    // $umeta = get_user_meta($user->ID, "telp", TRUE);

    $training_id = isset($_GET['training_id']) ? $_GET['training_id'] : '';

    if ($training_id != '') {
        
        if(isset($_SESSION["cart_item"][$training_id])) {
            $url_checkout = get_bloginfo('url') . "/proses-bayar";

            $jml_peserta = $_SESSION["cart_item"][$training_id]["jml_peserta"];
            $harga = $_SESSION["cart_item"][$training_id]["harga"];
            $total_harga = $jml_peserta * $harga;
            $cart = '';

        // $cart .= '<h5><strong>Pesanan Anda</strong></h5>';

        $cart .= "<div class='container'> 
                   <div class='row' style='background:#0d7d76;'>
                    <div class='col-8'><strong>" . $_SESSION["cart_item"][$training_id]["judul"] . "</strong></div> 
                    <div class='col-4'>
                    Rp</span> " . number_format($harga, 2) . " x " . $jml_peserta . " = Rp " . number_format($total_harga, 2) . "
                    </div>
                </div>
            </div><!-- .container -->
        ";

        $cart .= "<br>
            <div class='container'>               
                <div class='row'>";
                    $cart .= "<div class='col-6'>";
                    // $cart .=  "is login";
                    if (is_user_logged_in()) {

                        $cart .= "<form action='" . $url_checkout . "' method='post'>";
                        $cart .=  wp_nonce_field('post_nonce', 'post_nonce_field');

                        $cart .= "<p><strong>Masukkan Peserta</strong></p>";
                        for ($i = 1; $i <= $jml_peserta; $i++) {
                            $cart .= "<p><input class='form-control' style='width:100%;' type='text' name='peserta[]' placeholder='Nama Peserta " . $i . "' required> </p>";
                        }

                        $cart .= "<p>Masukkan kode promo <input class='form-control' style='width:20%;' type='text' name='coupon' id='kode_kupon' value='XYZ5'> <a class='button' style='width:30%;background:green;' id='cek_kupon'>Cek Kupon</a> </p>";

                        $cart .= "<input type='hidden' name='training_title' value='" . $_SESSION["cart_item"][$training_id]["judul"] . "'>";
                        $cart .= "<input type='hidden' name='training_id' value='" . $training_id . "'>";
                        $cart .= "<input type='hidden' name='total_harga' value='" . $total_harga . "'>";
                        $cart .= "<input type='hidden' name='harga' value='" . $harga . "'>";
                        $cart .= "<input type='hidden' name='jml_peserta' value='" . $jml_peserta . "'>";

                        $cart .= "<input type='hidden' name='submitted' value='bayar'>
                                    <p><button type='submit' class='button button_checkout'>Lanjut ke Pembayaran</button></p>";
                        $cart .= "</form>";
                    }

                $cart .= '</div><!-- .col-6 -->';
        $cart .= '<div class="col-6">';
              

        $cart .= '</div><!-- .col-6 -->';

        $cart .= '</div><!-- .row -->
                </div> <!--.container -->';
        echo $cart;
        } else {
            echo "Sesi telah habis";
            $url = get_bloginfo('url');
            echo("<script>location.href = '".$url."';</script>");
        }
        

    } else {
        echo "kenapa eror";
        ob_start();
        wp_redirect(get_bloginfo('url') . '/keranjang');exit;
    }



    echo '<script>
    feather.replace();    
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>';   

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

/*-------- Trainer ----------*/
add_shortcode('total_training', 'total_training');
function total_training()
{
    $count_posts = wp_count_posts($post_type = 'training');

    if ($count_posts) {
        return $published_posts = $count_posts->publish;
    }
}

/*-------- Tempat ----------*/
add_shortcode('cek_running', 'cek_running');
function cek_running($atts)
{
    global $post;
    $a = shortcode_atts(array(
        'lokasi' => 'single',
    ), $atts);

   $running = get_field('running', $post->ID);
   
   if( $running && in_array('yes', $running) ) {    
        $running = 'Running';
    } else {
        $running = 'Scheduled';
    }

    return "<p class='cek_running " . $a['lokasi'] . " " . $running . "'>" . $running . "</p>";
    wp_reset_postdata();
}


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


/*-------- Harga ----------*/
add_shortcode('harga', 'harga');
function harga()
{
    $harga = get_field('harga', get_the_ID());

    return number_format($harga) .",00";
}


// used for tracking error messages on registration
function sctn_errors()
{
    static $wp_error; // global variable handle
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}


// ta,bah kolom telpon pada page Users
function new_modify_user_table( $column ) {
    $column['telp'] = 'Telp/WA';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'telp' :
            return  get_user_meta( $user_id, 'telp', true );
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );

///////////////////////////////
//          COUPON           //
///////////////////////////////


 // Hook for adding admin menus
 add_action('admin_menu', 'wpcp_coupon_add_pages');
 
 // action function for above hook
 
function wpcp_coupon_add_pages() {
    $menu_slug = 'wpcp-coupon';
     add_menu_page(
        __( 'All Coupons', 'textdomain' ),
        __( 'Coupons','textdomain' ),
        'manage_options',
        $menu_slug,
        'wpcp_coupon_page_callback',
        'dashicons-tag',25
    );
    add_submenu_page( 
        $menu_slug,  
        'Add Coupon',
        'Add Coupon',
        'manage_options',
        'wpcp_coupon_add',
        'wpcp_coupon_add_callback',      
    );
    add_submenu_page( 
        'hide',  
        'Edit Coupon',
        'Edit Coupon',
        'manage_options',
        'wpcp_coupon_edit',
        'wpcp_coupon_edit_callback',      
    );
}


 
/**
 * Disply callback for the Unsub page.
 */

 function wpcp_coupon_page_callback() {
     
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cp_coupon", ARRAY_A);
    ?>

    <div class="wrap">
        <h1 class="wp-heading-inline">Coupons</h1>
        <a href="admin.php?page=wpcp_coupon_add" class="page-title-action">Tambah Baru</a>
        <hr class="wp-header-end">

        <table class="wp-list-table widefat fixed striped table-view-list pages" style="margin-top:8px;">
        <thead>
            <tr>
                <th scope="col" id="title" ><span>Program</span></th>                	
                <th scope="col" id="title" ><span>Coupon Code</span></th>                	
                <th scope="col" id="date"><span>Discount</span></th>	
                <!-- <th scope="col" id="date"><span>Type</span></th> -->
                <th scope="col" id="author">Start Date</th>     
                <th scope="col" id="date"><span>End Date</span></th>	
                <th scope="col" id="date"><span>Active</span></th>	
                <th scope="col" id="date"><span>Delete</span></th>	
            </tr>
        </thead>
        <tbody>
            <?php foreach($results as $result) { ?>
            <tr>
                <td><a href="admin.php?page=wpcp_coupon_add&id=<?php echo $result['id']; ?>"><?php echo $result['program']; ?></a></td>
                <td><?php echo $result['code']; ?></td>
                <td><?php echo $result['discount']; ?></td>
                <!-- <td><?php echo $result['type']; ?></td> -->
                <td><?php echo $result['start_date']; ?></td>
                <td><?php echo $result['end_date']; ?></td>
                <td><?php echo $result['active']; ?></td>
                <td>Delete</td>
            </tr>
            <?php } ?>
        </tbody>
        </table>
    
    </div>

     <!-- generate_coupon_code(5, 1); -->

<?php }

function wpcp_coupon_add_callback() { 
    
    if (isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

        $program = $_POST['program'];
        $code = $_POST['code'];
        $discount = $_POST['discount'];
        $type = 1;
        $start_date = strftime('%Y-%m-%d %H:%M:%S', strtotime(sanitize_text_field($_POST['start_date'])));
        $end_date = strftime('%Y-%m-%d %H:%M:%S', strtotime(sanitize_text_field($_POST['end_date'])));
        $active = isset($_POST['aktif']) ? 1 : 0;

        global $wpdb;
        $table = $wpdb->prefix.'cp_coupon';
        $data = array(
            'program' => $program, 
            'code' => $code, 
            'discount' => $discount, 
            'type' => $type, 
            'start_date' => $start_date, 
            'end_date' => $end_date,
            'active' => $active
        );
        $format = array('%s','%s', '%d', '%d', '%s', '%s', '%d');

        if($_POST['submitted'] == 'add') {
            $wpdb->insert($table,$data,$format);
            $my_id = $wpdb->insert_id;

            $url= 'admin.php?page=wpcp_coupon_add&id=' .$my_id;

            // echo("<script>location.href = '".$url."';</script>");
        } else {
            $id = $_POST['id'];
            $where = [ 'id' => $id ];
            $wpdb->update($table,$data,$where, $format);
            $url= 'admin.php?page=wpcp_coupon_add&id=' .$id;

            echo("<script>location.href = '".$url."';</script>");
        }

    } else {

        if(isset($_GET['id'])) {
            $id = $_GET['id'];

            global $wpdb;
            $results = $wpdb->get_row("SELECT *, 
            DATE_FORMAT(start_date, '%Y-%m-%dT%H:%i') AS cstart_date, 
            DATE_FORMAT(end_date, '%Y-%m-%dT%H:%i') AS cend_date  
            FROM {$wpdb->prefix}cp_coupon
            WHERE id= $id ", ARRAY_A);
        }
    ?>

<style>
    .form-control {
        width:100%;
    }
</style>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo (isset($id)) ? 'Edit': 'Add'; ?> Coupon</h1>
    </div>




<div class="container">
    <form action='' method='post'>
        <?php wp_nonce_field('post_nonce', 'post_nonce_field'); 
        
        if(isset($id)) {
            echo '<input type="hidden" name="id" value="' . $id .'">'; 
        }
        ?>
        <div class="row">
            <div class="col-2">
                Program *
            </div>
            <div class="col-7">
                <input type="text" class="form-control" name="program" id="program"  placeholder="Ex: New Year Sale" required value="<?php echo (isset($id)) ? $results['program']: ''; ?>">
            </div>
        </div>    
        <div class="row">
            <div class="col-2">
                Start Date
            </div>
            <div class="col-7">
                <input type="datetime-local" class="form-control" name="start_date" id="start_date"  placeholder="" required value="<?php echo (isset($id)) ? $results['cstart_date']: ''; ?>">
            </div>
        </div>    
        <div class="row">
            <div class="col-2">
                End Date
            </div>
            <div class="col-7">
                <input type="datetime-local" class="form-control" name="end_date" id="end_date"  placeholder="" required value="<?php echo (isset($id)) ? $results['cend_date']: ''; ?>">
            </div>
        </div>    
        <div class="row">
            <div class="col-2">
                Coupon Code *
            </div>
            <div class="col-7">
                <input type="text" class="form-control" name="code" id="code"   value=
                "<?php echo (isset($id) && ($results['code'] != '')) ? $results['code']:  generate_coupon_code(5, 1); ?>" required>
            </div>
        </div>    
        <!-- <div class="row">
            <div class="col-2">
                Type *
            </div>
            <div class="col-7">
                <input type="radio" class="form-control" name="type[]" value="1"> %
                <input type="radio" class="form-control" name="type[]" value="2"> Rp
            </div>
        </div>     -->
        <div class="row">
            <div class="col-2">
                Value Discount*
            </div>
            <div class="col-7">
                <input type="text" class="form-control" name="discount" id="discount"  placeholder="Ex: 50" style="width:60%" required value="<?php echo (isset($id)) ? $results['discount']: ''; ?>"> %
            </div>
        </div>    
        <div class="row">
            <div class="col-2">
                Active
            </div>
            <div class="col-7">
                <input type="checkbox" name="aktif" id="active" <?php echo (isset($id) && $results['active'] == 1) ? 'checked': ''; ?>> click to activate/deactivate this program
            </div>
        </div>    
        <div class="row">
            <div class="col-2">
               
            </div>
            <div class="col-7">
                <input type='hidden' name='submitted' value='<?php echo (isset($id)) ? 'edit': 'add'; ?>'>
                <p><button type='submit' class='button button_checkout'><?php echo (isset($id)) ? 'Edit': 'Add'; ?> Coupon</button></p>
            </div>
        </div>    
       
        
    </form>  
</div>     

<?php
    } //endif submit 
}

/* generate coupon code */
function generate_coupon_code($length, $num) {
    for($i=0; $i < $num; $i++) {
        $randomletter = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTU"), 0, $length);
        echo $randomletter;
    }
 }

 
add_action( 'wp_ajax_cekkupon', 'cekkupon' );
add_action( 'wp_ajax_nopriv_cekkupon', 'cekkupon' );
function cekkupon() {
    // Change the parameter of check_ajax_referer() to 'jsforwp_likes_nonce'
    check_ajax_referer( 'scajax_nonce' );  
    
    $kupon = sanitize_text_field($_POST['kupon']);
    
    global $wpdb;
    $results = $wpdb->get_row("SELECT *, 
        DATE_FORMAT(start_date, '%Y-%m-%dT%H:%i') AS cstart_date, 
        DATE_FORMAT(end_date, '%Y-%m-%dT%H:%i') AS cend_date  
        FROM {$wpdb->prefix}cp_coupon
        WHERE code= '$kupon'", ARRAY_A);
       
    if($results) {
        date_default_timezone_set('Asia/Jakarta');
        $today = date('Y-m-d H:i:s');

        if (($today >= $results['start_date']) && ($today <= $results['end_date']) && ($results['quota'] > 0) ){
            $valid= 1;
            $response['discount'] = $results['discount'];
        } else {
            $valid = 0;  
        }       

    } else {
        $valid = 0; 
    }  
    
    if($valid == 0 ) {
        $message ='Kupon tidak valid';
    } else {
        $message = 'Kupon valid. Anda akan mendapatkan potongan pembayaran sebesar ' . $response['discount'] .'%';
    }

    $response['valid'] = $valid;
    $response['message'] = $message;
    $response['type'] = 'success';   
  
    $response = json_encode( $response );
    echo $response;
    die();  
}

//////////////////////
///    CHECKOUT   ////
//////////////////////

add_action( 'wp_ajax_checkout', 'checkout' );
add_action( 'wp_ajax_nopriv_checkout', 'checkout' );
function checkout() {
    // Change the parameter of check_ajax_referer() to 'jsforwp_likes_nonce'
    check_ajax_referer( 'scajax_nonce' );   
    
    $isi = $_POST['isi'];

    $newarray = explode("&", $isi);

	foreach ($newarray as $key => $val) {
    	$id_field = explode("=", $val);
		$field[$id_field[0]] = $id_field[1];
    }

    echo '<pre>'; print_r($field); echo '</pre>';
       
    // $harga = $field['peserta[]'];       
    
    // $response['harga'] = $harga; 
    $response['type'] = 'success'; 
    $response = json_encode( $response );
    echo $response;
    die();  
}