( function( $ ){

  $('#daftar').click( function(e){

    e.preventDefault();

    var isi = $('.pendaftaran').serialize();

    $.ajax({
      type : 'post',
      dataType : 'json',
      url : scajax_globals.ajax_url,
      data : {
        action: "registrasi",
        isi : isi,
        _ajax_nonce: scajax_globals.nonce
      },
      success: function( response ) {
         if( 'success' == response.type ) {
             
             console.log(response)
             window.location.href = response.redir;
         }
         else {
            alert( 'Something went wrong, try logging in!' );
         }
      },
      error : function(error) {
        console.log(error);
      }
    })

  } );


  $('#cek_kupon').click( function(e){

    var kupon = $('#kode_kupon').val();

    e.preventDefault();

    if(kupon != '') {
      $.ajax({
        type : 'post',
        dataType : 'json',
        url : scajax_globals.ajax_url,
        data : {
          action: "cekkupon",
          kupon : kupon,
          _ajax_nonce: scajax_globals.nonce
        },
        success: function( response ) {
           if( 'success' == response.type ) {

               $('.message_kupon').html(response.message);
               console.log(response)

           }
           else {
              alert( 'Something went wrong, try logging in!' );
           }
        },
        error : function(error) {
          console.log(error);
        }
      })
    }  else {
      alert('Masukkan kode kupon ')
    }

  } );

  $('#button_checkout').click( function(e){

    var form_checkout = $('.form_checkout').serialize();

    e.preventDefault();

    console.log(form_checkout)

    // if(kupon != '') {
      $.ajax({
        type : 'post',
        dataType : 'json',
        url : scajax_globals.ajax_url,
        data : {
          action: "checkout",
          isi : form_checkout,
          _ajax_nonce: scajax_globals.nonce
        },
        success: function( response ) {
           if( 'success' == response.type ) {

               console.log(response)

           }
           else {
              alert( 'Something went wrong, try logging in!' );
           }
        },
        error : function(error) {
          console.log(error);
        }
      })
    // }  else {
    //   alert('Masukkan kode kupon ')
    // }

  } );




} )( jQuery );
