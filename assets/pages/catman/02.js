

chart = new Chartist.Bar('#stacked-bar-chart', {
        labels: ['Kontrak', 'Pagu'],
        series: [
            [0,0],[0,0]
        ]
    }, {
        stackBars: true,
        chartPadding: {
            top: 0,
            right: 15,
            bottom: 0,
            left: 0
        },
        horizontalBars: true,
    }).on('draw', function(data) {
        if(data.type === 'bar') {
            data.element.attr({
                style: 'stroke-width: 30px'
            });
        }
    });

$(document).ready(function(){
    $('.donut').peity('donut', {
        width: '100%',
        height: '200%',
        innerRadius: '65',
        fill: ['#CCCCCC', '#009886', '#FFAA00', '#EF5350']
    });

    $('#popup input[name="data_0"]').mask('999.000.000.000.000', {reverse: true});

});

var total_addendum = 0;

function dataHandler(data) {
    total_addendum = data.footer.addendum;

    $('.total_pagu').text(data.footer.pagu);
    $('.total_kontrak').text(data.footer.kontrak);
    $('.total_penyerapan').text(data.footer.penyerapan);
    $('.total_sisa_pagu').text(data.footer.sisa_pagu);
    $('.total_sisa_kontrak').text(data.footer.sisa_kontrak);
    $('.total_pekerjaan h2').text(data.total);

    $('.total_0').text(data.total_['0']);
    $('.total_50up').text(data.total_['50up']);
    $('.total_50down').text(data.total_['50down']);
    $('.total_100').text(data.total_['100']);
    chart_value = [data.total_['0'], data.total_['50down'], data.total_['50up'], data.total_['100']].join();

    var that = $('#data');
    that.attr("data-token", data.token);

    if(typeof data.rows === 'undefined'){
        $('#data').bootstrapTable('removeAll');
    }
    else {
    }

    var update = {
        labels: ['Kontrak', 'Pagu'],
        series: data.chart
    }; 
    chart.update(update);

    $('.donut').text(chart_value).change();
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
    url: '/dash/catman/processor',
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
        url: "/dash/catman/processor",
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

$("#data").on('click','.btn-load', submitFunc);
$("button[data-target='#panel-modal']").on('click', submitFunc);


$('#data').on('load-error.bs.table', function(status, res) {
    $(this).bootstrapTable('removeAll');
});

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

            $.ajax({ 

                type: "POST",
                url: "cat/fetcher.php",
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