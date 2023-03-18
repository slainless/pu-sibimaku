
    $('.dropdown-toggle').on('click', function (event) {
        $(this).parent().toggleClass('open');
        $(this).parent().siblings('.btn-group.open').removeClass('open');
    });

    $('#sorter input[name="order"]').on('change', function() {

        var me = $(this);

        me.parent().addClass('active');
        me.parent().siblings('.active.order').removeClass('active');
        $('#data').attr('data-order', me.val()).bootstrapTable('refresh');

    });

    $('#sorter input[name="sorter"]').on('change', function() {

        var me = $(this);

        me.parent().addClass('active');
        me.parent().siblings('.active.sort').removeClass('active');
        $('#data').attr('data-sort', this.value).bootstrapTable('refresh');

    });

    $('#filter input').on('change', function() {
        if(this.name !== 'all'){
            $('#checkbox-all').removeAttr('checked');
        }
        else {
            if(this.checked === true){
                $('#filter input:not([name="all"]').each( function(i, v){
                    v.checked = false;
                });
            }
            else {
                $('#filter input:not([name="all"]').each( function(i, v){
                    v.checked = true;
                });
            }
        }
        $('#data').bootstrapTable('refresh');
    });

    $('body').on('click', function (e) {
        if (!$('.btn-group').is(e.target) 
            && $('.btn-group').has(e.target).length === 0 
            && $('.open').has(e.target).length === 0
        ) {
            $('.btn-group.open').removeClass('open');
        }
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

    $('#data').bootstrapTable({
        pagination: true,
        showRefresh: true,
        pageSize: 50,
        showHeader: false,
        sidePagination: 'server',
        showFooter: false,
        search: true,
        url: '/dash/docs/processor',
        method: 'post',
        contentType: 'application/x-www-form-urlencoded',
        queryParams: function(params) {
            me = $('#data');

            params.data = me.attr("data-id");
            params.mode = me.attr("data-mode");
            params.primary = me.attr("data-primary");
            params.token = me.attr("data-token");
            params.token = me.attr("data-token");
            params.sort = me.attr("data-sort");
            params.order = me.attr("data-order");
            params.filter = $('#filter input:checked').serializeArray();
            return params;
        },
        rowStyle: 'rowFormatter',
        responseHandler: 'dataHandler',

    });

    function dataHandler(data) {

        that = $('#data');

        that.attr("data-token", data.token);
        if(typeof data.rows === 'undefined'){
            $('#data').bootstrapTable('removeAll');
        }
        return data;
    }

    function rowFormatter(row, index) {
        
        if(row.unlock === 1){
            return {
                css: '',
                classes: 'unread'
            }
        }
        else {
            return {
                css: '',
                classes: ''
            }
        }
    }

    var sendAjax = function(data) {

        $.ajax({ 

            type: "POST",
            url: "/dash/docs/processor",
            data: data

        })
        .done(function( str ) {

            var parsed = JSONParser(str);
            if(parsed){
                swal({
                    title: parsed.alert.title,
                    text: parsed.alert.text,
                    type: parsed.alert.type,
                    showConfirmButton: parsed.alert.confirm,
                    timer: parsed.alert.timer
                });
                that.attr("data-token", parsed.token);
                $('#data').bootstrapTable('refresh');
            }
            else {
            }

        });
    }

    $('#data').on('click', '.revisi', function() {

        var me = $(this);
        var that = $('#data');

        swal({
            title: "Permintaan Revisi",
            text: "Anda ingin meminta izin untuk merevisi (menghapus) file ini?.",
            type: "info",
            showCancelButton: true,
            confirmButtonClass: "btn-info",
            cancelButtonClass: "btn-inverse",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            closeOnConfirm: false,
            closeOnCancel: true,
            html: true,
        }, function (isConfirm) {
            if (isConfirm) {

                var data = { 
                    primary: me.attr('data-primary'),
                    mode: me.attr('data-mode'), 
                    data: me.attr('data-id'), 
                    token: that.attr('data-token') 
                }

                sendAjax(data);

            }
        });
    });


    $('#data').on('click', '.remove', function() {

        var me = $(this);
        var that = $('#data');

        $('.sweet-alert button.cancel').removeClass('btn-danger');

        swal({
            title: "Hapus file",
            text: "Anda yakin ingin menghapus file ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-warning",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            closeOnConfirm: false,
            closeOnCancel: true,
            html: true,
        }, function (isConfirm) {
            if (isConfirm) {

                var data = { 
                    primary: me.attr('data-primary'),
                    mode: me.attr('data-mode'), 
                    data: me.attr('data-id'), 
                    token: that.attr('data-token') 
                }

                sendAjax(data);

            }
        });
    });

    $('#data').on('click', '.edit-status', function() {

        var me = $(this);
        var that = $('#data');

        swal({
            title: "Ubah izin Revisi",
            text: "Tekan 'Buka' untuk membuka izin revisi atau 'Kunci' untuk mengunci izin revisi",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            cancelButtonClass: "btn-danger",
            confirmButtonText: "Buka",
            cancelButtonText: "Kunci",
            closeOnConfirm: false,
            closeOnCancel: false,
            html: true,
        }, function (isConfirm) {
            if (isConfirm) {

                var data = { 
                    primary: me.attr('data-primary'),
                    mode: me.attr('data-mode'), 
                    data: me.attr('data-id'), 
                    token: that.attr('data-token'),
                    action: 1
                }
                
                sendAjax(data);

            }
            else {

                var data = { 
                    primary: me.attr('data-primary'),
                    mode: me.attr('data-mode'), 
                    data: me.attr('data-id'), 
                    token: that.attr('data-token'),
                    action: 0
                }
                
                sendAjax(data);

            }
        });
    });

    $('#data').on('load-error.bs.table', function(status, res) {
        $(this).bootstrapTable('removeAll');
    });

    $("#docs").on('click', '.new-modal', function(e){
        var me = $(this);
        var that = $('#data');

        $.ajax({ 

            type: "POST",
            url: "/dash/docs/processor",
            data: { 
                primary: me.attr('data-primary'),
                mode: me.attr('data-mode'), 
                data: that.attr('data-id'), 
                token: that.attr('data-token') 
            } 

        })
        .done(function( str ) {

            var data = JSONParser(str);
            if(data){
                that.attr("data-token", data.token);
                if(typeof data.alert === 'undefined'){
                    $('#panel-modal .modal-content').append(data.data);
                     $('#panel-modal').modal('show');
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

    });

    $('#panel-modal').on('hidden.bs.modal', function (e) {
        $('#panel-modal .modal-content').empty();
        $('.dz-hidden-input').remove();
    });

    var resizeFunc = function() {
        var that = $('#docs');

        if(that.height() > 200) {
            that.removeAttr('style');
        }

        if(that.height() < 200){
            that.attr('style', 'min-height: 320px');
        }

    };

    $('#data').on('post-body.bs.table', resizeFunc);
