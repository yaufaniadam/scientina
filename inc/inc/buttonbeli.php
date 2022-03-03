add_shortcode('button_beli', 'button_beli');
function button_beli()
{
    $post_id = get_the_ID();  

    $url = '';
    $form = '';  
    $user = wp_get_current_user();   

    if (!is_user_logged_in()) { 

        $form .= '<h5 class="text-center">Beli Program Ini</h5>
        
        <ul class="mt-4 nav nav-pills d-flex justify-content-center">
        <li class="nav-item">
          <a class="nav-link aktif text-center" aria-current="page" href="#">
          <span class="badge rounded-pill">1</span><br>
          Buat Akun</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center" aria-current="page" href="#">
            <span class="badge rounded-pill">2</span><br>
            Nama Peserta</a>
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

        $form .= '<p style="text-align:center;"><a href="' . esc_url(wp_login_url(get_permalink() . '/?training_id='.  get_the_ID())) . '">Login disini </a>jika sudah terdaftar.</p>';

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
        $status_transaksi = status_transaksi(get_current_user_id(), $post_id);

        // dia login
        // cek status transaksi, jika ada transaksi, maka jalankan transaksi
        
        if($status_transaksi == 0) {

            echo "ga ada transaksi";
            
            $form .= '<h5 class="text-center">Beli Program Inie</h5>
            
            <ul class="mt-4 nav nav-pills d-flex justify-content-center">
               
                <li class="nav-item">
                    <a class="nav-link aktif text-center" aria-current="page" href="#">
                    <span class="badge rounded-pill">1</span><br>
                    Jumlah Peserta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" aria-current="page" href="#">
                    <span class="badge rounded-pill">2</span><br>
                    Bayar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" aria-current="page" href="#">
                    <span class="badge rounded-pill">3</span><br>
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

                    $html .= '<h5 class="text-center">Beli Program Ini</h5>';
                    $html .= '                    
                    <ul class="mt-4 nav nav-pills d-flex justify-content-center">
                        <li class="nav-item">
                        <a class="nav-link text-center" aria-current="page" href="#">
                        <span class="badge rounded-pill">1</span><br>
                        Jumlah Peserta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link aktif text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">2</span><br>
                            Nama Peserta</a>
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

                    $html .=  '<div id="load" class="p-2">
                    <form action="" method="POST" class="form_checkout">';
                    $html .=  '<input type="hidden" name="nonce" value="'. $nonce .'">';
                    $html .=  '<input type="hidden" name="total_harga" id="total_harga" value="'. $total_harga .'">';
                    $html .=  '<input type="hidden" name="jml_peserta" id="jml_peserta" value="'. $jml_peserta .'">';
                    for ($i = 1; $i <= $jml_peserta; $i++) {
                    $html .= '<div class="mb-2 row">
                            <label for="nama peserta" class="col-sm-4 col-form-label">Nama Peserta '.$i.'*</label>
                            <div class="col-sm-8">
                            <input type="text" name="peserta'.$i.'" class="form-control"  value="" required>
                            <span class="invalid-feedback error peserta' . $i. '_empty"></span>
                            </div>
                        </div>';
                    }
                    $html .='<div class="mb-2 row">
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
            
                    $html .= "<p><strong>" . get_the_title() . "</strong><br>             
                        Rp</span> " . number_format($total_harga) . " x " . $jml_peserta . " = Rp <span class='harga_asli'><span class='tru'></span>" . number_format($total_harga) . "</span> &nbsp;<span class='harga_baru'></span>                      
                    </p>";
                    $html .= "<input type='hidden' id='training_id' name='training_id' value='". $post->ID ."'>";
                    $html .= "<input type='hidden' id='training_title' name='training_title' value='". $post->post_title ."'>";
                    $html .= '<input type="hidden" name="url" id="url" class="" value="'. get_the_permalink() .'">';
                    $html .= "<input type='hidden' id='total_harga' name='total_harga' value='".$total_harga."'>";
                    $html .= "<input type='hidden' name='submitted' value='checkout'>
                        <hr style='border-top:1px solid white; padding:10px 0;'>
                        <p><button type='submit' class='button button_beli btn btn-warning' id='button_checkout'>
                            <span class='spinner-border text-light spinner-border-sm d-none' role='status' aria-hidden='true'></span>
                                <span class='visually-hidden'>Loading...</span>
                                Selanjutnya <i class='fas fa-arrow-right'></i> 
                                </button></p>";  
                    $html .= "</form></div>"; //#load close 
                } else if($status_pendaftaran == 'tambah_peserta') { // status, sudah tambah peserta tinggal bayar aja

                  echo  "eror di sini";

                    $transaksi = array(
                        'post_id' => $post->ID,
                        'post_title' => $post->post_title,
                        'total_harga' => $total_bayar,
                        'jml_peserta' => $jml_peserta,
                    );

                    $snapToken = get_midtrans($transaksi);

                    $html .= '<h5 class="text-center">Beli Program Inis</h5>';
                    $html .= '                    
                    <ul class="mt-4 nav nav-pills d-flex justify-content-center">
                        <li class="nav-item">
                        <a class="nav-link text-center" aria-current="page" href="#">
                        <span class="badge rounded-pill">1</span><br>
                        Buat Akun</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link aktif text-center" aria-current="page" href="#">
                            <span class="badge rounded-pill">2</span><br>
                            Nama Peserta</a>
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

                $html .= '<div id="bayar-midtranss" class=""> 
          
                <p>Training : ' . $training->post_title . ' (' . $jml_peserta . ' peserta)</p>
                <p>Rp: ' . number_format($total_bayar) . '</p>

                <button id="pay-button" class="btn btn-warning btn-lg btn-block">Bayar Sekarang</button>

                <div id="bayar-sukses"></div>
                <div id="result-json"></div>
                ';
                $html .= '
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
                } else  {

                    $html .= "<div class='py-3 px-3 pe-2' ><span class='alert alert-warning d-block;' style='display:block;width:100%;'><i class='fas fa-exclamation-triangle'></i> Anda sudah terdaftar di program ini</span></div>";
                    
                    
                } 

                echo $html;
            } 
        }
    }

    return $form;   
}