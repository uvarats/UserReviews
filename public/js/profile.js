function reviewDeleteFn(id){
    console.log(id);
    $("#userId").text(id);
    $("#staticBackdrop").modal('show');
}
function modalConfirm(){
    $.ajax({
        url: 'review/remove/' . $("#userId").text(),
        method: 'POST'
    })
}