
/**
* Theme: Minton Admin Template
* Author: Coderthemes
* SweetAlert - 
* Usage: $.SweetAlert.methodname
*/

!function ($) {
    "use strict";

    var SweetAlert = function () {
    };

    //examples
    SweetAlert.prototype.init = function () {

        //Parameter
        $('.delete').click(function () {

            var status_count = $(this).siblings("input[name='status']").attr("value");
            var mass_count = $(this).siblings("input[name='mass_load']").attr("value");

            swal({
                title: "Hapus kegiatan ini?",
                text: "Seluruh data pekerjaan dalam kegiatan ini akan ikut terhapus.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-warning",
                confirmButtonText: "Ya, saya yakin!",
                cancelButtonText: "Tidak, batalkan",
                closeOnConfirm: false,
                closeOnCancel: false,
                html: true,
            }, function (isConfirm) {
                if (isConfirm) {

                    $.ajax({ 
                        type: "POST",
                        url: "cat/fetcher.php",
                        data: { status: status_count, statistic: mass_count} 
                    })
                    .done(function( str ) { 
                        if(str == 'true'){
                            swal({
                                title: "Terhapus!",
                                text: "Kegiatan ini beserta data pekerjaan-nya telah dihapus.",
                                type: "success",
                                showConfirmButton: false,
                                timer: 2000,
                            });

                            setTimeout(function() {
                                window.location = window.location.href;
                            }, 2300);
                        }
                        else{
                            swal({
                                title: "Error!",
                                text: "Permintaan tidak dapat diproses, terjadi kesalahan.",
                                type: "error",
                                showConfirmButton: false,
                                timer: 2000,
                            });

                            setTimeout(function() {
                                window.location = window.location.href;
                            }, 2300);
                        }
                    });
                    

                } else {
                    swal({
                            title: "Batal!",
                            text: "Kegiatan ini batal dihapus.",
                            type: "error",
                            showConfirmButton: false,
                            timer: 1500,
                    });
                }
            });
        });

    },
        //init
        $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing
    function ($) {
        "use strict";
        $.SweetAlert.init()
    }(window.jQuery);