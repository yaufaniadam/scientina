<?php

/* -- SHORTCODE -- */
/*-------- Button Beli ----------*/
add_shortcode('button_beli', 'button_beli');
function button_beli()
{
    $post_id = get_the_ID();  

    $url = '';
    $form = '';  
    $user = wp_get_current_user();   

    if (!is_user_logged_in()) { 

        $form .= '<h5 class="text-center">Beli Program Ini</h5>
        
        <ul class="my-4 nav nav-pills d-flex justify-content-center">
        <li class="nav-item">
          <a class="nav-link aktif text-center" aria-current="page" href="#">
          <span class="badge rounded-pill">1</span><br>
          Buat Akun</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center" aria-current="page" href="#">
            <span class="badge rounded-pill">2</span><br>
            Tambah Peserta</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center" aria-current="page" href="#">
            <span class="badge rounded-pill">3</span><br>
            Bayar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center" aria-current="page" href="#">
            <span class="badge rounded-pill">4</span><br>
            Selesai</a>
        </li>
        
      </ul>';

        $form .= '<div id="load">
        
        <form action="' . $url . '" method="POST" class="pendaftaran p-3">
        
        <input type="hidden" name="url" id="url" class="" value="'. get_the_permalink() .'">';

        $form .= '<p style="text-align:center;"><a href="' . esc_url(wp_login_url(get_permalink())) . '">Login disini </a>jika sudah terdaftar.</p>';

        $form .= "<div id='daftar_error'><span class='alert alert-danger d-block'>Error! Periksa kembali.</span></div>";
        
        $form .= ' 
        <div class="row mb-2">
            <label for="email" class="col-sm-4 col-form-label">Email*</label>
            <div class="col-sm-8">
                <input type="text" name="email" id="email" class="form-control" value="">
                <span class="invalid-feedback error email_empty email_invalid email_used"></span>
            </div>
        </div>
        <div class="row mb-2">
            <label for="password" class="col-sm-4 col-form-label">Password*</label>
            <div class="col-sm-8">
                <input type="password" name="password" id="password" class="form-control" value="">
                <span class="invalid-feedback error password_thooshort password_empty"></span>
            </div>
        </div>
        <div class="row mb-2">
            <label for="telp" class="col-sm-4 col-form-label">Telp/WA* </label>
            <div class="col-sm-8">
                <input type="text" name="telp" id="telp" class="form-control" value="" placeholder="Contoh : 085612344567">
                <span class="invalid-feedback error telp_thooshort telp_notnumeric"></span>
            </div>
        </div>';

        $form .= "<input type='hidden' name='submitted' value='daftar'>";     
        
        $form .= '<div class="row">
            <label for="jml_peserta" class="col-sm-4 col-form-label">Jumlah Peserta* </label>
            <div class="col-sm-8">
                <input type="number" name="jml_peserta" id="jml_peserta" class="form-control" value="1" min="1" value="1">
            </div>
        </div>';   
 
        $form .= '<input type="hidden" name="harga" id="harga" value="' . get_field('harga', get_the_ID()) . '" />';
        $form .= '<input type="hidden" name="post_id" id="post_id" value="' . get_the_ID() . '" />'; 
        $form .= '<div class="form-group row mt-3">
                <label for="button" class="col-sm-4 col-form-label d-none d-sm-block">&nbsp;</label>
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-warning btn-md button button_beli" id="daftar">
                        <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                        Selanjutnya <i class="fas fa-arrow-right"></i>               
                    </button> 
                    </div>
                </div>'; 
        $form .= "<p class='tampil-data text-white'>* Wajib diisi</p>
        </form>
        ";

        $form .= "</div>";

    } else {       

        // dia login
        // cek status transaksi dari user yg login ini, jika ada transaksi, maka jalankan transaksi
        $status_transaksi = status_transaksi(get_current_user_id(), $post_id);

        if($status_transaksi == 0) {
            $form .= '<h5 class="text-center">Beli Program Inie</h5>
            
            <ul class="my-4 nav nav-pills d-flex justify-content-center">
               
                <li class="nav-item">
                    <a class="nav-link aktif text-center" aria-current="page" href="#">
                    <span class="badge rounded-pill">1</span><br>
                    Mulai</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" aria-current="page" href="#">
                    <span class="badge rounded-pill">2</span><br>
                    Tambah Peserta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" aria-current="page" href="#">
                    <span class="badge rounded-pill">3</span><br>
                    Bayar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" aria-current="page" href="#">
                    <span class="badge rounded-pill">4</span><br>
                    Selesai</a>
                </li>
                
            </ul>';
     
            $form .= '<div id="load" class="p-2">

            <form action="' . $url . '" method="POST" class="pendaftaran">
            
            <input type="hidden" name="url" id="url" class="" value="'. get_the_permalink() .'">';
            $form .= "<input type='hidden' name='submitted' value='add'>"; 

            $form .= '<div class="form-group row">
            <label for="jml_peserta" class="col-sm-4 col-form-label">Jumlah Peserta* </label>
            <div class="col-sm-8">
                <input type="number" name="jml_peserta" id="jml_peserta" class="form-control" value="1" min="1" value="1">
            </div>
            </div>';   
 
            $form .= '<input type="hidden" name="harga" id="harga" value="' . get_field('harga', get_the_ID()) . '" />';
            $form .= '<input type="hidden" name="post_id" id="post_id" value="' . get_the_ID() . '" />'; 
            $form .= '<input type="hidden" name="status_pendaftaran" value="awal" />'; 
            $form .= '<div class="form-group row mt-3">
                    <label for="button" class="col-sm-4 col-form-label d-none d-sm-block">&nbsp;</label>
                    <div class="col-sm-8">
                        <button type="submit" class="btn btn-warning btn-md button button_beli" id="daftar">
                            <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span class="visually-hidden">Loading...</span>
                            Selanjutnya <i class="fas fa-arrow-right"></i>                
                        </button> 
                        </div>
                    </div>'; 
            $form .= "<p class='tampil-data text-white'>* Wajib diisi</p>
            </form>
            ";
            $form .= "</div> "; //#load close

        } else {

            foreach ($status_transaksi as $post) { 
                $jml_peserta = get_field('jml_peserta', $post->ID);  
                $total_harga = get_field('total_harga', $post->ID);  
                $total_bayar = get_field('total_bayar', $post->ID);  
                $status_pendaftaran = get_field('status_pendaftaran', $post->ID);       
                $training = get_post(get_field('training', $post->ID));       
    
                $nonce = wp_create_nonce( 'scajax_nonce' );
                // $total_harga = $field["harga"]*$field["jml_peserta"];
                $html = '';    


                if($status_pendaftaran == 'awal') {

                    $form .= '<h5 class="text-center">Beli Program Ini</h5>';
                    $form .= '                    
                    <ul class="my-4 nav nav-pills d-flex justify-content-center">
                        <li class="nav-item">
                        <a class="nav-link text-center" aria-current="page" href="#">
                        <span class="badge rounded-pill">1</span><br>
                        Mulai</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link aktif text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">2</span><br>
                            Tambah Peserta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">3</span><br>
                            Bayar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">4</span><br>
                            Selesai</a>
                        </li>
                        
                    </ul>';

                    $form .=  '<div id="load" class="p-2">
                    <form action="" method="POST" class="form_checkout">';
                    $form .=  '<input type="hidden" name="nonce" value="'. $nonce .'">';
                    $form .=  '<input type="hidden" name="total_harga" id="total_harga" value="'. $total_harga .'">';
                    $form .=  '<input type="hidden" name="jml_peserta" id="jml_peserta" value="'. $jml_peserta .'">';
                    for ($i = 1; $i <= $jml_peserta; $i++) {
                    $form .= '<div class="mb-2 row">
                            <label for="nama peserta" class="col-sm-4 col-form-label">Nama Peserta '.$i.'*</label>
                            <div class="col-sm-8">
                            <input type="text" name="peserta'.$i.'" class="form-control"  value="" required>
                            <span class="invalid-feedback error peserta' . $i. '_empty"></span>
                            </div>
                        </div>';
                    }
                    $form .='<div class="mb-2 row">
                        <label for="nama peserta" class="col-sm-4 col-form-label">Punya kupon?</label>
                        <div class="col-sm-8">    
                        <div class="input-group mb-3">              
                            <input type="text" class="form-control" name="coupon" value="" id="kode_kupon" style="width:100px !important;">
                            <span class="btn btn-info" style="border-top-right-radius:5px;border-bottom-right-radius:5px" type="button" id="cek_kupon">
                            Cek Kupon</span>
                            <span class="invalid-feedback message_kupon d-block" ></span>
                        </div>
                        </div>
                    </div>';
            
                    $form .= "<p><strong>" . get_the_title() . "</strong><br>             
                        Rp</span> " . number_format($total_harga) . " x " . $jml_peserta . " = Rp <span class='harga_asli'><span class='tru'></span>" . number_format($total_harga) . "</span> &nbsp;<span class='harga_baru'></span>                      
                    </p>";
                    $form .= "<input type='hidden' id='training_id' name='training_id' value='". $post->ID ."'>";
                    $form .= "<input type='hidden' id='training_title' name='training_title' value='". $post->post_title ."'>";
                    $form .= '<input type="hidden" name="url" id="url" class="" value="'. get_the_permalink() .'">';
                    $form .= "<input type='hidden' id='total_harga' name='total_harga' value='".$total_harga."'>";
                    $form .= "<input type='hidden' name='submitted' value='checkout'>
                        <hr style='border-top:1px solid white; padding:10px 0;'>
                        <p><button type='submit' class='button button_beli btn btn-warning' id='button_checkout'>
                            <span class='spinner-border text-light spinner-border-sm d-none' role='status' aria-hidden='true'></span>
                                <span class='visually-hidden'>Loading...</span>
                                Selanjutnya <i class='fas fa-arrow-right'></i> 
                                </button></p>";  
                    $form .= "</form></div>"; //#load close 
                
                }  else if($status_pendaftaran == 'tambah_peserta') {
                   
                    $transaksi = array(
                        'post_id' => $post->ID,
                        'post_title' => $post->post_title,
                        'total_harga' => $total_bayar,
                        'jml_peserta' => $jml_peserta,
                    );

                    $snapToken = get_midtrans($transaksi);

                    $form .= '<h5 class="text-center">Beli Program Ini</h5>';
                    $form .= '                    
                    <ul class="my-4 nav nav-pills d-flex justify-content-center">
                        <li class="nav-item">
                        <a class="nav-link text-center" aria-current="page" href="#">
                        <span class="badge rounded-pill">1</span><br>
                        Mulai</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">2</span><br>
                            Tambah Peserta</a>
                        </li>
                        <li class="nav-item bayar">
                            <a class="nav-link aktif text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">3</span><br>
                            Bayar</a>
                        </li>
                        <li class="nav-item selesai">
                            <a class="nav-link text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">4</span><br>
                            Selesai</a>
                        </li>
                        
                    </ul>';

                $form .= '<div id="bayar-midtranss" class=" ms-2"> 
                <table class="table bg-primary">
                    <tr>
                        <td>Training</td>
                        <td>: ' . $training->post_title . ' (' . $jml_peserta . ' peserta)</td>
                    </tr>
                    <tr>
                        <td>Total Bayar</td>
                        <td>: Rp: ' . number_format($total_bayar) . '</td>
                    </tr>
                </table>

                <button id="pay-button" class="btn btn-warning btn-block">Bayar Sekarang</button>

                <div id="bayar-sukses"></div>
                <div id="result-json"></div>
                <br />
                ';
                $form .= '
                <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-NUHDTW6uipcvE7sz"> </script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
                <script type="text/javascript"> 
                $("#pay-button").on("click", function() {                 
                    snap.pay("' . $snapToken . '", {
                        
                        onSuccess: function(result){  
                      
                            $.ajax( {
                                type : "post",
                                dataType : "json",
                                url : scajax_globals.ajax_url,
                                data : {
                                action: "transaksi",
                                kode_transaksi : 200,
                                result : result,
                                order_id : ' . $post->ID . ',
                                _ajax_nonce: scajax_globals.nonce
                                },
                                
                                beforeSend: function ( xhr ) {
                                    console.log("Loading ...")   
                                },

                                success: function( response ) {
                                    if( "success" == response.type ) {
                                        $("#pay-button").hide();
                                        $("#bayar-sukses").html("Terima kasih, pembayaran Anda berhasil.");
                                        $(".nav-item.bayar a").removeClass("aktif");
                                        $(".nav-item.selesai a").addClass("aktif");
                                    }
                                    else {
                                       alert( "Error" );
                                    }
                                 },                  
                            });  

                        },
                        onPending: function(result) {
                            $.ajax( {
                                type : "post",
                                dataType : "json",
                                url : scajax_globals.ajax_url,
                                data : {
                                action: "transaksi",
                                kode_transaksi : 200,
                                result : result,
                                order_id : ' . $post->ID . ',
                                _ajax_nonce: scajax_globals.nonce
                                },
                                beforeSend: function ( xhr ) {
                                    console.log("Loading ...")                           
                                   
                                },
                                success: function( response ) {
                                    if( "success" == response.type ) {
                                        $("#pay-button").hide();
                                        $("#bayar-sukses").html("Pembayaran Anda sedang kami periksa.");
                                    }
                                    else {
                                       alert( "Error" );
                                    }
                                 },                  
                            });
                        },
                        onError: function(result) {
                            $.ajax( {
                                type : "post",
                                dataType : "json",
                                url : scajax_globals.ajax_url,
                                data : {
                                action: "transaksi",
                                kode_transaksi : 200,
                                result : result,
                                order_id : ' . $post->ID . ',
                                _ajax_nonce: scajax_globals.nonce
                                },
                                beforeSend: function ( xhr ) {
                                    console.log("Loading ...") 
                                },
                                success: function( response ) {
                                    if( "success" == response.type ) {
                                        $("#pay-button").hide();
                                        $("#bayar-sukses").html("Maaf pembayaran Anda gagal.");
                                    }
                                    else {
                                       alert( "Error" );
                                    }
                                 },                  
                            });
                        },
                        onClose: function(){
                            /* You may add your own implementation here */
                            console.log("Closed");
                        }
                    });

                    
                });    
                     
            </script></div>';

                } else {
                    
                    $form .= '<h5 class="text-center">Beli Program Ini</h5>';
                    $form .= '                    
                    <ul class="my-4 nav nav-pills d-flex justify-content-center">
                        <li class="nav-item">
                        <a class="nav-link text-center" aria-current="page" href="#">
                        <span class="badge rounded-pill">1</span><br>
                        Mulai</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">2</span><br>
                            Tambah Peserta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">3</span><br>
                            Bayar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link aktif text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">4</span><br>
                            Selesai</a>
                        </li>
                        
                    </ul>';

                    $form .= "<div class='py-3 px-3 pe-2' ><span class='alert alert-warning d-block;' style='display:block;width:100%;'><i class='fas fa-exclamation-triangle'></i> Anda sudah terdaftar di program ini</span></div>";
                    
                }                    
            }
        }
    }

    return $form;

    
}

