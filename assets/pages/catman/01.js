
    $('input[name="year"]').datepicker({
        autoclose: true,
        minViewMode: 4,
        format: "yyyy",
        startView: "years", 
        minViewMode: "years",
        orientation: "top"
    }).on('hide', function(e) {
        $('#data').bootstrapTable('selectPage', 1).bootstrapTable('refresh');

    });

    function JSONParser (str){
        try {
            var o = JSON.parse(str);

            if (o && typeof o === "object") {
                return o;
            }
        }
        catch (e) { }

        return false;
    };


    var total_pekerjaan = total_pagu = 0;

    function totalTitle(data){
        return '<span class="lead">Total</span>';
    }

    function dataHandler(data) {

        var that = $('#data');
        that.attr("data-token", data.token);

        if(typeof data.footer !== 'undefined'){
            total_pekerjaan = data.footer.jumlah;
            total_pagu = data.footer.pagu;
        }
        else {
            total_pekerjaan = total_pagu = 0;
        }

        if(typeof data.rows === 'undefined'){
            $('#data').bootstrapTable('removeAll');
            $('.fixed-table-footer').addClass('hidden');

        }
        else {
            $('.fixed-table-footer').removeClass('hidden');
        }
        
        return data;

    }

    function totalPekerjaan(data){

        if(total_pekerjaan > 0){
            total_pekerjaan = total_pekerjaan + " Pekerjaan";
        }
        return total_pekerjaan;

    }

    function totalPagu(data){

        return total_pagu;

    }

    function submitFunc(){

        var that = $('#data');

        $.ajax({ 

            type: "POST",
            url: "/dash/catman/processor",
            data: { 
                primary: $(this).attr('data-primary'), 
                year: $('input[name="year"]').val(),
                mode: $(this).attr('data-mode'), 
                data: $(this).attr('data-id'), 
                token: that.attr('data-token') 
            } 

        })
        .done(function( str ) {

            var data = JSONParser(str);
            if(data){
                that.attr("data-token", data.token);
                if(typeof data.alert === 'undefined'){
                    $('#panel-modal .modal-content').append(data.data);
                }
                else {
                    swal({
                        title: data.alert.title,
                        text: data.alert.text,
                        type: data.alert.type,
                        showConfirmButton: false,
                        timer: data.alert.timer
                    });
                    $('#panel-modal').modal('hide');
                    $('#data').attr("data-token", data.token).bootstrapTable('refresh');
                }
            }
            else {
            }
        });

    }

    $('#data').bootstrapTable({
        pagination: true,
        showRefresh: true,
        pageSize: 10,
        sidePagination: 'server',
        showFooter: true,
        search: true,
        url: '/dash/catman/processor',
        method: 'post',
        contentType: 'application/x-www-form-urlencoded',
        queryParams: function(params) {
            var that = $('#data');
            params.year = $('input[name="year"]').val();
            params.primary = that.attr('data-primary');
            params.mode = that.attr('data-mode');
            params.token = that.attr('data-token');
            return params;
        },
        sortName: 'title',
        responseHandler: 'dataHandler',

    });

    $('#data').on('load-error.bs.table', function(status, res) {
        $(this).bootstrapTable('removeAll');
        $('.fixed-table-footer').addClass('hidden');
    });

    $("#data").on('click','.btn-load', submitFunc);
    $("button[data-target='#panel-modal']").on('click', submitFunc);

    $('#panel-modal').on('hidden.bs.modal', function (e) {
        $('#panel-modal .modal-content').empty();
    });

    $('#data').on('click', '.delete', function () {

        var that = $('#data');
        var me = $(this);

        swal({
            title: "Hapus pekerjaan ini?",
            text: "Seluruh data dalam pekerjaan ini akan terhapus.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-warning",
            confirmButtonText: "Ya, saya yakin!",
            cancelButtonText: "Tidak, batalkan",
            closeOnConfirm: false,
            closeOnCancel: true,
            html: true,
        }, function (isConfirm) {
            if (isConfirm) {

                console.log(this);

                $.ajax({ 

                    type: "POST",
                    url: "/dash/catman/processor",
                    data: { 
                        primary: me.attr('data-primary'),
                        mode: me.attr('data-mode'), 
                        data: me.attr('data-id'), 
                        token: that.attr('data-token') 
                    } 

                })
                .done(function( str ) {

                    var data = JSONParser(str);
                    if(data){
                        swal({
                            title: data.title,
                            text: data.text,
                            type: data.type,
                            showConfirmButton: false,
                            timer: data.timer
                        });
                        that.attr("data-token", data.token);
                        $('#data').bootstrapTable('refresh');
                    }
                    else {
                    }

                });
                

            }
        });

    });