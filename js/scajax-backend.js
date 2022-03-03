( function( $ ){

  $(document).ready(function() {
       
    $('.cektransaksi').each(function() {
      var orderid = $(this).attr("id");
      $.ajax({
        type : 'post',
        dataType : 'json',
        url : scajax_backend_globals.ajax_url,
        data : {
          action: "cektransaksi",
          orderid : orderid,
          _ajax_nonce: scajax_backend_globals.nonce
        },
        beforeSend: function ( xhr ) {
        
      },
        success: function( response ) {
          if( 'success' == response.type ) {            
              // console.log(response.orderid);
              // $("#" + response.orderid .loader).hide()
              $("#" + response.orderid).html(response.status)
              $(".gross_amount." + response.orderid).html(response.gross_amount)
          }
          else {
              console.log( 'Something went wrong, try logging in!' );
          }
        },
        error : function(error) {
          console.log(error);
        }
      }).done(function() {
        setTimeout(function(){
          $('#daftar .spinner-border').addClass('d-none');
        },0);
      })
    });
 


  });



  
 
  
} )( jQuery );
