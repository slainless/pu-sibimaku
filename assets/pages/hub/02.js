


var total_addendum = 0;

function dataHandler(data) {

    var that = $('#data');
    that.attr("data-token", data.token);

    if(typeof data.rows === 'undefined'){
        $('#data').bootstrapTable('removeAll');
    }

    return data;
}

function detailFormatter(index, row, element) {
    var that = $('<dl class="dl-horizontal"></dl>');

    that.append('<dt>Sisa Anggaran</dt>').append('<dd>' + row.sisa_pagu + '</dd>');
    that.append('<dt>Sisa Kontrak</dt>').append('<dd>' + row.sisa_kontrak + '</dd>');
    that.append('<dt>Penyerapan</dt>').append('<dd>' + row.penyerapan + '</dd>');

    return that[0].outerHTML;
}

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
    detailView: true,
    detailFormatter: 'detailFormatter',
    pagination: true,
    showRefresh: true,
    pageSize: 10,
    sidePagination: 'server',
    showFooter: false,
    search: true,
    url: '/dash/hub/processor',
    method: 'post',
    contentType: 'application/x-www-form-urlencoded',
    queryParams: function(params) {
        var that = $('#data');

        params.primary = that.attr('data-primary');
        params.mode = that.attr('data-mode');
        params.token = that.attr('data-token');
        params.data = that.attr('data-id');
        return params;
    },
    sortName: 'title',
    responseHandler: 'dataHandler'
});


function submitFunc(){

    var that = $('#data');

    $.ajax({ 

        type: "POST",
        url: "/dash/hub/processor",
        data: { 
            primary: $(this).attr('data-primary'),
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

$('#data').on('load-error.bs.table', function(status, res) {
    $(this).bootstrapTable('removeAll');
});

