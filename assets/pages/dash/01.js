
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

$('#info').on('click', '.trigger', submitFunc);

function submitFunc(){

    $.ajax({ 

        type: "POST",
        url: "/dash/dashboard/processor",
        data: { 
            primary: $(this).attr('data-primary'),
            mode: $(this).attr('data-mode'), 
            data: $('#info').attr('data-id'), 
            token: $('#info').attr('data-token') 
        } 

    })
    .done(function( str ) {

            var data = JSONParser(str);
            if(data){
                $('#info').attr("data-token", data.token);
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
                }
            }
            else {
            }
    });

}

$('#panel-modal').on('hidden.bs.modal', function (e) {
    $('#panel-modal .modal-content').empty();
});