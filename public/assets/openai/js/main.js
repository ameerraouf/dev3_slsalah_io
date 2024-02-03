$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function handleDelete(event,msg) {
    var confirmed = confirm(msg);
    if (!confirmed) {
        event.preventDefault();
    }
}
