function reviewDeleteFn(id){
    console.log(id);
    $("#userId").text(id);
    $("#staticBackdrop").modal('show');
}
function modalConfirm(){
    let id = $('#userId').text()
    jQuery.ajax({
        url: '/review/remove/' + id,
        method: 'POST',
        success: function () {
            window.location.reload();
        }
    });
    $("#staticBackdrop").modal('hide');
}