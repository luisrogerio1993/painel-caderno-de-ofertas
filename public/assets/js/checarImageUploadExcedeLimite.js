$(document).ready(function () {
    $('input:file').change(function () {
        var arq = this.files[0];
        if(arq.size > 2097152){ //2M
            this.value = '';
            $('#modal-info').modal('show');
        }
    });
});