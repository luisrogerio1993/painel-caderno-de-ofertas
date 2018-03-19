function PreviewImage(idInput, idDivImg = 'div-img') {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById(idInput).files[0]);

    oFReader.onload = function (oFREvent) {
        document.getElementById(idDivImg).src = oFREvent.target.result;
    };
}