/*-------- Login ----------*/
add_shortcode('login', 'login');
function login()
{
    if (is_user_logged_in()) {  ?>
        <a class="loginheader" href="<?php echo get_bloginfo('url'); ?>/account">Akun Saya</a>
        <a class="loginheader" href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
        
        <?php } else { ?>
            <a class="loginheader" href="<?php echo esc_url(wp_login_url()); ?>" alt="Login">
                Login
            </a><?php }
}

/*-------- Tanggal ----------*/
add_shortcode('tanggal', 'tanggal');
function tanggal($atts)
{
    $a = shortcode_atts(array(
        'lokasi' => '',
        'training_id' => '',
    ), $atts);

    // $date = new DateTime(get_field('tanggal_mulai'));
    $mulai = get_field('tanggal_mulai', $a['training_id']);
    $selesai = get_field('tanggal_selesai', $a['training_id']);

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
        'training_id' => '',
    ), $atts);

    $running = get_field('running', $a['training_id']);
   
    return "<p class='cek_running " . $a['lokasi'] . " " . $running . "'>" . $running . "</p>";
    wp_reset_postdata();
}


/*-------- Tempat ----------*/
add_shortcode('tempat', 'tempat');
function tempat($atts)
{
    $a = shortcode_atts(array(
        'lokasi' => '',
        'training_id' => '',
    ), $atts);

    $online = get_field('online', $a['training_id']);
    if ($online && in_array('Offline', $online)) {
        if ($a['lokasi'] == 'loop') {
            $tempat = get_field('kota', $a['training_id']);
        } else {
            $tempat = get_field('tempat', $a['training_id']) . ' ' . get_field('kota', $a['training_id']);
        }
    } else {
        // $tempat = get_field('kota', $a['training_id']);
        $tempat = get_field('kota', $a['training_id']);
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

    return number_format($harga);
}

function status_transaksi($user_id, $post_id) {    
    $args = array(
        'post_type'     => 'orders',
        'post_status'   => 'draft',
        'author'   => $user_id,
        'meta_query'    => array(
            'compare' => 'AND',
            array (
                'key' => 'training',
                'value' => $post_id,
                'compare' => '=',
            ),           
        ),
    );

    $postslist = get_posts( $args );

    if($postslist) {      

        return $postslist;

    } else {

        return 0;
        // echo "gada transaksi";
    }
}


/*-------- Account ----------*/
add_shortcode('member_account', 'account');
function account()
{
    if (is_user_logged_in()) { 
        
        
   

    $html = '<div class="row">
                <div class="col-md-8 offset-md-2">
                  
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#home" class="nav-link active" data-bs-toggle="tab">Order History</a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile" class="nav-link" data-bs-toggle="tab">Ubah Password</a>
                        </li>
                       
                    </ul>
                    <div class="tab-content py-4">
                        <div class="tab-pane fade show active" id="home">';
                                    
                                $args = array(
                                    'post_type'     => 'orders',
                                    'post_status'   => 'publish',
                                    'author'   => get_current_user_id(),
                                    
                                );

                                $query = new WP_Query( $args );

                                $html .= '<table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Training</th>
                                                    <th>Tanggal</th>
                                                    <th>Peserta</th>
                                                    <th>Biaya</th>
                                                    <th>Proses Daftar</th>
                                                </tr>
                                            </thead>
                                        ';

                                    foreach ($query->posts as $query) {

                                        $transaction_id = get_post_meta($query->ID, 'transaction_id', true);
                                        $kode_kupon = get_post_meta($query->ID, 'kode_kupon', true);
                                        $total_harga = get_post_meta($query->ID, 'total_harga', true);
                                        $status_daftar = get_post_meta($query->ID, 'status_pendaftaran', true);
                                        $training_id = get_field( 'training', $query->ID);
                                        $tanggal_mulai = get_field('tanggal_mulai',$training_id);
                                        $tanggal_selesai = get_field('tanggal_selesai',$training_id);
                                        $e_mulai = explode(' ', $tanggal_mulai);
                                        $e_selesai = explode(' ', $tanggal_selesai);
                                        $tanggal = date('d M Y', strtotime($e_mulai[0]));

                                        if($kode_kupon ) {
                                                                                
                                            if ($transaction_id !='')
                                             {
                                                $harga = "<strike>Rp" . number_format($total_harga)."</strike><br>                                                
                                                <span class='gross_amount ".$transaction_id."'>Rp" . number_format(get_status_midtrans($transaction_id,'gross_amount'))." </span>";
                                             } 
                                           
                                          } else {
                                            if ($transaction_id !='')
                                            {
                                             $harga = "<span class='gross_amount ".$transaction_id."'>Rp" . number_format(get_status_midtrans($transaction_id,'gross_amount'))." </span>";
                                            } 
                                          }     

                                        $html .= "<tr>";
                                            $html .= "<td>" . get_the_title($training_id) . "</td>";                                           
                                            $html .= "<td>" . $tanggal . "</td>";                                           
                                            $html .= "<td>";
                                            
                                            $html .= "<ul>";
                                                $participant = get_field( 'participant', $query->ID);
                                                foreach ($participant as $participant) {

                                                    $hadir = get_field( 'presensi', $participant);

                                            
                                                    if( $hadir == 1) {
                                                        $sertifikat = "<a target='_blank' href='" . get_bloginfo('url') ."/sertifikat/?participant_id=" .$participant."'> (Sertifikat) </a>";
                                                    } else {
                                                        $sertifikat = '';
                                                    }

                                                    $html .= "<li>" . get_the_title($participant) . "  " . $sertifikat . " </li>";
                                                }

                                            $html .= "</ul>";


                                            $html .= "</td>";
                                            $html .= "<td>" . $harga . "</td>";
                                            $html .= "<td>" . $status_daftar . "</td>";
                                        $html .= "</tr>";
                                    }
                                $html .= "</table>";
                                    
                                $html .='</div>
                                    <div class="tab-pane fade" id="profile">
                                        <p>Ubah Password di sini</p>
                                    </div>
                                    
                                </div>         

                            
                            </div>
                        </div>';

        } else {
            $html = "Anda tidak berhak mengakses halaman ini";
        }

    return $html;
}

/*-------- Judul Dinamis untuk admin training ----------*/
add_shortcode('judul', 'judul');
function judul()
{
    $training_id = isset($_GET['training_id']) ? $_GET['training_id'] : "";

    if($training_id > 0) {
        return "<a href='" . get_bloginfo('url') . "/admin-training/detail-training/?training_id=" . $training_id . "'>" . get_the_title($training_id) . "</a>";
    } else {
        return "<a href='" . get_bloginfo('url') . "/admin-training/detail-training/?training_id=" . get_the_ID() . "'>" . get_the_title() . " (Rp " . get_field('harga', get_the_ID()) . ")</a>";
    }
    
}

/*-------- Judul Dinamis untuk nampilin peserta ----------*/
add_shortcode('judul_peserta', 'judul_peserta');
function judul_peserta()
{
    
    return "<a href=''>" . get_the_title() . "</a>";
    
    
}

function get_order_by_participant($participant_id) {
    $args = array(
        'post_type'     => 'orders',
        'post_status'   => array( 'pending', 'draft', 'publish' ),
        'meta_query'    => array(
            'compare' => 'AND',
            array (
                'key' => 'participant',
                'value' =>  $participant_id,
                'compare' => 'LIKE',
            ),           
        ),
        
    );

    $query = new WP_Query( $args );

    return $query->posts;

}


/*-------- Judul Dinamis untuk nampilin peserta ----------*/
add_shortcode('detail_training_admin', 'detail_training_admin');
function detail_training_admin()
{
    $training_id = isset($_GET['training_id']) ? $_GET['training_id'] : "";


    $table ='';
    $table .='<div class="jumbotron jumbotron-fluid bg-danger p-4">
    <div class="container">
      <h1 class="display-6">' . get_the_title($training_id) . '</h1>
      <p class="lead"> <i class="fas fa-calendar"></i> ' . do_shortcode('[tanggal lokasi="" training_id=' .$training_id . ']'). '  &nbsp; &nbsp; 
      <i class="fas fa-map-marker-alt"></i> ' . do_shortcode('[tempat lokasi="" training_id=' .$training_id . ']'). '<br /> ' 
      . do_shortcode('[cek_running lokasi="single" training_id=' . $training_id . ']') . '
      </p>
    </div>
  </div>';
    $table .='<table class="table table-success table-striped table-bordered">
        <thead>
            <tr>
                <th style="width:4%"  class="text-center">No</th>
                <th style="width:50%">Nama Peserta</th>
                <th style="width:20%">Order Id</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>';

        
    $args = array(
        'post_type'     => 'participant',
        'post_status'   => array( 'pending', 'draft', 'publish' ),
        'meta_query'    => array(
            'compare' => 'AND',
            array (
                'key' => 'training',
                'value' =>  $training_id,
                'compare' => 'LIKE',
            ),           
        ),
        
    );

    $query = new WP_Query( $args );

    // echo '<pre>'; print_r($query->posts); echo '</pre>';
    $i = 1;
    foreach($query->posts as $post) {

        $order_id = get_order_by_participant($post->ID)[0]->ID;

        $status_peserta = get_field('status_peserta', $post->ID);
        if($status_peserta =='disetujui') {
            $btn_status = 'success';
            $status_peserta = $status_peserta;
            $icon = 'check-circle';
        } else if($status_peserta =='pending') {
            $btn_status = 'warning';
            $status_peserta = $status_peserta;
            $icon = 'clock';
        } else if($status_peserta =='ditolak') {
            $btn_status = 'danger';
            $status_peserta = $status_peserta;
            $icon = 'times-circle';
        } else {
            $btn_status = 'warning';
            $status_peserta = 'belum diperiksa';
            $icon = 'clock';
        }

        $table .='<tr>
                <td  class="text-center">' . $i++ . '</td>
                <td>' . $post->post_title . '</td>
                <td> <a class="text-danger" href="' . get_bloginfo('url') . '/admin-training/detail-order/?order_id='  . $order_id . '">No Order : '  . $order_id . '</a></td>
                <td class="text-center">
                <a class="btn btn-' . $btn_status . ' btn-sm"> <i class="fas fa-' . $icon . '"></i> ' . $status_peserta . ' </a>
                </td>
               
            </tr>';
    }
           
       $table .='</tbody>

    </table>';
    return $table;
    
    
}

/*-------- Tabel semua training ----------*/
add_shortcode('show_trainings_admin', 'show_trainings_admin');
function show_trainings_admin()
{
    $training_id = isset($_GET['training_id']) ? $_GET['training_id'] : "";


    $table ='';
   
    $table .='<table class="table table-success table-striped table-bordered">
        <thead>
            <tr>
                <th style="width:4%" class="text-center">No</th>
                <th style="width:40%">Judul Training</th>
                <th style="width:30%">Tanggal</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>';

        
    $args = array(
        'post_type'     => 'training',
        'post_status'   => array( 'pending', 'draft', 'publish' ),
        // 'meta_query'    => array(
        //     'compare' => 'AND',
        //     array (
        //         'key' => 'training',
        //         'value' =>  $training_id,
        //         'compare' => 'LIKE',
        //     ),           
        // ),
        
    );

    $query = new WP_Query( $args );

    // echo '<pre>'; print_r($query->posts); echo '</pre>';
    $i= 1;
    foreach($query->posts as $post) {

        $table .='<tr>
                <td class="text-center">' . $i++ . '</td>
                <td>' . $post->post_title . '</td>
                <td style="font-size:14px;"> <i class="fas fa-calendar"></i> ' . do_shortcode('[tanggal lokasi="" training_id=' .$post->ID . ']') . '</td>
                <td class="text-center"> ' .  do_shortcode('[cek_running lokasi="single" training_id=' . $post->ID . ']') . ' </td>
                <td class="text-center">
                <a class="btn btn-success btn-sm" href="' . get_bloginfo('url') . '/admin-training/detail-training/?training_id=' . $post->ID . ' "><i class="fas fa-eye"></i></a>
                <a class="btn btn-warning btn-sm"  href="' . get_dashboard_url() . 'post.php?post=' . $post->ID . '&action=edit"><i class="fas fa-pencil-alt"></i></a>
                </td>
            </tr>';
    }
           
       $table .='</tbody>

    </table>';
    return $table;
    
    
}

/*-------- Tabel semua Order ----------*/
add_shortcode('show_orders_admin', 'show_orders_admin');
function show_orders_admin()
{
    $training_id = isset($_GET['training_id']) ? $_GET['training_id'] : "";


    $table ='';
   
    $table .='<table class="table table-success table-striped table-bordered">
        <thead>
            <tr>
                <th style="width:4%" class="text-center">No</th>  
                <th style="width:25%">Orders</th>
                <th style="width:40%">Training</th>
                <th class="text-center">Status Pendaftaran</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>';

        
    $args = array(
        'post_type'     => 'orders',
        'post_status'   => array( 'pending', 'draft', 'publish' ),
        // 'meta_query'    => array(
        //     'compare' => 'AND',
        //     array (
        //         'key' => 'training',
        //         'value' =>  $training_id,
        //         'compare' => 'LIKE',
        //     ),           
        // ),
        
    );

    $query = new WP_Query( $args );

    // echo '<pre>'; print_r($query->posts); echo '</pre>';

    $i=1;
    foreach($query->posts as $post) {

        $training_id = get_field( 'training', $post->ID);
        $status = get_field( 'status_pendaftaran', $post->ID);

        $table .='<tr>
                <td class="text-center">' . $i++ . '</td>
                <td>' . $post->post_title . '</td>
                <td>' .  get_the_title($training_id) . '</td>
                <td class="text-center">'. status_daftar($status) . '</td>
                <td class="text-center">
                <a class="btn btn-success btn-sm" href="' . get_bloginfo('url') . '/admin-training/detail-order/?order_id=' . $post->ID . ' "><i class="fas fa-eye"></i></a>
                <a class="btn btn-warning btn-sm"  href="' . get_dashboard_url() . 'post.php?post=' . $post->ID . '&action=edit"><i class="fas fa-pencil-alt"></i></a>
                </td>
            </tr>';
    }
           
       $table .='</tbody>

    </table>';
    return $table;
    
    
}

function status_daftar($status) {

    if($status == 'awal') {
        $status = 'Tambah Peserta';
        $class = "info";
        $icon = 'user-plus';
    } else
    if($status == 'tambah_peserta') {
        $status = 'Belum Lunas';
        $class = "secondary";
        $icon = 'hand-holding-usd';
    } else
    if($status == 'selesai') {
        $status = 'Verified';
        $class = "success";
        $icon = 'check-circle';
    } else
    if($status == 'sudah_bayar') {
        $status = 'Menunggu verifikasi';
        $class = "warning";
        $icon = 'clock';
    } else
    if($status == 'ditolak') {
        $status = 'Ditolak';
        $class = "dark";
        $icon = 'times-circle';
    }

return "<span class='btn btn-" . $class . " btn-sm p-1'><i class='fas fa-". $icon ."'></i> " . $status . "</span>";
}

/*-------- Tampil detail order ----------*/
add_shortcode('detail_order_admin', 'detail_order_admin');
function detail_order_admin()
{
    $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : "";

    $participants= get_field('participant', $order_id);
    $training= get_field('training', $order_id);
    $total_harga = get_post_meta($order_id, 'total_harga', true);
    $kode_kupon = get_post_meta($order_id, 'kode_kupon', true);
    $total_bayar = ($kode_kupon) ? get_post_meta($order_id, 'total_bayar', true) : $total_harga ;
   
    $transaction_id = get_post_meta($order_id, 'transaction_id', true);
    $transaction_time = get_post_meta($order_id, 'transaction_time', true);
   
    $status_pendaftaran = get_field('status_pendaftaran', $order_id);

    $payment = ($transaction_id !='') ? get_status_midtrans($transaction_id, 'payment_type') : ' ';
    $transaction_status = ($transaction_id !='') ? get_status_midtrans($transaction_id, 'transaction_status') : ' ';

    $table ='';
    $table .='
    <div class="jumbotron jumbotron-fluid bg-danger p-4 opacity-25">
        <div class="container">
            <div class="row">
                <div class="col-6">
                <h3 class="my-1"> SCTN-' . $order_id . '</h3>
                <p class="lead my-1" ><i class="fas fa-graduation-cap"></i> <a href="' . get_bloginfo('url') . '/admin-training/detail-training/?training_id=' .$training. '">' . get_the_title($training).'</a> &nbsp; &nbsp;</p>

                <p><i class="fas fa-calendar"></i> ' . get_the_date('d F Y', $order_id).' &nbsp; &nbsp;
                <i class="fas fa-dollar-sign"></i> Rp' . number_format($total_bayar).' &nbsp; &nbsp;</p>

                </div>
                <div class="col-3 mt-2">
                    <p class="my-1">Harga Asli : Rp' . number_format($total_harga) . ' </p>
                    <p class="my-1">Kode Kupon : ' . $kode_kupon . ' </p>
                    <p class="my-1">Tr. Status : ' . $transaction_status . '</p>
                </div>
                <div class="col-3 mt-2">
                    <p class="my-1 status">' . status_daftar($status_pendaftaran) . ' </p>
                    <p class="my-1">Tr. Time : ' . $transaction_time .' </p>
                    <p class="my-1">Payment Method: ' . $payment .' </p>
                </div>     
            </div>     
        </div>
    </div>';
    $table .='<table class="table table-success table-striped table-bordered">
        <thead>
            <tr>
                <th style="width:3%" class="text-center">No</th>            
                <th style="width:70%">Nama Peserta</th>
            </tr>
        </thead>
        <tbody>';
            if($participants)  {              
                $i=1;
                foreach($participants as $participant_id) {

                    $table .='<tr>
                            <td class="text-center">' .  $i++ . '</td>
                            <td>' .  get_the_title($participant_id) . '</td>
                            
                        </tr>';
                }
            }           
       $table .='</tbody>

    </table>';

    if($status_pendaftaran == 'sudah_bayar' ) {

    $table .= '<div class="alert alert-primary form_verifikasi">

    <form name="verifikasi_order" class="verifikasi_order">
    <input type="hidden" class="order_id" name="order_id" value="' . $order_id . '">
    <input type="hidden" class="training" name="training" value="' . $training . '">
        <strong>Terima Order</strong>
        <hr>
            <div class="row">
                <div class="col-8 d-flex align-items-center">
                    <span><input id="confirm_verifikasi" type="checkbox" required="required" class="d-inline-block confirm_verifikasi"> Order telah diperiksa dengan seksama. </span>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-warning btn-md button button_beli float-end" id="verifikasi">
                    <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="visually-hidden">Loading...</span>
                    Ya, Terima Order                
                </button> 
                </div>
            </div>       

    </form>
    
    </div>';
    }
    return $table;
    
    
}


/*-------- Sertifikat ----------*/
add_shortcode('sertifikat', 'sertifikat');
function sertifikat()
{

    
    $part_id = (isset($_GET['participant_id'])) ? (($_GET['participant_id'] !='') ? $_GET['participant_id'] : 'pilih participant' ) : 'pilih participant';


    $args = array(
        'post_type'     => 'orders',
        'post_status'   => 'publish',
        'author'   => get_current_user_id(),
        'meta_query'    => array(
            'compare' => 'AND',
            array (
                'key' => 'participant',
                'value' =>  $part_id,
                'compare' => 'LIKE',
            ),           
        ),
        
    );

    $query = new WP_Query( $args );

    foreach($query->posts as $post) {
        
        $participant = get_the_title($part_id);
        $participant_file = str_replace(' ','-',strtolower($participant));
        $training_id = get_field('training', $post->ID);

        $training = get_the_title($training_id);


    $sertifikat = '
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;700&family=Corinthia:wght@700&display=swap" rel="stylesheet">
    <style>
    html,
        body {
            width: 297mm;
            height: 210mm;
            line-height: 1.8;
            font-size: 14pt !important;
            font-family:"Fira Sans", Times, serif;   
            font-weight:400;        
        }
        h1, h2, h3, h4, h5, p{
            padding:0;margin:0;
            line-height:3rem;
        }

        div.kertas {
            width: 100%;
            height:100%;
            background: transparent url("' . get_template_directory_uri() .'/img/sertifikat.png") center top no-repeat;
            background-size: cover;
            padding-top:75px;
            padding-left:100px;
            padding-right:100px;
            padding-bottom:70px;
        } 
        .no-sertifikat {
            font-size:12pt;
        }
       
        .prolog {
            padding-top:130px; 
        }
        
        .nama {
            text-align:center;  
            line-height:60pt;                     
        }

        .nama h1 {
            font-size:60pt;
            font-weight:bold;
        }

        .judul-training {
            line-height:50pt;
        }

        .judul-training h2 {
            font-size:20pt;
            font-weight:bold;
        }

        .kertas td {
            text-align:center;
            font-size: 14pt !important;
        }
        .ttd {
            margin-top:20pt;
            width:300px;;
            height:100px;
            display:block;
        }
</style>

        <div class="kertas">
        <p class="no-sertifikat">No. 123456</p>
            <table style="width:100%;">                      
                <tr>
                    <td colspan="2" class="prolog"><p>This certificate is proudly presented to</p></td>
                </tr>                
                <tr>
                    <td colspan="2" class="nama"><h1>' . $participant . '</h1></td>                
                </tr>

                <tr>
                    <td colspan="2">
                        <p>for successfully passing the completing all contens
                    and final exam on course :
                        </p>
                    </td>               
                </tr>

                <tr>
                    <td colspan="2" class="judul-training" ><h3>' .$training . '</h3></td>               
                </tr>
                
                <tr>
                    <td colspan="2">
                        <div class="ttd">
                            Yogyakarta, 21 January 2021<br>
                            <img src=" ' . get_template_directory_uri() . '/img/ttd.png" width="200"/>                      
                            <br>Director Scientina
                        </div>
                    </td>               
                </tr>
            </table>
           
            
        </div>';

    // return $sertifikat; 
    
    require('vendor/autoload.php');
   
    $mpdf = new \Mpdf\Mpdf([
        // 'tempDir' => get_template_directory() .'/pdfdata',
        'mode' => 'utf-8',
        'format' => 'A4-L',
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_footer' => 0,
        'margin_bottom' => 0,
        'margin_top' => 0,
        'float' => 'left',
    ]);

    $mpdf->SetDefaultBodyCSS('background', "url(" . get_template_directory_uri() . " '/img/sertifikat.svg')");
    $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
    $mpdf->dpi = 300;
    $mpdf->img_dpi = 300;
    ob_clean();
    $mpdf->WriteHTML($sertifikat);

    $rand = rand(14,225);
    $mpdf->Output( WP_CONTENT_DIR. '/sertifikat/sertifikat-' . $participant_file . '-' . $rand . '.pdf', 'F');
    ob_end_clean();
}
   
}

/*-------- Tampil detail order ----------*/
add_shortcode('notif', 'notif_admin');
function notif_admin()
{
    return "<p class='alert alert-warning'><i class='fas fa-exclamation-triangle'></i> Ada Order menunggu verifikasi. <a href='" . get_bloginfo('url'). "/admin-training/order'>Klik di sini.</a></p>";
}
