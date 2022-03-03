(function ($) {

  $('#daftar').click(function (e) {

    $(".error").html('');

    e.preventDefault();

    var isi = $('.pendaftaran').serialize();

    $.ajax({
      type: 'post',
      dataType: 'json',
      url: scajax_globals.ajax_url,
      data: {
        action: "registrasi",
        isi: isi,
        _ajax_nonce: scajax_globals.nonce
      },
      beforeSend: function (xhr) {
        console.log('Loading ...')
        $('#daftar .spinner-border').removeClass('d-none');
        $('.form-control').removeClass('is-invalid');
      },
      success: function (response) {
        if ('success' == response.type) {
          if (response.error == 'error') {
            console.log(response);
            jQuery.each(response.error_code, function (i, val) {
              $(".error." + i).append(document.createTextNode(val));
              $(".error." + i).prev(".form-control").addClass("is-invalid");
            });
            $('#daftar_error').fadeIn(100).delay(2000);
          } else {
            //$('#load').html(response.html);             
            $(location).attr('href', response.redir);
          }
        }
        else {
          alert('Something went wrong, try logging in!');
        }
      },
      error: function (error) {
        console.log(error);
      }
    }).done(function () {
      setTimeout(function () {
        $('#daftar .spinner-border').addClass('d-none');
      }, 0);
    })

  });


  $('#load').on('click', '#cek_kupon', function (e) {

    var kupon = $('#kode_kupon').val();
    var total_harga = $('#total_harga').val();

    e.preventDefault();

    if (kupon != '') {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: scajax_globals.ajax_url,
        data: {
          action: "cekkupon",
          kupon: kupon,
          total_harga: total_harga,
          _ajax_nonce: scajax_globals.nonce
        },
        beforeSend: function (xhr) {
          console.log('Loading ...')
          $('#cek_kupon .spinner-border').removeClass('d-none');
          $('.form-control').removeClass('is-invalid');
        },
        success: function (response) {
          if ('success' == response.type) {
            if (response.valid == 1) {
              $('#harga_diskon').val(response.subtotal);
              $('.harga_baru').removeClass('d-none');
              $('.harga_asli .tru').addClass('d-block');
              $('.harga_baru').html(response.subtotal_nf);
              $('.message_kupon').addClass('text-warning');
              $('.message_kupon').html(response.message);
              $("#cek_kupon").prev(".form-control").addClass("is-valid");
              $("#cek_kupon").hide();
            } else {
              $('.message_kupon').removeClass('text-warning');
              $('.message_kupon').html(response.message);
              $("#cek_kupon").prev(".form-control").addClass("is-invalid");
              $('.harga_asli .tru').removeClass('d-block');
              $('#harga_diskon').val('');
              $('.harga_baru').addClass('d-none');
            }
          }
          else {
            console.log(response);
            alert('Something went wrong, try logging in!');
          }
        },
        error: function (error) {
          console.log(error);
        }
      }).done(function () {
        setTimeout(function () {
          $('#cek_kupon .spinner-border').addClass('d-none');
        }, 0);
      })
    } else {
      $('.message_kupon').removeClass('text-warning')
      $("#cek_kupon").prev(".form-control").addClass("is-invalid");
      $('.message_kupon').html('Masukkan kode kupon ')
    }

  });

  $('#load').on('click', '#button_checkout', function (e) {
    $(".error").html('');
    $("input").removeClass('.form-error');

    var form_checkout = $('.form_checkout').serialize();

    e.preventDefault();

    // if(kupon != '') {
    $.ajax({
      type: 'post',
      dataType: 'json',
      url: scajax_globals.ajax_url,
      data: {
        action: "checkout",
        isi: form_checkout,
        _ajax_nonce: scajax_globals.nonce
      },
      beforeSend: function (xhr) {
        console.log('Loading ...')
        $('#button_checkout .spinner-border').removeClass('d-none');
        $('.form-control').removeClass('is-invalid');
      },
      success: function (response) {
        if ('success' == response.type) {

          if (response.error == 'error') {

            var counter = 1;
            jQuery.each(response.error_code, function (i, val) {
              $(".error." + i).append(document.createTextNode(val));
              $(".error." + i).removeClass('d-none');
              $(".error." + i).addClass('d-block');

              counter++;
            });
          } else {
            $(location).attr('href', response.redir);
          }

        }
        else {
          alert('Something went wrong, try logging in!');
        }
      },
      error: function (error) {
        console.log(error);
      }
    }).done(function () {
      setTimeout(function () {
        $('#button_checkout .spinner-border').addClass('d-none');
      }, 0);
    })

  });

  $(document).ready(function () {

    $('.cektransaksi').each(function () {
      var orderid = $(this).attr("id");
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: scajax_globals.ajax_url,
        data: {
          action: "cektransaksi_front",
          orderid: orderid,
          _ajax_nonce: scajax_globals.nonce
        },
        beforeSend: function (xhr) {

        },
        success: function (response) {
          if ('success' == response.type) {
            // console.log(response.orderid);
            // $("#" + response.orderid .loader).hide()
            $("#" + response.orderid).html(response.status)
            $(".gross_amount." + response.orderid).html(response.gross_amount)
          }
          else {
            console.log('Something went wrong, try logging in!');
          }
        },
        error: function (error) {
          console.log(error);
        }
      }).done(function () {
        setTimeout(function () {
          $('#daftar .spinner-border').addClass('d-none');
        }, 0);
      })
    });
  });

  $('#verifikasi').click(function (e) {

    $(".error").html('');

    e.preventDefault();

    var order_id = $('.order_id').val();
    var training = $('.training').val();
    var confirm_verifikasi = $('#confirm_verifikasi').prop('checked');

    if (!confirm_verifikasi == true) {
      alert('Konfirmasi belum dicentang')
    } else {

      $.ajax({
        type: 'post',
        dataType: 'json',
        url: scajax_globals.ajax_url,
        data: {
          action: "verifikasi",
          order_id: order_id,
          training: training,
          confirm_verifikasi: confirm_verifikasi,
          _ajax_nonce: scajax_globals.nonce
        },
        beforeSend: function (xhr) {
          console.log('Loading ...')
          // $('#verifikasi .spinner-border').removeClass('d-none');
          // $('.form-control').removeClass('is-invalid');
        },
        success: function (response) {
          if ('success' == response.type) {
            // $('#verifikasi .spinner-border').addClass('d-none');
             $('.form_verifikasi').addClass('d-none');                        
             $('.status .btn').removeClass('btn-warning');                        
             $('.status .btn').addClass('btn-success');                        
             $('.status .btn').html('<i class="fas fa-check-circle"></i> Verified');                        
          }
          alert('Order berhasil diverifikasi');
        },
        error: function (error) {
          console.log(error);
        }
      }).done(function () {
        setTimeout(function () {
          // $('#daftar .spinner-border').addClass('d-none');
        }, 0);
      });
    } //endif
  }); //varifikasi

})(jQuery);
